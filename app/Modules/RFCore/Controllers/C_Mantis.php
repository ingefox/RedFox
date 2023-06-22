<?php
namespace RFCore\Controllers;

use CodeIgniter\HTTP\Files\UploadedFile;
use DateTime;
use RFCore\Models\M_Mantis;

class C_Mantis extends  RF_Controller
{
    const ISSUES_VIEW 		= 'RFCore\Views\Mantis\V_Issues';
    const ISSUE_FORM_VIEW 	= 'RFCore\Views\Mantis\V_IssueForm';

    /**
     * @var int|mixed
     */
    private $projectId;

    public function __construct()
    {
		parent::__construct();
        $this->projectId = defined('INT_MANTIS_PROJECT_ID') ? constant('INT_MANTIS_PROJECT_ID') : MANTIS_PROJECT_ID;
    }

    public function getProjectIssues(){
        $M_Mantis = new M_Mantis();
        $res = $M_Mantis->getProjectIssues($this->projectId);

        $ret = redirect()->to(base_url(SC_NOT_FOUND));

        if($res['status'] == SC_SUCCESS){
            $ret = render(self::ISSUES_VIEW, ['data' => $res['data']->issues]);
        }

        return $ret;
    }

	/**
	 * Function responsible for handling issue creation requests
	 * @return false|string
	 */
	public function addIssue()
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => 'Une erreur interne est survenue lors du traitement de votre demande'];

		// Making that the current user is logged in
		if (session()->get(SESSION_KEY_LOGGED_IN))
		{
			// Retrieving sent data
			$issue = $this->request->getPostGet('issue');

			// Adding the project ID to the issue data
			$issue['project']['id'] = $this->projectId;

			// Adding the feature category to the issue summary
			$issue['summary'] = '['.$issue['feature'].'] '.$issue['summary'];
			unset($issue['feature']);

			// Adding additional data to the issue
			$dt = new DateTime();
			$issue['additional_information'] .= '- <strong>Date :</strong> '.$dt->format('d/m/Y à H:i:s')."\n";
			$issue['additional_information'] .= '- <strong>Utilisateur :</strong> '.session()->get('email').' (ID : '.session()->get('id').')'."\n";
			$issue['additional_information'] .= '- <strong>Rôle :</strong> '.(ROLES_ARRAY_STR[session()->get('roles')] ?? 'Inconnu ('.session()->get('roles').')')."\n";
			$issue['additional_information'] .= '- <strong>Navigateur :</strong> '.$this->request->getUserAgent()->getBrowser().' (Version : '.$this->request->getUserAgent()->getVersion().' - '.$this->request->getUserAgent()->getPlatform().')'."\n";
			$issue['additional_information'] .= '- <strong>Mobile ? :</strong> '.(($this->request->getUserAgent()->isMobile() ? 'Oui ('.$this->request->getUserAgent()->getMobile().')' : 'Non'))."\n";
			$issue['additional_information'] .= '- <strong>URL courant :</strong> '.$this->request->getUserAgent()->getReferrer();

			// Generating the current log file references
			$logFilename = 'log-' . date('Y-m-d') . '.log';
			$currentLogFile = ROOTPATH . 'writable' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . $logFilename;

			// Checking if the log file exists
			if (is_file($currentLogFile))
			{
				// Attaching the current log file to the issue
				// Even though it is not mentioned in the API documentation, it is mandatory to send the file content as base64 encoded string to prevent file corruption
				$issue['files'][] = [
					'name' 		=> $logFilename,
					'content' 	=> base64_encode(file_get_contents($currentLogFile)),
				];
			}

			// Retrieving potential attached files
			$attachments = $this->request->getFiles();

			// Checking if there are any attachments
			if (!empty($attachments))
			{
				$attachments = $attachments['issue']['attachments'];
			}

			// Adding the attachments to the issue
			/** @var UploadedFile $attachment */
			foreach ($attachments as $attachment)
			{
				// Checking if the file is valid
				if ($attachment->isValid() && ! $attachment->hasMoved())
				{
					// Moving the file to a temporary location
					$attachment->move(WRITEPATH . 'uploads');
					$newPath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . $attachment->getName();

					// Attaching the file to the issue
					// Even though it is not mentioned in the API documentation, it is mandatory to send the file content as base64 encoded string to prevent file corruption
					$issue['files'][] = [
						'name' 		=> $attachment->getName(),
						'content' 	=> base64_encode(file_get_contents($newPath)),
					];

					// Deleting the temporary file
					unlink($newPath);
				}
			}

			$M_Mantis = new M_Mantis();
			$response = $M_Mantis->postIssue($issue);

			// If the issue has been created successfully, its access link can be returned
			if (
				($response['status'] === SC_SUCCESS)
				&& !empty($response['data'])
				&& !empty($response['data']->issue)
			)
			{
				$ret = [
					'status' => SC_SUCCESS,
					'reason' => 'Votre demande de support a été créée avec succès (<a target="_blank" href="https://mantisbt.services.ingefox.com/view.php?id='.$response['data']->issue->id.'">Accéder au ticket</a>)'
				];
			}
			else
			{
				// Otherwise, an error message is returned
				$ret = [
					'status' => SC_BAD_REQUEST,
					'reason' => 'Une erreur est survenue lors de la création de votre demande de support. <br><br>Merci de réessayer ultérieurement.'
				];
			}
		}
		else {
			$ret['reason'] = 'Vous devez être connecté pour pouvoir créer un nouveau ticket';
			$ret['status'] = SC_FORBIDDEN;
		}

		return json_encode($ret);
	}

	/**
	 * Function responsible for displaying the Mantis issue form
	 * @return false|string
	 */
	public function displayIssueForm()
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => 'Une erreur interne est survenue lors du traitement de votre demande.', 'data' => null];

		helper('form');

		$M_Mantis = new M_Mantis();

		// Retrieving the project details
		$response = $M_Mantis->getProject($this->projectId);

		// If the project details have been retrieved successfully
		if ($response['status'] === SC_SUCCESS) {

			// Retrieving the project categories
			$data = [
				'categories' => $response['data']->projects[0]->categories,
			];

			$ret = view(self::ISSUE_FORM_VIEW, $data);
		}

		return json_encode($ret);
	}
}
