<?php

use CodeIgniter\Router\RouteCollection;

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 * The RouteCollection object allows you to modify the way that the
 * Router works, by acting as a holder for it's configuration settings.
 * The following methods can be called on the object to modify
 * the default operations.
 *
 *    $routes->defaultNamespace()
 *
 * Modifies the namespace that is added to a controller if it doesn't
 * already have one. By default this is the global namespace (\).
 *
 *    $routes->defaultController()
 *
 * Changes the name of the class used as a controller when the route
 * points to a folder instead of a class.
 *
 *    $routes->defaultMethod()
 *
 * Assigns the method inside the controller that is ran when the
 * Router is unable to determine the appropriate method to run.
 *
 *    $routes->setAutoRoute()
 *
 * Determines whether the Router will attempt to match URIs to
 * Controllers when no specific route has been defined. If false,
 * only routes that have been defined here will be available.
 */
$routes->setDefaultNamespace('');
$routes->setDefaultController(INTEGRATION_BASE_MODULE.'\Controllers\C_User');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', INTEGRATION_BASE_MODULE.'\Controllers\C_User::index');

$routes->add('displayToast',INTEGRATION_BASE_MODULE.'\Controllers\C_Global::displayToast');
$routes->add('test',INTEGRATION_BASE_MODULE.'\Controllers\C_Global::test');

//--------------------------------------------------------------------
// User Management
//--------------------------------------------------------------------

$routes->group('Users',
	/**
	 * @param $routes RouteCollection
	 */
	function($routes)
	{
		$controllerPath = 'RFCore\Controllers\C_User';
		$integrationControllerPath = INTEGRATION_BASE_MODULE.'\Controllers\C_User';

		$routes->add('login',$controllerPath.'::login');
		$routes->add('logout',$controllerPath.'::logout');
		$routes->add('forgottenPassword',$controllerPath.'::forgottenPassword');
		$routes->add('newPassword',$controllerPath.'::newPassword');
		$routes->add('switchActivationStatus/(:any)',$controllerPath.'::switchActivationStatus/$1');
		$routes->add('add',$controllerPath.'::add');
		$routes->add('edit',$controllerPath.'::edit');
		$routes->add('delete/(:any)',$controllerPath.'::delete/$1');
		$routes->add('manage',$controllerPath.'::manage');
		$routes->add('getList',$controllerPath.'::getList');
		$routes->add('register',$controllerPath.'::register');
	}
);

$routes->add('getUserList',INTEGRATION_BASE_MODULE.'\Controllers\C_User::getUserList');

//--------------------------------------------------------------------
// MANTIS
//--------------------------------------------------------------------

$routes->group('Mantis',
	/**
	 * @param $routes RouteCollection
	 */
	function($routes)
	{
		$controllerPath = 'RFCore\Controllers\C_Mantis';

		$routes->add('displayIssueForm',$controllerPath.'::displayIssueForm');
		$routes->add('addIssue',$controllerPath.'::addIssue');
	}
);
