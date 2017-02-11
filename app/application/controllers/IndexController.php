<?php
namespace Controllers;
use Core\View;
class IndexController extends View
{
	public function indexAction()
	{
		self::setLayout("login");
	}
	public function testDummyAction()
	{
		
	}
}
