<?php

namespace RFCore\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\SchemaValidator;
use Doctrine\ORM\Tools\ToolsException;

class M_Doctrine extends RF_Model
{
    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
		parent::$em = service('doctrine');
    }

    /**
	 * Updates DB schema
	 */
	public function updateSchema(){
		$ret = ['status' => true, 'message' => 'Le schema a été mis à jour'];
		$tool = new SchemaTool(parent::$em);
		try {
		$tool->updateSchema($this->getEntitiesMetadata());
		} catch(\Exception $e) {
			$ret['status'] = false;
			$ret['message'] = $e->getMessage();
			return $ret;
		}
		return $ret;


	}

	/**
	 * Creates DB schema
	 */
	public function createSchema(){
		$tool = new SchemaTool(parent::$em);
		try {
			$tool->createSchema($this->getEntitiesMetadata());
		} catch (ToolsException $e) {
			echo $e->getMessage();
		}
	}

	/**
	 * returns database structure
	 *
	 * @return array contains keys: - status: if creation is done without errors
	 * 								- message: if error, a message is returned
	 * 								- fileContent: content of schema
	 *
	 */
	public function createSchemaSql(){
		$ret = ['status' => true, 'message' => 'Creation du schema reussi', 'structure' => ''];
		$tool = new SchemaTool(parent::$em);
		try {
			 $structure = $tool->getCreateSchemaSql($this->getEntitiesMetadata());
			 foreach($structure as $table){
				$ret['structure'] .= $table . ';\r';
			 }
		} catch (ToolsException $e) {
			$ret['status'] = false;
			$ret['message'] = $e->getMessage();
			return $ret;
		}
		return $ret;
	}
	/**
	 * Validates current schema and returns eventual errors
	 */
	public function validateSchema(){
		$ret = ['status' => false, 'message' => 'Une erreur est survenue'];
		$validator = new SchemaValidator(parent::$em);
		try {
			$errors = $validator->validateMapping();
			if (count($errors) > 0)
			{
				$ret['message'] = implode('\n\n', $errors);
			}
			else
			{
				$ret['status'] = true;
				$ret['message'] = 'Le schema est valide';
			}
		} catch (\Exception $e) {
			$ret['message'] = $e->getMessage();
			return $ret;
		}
		return $ret;
	}

	/**
	 * @return array Entities metadata
	 */
	public function getEntitiesMetadata(){

		$entities = array();

		$modulePath = APPPATH . 'Modules';
		foreach (scandir($modulePath) as $module){
			if (is_dir($modulePath . '/' . $module . '/Entities')) {
				foreach (scandir($modulePath . '/' . $module . '/Entities') as $moduleName) {
					if ($moduleName != '.' && $moduleName != '..' && strpos($moduleName, 'RF_') === false) {
						$entities[] = parent::$em->getClassMetadata(str_replace('.php', '', $module . '\Entities\\' . $moduleName));
                    }
                }
			}
		}
		return $entities;
	}
}
