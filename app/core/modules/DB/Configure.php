<?php

namespace Modules\DB;

use Modules\Helpers\Helper as Help;
use Exception;

/**
 * Clase para configurar conexion.
 * 
 * @package    Corpsite
 * @subpackage library_DataBase
 * @author     Jorge Mendez Ortega <jorge.mendez.ortega@gmail.com>
 */
class Configure
{
    //=====================================================================//
    // METODOS PROTEGIDOS                                                  //
    //=====================================================================//

    /**
     * Constructor.
     *
     * @param String $name Nombre de la conexion.
     *
     * @return void.
     */
    protected function prepareConnection(Help $helper)
    {
        $this->strConnect .= $helper->decode(BD_BASE);
        $this->user        = $helper->decode(BD_USER);
        $this->password    = $helper->decode(BD_PASS);
    }

    //=====================================================================//

    /**
     * Consige el password de conexion.
     *
     * @return string.
     */
    protected function getPassword()
    {
        return $this->password;
    }

    //=====================================================================//

    /**
     *  Consigue el user de conexion.
     *
     * @return string.
     */
    protected function getUser()
    {
        return $this->user;
    }

    //=====================================================================//

    /**
     *  Consige cadesa de conexion.
     *
     * @return string.
     */
    protected function getStrConnect()
    {
        switch(CONNECTION_TYPE)
        {
            case "mys":
            default :
                $odbc = "mysql:host=localhost;dbname=";
            break;
            case "oci":
                $odbc = "oci:dbname=";
            break;
            case "mss":
             $odbcs = "qlsrv:Server={server};Database=";
            break;

        }
        return $odbc . $this->strConnect;
    }

    //=====================================================================//
    /// Variable Private                                                   //
    //=====================================================================//

    /**
     * Password para login.
     * @var string.
     */
    private $password = null;
    /**
     * Cadena de conexion.
     * @var string.
     */
    private $strConnect = null;
    /**
     * Usuario de conexion.
     * @var string.
     */
    private $user = null;
}