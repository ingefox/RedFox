<?php

namespace RFCore\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use RFCore\Entities\E_RFModule;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use VersionControl_SVN;
use ZipArchive;

class M_ModuleManager extends RF_Model
{
    
    /** @var $repository EntityRepository */
    private $repository;
    
	public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
	{
		parent::__construct($db, $validation);
        $this->repository = parent::$em->getRepository("RFCore\Entities\E_RFModule");
    }

	public function getRedFoxVersion(){
		/** @var E_RFModule $mod */
		$this->refreshDb();
		$mod = $this->repository->findOneBy(array("name" => 'RFCore'));
		return ($mod != null) ? $mod->getVersion():'?';
	}

    /**
     * Uninstall a module from the system
     * @param $moduleName string
     * @return array
     */
    public function uninstallModule($moduleName){
        $ret = ['status' => false, 'message' => 'Erreur lors de la désinstallation'];
        $mod = $this->repository->findOneBy(array("name" => $moduleName));
        if (($mod->getRFChildren()->count() == 0) && ($mod->getProjectChildren()->count() == 0)) {
            $dir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . $mod->getName();
            $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
            $ret['status'] = rmdir($dir);
            if ($ret['status']) {
                $ret['message'] = "Désinstallation terminée.";
            }
        }
        else {
            $ret['message'] = "Certains modules dépendent du module sélectionné.";
        }
        return $ret;
    }

    public function extractModuleArchive($name){
        $filename = dirname(__DIR__, 3).DIRECTORY_SEPARATOR.'ModulesTemp'.DIRECTORY_SEPARATOR.$name.'.zip';
        $zip = new ZipArchive;
        $zip->open($filename);
        $ret = $zip->extractTo(dirname(__DIR__, 2));
        $zip->close();
        unlink($filename);
        return $ret;
    }

    /**
     * Convert a RFModule array into a JSON array
     * @return array
     */
	public function getInstalledModulesJson(){
        $modulesArray = $this->getInstalledModulesList();
        $modulesListJson = array();
        /** @var E_RFModule $module */
        foreach ($modulesArray as $module){
            $modulesListJson[] = $module->toJson();
        }
        return $modulesListJson;
    }

    /**
     * Parse a JSON array into an array of RFModules
     * @param $jsonArray array JSON Array containing a list of modules
     * @return array An array of RFModules
     */
	public function parseModuleJsonArray($jsonArray){
        $modulesList = array();
        try {
            foreach ($jsonArray as $module) {
                $newModule = new E_RFModule($module);
                if (key_exists('RF_dependencies', $module)) $newModule->setRFDependencies($module['RF_dependencies']);
                if (key_exists('Project_dependencies', $module)) $newModule->setProjectDependencies($module['Project_dependencies']);
                $modulesList[$module['name']] = $newModule;
            }
        } catch (Exception $e) {
            log_message('error', 'Exception while parsing Modules JSON array : ' . $e);
        }
        return $modulesList;
    }

    /**
     * Ask the DB to retrieve a list of installed modules
     * @return mixed
     */
	public function getInstalledModulesList(){
		return parent::$em->getRepository('RFCore\Entities\E_RFModule')->findAll();
	}

    /**
     * Ask the DB to retrieve a list of installed modules
     * @return mixed
     */
	public function getInstalledModulesListWithIndex(){
		return parent::$em->getRepository('RFCore\Entities\E_RFModule')->findAllIndexed();
	}

	public function removeDeletedModulesFromDb(){
        /** @var EntityManager $em */
	    $em = service("doctrine");
	    $repository = $em->getRepository("RFCore\Entities\E_RFModule");
	    $modulesInDb = $repository->findAllIndexed();
        $modulesInstalled = scandir(APPPATH."Modules");
        foreach ($modulesInstalled as $mod) {
			if ($mod != "." && $mod != "..") {
				unset($modulesInDb[$mod]);
			}
		}
        foreach ($modulesInDb as $key=>$modDeleted) {
            try {
                $em->remove($modDeleted);
				log_message('info', 'Module "'.$modDeleted->getName().'" deleted');
			} catch (\Exception $e) {
                log_message('error', 'Error while removing module from DB : '.$e);
            }
        }
        try {
            $em->flush();
        } catch (\Exception $e) {
            log_message('error', 'Error while removing module from DB : '.$e);
        }
    }

    public function updateModule($modName){
        $modulesFolder = dirname(__DIR__, 2).DIRECTORY_SEPARATOR;
        rename($modulesFolder.$modName,$modulesFolder.$modName."_OLD");
        if ($this->extractModuleArchive($modName)){
            $dir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . $modName."_OLD";
            $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
            rmdir($dir);
            $this->refreshDb();
        }
        else{
            $dir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . $modName;
            $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
            rmdir($dir);
            rename($modulesFolder.$modName."_OLD",$modulesFolder.$modName);
        }
    }

    /**
     * Refresh the database with modules contained in the "Modules" directory
     */
	public function refreshDb(){
		// Defining "Modules" folder real path
		$modulePath = APPPATH."Modules";

		// dependencies lists instantiation
		$modulesRFDependencies = array();
		$modulesProjectDependencies = array();

		foreach (scandir($modulePath) as $module){
            try {
                if (is_dir($modulePath . DIRECTORY_SEPARATOR . $module) && $module != "." && $module != "..") {
                    $json = json_decode(file_get_contents($modulePath . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . "METADATA.json"), true);

                    /** @var E_RFModule $newModule */
                    $newModule = $this->repository->findOneBy(array("name" => $json['name']));
                    $moduleExist = $newModule != null;
                    // Checking if the module is already present in the DB
                    $params = [
                        'name' => $json['name'],
                        'version' => $json['version'],
                        'description' => $json['description'],
                        'releaseNote' => $json['release_note'],
                    ];
                    if (!$moduleExist) {
                        // If not, a new one is created
                        $newModule = new E_RFModule($params);
                    } else {
                        $newModule->update($params);
						log_message('info', 'Module "'.$newModule->getName().'" updated');
                    }
                    if (!$moduleExist) {
                        try {
                            // Try to persist the module in the DB
                            parent::$em->persist($newModule);
							log_message('info', 'Module "'.$newModule->getName().'" persisted');
						} catch (\Exception $e) {
                            log_message('error', 'Error while refreshing DB : '.$e);
                        }
                    }
                    // Adding possible dependencies to their corresponding list
                    if (key_exists('RF_dependencies', $json)) {
                        foreach ($json['RF_dependencies'] as $dependency) $modulesRFDependencies[][$json['name']] = $dependency;
                    }
                    if (key_exists('Project_dependencies', $json)) {
                        foreach ($json['Project_dependencies'] as $dependency) $modulesProjectDependencies[][$json['name']] = $dependency;
                    }
                }
            } catch (\Exception $e) {
                log_message('error', 'Error while refreshing DB : '.$e);
            }
        }

		// First flush to update/add modules to the DB
		try {
			parent::$em->flush();
		} catch (\Exception $e) {
            log_message('error', 'Error while refreshing DB for modules : '.$e);
		} 

		// Retrieving RF generic modules from DB for dependencies provisioning
        foreach ($modulesRFDependencies as $row){
            foreach ($row as $key=>$value) {
                /** @var E_RFModule $mod */
                $mod = $this->repository->findOneBy(array("name" => $key));
                $dependency = $this->repository->findOneBy(array("name" => $value));
                $mod->getRFDependencies()->add($dependency);
            }
        }

        // Retrieving Project related modules from DB for dependencies provisioning
        foreach ($modulesProjectDependencies as $row){
            foreach ($row as $module => $value) {
                /** @var E_RFModule $mod */
                $mod = $this->repository->findOneBy(array("name" => $module));
                $dependency = $this->repository->findOneBy(array("name" => $value));
                $mod->getProjectDependencies()->add($dependency);
            }
        }

        // Second flush to update/add modules' dependencies in the DB
		try {
            parent::$em->flush();
		} catch (\Exception $e) {
            log_message('error', 'Error while refreshing DB for modules : '.$e);
        }
        $this->removeDeletedModulesFromDb();
        $M_DoctrineMod = new M_Doctrine();
        $M_DoctrineMod->updateSchema();
	}
    
    /**
     * with SVN library, execute svn export command
     *
     * @param array $args (0:repo path, 1:path to export)
     * @param array $switches 
     * @return void
	 * @throws \VersionControl_SVN_Exception
     */
    public function exportModule($args, $switches)
    {
        $svn = VersionControl_SVN::factory(array('export'));
        $svn->export->run($args, $switches);
    }

    /**
     * with SVN library, execute svn list command to get every folders in type module folder
     *
     * @param string $type 'ModulesRedFox' or 'ModulesProjets'
     * @return array 
	 * @throws \VersionControl_SVN_Exception
     */
    public function getModules($type)
    {
        $modules = [];

        $repository = SVN_PATH.$type;
        $args = array($repository);
        $svn = VersionControl_SVN::factory(array('list')); 
        $list = $svn->list->run($args);
        foreach($list['list'][0]['entry'] as $items)
        {
                $module = $items['name'];
                $modules['modules'][$module] = $module;
        }
        return $modules;
    }

    /**
     * with SVN library, execute svn list and svn log commands to get infos about folders in module folder
     *
     * @param string $module which module to get infos from
     * @return array
	 * @throws \VersionControl_SVN_Exception
     */
    public function getRevisions($module)
    {
        $repository = SVN_PATH.$module;
        $svn = VersionControl_SVN::factory(array('list', 'log'));
        $logs = $svn->log->run(array($repository));
        $tempRev = [];

        foreach($logs['logentry'] as $revision)
        {
            $index = intval($revision['revision']);
            $tempRev[$index] = $revision['revision'].' | '.$revision['author']. ' - "'.$revision['msg'].'"';
        }
       
        krsort($tempRev);
        foreach($tempRev as $k=>$v) $revisions[] = ['num' => $k, 'msg' => $v];
        return $revisions;
    }

    /**
     * recursively delete directory and files inside
     *
     * @param string $dir directory to delete
     * @return void
     */
    public function rrmdir($dir) {
        if (is_dir($dir)) {
          $objects = scandir($dir);
          foreach ($objects as $object) 
          {
            if ($object != "." && $object != "..") 
            {
                if (filetype($dir.DIRECTORY_SEPARATOR.$object) == "dir") 
                    $this->rrmdir($dir.DIRECTORY_SEPARATOR.$object); 
                else 
                    unlink($dir.DIRECTORY_SEPARATOR.$object);
            }
          }
          reset($objects);
          rmdir($dir);
        }
    }

    /**
     * put in opened ZipArchive files from source path, and output in destination path
     *
     * @param ZipArchive $zip opened ZipArchive
     * @param string $source source path
     * @param string $destination destination path
     * @return void
     */
    function zipFile($zip, $source, $destination)
    {
        $source = str_replace('\\', '/', realpath($source));

        if (is_dir($source) === true)
        {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source), \RecursiveIteratorIterator::SELF_FIRST);

            foreach ($files as $file)
            {
                if($file->getFileName() != basename($zip->filename))
                {   
                    $file = str_replace('\\', '/', realpath($file));
                    if (strpos($file,$source) !== false) 
                    { 
                        if (is_dir($file) === true)
                            $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                        else if (is_file($file) === true)
                            $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                        
                    }  
                }
            }
        }
        else if (is_file($source) === true)
        {
            $zip->addFromString(basename($source), file_get_contents($source));
        }
    }
}
