<?php


namespace RFCore\Controllers;


use RFCore\Models\M_BOUser;
use RFCore\Models\M_ModuleManager;

class C_BackOffice extends RF_Controller
{
	public function index()
	{
		helper('url');

		$M_BOUser = new M_BOUser();
		$M_BOUser->checkDefaultUser();

		$ret = redirect()->route('RF-BackOffice/BOUsers/login');;

		// if a user is already logged in, the home page is loaded
		if (session()->has('logged_in_redfox') && session()->get('logged_in_redfox')){
			$M_ModuleManager = new M_ModuleManager();
			$ret = render(BO_HOME_PAGE, ['title' => 'Accueil', 'RFVersion' => $M_ModuleManager->getRedFoxVersion()], [], LAYOUT_BO);
		}
		// Otherwise, the login page is displayed

		return $ret;
	}
}
