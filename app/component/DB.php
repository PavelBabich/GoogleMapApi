<?php

namespace app\component;

use PDO;

class DB {

    protected $db;

    public function __construct() {
        $config = require 'app/config/db_params.php';
        $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'] . ';port=3306';
        $this->db = new PDO($dsn, $config['user'], $config['password']);
    }

    public function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            foreach ($params as $key => $val) {
                if (is_int($val)) {
                    $type = PDO::PARAM_INT;
                } else {
                    $type = PDO::PARAM_STR;
                }
                $stmt->bindValue(':'.$key, $val, $type);
            }
        }
        $stmt->execute();
        return $stmt;
    }

    public function row($sql, $params = []) {
        $result = $this->query($sql, $params);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}