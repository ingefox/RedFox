<?php

namespace IntegrationModuleTemp\Controllers;

use RFCore\Controllers\RF_Controller;

class C_Global extends RF_Controller
{

	/**
	 * Generates and return a toast element
	 */
	function displayToast()
	{
		$icon = $this->request->getPostGet('icon');
		$options = [
			'icon'       => empty($icon) ? null : base_url($icon),
			'title'      => $this->request->getPostGet('title'),
			'message'    => $this->request->getPostGet('message'),
			'type'       => intval($this->request->getPostGet('type')),
			'autohide'   => filter_var($this->request->getPostGet('autoHide'),FILTER_VALIDATE_BOOL),
			'toastIndex' => intval($this->request->getPostGet('toastIndex'))
		];

		echo json_encode(view(INTEGRATION_BASE_MODULE.'\Views\V_Toast',$options));
	}
}
