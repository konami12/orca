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
	 */
	public function __construct($view = "")
	{
		Twig::register();
		$load       = new LoadFile([PATH_LAYOUTS, PATH_VIEWS]);
		self::$twig = new Environment($load);
		$lexer      = new Lexer(self::$twig, ['tag_comment'   => ['[#','#]'],
					        				  'tag_block'     => ['[%','%]'],
					        				  'tag_variable'  => ['[[',']]'],
					        				  'interpolation' => ['#[',']']]);
		self::$twig->setLexer($lexer);
		self::$view = $view . EXTENSION_TEMPLATES;
		self::$var  = new \stdClass();
	}
	
	//===============================================//

	/**
	 * Set the layout for the view.
	 * 
	 * @param string $layout Name of layout.
	 * @return void
	 */
	protected static function setLayout($layout = 'index')
	{
		self::$layout = $layout;
	}
	
	//===============================================//
	
	/**
	 * Get the name of layout.
	 * 
	 * @return string.
	 */
	protected static function getLayout()
	{
		return self::$layout;
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
			$this->setVar();
			echo self::$twig->render(self::$layout . EXTENSION_TEMPLATES , ['content' => self::$view]);
		}
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

	static private $twig   = '';
	static private $view   = '';
	static private $layout = 'index';
	static protected $var    = '';
	static protected $layoutEnabled = true; 

}