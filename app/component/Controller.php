<?php

namespace app\component;

abstract class Controller {
    public $view;

    public $model;

    /**
     * @param $view
     */
    public function __construct(){
        $this->view = new View();
    }

    public function index(){}
}