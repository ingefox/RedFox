<?php

namespace RFCore\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use RFCore\Entities\E_BOUser;

class M_BOUser extends RF_Model
{

    /** @var EntityRepository $repository */
    private $repository;

    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
        $this->repository = parent::$em->getRepository('RFCore\Entities\E_BOUser');
        $this->entityName = 'RFCore\Entities\E_BOUser';
    }

    public function checkDefaultUser(){
    	$user = $this->findOneBy('email', BO_DEFAULT_USER);
    	if ($user == null){
    		$data = [
    			'email' => BO_DEFAULT_USER,
				'password' => BO_DEFAULT_USER_PASSWORD
			];
			try {
				$user = new E_BOUser($data);
				parent::$em->persist($user);
				parent::$em->flush();
			} catch (\Exception $e) {
				log_message('error', 'Error while persisting a new User in DB : '.$e);
			}
		}
	}

    /**
     * Persist a user to the DB
     * @param $user E_BOUser BOUser to be persisted
     * @return bool Return "true" if the user has been persisted into the DB successfully, "false" otherwise
     */
    public function addUser($user){
        $ret = SC_INTEGRATION_USER_ALREADY_EXIST;
        if ($this->repository->findOneBy(array("email" => $user->getEmail())) == null){
            try {
                parent::$em->persist($user);
                parent::$em->flush();
                $ret = SC_SUCCESS;
            } catch (\Exception $e) {
                log_message('error', 'Error while persisting a new User in DB : '.$e);
            }
        }else{
            $ret=SC_INTEGRATION_USER_ALREADY_EXIST;
        }
        return $ret;
    }

    /**
     * Remove a user from the DB
     * @param $userID string ID of the user
     * @return bool Return "true" if the user has been removed from the DB successfully, "false" otherwise
     */
    public function deleteUser($userID){
        $ret = false;
        $user = $this->repository->find($userID);
        if ($user != null){
            try {
                parent::$em->remove($user);
                parent::$em->flush();
                $ret = true;
			} catch (\Exception $e) {
                log_message('error', 'Error while deleting a User from DB : '.$e);
            }
        }
        return $ret;
    }

    public function getUserList(){
        /** @var EntityManager $em */
        $em = service('doctrine');
        return $em->getRepository('RFCore\Entities\E_BOUser')->findAllIndexed();
    }

    public function getUserListJson(){
        $userArray = $this->getUserList();
        $userListJson = array();
        /** @var E_BOUser $user */
        foreach ($userArray as $user){
            $userListJson[] = $user->toJson();
        }
        return $userListJson;
    }

    /**
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function verifyLogin(string $email, string $password){
        $ret = false;
        /** @var E_BOUser $userInDB */
        $userInDB = $this->repository->findOneBy(array("email" => $email));
        if ($userInDB != null) $ret = password_verify($password, $userInDB->getPassword());
        return $ret;
    }
}
