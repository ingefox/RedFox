<?php

use CodeIgniter\HTTP\RedirectResponse;

if ( ! function_exists('render'))
{
    /**
     * @param string $view
     * @param array $data
     * @param array $options
     * @param string $layout
     * @param bool $isFile
     * @return RedirectResponse|string
     */
    function render(string $view, array $data = [], array $options = [], string $layout = LAYOUT, $isFile = true)
    {
        if (CI_DEBUG && !session()->get('logged_in_redfox') && !in_array($view,AUTHORIZED_URLS_NO_SESSION_BO)){
        	echo view(MAINTENANCE_PAGE);
		}
		else{
        if (
            // NO SESSION OR USER NOT LOGGED IN
            !(session()->get('logged_in') || session()->get('logged_in_redfox'))
            // NOT AN AUTHORIZED URL WITHOUT SESSION
				&& !(in_array($view, AUTHORIZED_URLS_NO_SESSION) || in_array($view, AUTHORIZED_URLS_NO_SESSION_BO))
			) {
            // REDIRECT TO HOME PAGE
            return redirect()->to(base_url());
			} else {
            // manage Menu 
				$viewMenu = "";
				if (defined('VIEW_MENU')) {
					$viewMenu = view(VIEW_MENU);
            }

				if ($isFile) $viewContent = view($view, $data, $options);
            else $viewContent = $view;  

            // DISPLAY THE REQUESTED VIEW
            echo view(
                $layout,
                [
                    'content' => $viewContent,
                    'menu' => $viewMenu,
                ],
                $options
            );
        }
    }
	}
}
