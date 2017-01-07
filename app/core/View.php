<?php

namespace Core;

use Twig_Autoloader as Twig;
use Twig_Loader_Filesystem as LoadFile;
use Twig_Environment as Environment;
use Twig_Lexer as Lexer;
/**
 * Render of view.
 * 
 * @category   core
 * @package    app_core
 * @subpackage view
 * @author     jorge mendez ortega <jorge.mendez.ortega@gmail.com> 
 */
class View
{
	/**
	 * Begin the render of views.
	 * 
	 * @param string $view Name of view.
	 * @param string $url  Url of page.
	 * 
	 */
	public function __construct($view = "", $url = "")
	{
		$this->config();
		self::$view      = $view . EXTENSION_TEMPLATES;
		self::$var       = new \stdClass();
		self::$var->URL  = URL;
	}
	
	//===============================================//

	/**
	 * Set the layout for the view.
	 * 
	 * @param string $layout Name of layout.
	 * @return void
	 */
	protected function setLayout($layout = 'index')
	{
		self::$layout = $layout;
	}

	//===============================================//
	
	/**
	 * Get the name of layout.
	 * 
	 * @return string.
	 */
	protected function getLayout()
	{
		return self::$layout;
	}

	//===============================================//

	/**
	 * Disabled layout
	 * 
	 * @return void.
	 */
	protected function disabletLayout()
	{
		self::$layoutEnabled = false;
	}

	//===============================================//
	
	/**
	 * Render the view.
	 *
	 * @return void.
	 */
	public function __destruct()
	{
		if (self::$layoutEnabled)
		{
			try
			{
				$this->setVar();
				echo self::$twig->render(self::$layout . EXTENSION_TEMPLATES, ['content' => self::$layout . '/' . self::$view]);	
			}
			catch(Exception $e)
			{
				echo $e->getMessage();
				die();
			}
		}
	}
	
	//===============================================//	

	/**
	 * Set config for twig.
	 * 
	 * @return void.
	 */
	private function config()
	{
		Twig::register();
		$load       = new LoadFile([PATH_LAYOUTS, PATH_VIEWS]);
		self::$twig = new Environment($load);
		$lexer      = new Lexer(self::$twig, ['tag_comment'   => ['[#','#]'],
					        				  'tag_block'     => ['[%','%]'],
					        				  'tag_variable'  => ['[[',']]'],
					        				  'interpolation' => ['#[',']']]);
		self::$twig->setLexer($lexer);
	}

	//===============================================//	

	/**
	 * Set all variables.
	 *
	 * @return void.
	 */
	private function setVar()
	{
		$len  = count((array)self::$var);
		$twig = self::$twig;

		if ($len > 0)
		{
			foreach (self::$var as $key => $value)
			{
				$twig->addGlobal($key, $value);
			}
		}
	}

	//===============================================//	

	/**
	 * Name layout that render.
	 * 
	 * @var string
	 */
	static private $layout = 'index';
	/**
	 * Enabled or disbled the layout
	 * 
	 * @var boolean
	 */
	static private $layoutEnabled = true; 
	/**
	 * Instance of twig.
	 * 
	 * @var Twig_Environment
	 */
	static private $twig = '';
	/**
	 * Name of view.
	 * 
	 * @var stirng.
	 */
	static private $view = '';
	/**
	 * List of variables.
	 * 
	 * @var stdClass.
	 */
	static protected $var = '';

}