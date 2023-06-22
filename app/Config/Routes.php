<?php namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * --------------------------------------------------------------------
 * URI Routing
 * --------------------------------------------------------------------
 * This file lets you re-map URI requests to specific controller functions.
 *
 * Typically there is a one-to-one relationship between a URL string
 * and its corresponding controller class/method. The segments in a
 * URL normally follow this pattern:
 *
 *    example.com/class/method/id
 *
 * In some instances, however, you may want to remap this relationship
 * so that a different class/function is called than the one
 * corresponding to the URL.
 */

// Create a new instance of our RouteCollection class.
$routes = Services::routes(true);

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
    require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

//--------------------------------------------------------------------
// BackOffice
//--------------------------------------------------------------------

$routes->group('RF-BackOffice',
    /**
     * @param $routes RouteCollection
     */
    function($routes)
    {

        $routes->add('', 'RFCore\Controllers\C_BackOffice::index');

        //--------------------------------------------------------------------
        // ModuleManager
        //--------------------------------------------------------------------

        $routes->add('ModuleManager', 'RFCore\Controllers\C_ModuleManager');
        $routes->add('getInstalledModules', 'RFCore\Controllers\C_ModuleManager::ajaxGetInstalledModules');
        $routes->add('getAvailableModules', 'RFCore\Controllers\C_ModuleManager::ajaxGetAvailableModules');
        $routes->add('getUpdateModules', 'RFCore\Controllers\C_ModuleManager::ajaxGetUpdateModules');
        $routes->add('uninstallModule', 'RFCore\Controllers\C_ModuleManager::ajaxUninstallModule');
        $routes->add('installModule', 'RFCore\Controllers\C_ModuleManager::ajaxInstallModule');
        $routes->add('updateModule', 'RFCore\Controllers\C_ModuleManager::ajaxUpdateModule');
        $routes->add('ModuleDownloader', 'RFCore\Controllers\C_ModuleManager::moduleDownloader');
        $routes->add('selectModule', 'RFCore\Controllers\C_ModuleManager::selectModule');
        $routes->add('fillInputs', 'RFCore\Controllers\C_ModuleManager::fillInputs');
        $routes->add('selectModuleType', 'RFCore\Controllers\C_ModuleManager::selectModuleType');

        $routes->group('BOUsers',
            /**
             * @param $routes RouteCollection
             */
            function($routes)
            {
                //--------------------------------------------------------------------
                // BOUserManager
                //--------------------------------------------------------------------

                $routes->add('RegisterBO', 'RFCore\Controllers\C_BOUser::registerUser');
                $routes->add('ManageUserBO', 'RFCore\Controllers\C_BOUser::manageUser');
                $routes->add('EditUserBO', 'RFCore\Controllers\C_BOUser::editUser');
                $routes->add('getUserList', 'RFCore\Controllers\C_BOUser::getUserList');
                $routes->add('login','RFCore\Controllers\C_BOUser::login');
                $routes->add('logout','RFCore\Controllers\C_BOUser::logout');
                $routes->add('deleteUser','RFCore\Controllers\C_BOUser::ajaxDeleteUser');
            });

        //--------------------------------------------------------------------
        // APIManager
        //--------------------------------------------------------------------

        $routes->add('ManageAPI', 'RFCore\Controllers\C_APIManager::index');
        $routes->add('getAPIList', 'RFCore\Controllers\C_APIManager::getAPIList');
        $routes->add('editAPI', 'RFCore\Controllers\C_APIManager::editAPI');

        //--------------------------------------------------------------------
        // DatabaseManager
        //--------------------------------------------------------------------

        $routes->add('ManageDatabase', 'RFCore\Controllers\C_DatabaseManager::index');
        $routes->add('validateSchema', 'RFCore\Controllers\C_DatabaseManager::validateSchema');
        $routes->add('updateSchema', 'RFCore\Controllers\C_DatabaseManager::updateSchema');
        $routes->add('exportDbSql', 'RFCore\Controllers\C_DatabaseManager::exportDbSql');
        $routes->add('exportDbCsv', 'RFCore\Controllers\C_DatabaseManager::exportDbCsv');
    });

//--------------------------------------------------------------------
// Doctrine
//--------------------------------------------------------------------

$routes->add('InitDoctrine', 'DoctrineMod\Controllers\C_Doctrine::init');

include dirname(__DIR__, 1) . DIRECTORY_SEPARATOR.'Modules'.DIRECTORY_SEPARATOR.INTEGRATION_BASE_MODULE.DIRECTORY_SEPARATOR.'Config'.DIRECTORY_SEPARATOR.'Routes.php';

