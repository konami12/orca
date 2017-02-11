<?php

namespace Core;

/**
 * Load controller and methods.
 *
 * @category   core
 * @package    app_core
 * @subpackage core
 * @author     jorge mendez ortega <jorge.mendez.ortega@gmail.com> 
 */
class Core
{
	/**
	 * Namespace for controllers
	 */
	const NAMESPACE_CONTROLLERS = "Controllers\\";
	
	//===============================================//	
	
	/**
	 *
	 * Begin the call of controllers
	 * 
	 */
	public function __construct()
	{
		$this->getControllerClass();
		$path = PATH_CONTROLLER . $this->controller . ".php";

		//verify if the file controller exists
		if (file_exists($path))
		{
			//get class for namespace
			$class = self::NAMESPACE_CONTROLLERS . $this->controller;
			$this->controller = new $class($this->config);
		}
		else
		{
			echo "Error 404";
			exit;
		}

		//verify if tehe method exist in the controller
		if (!method_exists($this->controller, $this->method))
		{
			throw new \Exception("Error Processing Method {$this->method}", 1);
		}
	}

	//===============================================//

	/**
	 * Launch of Controller and Method
	 * 
	 * @return
	 */
	public function run()
	{
		call_user_func_array([$this->controller, $this->method],[]);
	}

	//===============================================//
	// method privates                  			 //
	//===============================================//

	/**
	 * Get controller and method.
	 * 
	 * @return array.
	 */
	private function getControllerClass()
	{
		$controller = "index";
		if (isset($_GET["url"]))
		{
			$url              = rtrim($_GET["url"] , '/');
			$url              = filter_var($url, FILTER_SANITIZE_URL);
			$url              = ucfirst($url);
			$url              = ucwords($url, '-');
			$url              = explode('/', $url);
			$this->controller = $url[0] . "Controller";
			$controllers      = $url[0];
			if (isset($url[1]))
			{
				$this->method = str_replace("-", '', $url[1]) . "Action";
				$this->view   = strtolower($url[1]);
			}
		}
		
		$this->config = ['view' => $this->view,
						 'url'  => $controller . "/" .  $this->view];
	}

	//===============================================//
	// Variables privates               			 //
	//===============================================//
	/**
	 * Name of controller.
	 * 
	 * @var string.
	 */
	private $controller = "IndexController";
	/**
	 * Config for the site.
	 * 
	 * @var array
	 */
	private $config = [];
	/**
	 * Name of method.
	 * 
	 * @var string
	 */
	private $method = "indexAction";
	/**
	 * Name of method.
	 * 
	 * @var string
	 */
	private $view = "index";
}