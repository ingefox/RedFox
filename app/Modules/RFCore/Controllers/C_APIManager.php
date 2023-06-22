<?php

namespace RFCore\Controllers;

use RFCore\Entities\E_API;
use RFCore\Models\M_API;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use RFCore\Controllers\RF_Controller;

class C_APIManager extends RF_Controller
{
    public function index(){
        echo render("RFCore\Views\V_ManageAPI", ['title' => 'Gestion des API'], [], LAYOUT_BO);
    }

    //--------------------------------------------------------------------
    // Edit
    //--------------------------------------------------------------------

    /** @var array $editRules Rules used in Edit forms for APIManager */
    public $editRules = [
        'key' => [
            'label' => "Clé",
            'rules' => "required",
            'errors' => ['required' => 'Erreur : clé obligatoire.']
        ]
    ];

    /**
     * AJAX function for editing an API
     * @return false|string
     */
    public function editAPI(){
        /**
         * @var E_API $API
         */
        helper(['form', 'url']);
        $request = $this->request;
        $data = ['errors' => []];
        $view = "RFCore\Views\V_editAPI";
        $M_API = new M_API();
        if (!$this->validate($this->editRules) && $request->getVar('submitted') == "true")
        {
            $data['errors'] = $this->validator->getErrors();
        }
        elseif ($this->validate($this->editRules) && $request->getVar('submitted') == "true"){
            $API = $M_API->findOneBy('key', $request->getPostGet('key'));
            $testAPI = $M_API->findOneBy('id', $request->getPostGet('id'));
            if ($API->getId() == $testAPI->getId()) {
                try {
                    $API->update(['value' => $request->getPostGet('value'), 'description' => $request->getPostGet('description')]);
                    $M_API->flush();
                } catch (\Exception|OptimisticLockException|ORMException $e) {
                    $data['alert'] = "Une erreur est survenue : " . $e;
                    $data['type'] = 'danger';
                    return json_encode(view($view, $data));
                }
                $data['alert'] = 'API correctement modifiée !';
                $data['type'] = 'success';
            } else {
                $data['errors'] = ['key' => 'Clé déjà utilisée.'];
            }
        }
        return json_encode(view($view, $data));
    }

    //--------------------------------------------------------------------
    // Get
    //--------------------------------------------------------------------

    public function getAPIList(){
        $data = array();
        $data['status'] = "Forbidden access : Not an AJAX request";
        if ($this->request->isAJAX()){
            $M_API = new M_API();
            $data = ['data' => $M_API->getAPIListJson()];
        }
        return json_encode($data);
    }
}
