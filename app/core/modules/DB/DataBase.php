<?php
namespace Modules\DB;


use Modules\DB\Configure as Config;
use Modules\Helpers\Helper as Help;
use PDO;
use ArrayIterator;

/**
 * Clase para configurar conexion.
 * 
 * @package    Corpsite
 * @subpackage library_DataBase
 * @author     Jorge Mendez Ortega <jorge.mendez.ortega@gmail.com>
 */
class DataBase extends Config
{
    //=====================================================================//
    // METODOS PUBLICOS                                                    //
    //=====================================================================//

    /**
     * Permite borrar un registro.
     * 
     * @param string $table Nombre de la tabla.
     * 
     * @return self Fluent interface.
     */
    public function delete($table = '')
    {
        $query       = 'DELETE FROM '.$table;
        $this->query = $query . $this->query;
        $this->type = 'D';
        return $this;
    }

    //=====================================================================//

    /**
     * Eejecuta el query armado.
     *
     * @return resultado del query.
     */
    public function execute()
    {
        $odbc    = $this->odbc;
        $type    = $this->type;
        $request = NULL;

        $odbc->beginTransaction();
        $stm = $this->prepareStatement();

        switch ($type)
        {
            case 'D':
            case 'U':
                $request = $stm->rowCount();
            break;
            case 'I':
                $request = $odbc->lastInsertId();
            break;
            case 'S':
                $request = $stm->fetchAll(PDO::FETCH_ASSOC);
            break;
            case 'Q':
                $stm->execute();
                $request = $stm->fetchAll(PDO::FETCH_ASSOC);
            break;
        }

        $odbc->commit();
        $this->values = [0 => [], 1 => []];
        return $request;
    }    

    //=====================================================================//

    /**
     * Permite conseggir la instacioa de la clase.
     * 
     * @param Help   $helper Instancaia de Modules\Helper\Help.
     * 
     * @return Obj library/DataBase/DataBase;
     */
    public static function getInstance(Help $helper)
    {        
        if (!(self::$instance instanceof DataBase))
        {
            self::$instance = new DataBase($helper);
        }
        return self::$instance;
    }

    //=====================================================================//    
    
    /**
     * Muestra el query armado
     *
     * @return string.
     */
    public function getQuery()
    {
        return $this->query;
    }

    //=====================================================================//

    /**
     * Crea el query para insertar un nuevo registro.
     *
     * @param string $table Nombre de la tabla.
     * @param array  $field Campos.
     * 
     * @return self Fluent interface.
     */
    public function insert($table = '', array $field = [])
    {
        $query           = 'INSERT INTO ' . $table . ' (';
        $values          = 'VALUES (';
        $field           = new ArrayIterator($field);

        $this->values[0] = $field;

        while ($field->valid())
        {
            $query  .= $field->key() . ', ';
            $values .= ' ?, ';
            $field->next();
        }

        $query       = substr($query, 0, -2) . ')';
        $values      = substr($values, 0, -2) . ')';
        $this->query = $query . $values;
        $this->type = 'I';
        return $this;
    }    

    //=====================================================================//

    /**
     * Permite crear un query perzonalizado.
     * 
     * @param  string $query Sentencia Sql.
     * 
     * @return self Fluent interface.
     */
    public function query($query = '')
    {
        $this->query = $query;
        $this->type  = "Q";
        return $this; 
    }

    //=====================================================================//

    /**
     * Creacion de sentencia Where Like
     *
     * @param array  $field Campos.
     * @param string $logic Operador logico. 
     * 
     * @return self Fluent interface.
     */
    public function like(array $field = [], $logic = 'AND')
    {
        $query = $this->_where($field, $logic);
        $field = new ArrayIterator($field);

        while ($field->valid())
        {
            $query .= $field->key() . ' LIKE ? ' . $logic . ' '; 
            $key    = $field->key();
            $val    = $field->offsetGet($key);

            $field->offsetSet($key, '%' . $val . '%'); 
            $field->next();
        }

        $len = (strlen($logic) + 2) * -1; 
        
        $query           = substr($query, 0, $len);
        $this->query     .= $query;
        return $this;
    }

    //=====================================================================//

    /**
     * permite limitar los resultados obtenidos
     *
     * @param int $limit Numero de elementos a mostrar.
     * 
     * @return self Fluent interface.
     */
    public function limit($limit = 10)
    {
        $this->query     .= ' LIMIT ' . $limit;
        return $this;
    }

    //=====================================================================//

    /**
     * [order description]
     * @param  string $field Campo por el cual se ordenara.
     * @param  string $order Cual sera el orden ah realizar.
     * 
     * @return self Fluent interface.
     */
    public function order($field, $order = 'ASC')
    {
        $this->query .= ' ORDER BY ' . $field . ' ' .$order;
        return $this;
    }
    
    //=====================================================================//

    /**
     * Crear query para consulta
     *
     * @param string $table Nombre de la tabla.
     * @param string $field Campos.
     *
     * @return self Fluent interface.
     */
    public function select($table = '', $field = '*')
    {
        $query = 'SELECT ' . $field . ' FROM '. $table;
        $this->query = $query . $this->query;
        $this->type = 'S';
        return $this;
    }

    //=====================================================================//

    /**
     * Create query para actualizar un registro.
     *
     * @param string $table Nombre de la tabla.
     * @param array  $field Campos.
     * 
     * @return self Fluent interface.
     */
    public function update($table = '', array $field = [])
    {
        $query           = 'UPDATE ' . $table . ' SET ';
        $values          = '';
        $field           = new ArrayIterator($field);
        $this->values[0] = $field;
        
        while ($field->valid())
        {
            $values .= $field->key() . ' = ?, '; 
            $field->next();
        }
        
        $values = substr($values, 0, -2);
        $this->query = $query . $values . $this->query;
        $this->type = 'U';
        return $this;
    }

    //=====================================================================//
    
    /**
     * Crea sentencia query.
     *
     * @param array  $field Campos.
     * @param string $logic Operador logico. 
     * 
     * @return self Fluent interface.
     */
    public function where(array $field = [], $logic = 'AND')
    {
        $query = $this->_where($field, $logic);
        $field = new ArrayIterator($field);

        while ($field->valid())
        {
            $query .= $field->key() . ' = ? ' . $logic . ' '; 
            $field->next();
        }

        $len = (strlen($logic) + 2) * -1; 
        
        $query       = substr($query, 0, $len);
        $this->query .= $query;
        return $this;
    }

    //=====================================================================//
    // METODOS PRIVADOS                                                    //
    //=====================================================================//
    
    /**
     * Constructor.
     *
     * @param Help   $helper Instancaia de Modules\Helper\Help.
     *
     * @return void.
     */
    private function __construct(Help $helper)
    {
        $this->prepareConnection($helper);
        $this->setConnection();
    }

    //=====================================================================//

    /**
     * Cierra la conexion establecisa.
     *
     * @return void.
     */
    private function closeConnection()
    {
        $this->odbc = null;
        unset($this->odbc);
    }

    //=====================================================================//
    
    /**
     * Prepara el query.
     *
     * @return void.
     */
    private function prepareStatement()
    {
        $query        = $this->query;
        $odbc         = $this->odbc;
        $stm          = $odbc->prepare($query);
        $values       = $this->values;
        $cont         = 1;
        $this->query  = '';
        $this->values = NULL;

        if ($values !== NULL)
        {
            foreach ($values as $key => $value)
            {
                foreach ($value as $key2  => $value2)
                {
                    $stm->bindValue($cont, strtolower($value2));
                    $cont++;
                }
            }
        }

        $stm->execute();
        $errorCode = (int)$stm->errorCode();

        if($errorCode > 0)
        {
           $stm = "Msg Error <b>( " . implode(" :: ", $stm->errorInfo()) . " )<b/>";
           echo ($stm);
           die();
        }

        return $stm;
    }

    //=====================================================================//
    
    /**
     * Permite establecer la conexion.
     *
     * @return void.
     */
    private function setConnection()
    {
        try
        {
            $strConnect = $this->getStrConnect();
            $user       = $this->getUser();
            $password   = $this->getPassword();
            $this->odbc = new PDO($strConnect, $user, $password);
            $this->odbc->exec("set names utf8");
        }
        catch(PDOExeption $e)
        {
            echo "Warning of connection ". $e->getMessage() . "...!";
            die();
        }
    }

    //=====================================================================//
    
    /**
     * Permite concatenar opciones de un where.
     *
     * @return string.
     */
    private function _where($field, $logic)
    {
        $query           = (strpos($this->query, ' WHERE ')) ? ' ' . $logic . ' ' : ' WHERE ';
        $this->values[1] = array_merge($this->values[1], $field);
        return $query;
    }

    //=====================================================================//
    // DESTRUCTOR                                                          //
    //=====================================================================//
    
    /**
     * Destructor
     *
     * @return void.
     */
    public function __destruct()
    {
        $this->closeConnection();
    }

    //=====================================================================//
    // VARIABLES PRIVADAS                                                  //
    //=====================================================================//
    /**
     * Intancia de la clase
     * @var library/DataBase/DataBase
     */
    private static $instance = NULL;
    /**
     * Connector.
     * @var object.
     */
    private $odbc = NULL;
    /**
     * Password para la conexion.
     * @var string.
     */
    private $query = '';
    /**
     * Statement 
     *
     * @var object
     */
    private $stm = null;
    /**
     * Cadena de conexion.
     * @var string.
     */
    private $type = NULL;
    /**
     * Valores a cargar en el query.
     * @var Array.
     */
    private $values = [0 => [], 1 => []];

}