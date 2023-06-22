<?php

namespace RFCore\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use DateTime;
use RFCore\Entities\E_User;
use RFCore\Models\M_Notification;
use RFCore\Models\M_User;

class C_User extends RF_Controller
{
    const USERS_FORM = 'RFCore\Views\Users\V_UserForm';
    const USERS_REGISTER_FORM = 'RFCore\Views\Users\V_UserRegisterForm';

    public function index()
    {
        // If a user is already logged in, the home page is loaded
        if (session()->get(SESSION_KEY_LOGGED_IN)) {
            echo render(HOME_PAGE, ['title' => 'Accueil']);
        } // Otherwise, the login page is displayed
        else $this->login();
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

        $options['noH100'] = false;

        switch ($step) {
            case 1:
                // Step 1: Display email form
                $ret = json_encode(view(FORM_FORGOTTEN_PWD_STEP_1, $options));
                break;
            case 2:
                // Step 2: Send the email
                $postData = $request->getPostGet('forgottenPwd');

                $options['alert'] = 'Un problème est survenu lors de l\'envoi de l\'email.';
                $options['type'] = 'danger';

                if (is_array($postData)) {
                    $email = $postData['email'];
                    // Everything went well : sending the password reset email
                    if ($M_User->sendPwdResetEmail($email) == SC_SUCCESS) {
                        $options['alert'] = 'Un email vient de vous être envoyé. Merci de cliquer sur le lien qu\'il contient.';
                        $options['type'] = 'success';
                        $ret = json_encode(view(FORM_FORGOTTEN_PWD_STEP_2, $options));
                    }
                    else
                    {
                        $options['alert'] = "Aucun utilisateur n'a été trouvé avec cette adresse email.";
                        $options['type'] = 'danger';
                    }
                }

                $ret = json_encode(view(FORM_FORGOTTEN_PWD_STEP_1, $options));

                break;
            case 3:
                // Step 3 : Token verification
                $token = $request->getPostGet('token');

                $options = [
                    'action' => 'Users/forgottenPassword', // Route
                    'token' => $token, // Security token
                    'noH100' => false,
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
                if (is_array($postData)) {
                    $options = [
                        'action' => 'Users/forgottenPassword', // Route
                        'token' => $postData['token'], // Security token
                    ];

                    switch (false){
                        case ($postData['password'] == $postData['passwordConf']):
                            $options['errors'] = [
                                'pwdReset.passwordConf' => 'Le mot de passe de confirmation n\'est pas identique.'
                            ];
                            break;
                        default:
                            if ($M_User->resetPassword($postData['token'], $postData['password']) != SC_SUCCESS) {
                                $options['alert'] = 'Une erreur interne est survenue';
                                $options['type'] = 'danger';
                            }
                            else {
                                $options['alert'] = 'Mot de passe modifié avec succès.';
                                $options['type'] = 'success';
                            }
                            break;
                    }
                    $ret = render(FORM_FORGOTTEN_PWD_STEP_3, $options);
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
    public function newPassword()
    {
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
        if ($token != null) {
            $options['token'] = $token;
            if ($M_User->findOneBy('securityToken', $token) != null) {
                // Displaying password creation form
                $ret = render(FORM_NEW_PWD, $options);
            }
        } elseif (session()->has(SESSION_KEY_LOGGED_IN)) {
            $options['token'] = null;
            $ret = render(FORM_NEW_PWD, $options);
        }

        $postData = $request->getPostGet('newPwd');

        // Second step of the process (processing form data)
        if (is_array($postData)) {
            $user = $M_User->findOneBy('securityToken', $postData['token']);

            switch (false){
                case ($postData['password'] == $postData['passwordConf']):
                    $options['errors'] = [
                        'newPwd.passwordConf' => 'Le mot de passe de confirmation n\'est pas identique.'
                    ];

                    $options['token'] =$postData['token'];


                    $ret = render(FORM_NEW_PWD, $options);

                    break;
                default:
                    if ($M_User->resetPassword($postData['token'], $postData['password']) != SC_SUCCESS) {
                        $options['alert'] = 'Une erreur interne est survenue';
                        $options['type'] = 'danger';
                        $ret = render(FORM_NEW_PWD, $options);
                    }
                    else {
                        $options['alert'] = 'Mot de passe modifié avec succès.';
                        $options['type'] = 'success';

                        if(!$user->getIsActive())
						{
							$M_User->switchActivationStatus($user->getProperty('id'));
						}
                        $ret = redirect()->to(base_url());
                    }
                    break;
            }
        }

        return $ret;
    }

    /**
     * Function responsible for the email confirmation process
     * __NO_SESSION_REQUIRED__
     * @return mixed
     */
    public function confirmEmail()
    {
        // Load helpers and libraries
        helper(['form', 'url']);
        $M_User = new M_User();

        // Default return
        $ret = redirect()->to(base_url());

        $options = [
            'result' => false
        ];

        $request = $this->request;

        $token = $request->getGetPost('token');
        // Checking if a corresponding token exists in the DB
        if ($token != null) {
            $options['token'] = $token;
            /** @var E_User $user */
            $user = $M_User->findOneBy('securityToken', $token);

            $data = [
                'securityTokenExpiration' => null,
                'securityToken' => null
            ];

            if (
                ($user != null)
                && ($M_User->switchActivationStatus($user->getId()) == SC_SUCCESS)
                && ($M_User->updateEntity('id',$user->getId(),$data) == SC_SUCCESS)
            ) {
                $options['result'] = true;
            }
            $ret = render(VIEW_EMAIL_CONFIRMATION, $options);
        }

        return $ret;
    }

    //--------------------------------------------------------------------
    // LOGIN
    //--------------------------------------------------------------------

    // Rules used with the login form
    public $loginRules = [
        'user.email' => [
            'label' => 'Email',
            'rules' => 'required',
            'errors' => ['required' => 'Vous devez renseigner une adresse email.']
        ],
        'user.password' => [
            'label' => 'Mot de passe',
            'rules' => 'required',
            'errors' => ['required' => 'Vous devez renseigner un mot de passe.']
        ]
    ];

    /**
     * Function responsible for validating and displaying the login form
     */
    public function login()
    {
        $ret = redirect()->to(base_url());
        if (!session()->get(SESSION_KEY_LOGGED_IN))
        {
            $M_User = new M_User();// Default view

            $view = LOGIN_PAGE;

            $options = [
                'errors' => array(), // Form errors array
                'title' => 'Connexion', // Page title
                'action' => 'Users/login', // Route
				'hideMenu' => true
            ];

            helper(['form', 'url', 'cookie']);
            $request = $this->request->getPostGet('user');// Has a cookie been set ?
            if (empty($_COOKIE[SESSION_REMEMBER_ME]))
            {

                // The validation process returned some errors
                if (!$this->validate($this->loginRules) && $this->request->getRawInput() != null)
                {
                    $options['errors'] = $this->validator->getErrors();
                } // The validation process ran without any error
                elseif ($this->validate($this->loginRules))
                {
                    // The given credentials have been verified, a new session is opened
                    if ($M_User->verifyLogin($request['email'], $request['password']))
                    {

                        /** @var E_User $loggedUser */
                        $loggedUser = $M_User->findOneBy("email", $request['email']);

                        // Defining session data for the logged user
                        $userData = [
                            'id' => $loggedUser->getId(),
                            'email' => $loggedUser->getEmail(),
                            'firstname' => $loggedUser->getFirstName(),
                            'lastname' => $loggedUser->getLastName(),
                            'roles' => $loggedUser->getRoles(),
                            SESSION_KEY_LOGGED_IN => TRUE
                        ];
                        session()->set($userData);

                        $M_Notifications = new M_Notification();
                        $M_Notifications->checkForUnreadNotifications($loggedUser->getId());

                        $ret = redirect()->to(base_url());

                        // "Remember me" checkbox checked ?
                        if (key_exists('rememberMe', $request) && ($request['rememberMe'] != null))
                        {
                            // Encrypting logged user's email with BCRYPT
                            $cookieToken = password_hash($loggedUser->getEmail(), PASSWORD_BCRYPT);
                            // Saving it in the DB
                            $M_User->setCookieToken($loggedUser->getEmail(), $cookieToken);
                            // Setting the cookie for 21 days (86400 seconds * 21 days = 3 weeks)
                            $ret = $ret->setCookie(SESSION_REMEMBER_ME, $cookieToken, 86400 * 21); // TODO -> Constant
                        }
                        // Setting options to null so the return value is not overwritten
                        $options = null;
                    } else
                    {
                        // The given credentials could not be verified
                        $options['errors'] = ['user.password' => 'Identifiants incorrects'];
                    }
                }
            } else
            {
                // A cookie exists for the user
                // Retrieving the corresponding user data
                $loggedUser = $M_User->findOneBy("cookieToken", $_COOKIE[SESSION_REMEMBER_ME]);

                // Defining session data for the logged user
                $userData = [
                    'id' => $loggedUser->getId(),
                    'email' => $loggedUser->getEmail(),
                    'roles' => $loggedUser->getRoles(),
                    SESSION_KEY_LOGGED_IN => TRUE
                ];
                session()->set($userData);

                $ret = redirect()->to(base_url());
                $options = null;
            }// If options = NULL : everything went well; otherwise : re displaying the login form
            if ($options != null)
            {
                $ret = render($view, $options);
            }
        }

        return $ret;
    }

    //--------------------------------------------------------------------
    // LOGOUT
    //--------------------------------------------------------------------

    /**
     * Function called for user logout
     */
    public function logout()
    {
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
    public function manage()
    {
        helper(['form', 'url']);
        return render("RFCore\Views\Users\V_UserManager", [
            'title' => 'Gestion des utilisateurs',
        ]);
    }

    /**
     * Retrieve the list of all the users present in the DB
     * Used primarily for Datatable provisioning
     * @return false|string
     */
    public function getList()
    {
        if ($this->request->isAJAX() && (session()->get('roles') & ROLE_ADMIN)) {
            $M_User = new M_User();
            $data = array();
            $data['data'] = $M_User->getAll(TRUE);
        } else {
            $data['status'] = "Forbidden access : Not an AJAX request";
        }
        return json_encode($data);
    }

    /**
     * Switch the activation status of the given user
     * @param $id string|int User ID
     * @return false|string
     */
    public function switchActivationStatus($id)
    {
        $data = array();
        if ($this->request->isAJAX() && (session()->get('roles') & ROLE_ADMIN)) {
            $M_User = new M_User();
            $ret = $M_User->switchActivationStatus($id);
            switch ($ret) {
                case SC_SUCCESS:
                    $data['status'] = 'ok';
                    $data['message'] = 'Changement de statut effectué';
                    break;
                case SC_INTEGRATION_USER_UNKNOWN:
                    $data['status'] = 'ko';
                    $data['message'] = 'Aucun utilisateur trouvé';
                    break;
                case SC_INTERNAL_SERVER_ERROR:
                    $data['status'] = 'ko';
                    $data['message'] = 'Une erreur s\'est produite';
                    break;
            }
        } else {
            $data['status'] = "Access forbidden";
        }
        return json_encode($data);
    }

    /**
     * Function responsible for displaying and processing the user registration form
     */
    public function add()
    {
        helper(['form', 'url']);

        // Preparing the action URL
        $options = [
            'action' => base_url('Users/add'),
            'actionButton' => lang('buttons.add'),
        ];

        //////////////////////////////////////////////////

        // Defining the rules of the form

        $registerRules = [
            'user.firstname' => [
                'label' => mb_strtolower(lang('users.fields.firstname')),
                'rules' => 'permit_empty',
                'errors' => [
                ]
            ],
            'user.lastname' => [
                'label' => mb_strtolower(lang('users.fields.lastname')),
                'rules' => 'permit_empty',
                'errors' => [
                ]
            ],
            'user.email' => [
                'label' => mb_strtolower(lang('users.fields.email')),
                'rules' => 'required|is_unique[users.email]|valid_email',
                'errors' => [
                    'required' => lang('validation.rules.required.feminineA'),
                    'is_unique' => lang('validation.rules.is_unique.apostrophe'),
                    'valid_email' => lang('validation.rules.valid.apostrophe'),
                ]
            ],
            'user.type' => [
                'label' => mb_strtolower(lang('users.fields.role')),
                'rules' => 'required',
                'errors' => [
                    'required' => lang('validation.rules.required.masculineThe'),
                ]
            ],
        ];

        //////////////////////////////////////////////////

        // Data has been sent and the form contains errors
        if (
            !$this->validate($registerRules)
            && $this->request->getPostGet('submitted')
        )
        {
            $options['errors'] = $this->validator->getErrors();
        }
        // Data has been sent and the form contains no errors
        elseif ($this->validate($registerRules))
        {
            // Retrieving form data
            $userData = $this->request->getPostGet('user');

            // Defining default values
            $userData['roles'] = $userData['type'];

            $M_User = new M_User();

            // Result default value
            $options['result'] = false;

            if (
                // Adding the user to the DB
                ($M_User->addUser($userData) == SC_SUCCESS)
                // Sending a confirmation email to the user
                && (
					!COMFOX_AVAILABLE
					||
					($M_User->sendPwdResetEmail($userData['email'], true) == SC_SUCCESS)
				)
            )
            {
                    $options['type'] = 'success';
                    $options['alert'] = lang('users.register.messages.success');
            }else{
                $options['type'] = 'danger';
                $options['alert'] = lang('users.register.messages.error');
            }
        }

        return json_encode(view(self::USERS_FORM, $options));
    }

	/**
	 * Function responsible for displaying and processing the user registration form
	 */
	public function register()
	{
		helper(['form', 'url']);

		// Preparing the action URL
		$options = [
			'action' => base_url('Users/register')
		];

		//////////////////////////////////////////////////

		// Defining the rules of the form

		$registerRules = [
			'user.firstname' => [
				'label' => mb_strtolower(lang('users.fields.firstName')),
				'rules' => 'required',
				'errors' => [
					'required' => lang('validation.rules.required.masculineA'),
				]
			],
			'user.lastname' => [
				'label' => mb_strtolower(lang('users.fields.lastName')),
				'rules' => 'required',
				'errors' => [
					'required' => lang('validation.rules.required.masculineA'),
				]
			],
			'user.phone' => [
				'label' => mb_strtolower(lang('users.fields.phone')),
				'rules' => 'required',
				'errors' => ['required' => lang('validation.rules.required.masculineA')]
			],
			'user.email' => [
				'label' => mb_strtolower(lang('users.fields.email')),
				'rules' => 'required|is_unique[users.email]|valid_email',
				'errors' => [
					'required' => lang('validation.rules.required.feminineA'),
					'is_unique' => lang('validation.rules.is_unique.apostrophe'),
					'valid_email' => lang('validation.rules.valid.apostrophe'),
				]
			],
			'user.password' => [
				'label' => mb_strtolower(lang('users.fields.password')),
				'rules' => 'required',
				'errors' => ['required' => lang('validation.rules.required.masculineA')]
			]
		];

		if (defined('KEY_INDEX_GTU'))
		{
			$registerRules['user.CGUValidated'] = [
				'label' => mb_strtolower(lang('users.fields.CGUValidated2')),
				'rules' => 'required',
				'errors' => ['required' => lang('validation.rules.required.acceptPlural')]];
		}

		//////////////////////////////////////////////////

		// Data has been sent and the form contains errors
		if (
			!$this->validate($registerRules)
			&& ($this->request->getRawInput() != null)
		)
		{
			$options['errors'] = $this->validator->getErrors();
		}
		// Data has been sent and the form contains no errors
		elseif ($this->validate($registerRules))
		{
			// Retrieving form data
			$userData = $this->request->getPostGet('user');

			// Defining default values
			$userData['roles'] = ROLE_USER;
			$userData['CGUValidated'] = true;
			$userData['CGUValidatedDate'] = new DateTime('NOW');

			if (!(COMFOX_AVAILABLE))
			{
				$userData['isActive'] = true;
			}

			$M_User = new M_User();

			// Result default value
			$options['result'] = false;

			if (
				// Adding the user to the DB
				($M_User->addUser($userData) == SC_SUCCESS)
				// Sending a confirmation email to the user
				&& ($M_User->sendConfirmationEmail($userData['email']) == SC_SUCCESS)
			)
			{
				$options['result'] = true;
			}
		}

		echo render(self::USERS_REGISTER_FORM, $options);
	}


  	/**
     * Function responsible for displaying and processing the user modification form
     *
     */
    public function edit()
    {
        helper(['form', 'url']);

        // Preparing the action URL
        $options = [
            'action' => base_url('Users/edit'),
            'actionButton' => lang('buttons.edit'),
        ];

        //////////////////////////////////////////////////

        // Defining the rules of the form

        $registerRules = [
            'user.firstname' => [
                'label' => mb_strtolower(lang('users.register.inputs.firstname')),
                'rules' => 'permit_empty',
                'errors' => [
                ]
            ],
            'user.lastname' => [
                'label' => mb_strtolower(lang('users.register.inputs.lastname')),
                'rules' => 'permit_empty',
                'errors' => [
                ]
            ],
            'user.email' => [
                'label' => mb_strtolower(lang('users.register.inputs.email')),
                'rules' => 'required|is_unique[Users.email,id,{id}]|valid_email',
                'errors' => [
                    'required' => lang('form_validation.rules.required.feminineA'),
                    'is_unique' => lang('form_validation.rules.is_unique.apostrophe'),
                    'valid_email' => lang('form_validation.rules.valid.apostrophe'),
                ]
            ],
            'user.type' => [
                'label' => mb_strtolower(lang('users.register.inputs.role')),
                'rules' => 'required',
                'errors' => [
                    'required' => lang('form_validation.rules.required.masculineThe'),
                ]
            ],
        ];

        $M_User = new M_User();
        $userId = $this->request->getPostGet('userId') != null ? $this->request->getPostGet('userId') : $this->request->getPostGet('user')['id'] ;
        $user = $M_User->findOneBy('id', $userId);

		// TODO - Handle not found user error
		$ret = '';

        if ($user != null ) {

            $options['user'] = $user;
            $options['typeUser'] = $user->getProperty('roles');

            //////////////////////////////////////////////////

            // Data has been sent and the form contains errors
            if (
                !$this->validate($registerRules)
                && ($this->request->getPostGet('submitted') == true)
            )
            {
                $options['errors'] = $this->validator->getErrors();
            }
            // Data has been sent and the form contains no errors
            elseif ($this->validate($registerRules))
            {
                // Retrieving form data
                $userData = $this->request->getPostGet('user');

                // Defining default values
                $userData['roles'] = $userData['type'];

                $M_User = new M_User();

                // Result default value
                $options['result'] = false;

                if (
                    // Adding the user to the DB
                    ($M_User->editUser($userData) == SC_SUCCESS)
                )
                {
                        $options['type'] = 'success';
                        $options['alert'] = lang('users.edit.messages.success');
                        $options['typeUser'] = $userData['type'];

                }else{
                    $options['type'] = 'danger';
                    $options['alert'] = lang('users.edit.messages.error');
                }
            }

            $ret = json_encode(view(self::USERS_FORM, $options));
        }

		return $ret;
    }

	/**
	 * Function responsible for handling user delete requests
	 * @param int $id The id of the user to delete
	 */
    public function delete(int $id)
    {
        $ret = ['status' => SC_INTERNAL_SERVER_ERROR,'reason' => lang('users.delete.messages.error')];

        if (session()->get('roles') & ROLE_ADMIN) {
            $M_User = new M_User();

            if ($M_User->deleteUser($id)) {
                $ret['status'] = SC_SUCCESS;
				$ret['reason'] = lang('users.delete.messages.success');
            }
        }
		else {
			$ret['reason'] = 'Vous n\'êtes pas autorisé à effectuer cette action';
			$ret['status'] = SC_FORBIDDEN;
		}

        return json_encode($ret) ;
    }
}
