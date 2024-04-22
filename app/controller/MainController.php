<?php

namespace app\controller;

use app\component\Controller;
use app\model\Map;

class MainController extends Controller{

    public function index(){
        $modelMap = new Map();
        $mapUrl = $modelMap->getUrl();
        $userRequests = $modelMap->getRequests();

        $this->view->render('index', array('mapUrl' => $mapUrl, 'userRequests' => $userRequests));
    }
}
