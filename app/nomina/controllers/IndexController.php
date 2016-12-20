<?php
	
namespace Controllers;
use Core\View;
class IndexController extends View
{
	public function indexAction()
	{
		echo URL;
	}

	public function dummyTestAction()
	{
		self::$var->test = "Variable desde dummy test";
	}

}

?>