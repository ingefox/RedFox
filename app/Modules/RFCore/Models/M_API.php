<?php

namespace RFCore\Models;

use RFCore\Entities\E_API;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class M_API extends RF_Model
{
    const ENTITY_NAME = 'RFCore\Entities\E_API';

    /** @var EntityRepository $repository */
    private $repository;

    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
        $this->repository = parent::$em->getRepository('RFCore\Entities\E_API');
        $this->entityName = self::ENTITY_NAME;
    }

    /**
     * Persist an API instance in the DB
     * @param $API E_API API instance to be persisted
     * @return bool Return {true} if the API has been persisted into the DB successfully, {false} otherwise
     */
    public function addAPI($API){
        $ret = false;
        if ($this->repository->findOneBy(array("key" => $API->getKey())) == null){
            try {
                parent::$em->persist($API);
                parent::$em->flush();
                $ret = true;
            } catch (OptimisticLockException|ORMException $e) {
                log_message('error', 'Error while persisting an API instance: '.$e);
            }
        }
        return $ret;
    }

    /**
     * Retrieve the key of a specified API reference
     * @param $APIRef string API reference
     * @return string|null Either the value or null if not found
     */
    public function getAPIKey($APIRef){
        $ret = null;
        /** @var E_API $key */
        $key = $this->findOneBy('key', $APIRef);
        if ($key != null){
            $ret = $key->getValue();
        }
        return $ret;
    }

    public function getUser($property, $value){
        return $this->findOneBy($property, $value, 'RFCore\Entities\E_User');
    }

    /**
     * Remove an API instance from the DB
     * @param $key string Key of the API object
     * @return bool Return {true} if the API has been removed from the DB successfully, {false} otherwise
     */
    public function deleteAPI($key){
        $ret = false;
        $API = $this->repository->find($key);
        if ($API != null){
            try {
                parent::$em->remove($API);
                parent::$em->flush();
                $ret = true;
            } catch (OptimisticLockException|ORMException $e) {
                log_message('error', 'Error while removing an API instance: '.$e);
            }
        }
        return $ret;
    }

    /**
     * @return array
     */
    public function getAPIList(){
        return $this->repository->findAllIndexed();
    }

    /**
     * Return a list of all API instances in DB in a JSON formatted array
     * @return array API List in JSON format
     */
    public function getAPIListJson(){
        $APIArray = $this->getAPIList();
        $APIListJson = array();
        /** @var E_API $API */
        foreach ($APIArray as $API){
            $APIJson = array();
            $APIJson['id'] = $API->getId();
            $APIJson['key'] = $API->getKey();
            $APIJson['description'] = $API->getDescription();
            $APIJson['value'] = $API->getValue();
            $APIListJson[] = $APIJson;
        }
        return $APIListJson;
    }
}