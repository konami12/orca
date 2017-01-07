<?php

namespace Controllers;
use Core\View;
class IndexController extends View
{
	public function indexAction()
	{
		self::$var->prueba = 10000;
	}

	public function ajaxAction()
	{
		self::disabletLayout();
		$v = ["powell" => 15];
		echo json_encode($v);
	}

}
