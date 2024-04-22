<?php

namespace app\controller;

use app\component\Controller;
use app\model\Map;

class MapController extends Controller{

    public function __construct(){
        parent::__construct();
        $this->model = new Map();
    }

    public function getApiMap(string $search = ''){
        echo $this->model->getUrl($search);
    }

    public function save(){
        if(!empty($_POST['search'])){
            $request = $this->model->cleanParams($_POST['search']);

            $this->model->saveRequest($request);
        }
    }

    public function autocomplete(string $query){
        $query = $this->model->cleanParams($query);

        echo $this->model->getAutocompletePlaces($query);
    }
}