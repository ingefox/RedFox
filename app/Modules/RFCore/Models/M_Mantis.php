<?php
/** @noinspection DuplicatedCode */
/** @noinspection PhpUnused */

namespace RFCore\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;
use DateTime;
use Exception;
use RFCore\Models\RF_Model;

class M_Mantis extends RF_Model
{
	// API URL
	private $baseURL;

	// Token management
	private $token;

	// cURL handle
	private $ch;

	// Header options array
	private $headers;

	public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
	{
		parent::__construct($db, $validation);

		// Retrieving a valid base URL that will be used by the client
		$this->baseURL = defined('INT_MANTIS_BASE_URL') ? constant('INT_MANTIS_BASE_URL') : MANTIS_BASE_URL;
		$this->token = defined('INT_MANTIS_TOKEN') ? constant('INT_MANTIS_TOKEN') : MANTIS_TOKEN;
	}

	// =================================================================================================================
	// CONTRACTS
	// =================================================================================================================

    /**
     * Retrieve the list of issues in a project
     * @param $projectId
     * @return array
     */
	public function getProjectIssues($projectId): array
	{
        $response = $this->_curlRequest('issues/?project_id='.$projectId);

		return $this->_handleResponse($response);
	}

    /**
     * Create a new issue
     * @param $data
     * @return array
     */
    public function postIssue($data): array
    {
        $ret = [
			'status' 	=> SC_INTERNAL_SERVER_ERROR,
			'reason' 	=> 'Une erreur interne est survenue. Merci de réessayer ultérieurement',
		];

		try {
			$response = $this->_curlRequest('issues', 'POST', json_encode($data));
			$ret = $this->_handleResponse($response);
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . ' : ' . $e);
		}

        return $ret;
    }

    /**
     * Retrieves the details of a project
     * @param $projectId
     * @return array
     */
    public function getProject($projectId): array
    {
        $response = $this->_curlRequest('projects/'.$projectId);

        return $this->_handleResponse($response);
    }

    /**
     * @param string $endpoint no leading slash
     * @param string $method GET/POST
     * @param string $postData data to post as JSON string
     * @return bool|string
     */
    private function _curlRequest(string $endpoint, string $method = 'GET', string $postData = ''){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->baseURL.'/'.$endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                'Authorization: '.$this->token,
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => $postData,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    /**
     * Handles the errors and JSON operations on curl return
     * @param $response mixed the response from a curl request
     * @param $returnAsJson bool set to true if we want to keep the return as a json string
     * @return array
     */
    private function _handleResponse($response, bool $returnAsJson = false): array
    {
        $ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'data' => null];

        if ($response !== false){
            $ret = ['status' => SC_SUCCESS, 'data' => ($returnAsJson)?$response:json_decode($response)];
        }

        return $ret;
    }
}
