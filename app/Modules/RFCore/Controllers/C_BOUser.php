<?php
namespace RFCore\Controllers;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use RFCore\Entities\E_BOUser;
use RFCore\Models\M_BOUser;

/**
 * Class C_BOUser
 * Controller for BOUserManager module
 * @package RFCore\Controllers
 */
class C_BOUser extends RF_Controller
{
    const BO_REGISTRATION_FORM_VIEW = "RFCore\Views\Modals\VM_RegisterForm";

    //--------------------------------------------------------------------
    // Registration
    //--------------------------------------------------------------------

    /**
     * Function responsible for validating and displaying the registration form
     */
    public function registerUser(){
        helper(['form', 'url']);
        $request = $this->request;
        $M_BOUser = new M_BOUser();
        $params = ['errors' => array()];
        $view = self::BO_REGISTRATION_FORM_VIEW;
        // The validation process returned some errors
        if (! $this->validate($this->registrationRules) && $request->getRawInput() != null)
        {
            $params = ['errors' => $this->validator->getErrors()];
        }
        // The validation process ran without any error
        elseif ($this->validate($this->registrationRules)){
            try {
                $user = new E_BOUser([
                    'username' => $request->getVar('username'),
                    'password' => $request->getVar('password')
                ]);
                // Try to add a new user
                if ($M_BOUser->addUser($user)){
                    $params['alert'] = 'Utilisateur correctement ajouté !';
                    $params['type'] = 'success';
                }
                // If the given username already exist in the DB, an alert is returned
                else{
                    $params = ['errors' => ['username' => 'Ce nom d\'utilisateur est déjà utilisé.']];
                }
            } catch (Exception $e) {
                $params['alert'] = 'Erreur serveur : problème lors de la création de l\'objet E_BOUser';
                $params['type'] = 'danger';
                log_message('error', 'Error while creating a new E_BOUser instance : '.$e);
            }
        }
        return json_encode(view($view, $params));
	}

	// Rules used with the registration form
    public $registrationRules = [
        'username'     => [
            'label' => 'Nom d\'utilisateur',
            'rules' => 'required',
            'errors' => ['required' => 'Vous devez renseigner un nom d\'utilisateur.']
        ],
        'password'      => [
            'label'     => 'Mot de passe',
            'rules'     => 'required',
            'errors'    => ['required' => 'Vous devez renseigner un mot de passe.']
        ],
        'pass_confirm' => [
            'label'     => 'Mot de passe de confirmation',
            'rules'     => 'required|matches[password]',
            'errors'    => [
                'required'  => 'Vous devez confirmer votre mot de passe.',
                'matches'   => 'Votre mot de passe de confirmation ne correspond pas.'
            ]
        ]
    ];

    //--------------------------------------------------------------------
    // Login
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
        $view = BO_LOGIN_PAGE;
        $options = [
            'errors' => array(),
            'title' => 'Connexion',
            'action' => 'RF-BackOffice/BOUsers/login'
        ];
        helper(['form', 'url']);
        $request = $this->request->getPostGet('user');
        $M_User = new M_BOUser();
        // The validation process returned some errors
        if (! $this->validate($this->loginRules) && $this->request->getRawInput() != null)
        {
            $options['errors'] = $this->validator->getErrors();
        }
        // The validation process ran without any error
        elseif ($this->validate($this->loginRules)){
            if ($M_User->verifyLogin($request['email'], $request['password'])){
                // The given credentials have been verified, a new session is opened
                /** @var E_BOUser $loggedUser */
                $loggedUser = service('doctrine')->getRepository('RFCore\Entities\E_BOUser')->findOneBy(array("email" => $request['email']));
                $userdata = [
                    'id' => $loggedUser->getId(),
                    'email' => $loggedUser->getEmail(),
                    'logged_in_redfox' => TRUE
                ];
                session()->set($userdata);
                return redirect()->route('RF-BackOffice');
            }
            else{
                // The given credentials could not be verified
                $options['errors'] = ['password' => 'Identifiants incorrects'];
            }
        }
        return render($view, $options, [], LAYOUT_BO);
    }

    /**
     * Function called for user logout
     */
    public function logout(){
        // Reset and destroy the previous session
        session()->set('logged_in_redfox', FALSE);
        session()->destroy();
        // Redirect to the login page
        return redirect()->route('RF-BackOffice');
    }

    //--------------------------------------------------------------------
    // Edit
    //--------------------------------------------------------------------

    /**
     * Function responsible for validating and displaying the edit form
     */
    public function editUser(){
        helper(['form', 'url']);
        $M_BOUser = new M_BOUser();

        /** @var E_BOUser $user */
        $user = $M_BOUser->findOneBy("id",$this->request->getPostGet('id'));

        $view = "RFCore\Views\Modals\VM_EditForm";
        $options = ['errors' => []];

        $request = $this->request;
        // The validation process returned some errors
        if (! $this->validate($this->editRules) && $request->getVar('submitted') == "true")
        {
            $options['errors'] = $this->validator->getErrors();
        }
        // The validation process ran without any error
        elseif ($this->validate($this->editRules) && $request->getVar('submitted') == "true"){
            /**
             * @var E_BOUser $user
             * @var E_BOUser $userVerif
             */
            $user = $M_BOUser->findOneBy("id",$this->request->getPostGet('id'));
            $userVerif = $M_BOUser->findOneBy("username",$this->request->getPostGet('username'));
            // Check that the chosen username is not already taken by another user
            if ($userVerif == null || $userVerif->getId() == $request->getVar('id')){
                try {
                    $values = ['username' => $request->getVar('username')];
                    if (!empty($request->getVar('password'))) $values['password'] = $request->getVar('password');
                    $user->update($values);
                    $M_BOUser->flush();
                } catch (OptimisticLockException|ORMException|Exception $e) {
                    $options['errors'] = "Une erreur est survenue : ".$e;
                    return json_encode(view($view, $options));
                }
                // If the current user edited its own account, the session data needs to be updated
                if (session()->get('id') >= $user->getId()){
                    $userdata = [
                        'id' => $user->getId(),
                        'username' => $user->getEmail(),
                        'logged_in_redfox' => TRUE
                    ];
                    session()->set($userdata);
                }
                $options['alert'] = 'Utilisateur correctement modifié !';
                $options['type'] = 'success';
            }
            // If the given username already exist in the DB, an alert is returned
            else{
                $options['errors'] = ['username' => 'Ce nom d\'utilisateur est déjà utilisé.'];
            }
        }
        return json_encode(view($view, $options));
    }

    // Rules used with the registration form
    public $editRules = [
        'id' => [
            'rules' => 'required'
        ],
        'username'     => [
            'label' => 'Nom d\'utilisateur',
            'rules' => 'required',
            'errors' => ['required' => 'Vous devez renseigner un nom d\'utilisateur.']
        ],
        'pass_confirm' => [
            'label'     => 'Mot de passe de confirmation',
            'rules'     => 'matches[password]',
            'errors'    => [
                'matches'   => 'Votre mot de passe de confirmation ne correspond pas.'
            ]
        ]
    ];

    //--------------------------------------------------------------------
    // BOUser management
    //--------------------------------------------------------------------

    public function manageUser(){
        helper(['form','url']);
        return render("RFCore\Views\V_UserManagerBO", [
            'title' => 'Gestion des utilisateurs',
        ], [], LAYOUT_BO);
    }

    public function getUserList(){
        if ($this->request->isAJAX()) {
            $M_User = new M_BOUser();
            $data = array();
            $data['data'] = $M_User->getUserListJson();
        }
        else {
            $data['status'] = "Forbidden access : Not an AJAX request";
        }
        return json_encode($data);
    }

    public function ajaxDeleteUser(){
        $data = array();
        if ($this->request->isAJAX()) {
            $M_User = new M_BOUser();
            /** @var E_BOUser $user */
            $user = service('doctrine')->getRepository('RFCore\Entities\E_BOUser')->findOneBy(array("username" => $this->request->getPostGet('username')));
            if ($user != null) {
                $M_User->deleteUser($user->getId());
                $data['status'] = "Utilisateur supprimé";
            }
            else $data['status'] = "Aucun utilisateur trouvé";
        }
        else {
            $data['status'] = "Forbidden access : Not an AJAX request";
        }
        return json_encode($data);
    }
}
