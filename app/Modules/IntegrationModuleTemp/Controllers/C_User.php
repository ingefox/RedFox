<?php
namespace IntegrationModuleTemp\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use Exception;
use IntegrationModuleTemp\Models\M_User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use IntegrationModuleTemp\Entities\E_User;
use RFCore\Controllers\RF_Controller;

class C_User extends RF_Controller
{
    public function index()
    {
		if (session()->get('logged_in')) {
			// If a user is already logged in, the home page is loaded
			$ret = render(HOME_PAGE, ['title' => 'Accueil']);
        }
        else
		{
			// Otherwise, the login page is displayed
			$ret = $this->login();
		}

		return $ret;
    }

    //--------------------------------------------------------------------
	// PASSWORD
    //--------------------------------------------------------------------

    /**
     * Function responsible for the forgotten password process
     * __NO_SESSION_REQUIRED__
     */
    public function forgottenPassword()
    {
		// Load helpers and libraries
		helper(['form', 'url']);
		$M_User = new M_User();

		// Default return
		$ret = redirect()->to(base_url());

        $options = [
			'action' => 'Users/forgottenPassword' // Route
        ];

		$request = $this->request;

		// Retrieving the current step of the process
		$step = $request->getPostGet('step');

        switch ($step){
			case 1:
				// Step 1: Display email form
				$ret = json_encode(view(FORM_FORGOTTEN_PWD_STEP_1, $options));
				break;
			case 2:
				// Step 2: Send the email
				$postData = $request->getPostGet('forgottenPwd');

				$options['alert']   = 'Un problème est survenu lors de l\'envoi de l\'email.';
				$options['type']    = 'danger';

				$ret = json_encode(view(FORM_FORGOTTEN_PWD_STEP_1, $options));

				if(is_array($postData))
        {
					$email = $postData['email'];
					// Everything went well : sending the password reset email
					if ($M_User->sendPwdResetEmail($email) == SC_SUCCESS) {
						$options['alert'] = 'Un email vient de vous être envoyé. Merci de cliquer sur le lien qu\'il contient.';
                            $options['type']  = 'success';
						$ret = json_encode(view(FORM_FORGOTTEN_PWD_STEP_2, $options));
                        }
					// TODO - User not found
                    }
				break;
			case 3:
				// Step 3 : Token verification
				$token = $request->getPostGet('token');

				$options = [
					'action' => 'Users/forgottenPassword', // Route
					'token' => $token, // Security token
				];

				if ($M_User->findOneBy('securityToken', $token) != null) {
					// A corresponding security token has been found in the DB
					$ret = render(FORM_FORGOTTEN_PWD_STEP_3, $options);
                }
				// TODO - Token expired
				// TODO - Invalid Token

				break;
			case 4:
				// Step 4 : Updating User password
				$postData = $request->getPostGet('pwdReset');
				if (is_array($postData)){
					if ($M_User->resetPassword($postData['token'],$postData['password']) != SC_SUCCESS){
						$options = [
							'action' => 'Users/forgottenPassword', // Route
							'token' => $postData['token'], // Security token
							'alert' => 'Une erreur interne est survenue', // Alert message
							'type' => 'danger' // Alert type
						];

						$ret = render(FORM_FORGOTTEN_PWD_STEP_3, $options);
            }
        }
				break;
        }


        return $ret;
	}


    /**
	 * Function responsible for the password creation process
	 * __NO_SESSION_REQUIRED__
	 * @return RedirectResponse
     */
    public function newPassword(){
		// Load helpers and libraries
        helper(['form', 'url']);
            $M_User = new M_User();

		// Default return
		$ret = redirect()->to(base_url());

        $options = [
			'action' => ROUTE_NEW_PWD_ACCOUNT, // Route
        ];

        $request = $this->request;

		$token = $request->getGetPost('token');
		// Checking if a corresponding token exists in the DB
		if ($token != null){
			$options['token'] = $token;
			if ($M_User->findOneBy('securityToken', $token) != null) {
				// Displaying password creation form
				$ret = render(FORM_NEW_PWD, $options);
        }
                    }
		elseif (session()->has('logged_in')){
			$options['token'] = null;
			$ret = render(FORM_NEW_PWD, $options);
		}

		$postData = $request->getPostGet('newPwd');
		// Second step of the process (processing form data)
		if (is_array($postData)){
			// Something went wrong : displaying an alert
			if ($M_User->resetPassword($postData['token'],$postData['password']) != SC_SUCCESS){
				$options['token'] = $postData['token'];
				$options['alert'] = 'Une erreur interne est survenue';
                    $options['type'] = 'danger';
				$ret = render(FORM_NEW_PWD, $options);
                }
			// Everything went well : redirecting to base url
			else{
				$ret = redirect()->to(base_url());
				// TODO - Display an alert to notify the user that everything is OK
            }
        }

        return $ret;
	}

    //--------------------------------------------------------------------
    // LOGIN
    //--------------------------------------------------------------------

    // Rules used with the login form
    public $loginRules = [
        'user.email'     => [
            'label' => 'Email',
            'rules' => 'required',
            'errors' => ['required' => 'Vous devez renseigner une adresse email.']
        ],
        'user.password'      => [
            'label'     => 'Mot de passe',
            'rules'     => 'required',
            'errors'    => ['required' => 'Vous devez renseigner un mot de passe.']
        ]
    ];

    /**
     * Function responsible for validating and displaying the login form
     */
    public function login(){
    	$M_User = new M_User();

    	// Default view
        $view = LOGIN_PAGE;
        $options = [
            'errors' => array(), // Form errors array
            'title' => 'Connexion', // Page title
            'action' => 'Users/login', // Route
			'hideMenu' => true
        ];

        helper(['form', 'url','cookie']);

        $request = $this->request->getPostGet('user');

        // Has a cookie been set ?
		if (empty($_COOKIE[SESSION_REMEMBER_ME])) {

        // The validation process returned some errors
			if (!$this->validate($this->loginRules) && $this->request->getRawInput() != null) {
            $options['errors'] = $this->validator->getErrors();
        }
        // The validation process ran without any error
			elseif ($this->validate($this->loginRules)) {
				// The given credentials have been verified, a new session is opened
				if ($M_User->verifyLogin($request['email'], $request['password'])) {

                /** @var E_User $loggedUser */
					$loggedUser = $M_User->findOneBy("email", $request['email']);

					// Defining session data for the logged user
					$userData = [
                    'id' => $loggedUser->getId(),
                    'email' => $loggedUser->getEmail(),
                    'roles' => $loggedUser->getRoles(),
                    'logged_in' => TRUE
                ];
					session()->set($userData);

					$ret = redirect()->to(base_url());

					// "Remember me" checkbox checked ?
					if ($request['rememberMe'] != null) {
						// Encrypting logged user's email with BCRYPT
						$cookieToken = password_hash($loggedUser->getEmail(), PASSWORD_BCRYPT);
						// Saving it in the DB
						$M_User->setCookieToken($loggedUser->getEmail(), $cookieToken);
						// Setting the cookie for 21 days (86400 seconds * 21 days = 3 weeks)
						$ret = $ret->setCookie(SESSION_REMEMBER_ME, $cookieToken, 86400*21); // TODO -> Constant
					}
					// Setting options to null so the return value is not overwritten
					$options = null;
				} else {
					// The given credentials could not be verified
					$options['errors'] = ['user.password' => 'Identifiants incorrects'];
				}
			}
            }
            else{
			// A cookie exists for the user
			// Retrieving the corresponding user data
			$loggedUser = $M_User->findOneBy("cookieToken", $_COOKIE[SESSION_REMEMBER_ME]);

			// Defining session data for the logged user
			$userData = [
				'id' => $loggedUser->getId(),
				'email' => $loggedUser->getEmail(),
				'roles' => $loggedUser->getRoles(),
				'logged_in' => TRUE
			];
			session()->set($userData);

			$ret = redirect()->to(base_url());
			$options = null;
            }

		// If options = NULL : everything went well; otherwise : re displaying the login form
		if ($options != null) {
			$ret = render($view, $options);
        }

		return $ret;
    }

    //--------------------------------------------------------------------
    // LOGOUT
    //--------------------------------------------------------------------

    /**
     * Function called for user logout
     */
    public function logout(){
        // Reset and destroy the previous session
        session()->destroy();
        // Redirect to the login page and deleting session cookie
        return redirect()->to(base_url())->setCookie(SESSION_REMEMBER_ME);
    }

    //--------------------------------------------------------------------
    // MANAGE
    //--------------------------------------------------------------------

    /**
	 * Display the User list interface
     */
    public function manage(){
        helper(['form','url']);
        return render(INTEGRATION_BASE_MODULE."\Views\Users\V_UserManager", [
            'title' => 'Gestion des utilisateurs',
        ]);
    }

	/**
	 * Retrieve the list of all the users present in the DB
	 * Used primarily for Datatable provisioning
	 * @return false|string
	 * TODO - Unused in this project => Need to be removed ?
	 */
    public function getList(){
        if ($this->request->isAJAX()) {
            $M_User = new M_User();
            $data = array();
            $data['data'] = $M_User->getAll(TRUE);
        }
        else {
            $data['status'] = "Forbidden access : Not an AJAX request";
        }
        return json_encode($data);
    }

	/**
	 * Switch the activation status of the given user
	 * @param $id string|int User ID
	 * @return false|string
	 */
    public function switchActivationStatus($id){
        $data = array();
        if ($this->request->isAJAX()) {
            $M_User = new M_User();
			$ret = $M_User->switchActivationStatus($id);
			switch ($ret){
				case SC_SUCCESS:
                $data['status'] = "Utilisateur supprimé";
					break;
				case SC_INTEGRATION_USER_UNKNOWN:
					$data['status'] = "Aucun utilisateur trouvé";
					break;
				case SC_INTERNAL_SERVER_ERROR:
					$data['status'] = "Une erreur s'est produite";
					break;
            }
        }
        else {
            $data['status'] = "Forbidden access : Not an AJAX request";
        }
        return json_encode($data);
    }
}
