<?php

namespace Modules;


use Modules\DB\DataBase as DB;
use Modules\DB\Factory as Test;
use Modules\Helpers\Helper as Helper;
use Pimple\Container as PimpleContainer;
use Exception;

class DependencyLoader
{
    //=====================================================================//
    // METODOS PUBLICOS                                                    //
    //=====================================================================//

    /**
     * Permite conseggir la instacioa de la clase.
     * 
     * @param String $name Nombre de la conexion.
     * 
     * @return Obj modules\DependencyLoader
     */
    public static function getInstance()
    {        
        if (!(self::$instance instanceof DependencyLoader))
        {
            self::$instance = new DependencyLoader();
        }
        return self::$instance;
    }

    /**
     * Permite la carga de las dependencias. 
     * 
     * @param  string $name Nombre de la dependencia ah cargar.
     * @return Instancia de la dependencia.
     */
    public function load($name = "")
    {
    	$request = (isset(self::$pimple[$name])) ? true : false;
    	
    	if (!$request)
    	{
    		throw new Exception("NO SE ENCUENTRA LA FUNCION \" " . $name . " \" " , 5);
    	}
    	return self::$pimple[$name];
    }

    //=====================================================================//
    // METODOS PRIVADOS                                                    //
    //=====================================================================//

    /**
     * Constructor.
     *
     * @return void.
     */
    private function __construct()
    {
    	$pimple = new PimpleContainer();
        
        //======= Inicia el llamado de las dependencias =======//

        $pimple["helper"] = function ($c) {
            return Helper::getInstance();
        };
        $pimple["dataBase"] = function ($c) {
            return DB::getInstance($c["helper"]);
        };
        $pimple["test"] = function ($c) {
            return new Test($c["dataBase"]);
        };

       //======= Termina el llamado de las dependencias =======//

    	self::$pimple = $pimple;
    }

    //=====================================================================//
    // VARIABLES PRIVADAS                                                  //
    //=====================================================================//

    /**
     * Intancia de la clase
     * 
     * @var modules\DependencyLoader
     */
    private static $instance = NULL;	
    /**
     * Intancia de la clase
     * 
     * @var modules\DependencyLoader
     */
    private static $pimple = NULL;
}