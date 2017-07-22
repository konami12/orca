<?php 


namespace Modules\Helpers;

class Helper
{
    //=====================================================================//
    // METODOS PUBLICOS                                                    //
    //=====================================================================//

	/**
	 * Permite codificar y de codificar una cadena.
	 * 
	 * @param  String $cadena Cadena ah decodificar.
	 * @return String.
	 */
	public function decode($cadena)
	{
        $cadena = base64_decode($cadena);
        $crypt = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, self::$key, $cadena, MCRYPT_MODE_CBC, md5(self::$key));
        return rtrim($crypt, "\0");
	}

    //=====================================================================//

	/**
	 * Permite codificar una cadena.
	 * 
	 * @param  String $cadena Cadena ah decodificar.
	 * @return String.
	 */
	public function encode($cadena)
	{
        $crypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, self::$key, $cadena, MCRYPT_MODE_CBC, md5(self::$key));
        return base64_encode($crypt);
	}

    //=====================================================================//

    /**
     * Permite conseggir la instacioa de la clase.
     * 
     * @param String $name Nombre de la conexion.
     * 
     * @return Obj modules\DependencyLoader
     */
	public function getInstance()
	{
        if (!(self::$instance instanceof Helper))
        {
            self::$instance = new Helper();
        }
        return self::$instance;
	}

    //=====================================================================//
    // METODOS PRIVADOS                                                    //
    //=====================================================================//

	private function __construct()
	{
		self::$key = md5(KEY);
	}

    /**
     * Intancia de la clase
     * 
     * @var modules\helpers\helper
     */
    private static $instance = NULL;
	private static $key = "";
}