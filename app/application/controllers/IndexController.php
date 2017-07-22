<?php

namespace Controllers;

use Core\View;

class IndexController extends View
{
    public function indexAction()
    {
        //cagamos el layout
        self::setLayout("login");
        //setiamos una variable para la vista
        $test = self::$loader->load("dataBase");
        $res = $test->select("tbl_user")
                    ->execute();
        var_dump($res[0]["email"]);
    }
}