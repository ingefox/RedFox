<?php

namespace IntegrationModuleTemp\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;
use ComFox\Models\M_Email;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use IntegrationModuleTemp\Entities\E_User;
use RFCore\Models\RF_Model;

class M_User extends RF_Model
{

    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
        $this->entityName = INTEGRATION_BASE_MODULE.'\Entities\E_User';
    }

    /**
	 * Persist a user in the DB
	 * @param array $data User-related data
	 * @return int StatusCode
     */
    public function addUser($data){
        $ret = SC_INTERNAL_SERVER_ERROR;
        if ($this->findOneBy("email",$data['email']) == null){
            try {
                $user = new E_User($data);

                if($user != NULL)
                {
                parent::$em->persist($user);
                parent::$em->flush();
                }

                $ret = SC_SUCCESS;
            } catch (\Exception|ORMException $e) {
                log_message('error', 'Error while persisting an User instance: '.$e);
            }
        }
        else $ret = SC_INTEGRATION_USER_ALREADY_EXIST;

        return $ret;
    }

    /**
	 * Switch the activation status of an user account
	 * @param $id int|string User ID
	 * @return int StatusCode
     */
    public function switchActivationStatus($id){
		$ret = SC_INTERNAL_SERVER_ERROR;
            try {
			/** @var E_User $user */
			$user = $this->findOneBy('id', $id);
			if ($user != null) {
				$user->update(['isActive' => !$user->getIsActive()]);
                    parent::$em->flush();
				// If the new status is defined to 'Active' (TRUE) and a password has not been defined yet => Send the activation email
				if ($user->getIsActive() && ($user->getPassword() == null)){
					$this->sendPwdResetEmail($user->getEmail(), true);
                }
				$ret = SC_SUCCESS;
            }
			// User not found in the DB
			else SC_INTEGRATION_USER_UNKNOWN;
		} catch (\Exception $e) {
			log_message('error', 'Error while updating an User instance: '.$e);
        }
        return $ret;
    }





    /**
     * Remove a user from the DB
     * @param $userID string ID of the user
     * @return int StatusCode
	 * TODO - Unused
     */
    public function deleteUser($userID){
        $ret = SC_INTERNAL_SERVER_ERROR;
        $user = $this->findOneBy('id',$userID);
        if ($user != null){
            try {
                parent::$em->remove($user);
                parent::$em->flush();
                $ret = SC_SUCCESS;
            } catch (OptimisticLockException|ORMException $e) {
                log_message('error', 'Error while removing an User instance: '.$e);
            }
        }
        return $ret;
    }

	/**
	 * Retrieve all the Users from the DB
	 * @param bool $jsonFormatted Return the result in JSON compatible array ?
	 * @return array
	 * TODO - Unused
	 */
    public function getAll($jsonFormatted = false){
        $ret = $this->findAllEntities();
		if ($jsonFormatted) {
        $userListJson = array();
        /** @var E_User $user */
			foreach ($ret as $user) {
            $userJson = array();
            $userJson["id"] = $user->getId();
            $userJson["email"] = $user->getEmail();
            $userJson["roles"] = $user->getRoles();
            $userJson["roles_str"] = '';
				foreach (ROLES_ARRAY_STR as $key => $value) {
					if ($user->getRoles() & $key) {
						$userJson["roles_str"] .= "- " . $value . "<br>";
                }
            }
            $userListJson[] = $userJson;
        }
			$ret = $userListJson;
		}
		return $ret;
    }

    /**
	 * Verify that the given password matches the email passed as parameter
     * @param string $email User email address
     * @param string $password User password
     * @return bool
     */
    public function verifyLogin(string $email, string $password){
        $ret = false;
        /** @var E_User $userInDB */
        $userInDB = $this->findOneBy("email", $email);
        if ($userInDB != null) $ret = password_verify($password, $userInDB->getPassword()) && ($userInDB->getIsActive());
        return $ret;
    }


    /**
	 * @param string $email User email address
	 * @param boolean $firstConnection Is it a first connection type email ? (activation email)
	 * @return int StatusCode
     */
    function sendPwdResetEmail($email, $firstConnection = false)
    {
        $ret = SC_INTEGRATION_EMAIL_SEND_ERROR;

        // Check if the ComFox module is available
        if(COMFOX_AVAILABLE)
        {
            // Retrieve the user data
            /** @var E_User $user */
			$user = $this->findOneBy('email',$email);

            // Generate a new unique token
            $token = sha1(uniqid(rand()));

			try {

				// Define an expiration date for the token
				// Can throws Exception
				$expDate = new \DateTime();
				$expDate->modify('+1 day'); // TODO -> Constant ?

				// Prepare data used for updating the user
				$userInfo=[
					'securityToken' => $token,
					'securityTokenExpiration' => $expDate
				];

				// Checks if a user corresponding to the given email exists in the DB
				if (($user != NULL)) {

					// Update the user with the new data
					$user->update($userInfo);
                $this->flush();

					$M_Email = new M_Email();

					// Retrieving email configuration
					$config = config(INTEGRATION_BASE_MODULE . '\\Config\\IntegrationConfig');
					$data[emailConfig] = $config->emailConfig;

					// Preparing data for sending the email
					$data[emailTo] = $user->getEmail();
					$data[emailFrom] = $data[emailConfig][emailFrom];

					// Preparing the email content
					if (!$firstConnection) {
						// User has already logged in at least once
						// Generate an URL containing the required parameters (token + process step)
						$updatePwdUrl = base_url() . '/' . ROUTE_UPDATE_PWD_ACCOUNT . '?token=' . $user->getProperty('securityToken') . '&step=3';
						$htmlLink = '<a href="' . $updatePwdUrl . '">'.$updatePwdUrl.'</a>';

						$data[emailSubject] = INTEGRATION_UPDATE_PWD_EMAIL_TITLE;
						$data[emailMessage] = view(INTEGRATION_UPDATE_PWD_EMAIL_MESSAGE, ['link' => $htmlLink, 'email' => $user->getEmail()]);
					} else {
						// User logs in for the first time
						// Generate an URL containing the required parameters (token + process step)
						$updatePwdUrl = base_url() . '/' . ROUTE_NEW_PWD_ACCOUNT . '?token=' . $user->getProperty('securityToken');
						$htmlLink = '<a href="' . $updatePwdUrl . '">'.$updatePwdUrl.'</a>';

						$data[emailSubject] = INTEGRATION_NEW_PWD_EMAIL_TITLE;
						$data[emailMessage] = view(INTEGRATION_NEW_PWD_EMAIL_MESSAGE, ['link' => $htmlLink, 'email' => $user->getEmail()]);
					}

					// Sending the email
					if ($M_Email->sendEmail($data)) {
						$ret = SC_SUCCESS;
            }
				}
			} catch (\Exception $e) {
				log_message('error', 'Error while persisting a User instance: '.$e);
			}
        }
        return $ret;
    }



    /**
	 * Function used for setting a new password for a User after a password reset
	 * @param string $token Security Token
	 * @param string $password new password
	 * @return int StatusCode
     */
    public function resetPassword($token, $password){
    	$ret = SC_INTERNAL_SERVER_ERROR;

    	/** @var E_User $user */
		if($token != null) $user = $this->findOneBy('securityToken', $token);
		else $user = $this->findOneBy('id', session()->get('id'));

    	try{
			// Retrieving today date
			$today = new \DateTime();
			// Verify that the security token is not expired
			if (
				(($user != null) && ($today <= $user->getSecurityTokenExpiration()))
				|| (($user != null) && (session()->has('id')))
			) {
				// Token OK => setting the new password and resetting the security token data
				$data = [
					'password' => $password,
					'securityToken' => null,
					'securityTokenExpiration' => null
            ];
				$user->update($data);
				$this->flush();
				$ret = SC_SUCCESS;
                }
            }
		catch (\Exception $e) {
			log_message('error', 'Error while updating a User instance: '.$e);
        }
        return $ret;
    }


    /**
	 * Allow to set the cookie token of a given user for automatic login
	 * @param $email string User email
	 * @param $token string Cookie token
	 * @return int StatusCode
     */
	public function setCookieToken($email, $token){
		$ret = SC_INTERNAL_SERVER_ERROR;

		/** @var E_User $user */
		$user = $this->findOneBy('email', $email);
            
		if ($user != null){
			$data = ['cookieToken' => $token];
			try {
				$user->update($data);
				$this->flush();
				$ret = SC_SUCCESS;
			} catch (\Exception $e) {
				log_message('error', 'Error while updating a User instance: '.$e);
            }
        } 
		// No user found for the given parameter
		else $ret = SC_INTEGRATION_USER_UNKNOWN;

		return $ret;
    }

}
