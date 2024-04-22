<?php

namespace app\component;

class View {
    public function render($view, $data){
        if(is_array($data)) {
            extract($data);
        }

        include 'app/view/' . $view . '.php';
    }

    public static function errorPage(){
        include 'app/view/404.php';
        exit;
    }
}