<?php


namespace RFCore\Models;

use RFCore\Entities\E_ProjectConfig;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class M_ProjectConfig extends RF_Model
{
	const ENTITY_NAME = 'RFCore\Entities\E_ProjectConfig';

	/** @var EntityRepository $repository */
	private $repository;

	public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
	{
		parent::__construct($db, $validation);
		$this->repository = parent::$em->getRepository(self::ENTITY_NAME);
		$this->entityName = self::ENTITY_NAME;
	}

	/**
	 * Persist a E_ProjectConfig instance in the DB
	 * @param $config E_ProjectConfig E_ProjectConfig instance to be persisted
	 * @return int Persistence process result
	 */
	public function addProjectConfig(E_ProjectConfig $config): int
	{
		$ret = SC_INTERNAL_SERVER_ERROR;
		if ($this->repository->findOneBy(array("key" => $config->getKey())) == null){
			try {
				parent::$em->persist($config);
				parent::$em->flush();
				$ret = SC_SUCCESS;
			} catch (OptimisticLockException|ORMException $e) {
				log_message('error', 'Error while persisting a ProjectConfig instance: '.$e);
			}
		}
		else{
			$ret = SC_DOCTRINE_DUPLICATE_ENTITY;
		}
		return $ret;
	}

	/**
	 * Persist a E_ProjectConfig instance in the DB
	 * @param $data array Data to be persisted
	 * @return int Persistence process result
	 */
	public function saveProjectConfig(array $data): int
	{
		$ret = SC_INTERNAL_SERVER_ERROR;
		$projectConfig = $this->repository->findOneBy(array("id" => $data['id']));
		if ($projectConfig != null){
			try {
				$projectConfig->update($data);
				parent::$em->flush();
				$ret = SC_SUCCESS;
			} catch (OptimisticLockException|ORMException $e) {
				log_message('error', 'Error while persisting a ProjectConfig instance: '.$e);
			}
		}
		else{
			$ret = SC_DOCTRINE_ENTITY_NOT_FOUND;
		}
		return $ret;
	}

	/**
	 * Return a list of all the ProjectConfig entries in the DB
	 * @return array
	 */
	public function getProjectConfigList(): array
	{
		return $this->repository->findAllIndexed();
	}

	/**
	 * Return a list of all ProjectConfig instances in DB in a JSON formatted array
	 * @return array ProjectConfig list in a JSON format
	 */
	public function getProjectConfigListJson(){
		$ProjectConfigArray = $this->getProjectConfigList();
		$ProjectConfigListJson = array();
		/** @var E_ProjectConfig $ProjectConfig */
		foreach ($ProjectConfigArray as $ProjectConfig){
			$ProjectConfigJson = array();
			$ProjectConfigJson['id'] = $ProjectConfig->getId();
			$ProjectConfigJson['key'] = $ProjectConfig->getKey();
			$ProjectConfigJson['label'] = $ProjectConfig->getLabel();
			$ProjectConfigJson['value'] = $ProjectConfig->getValue();
			$ProjectConfigListJson[] = $ProjectConfigJson;
		}
		return $ProjectConfigListJson;
	}
}
