<?php

namespace Core;

use Twig_Autoloader as Twig;
use Twig_Loader_Filesystem as LoadFile;
use Twig_Environment as Environment;
use Twig_Lexer as Lexer;
use Modules\DependencyLoader as Loader;

/**
 * Renderea la vista.
 * 
 * @category   core
 * @package    app_core
 * @subpackage view
 * @author     jorge mendez ortega <jorge.mendez.ortega@gmail.com> 
 */
class View
{
	/**
	 * Inicia el rendereo de las vistas.
	 *
	 * @param string $controller Nombre del controlador.
	 * @param string $view       Nombre de la vista.
	 * @param string $url        Url de la pagina.
	 * 
	 */
	public function __construct($config)
	{
		$this->config();
		self::$view      = $config['view'] . EXTENSION_TEMPLATES;
		self::$var       = new \stdClass();
		self::$var->URL  = URL;
		self::$var->PAGE = URL . $config['url'];
		self::$content   = $config['url'];
		self::$loader    = Loader::getInstance();
 	}
	
	//===============================================//

	/**
	 * Asigna el layout para la vista.
	 * 
	 * @param string $layout Nombre del layout.
	 * @return void.
	 */
	protected function setLayout($layout = 'index')
	{
		self::$layout = $layout;
	}

	//===============================================//
	
	/**
	 * Consigue el nombre del layout.
	 * 
	 * @return string.
	 */
	protected function getLayout()
	{
		return self::$layout;
	}

	//===============================================//

	/**
	 * Deshabilita el layout de la vista;
	 * 
	 * @return void.
	 */
	protected function disabletLayout()
	{
		self::$layoutEnabled = false;
	}

	//===============================================//
	
	/**
	 * Renderea la vista.
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
				echo self::$twig->render(self::$layout . EXTENSION_TEMPLATES, ['content' => self::$content . EXTENSION_TEMPLATES]);	
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
	 * Prepara la configuracion para twig.
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
	 * Pasa las variables a la vista.
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
	 * Listado de variables.
	 * 
	 * @var stdClass.
	 */
	static protected $var = '';
	/**
	 * Instancide de DependencyLoader.
	 * 
	 * @var Modules\DependencyLoader.
	 */
	static protected $loader = '';
	/**
	 * Nombre del layout que se desa renderear.
	 * 
	 * @var string
	 */
	static private $layout = 'index';
	/**
	 * Deshabilita el template.
	 * 
	 * @var boolean
	 */
	static private $layoutEnabled = true; 
	/**
	 * Instancia de twig.
	 * 
	 * @var Twig_Environment
	 */
	static private $twig = '';
	/**
	 * Nombre de la vista.
	 * 
	 * @var stirng.
	 */
	static private $view = '';
	/**
	 * Contenido por default.
	 *
	 * @var string.
	 */
	static private $content = 'index/index';
}