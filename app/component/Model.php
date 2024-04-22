<?php

namespace app\component;

abstract class Model {
    protected $db;

    public function __construct(){
        $this->db = new DB();
    }
}