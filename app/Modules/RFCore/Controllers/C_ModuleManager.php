<?php

namespace RFCore\Controllers;

use CodeIgniter\Controller;
use RFCore\Controllers\RF_Controller;
use RFCore\Models\M_ModuleManager;
use ZipArchive;


class C_ModuleManager extends RF_Controller
{
	public function index()
	{
		return render("RFCore\Views\V_ModuleManager", ['title' => 'Modules Manager'], [], LAYOUT_BO);
	}

    ////////////////////////////////////////////////////////////////////////
    ///////////////////////////////// AJAX /////////////////////////////////
    ////////////////////////////////////////////////////////////////////////

    public function ajaxGetModulesList(){
        $POSTdata = http_build_query(array('projectID' => PROJECT_ID, 'RF_KEY' => 'REDFOX_MOD'));

        $opts = array('http' => array(
            'method'  => 'POST',
            'header'  => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $POSTdata
        ));

        $context  = stream_context_create($opts);
        $json = file_get_contents(MODSERVER_URL.'getAvailableModules', false, $context);
        $obj = json_decode($json);
        return $obj->data;
    }

    /**
     * Retrieve a list of available modules using AJAX
     * @return false|string
     */
    public function ajaxGetAvailableModules(){
        if ($this->request->isAJAX()) {
            $data = array();
            $M_ModuleManager = new M_ModuleManager();
            $installedModules = $M_ModuleManager->getInstalledModulesJson();
            $availableModules = $this->ajaxGetModulesList();
            $filteredList = array();
            foreach ($availableModules as $availableModule){
                $found = false;
                foreach ($installedModules as $installedModule){
                    if ($availableModule->name == $installedModule['name']){
                        $found = true;
                        break;
                    }
                }
                if (!$found) $filteredList[] = $availableModule;
            }

            $data['data'] = $filteredList;
        }
        else {
            $data['status'] = "Forbidden access : Not an AJAX request";
        }
        return json_encode($data);
    }

    /**
     * Retrieve a list of update available using AJAX
     * @return false|string
     */
    public function ajaxGetUpdateModules(){
        if ($this->request->isAJAX()) {
            $data = array();
            $M_ModuleManager = new M_ModuleManager();
            $installedModules = $M_ModuleManager->getInstalledModulesJson();
            $availableModules = $this->ajaxGetModulesList();
            $filteredList = array();
            foreach ($availableModules as $availableModule){
                $updateFound = false;
                foreach ($installedModules as $installedModule){
                    if ($availableModule->name == $installedModule['name']){
                        if ($availableModule->version != $installedModule['version']) $updateFound = true;
                        break;
                    }
                }
                if ($updateFound) $filteredList[] = $availableModule;
            }

            $data['data'] = $filteredList;
        }
        else {
            $data['status'] = "Forbidden access : Not an AJAX request";
        }
        return json_encode($data);
    }

    /**
     * Retrieve a list of installed modules using AJAX
     * @return false|string
     */
    public function ajaxGetInstalledModules(){
        if ($this->request->isAJAX()) {
            $M_ModuleManager = new M_ModuleManager();
            $M_ModuleManager->refreshDb();
            $data = array();
            $data['data'] = $M_ModuleManager->getInstalledModulesJson();
        }
        else {
            $data['status'] = "Forbidden access : Not an AJAX request";
        }
        return json_encode($data);
    }

    public function ajaxInstallModule(){
        $ret['status'] = "Forbidden access : Not an AJAX request";
        if ($this->request->isAJAX()) {
            $M_ModuleManager = new M_ModuleManager();
            $modName = $this->request->getPostGet('name');
            $modVersion = $this->request->getPostGet('version');
            $modType = $this->request->getPostGet('type');

            /* The following 2 lines are not mandatory but we keep it to avoid risk of exceeding default execution time and memory */
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '2048M');

            // Url of zipped file at old server
            $file = MODSERVER_URL.'getModuleArchive?name='.$modName.'&version='.$modVersion.'&type='.$modType;

            $dest = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR .'ModulesTemp'.DIRECTORY_SEPARATOR.$modName.'.zip';

            $data = file_get_contents($file);
            $handle = fopen($dest,"wb");
            fwrite($handle, $data);
            fclose($handle);

            $M_ModuleManager->extractModuleArchive($modName);
            $M_ModuleManager->refreshDb();
            $ret['status'] = 'Installation terminée.';
        }
        return json_encode($ret);
    }

    public function ajaxUninstallModule(){
        $data['status'] = "Forbidden access : Not an AJAX request";
        if ($this->request->isAJAX()) {
            $M_ModuleManager = new M_ModuleManager();
            $data = $M_ModuleManager->uninstallModule($this->request->getPostGet("name"));
            $M_ModuleManager->refreshDb();
        }
        return json_encode($data);
    }

    public function ajaxUpdateModule(){
        $ret['status'] = "Forbidden access : Not an AJAX request";
        if ($this->request->isAJAX()) {
            $M_ModuleManager = new M_ModuleManager();

            $modName = $this->request->getPostGet('name');
            $modVersion = $this->request->getPostGet('version');
            $modType = $this->request->getPostGet('type');

            /* The following 2 lines are not mandatory but we keep it to avoid risk of exceeding default execution time and memory */
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '2048M');

            // Url of zipped file at old server
            $file = MODSERVER_URL.'getModuleArchive?name='.$modName.'&version='.$modVersion.'&type='.$modType;

            $dest = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR .'ModulesTemp'.DIRECTORY_SEPARATOR.$modName.'.zip';

            $data = file_get_contents($file);
            $handle = fopen($dest,"wb");
            fwrite($handle, $data);
            fclose($handle);

            $M_ModuleManager->updateModule($modName);
            $ret['status'] = 'Mise à jour terminée.';
        }
        return json_encode($ret);
    }

    /**
     * when module type is selected, fills modules select
     *
     * @return false|string
     */
    public function selectModuleType()
    {
        $m = new M_ModuleManager;
        if ($this->request->isAJAX()) 
        {
            $type = $this->request->getPostGet('type');
            $modules = $m->getModules($type);
        }
        else
        {
            $ret['status'] = "Forbidden access : Not an AJAX request";
        }
        return json_encode($modules);
    }

    /**
     * when module is selected, fills revisions select
     *
     * @return false|string
     */
    public function selectModule()
    {
        $m = new M_ModuleManager;
        if ($this->request->isAJAX()) 
        {
            $type = $this->request->getPostGet('type');
            $module = $this->request->getPostGet('module');
            $module = $type.'/'.$module;
            $revisions = $m->getRevisions($module);
            $ret['revisions'] = $revisions;
            $ret['status'] = 'ok';
        }
        else
        {
            $ret['status'] = "Forbidden access : Not an AJAX request";
        }
        return json_encode($ret);
    }

    /**
     * fills name and release note inputs with module and revision selected option text
     *
     * @return false|string
     */
    public function fillInputs()
    {
        if ($this->request->isAJAX())
        {
            $module = $this->request->getPostGet('module');
            $revision = $this->request->getPostGet('revision');

            //gets text between quotes in selected revision option
            $rl = preg_replace('/(.*)"(.*)"(.*)/sm', '\2', $revision);

            $infos = ['name' => $module, 'rl' => $rl];
            $ret['infos'] = $infos;
            $ret['status'] = 'ok';
        }
        else
        {
            $ret['status'] = "Forbidden access : Not an AJAX request";
        }
        return json_encode($ret);
    }

    /**
     * creates temp zip file, put files in it and send it to client
     *
     */
    public function moduleDownloader()
    {  
        $data = [];           
        $rf_dependencies = [];
        $proj_dependencies = [];

        $request = $this->request;

        $type = $this->request->getPostGet('dd_type');
        $module = $this->request->getPostGet('dd_modules');
        $revision = $this->request->getPost('dd_revisions');
        $name = $this->request->getPostGet('text_name');
        $description = $this->request->getPost('text_desc');
        $version = $this->request->getPost('text_version');
        $releaseNote = $this->request->getPost('text_relnote');

        if(null !== ($this->request->getPost('multi_rf_dep')))
        {
            foreach($this->request->getPost('multi_rf_dep') as $rf_dep)
            {
                $rf_dependencies[] = $rf_dep;
            }
        }
        
        if(null !== ($this->request->getPost('multi_proj_dep')))
        {
            foreach($this->request->getPost('multi_proj_dep') as $proj_dep)
            {
                $proj_dependencies[] = $proj_dep;
            }
        }


        if ($this->validate($this->formRules))
        {
            $m = new M_ModuleManager();

            //create temp folder 
            if(!file_exists('public/temp')){ mkdir('public/temp'); }
            $tempFolder = tempnam('public/temp', "folder");
            
            if(file_exists($tempFolder)){ unlink($tempFolder); }
            mkdir($tempFolder);

            $tempFile = tempnam($tempFolder, "zip");

            $zip = new ZipArchive;
            $zip->open($tempFile, ZipArchive::CREATE || ZipArchive::OVERWRITE);
            
            //create and add json to zip
            $json = ['name' => $name,
                    'description' => $description,
                    'version' => $version,
                    'release_note' => $releaseNote,
                    'RF_dependencies' => $rf_dependencies,
                    'Project_dependencies' => $proj_dependencies];
            
            //get module files and put them in zip
            $repository = SVN_PATH.$type.'/'.$module;
            $args = array($repository, $tempFolder);
            $switches = array('force' => true, 'revision' => $revision);
            $m->exportModule($args, $switches);
            $m->ZipFile($zip, $tempFolder, $tempFile);

            //overwrite exported METADATA.json
            $content = json_encode($json, JSON_PRETTY_PRINT);
            $zip->addFromString("METADATA.json", $content);

            $zip->close();  

            //set headers, read then delete temp file
            $this->response->setHeader('Content-Type', 'application/zip'); 
            if($version == '')
                $this->response->setHeader('Content-Disposition', 'attachment; filename='.$module.'.zip');
            else
                $this->response->setHeader('Content-Disposition', 'attachment; filename='.$module.'_'.$version.'.zip');

            //read from file and output the file on standard stream
            readfile($tempFile); 
            $m->rrmdir($tempFolder);
        }
        else if(!$this->validate($this->formRules) && $request->getRawInput() != null)
        {
            $data['alert'] = $this->validator->getErrors();
            $data['alert']['type'] = 'error';
            
            
            //$data['dd_type'] = $this->request->getPostGet('dd_type');
            $data['selectedModuleType'] = $type;
            $data['selectedModule'] = $module;
            $data['selectedRevision'] = $revision;
            $data['selectedRfDep'] = $rf_dependencies;
            $data['selectedProjDep'] = $proj_dependencies;
        }
        
        $this->makeForm($data);

    }

    public $formRules = [
        'text_name'     => [
            'label' => 'Nom',
            'rules' => 'required|alpha_numeric',
            'errors' => ['required' => 'Vous devez renseigner un nom de module.',
                         'alpha_numeric' => 'Le nom du module n\'est pas valide.']
        ],
        'text_version'      => [
            'label'     => 'Version',
            'rules'     => 'alpha_numeric|permit_empty',
            'errors'    => ['alpha_numeric' => 'La version n\'est pas une chaine valide.']
        ]
    ];

    /**
     * creates a form to select informations to put in zip file
     *
     * @return string
     */
    public function makeForm($data = [])
    {
        $m = new M_ModuleManager();
        helper(['form', 'url', 'rfform']);

        $selectedValues=[
             'selectedModule' => null,
             'selectedRevision' => null,
            'selectedModuleType' => null  
        ];
        
        $rf_depSelected = [];
        $proj_depSelected = [];

        if(array_key_exists('selectedModule', $data))
        {
            $selectedValues['selectedModule'] = $data['selectedModule'];
            $selectedValues['selectedRevision'] = $data['selectedRevision'];
            $selectedValues['selectedModuleType'] = $data['selectedModuleType'];
            $rf_depSelected = $data['selectedRfDep'] ?? [];
            $proj_depSelected = $data['selectedProjDep'] ?? [];
        }

        $modules_dep =  $m->getModules('ModulesRedFox')['modules'];
        $projects_dep = $m->getModules('ModulesProjets')['modules'];
        
        
        $module_revisions =  [];  
        $modules = [];

        $selectDefaultType = ['0' => 'Selectionnez un type de module...'];
        $modulesType =  ['ModulesRedFox' => 'Modules RedFox', 'ModulesProjets' => 'Modules d\'intégration'];
        $optionsType = array_merge($selectDefaultType, $modulesType);

        $entityForm = ['form' =>['id' => 'manageModules','action' => 'ModuleDownloader', 'label-colSize' => 'col-sm-2'],
                       'content' =>[ 'cols' => [[
                           'title_main' => ['type' => 'title', 'text'=>'Télécharger un module'],
                           'dd_type'=>['type' => 'dropdown', 'options' => $optionsType, 'label' => 'Type', 'onchange' => 'selectModuleType();', 'selected' => $selectedValues['selectedModuleType']],
                           'dd_modules'=>['type' => 'dropdown', 'options' => $modules, 'label' => 'Modules', 'onchange' => 'selectModule();', 'disabled' => 'disabled'],
                           'dd_revisions'=>['type' => 'dropdown', 'options' => $module_revisions, 'label' => 'Révisions', 'onchange' => 'fillInputs();', 'disabled' => 'disabled'],
                           'title_infos' => ['type' => 'title', 'text'=>'Informations'],
                           'text_name'=>['type' => 'text', 'label' => 'Nom'],
                           'text_desc'=>['type' => 'text', 'label' => 'Description'],
                           'text_version'=>['type' => 'text', 'label' => 'Version', 'maxlength' => '20'],
                           'text_relnote'=>['type' => 'text', 'label' => 'Release note'],
                           'multi_rf_dep'=>['type' => 'multiselect', 'label' => 'RF Dependencies', 'options' => $modules_dep, 'name' => 'multi_rf_dep[]', 'selected' => $rf_depSelected],
                           'multi_proj_dep'=>['type' => 'multiselect', 'label' => 'Project Dependencies', 'options' => $projects_dep, 'name' => 'multi_proj_dep[]', 'selected' => $proj_depSelected],
                           'button_dl' =>['type' => 'button_submit', 'value' => 'Télécharger .zip', 'class' => 'btn btn-primary btn-lg', 'disabled' => 'disabled']
                           ]]],
                        'formScript' => view('RFCore\Views\Scripts\S_moduleDownloader', $selectedValues)];

        return rfform($entityForm, $data, LAYOUT_BO);
    }
}
