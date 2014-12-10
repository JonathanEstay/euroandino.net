<?php

/* 
 * Proyecto : Euroandino.net
 * Autor    : Tsyacom Ltda.
 * Fecha    : Miercoles, 10 de octubre de 2014
 */

class Database
{
    protected $_conexion;
    
    public function __construct() {
        $this->_conexion= mssql_connect(DB_HOST, DB_USER, DB_PASS);
        /*
         * ODBC
         * $connection_string = 'DRIVER={SQL SERVER};SERVER='. $_TEMP["server"] . ';DATABASE=' . $_TEMP["database"]; 
         * $conexion = odbc_connect($connection_string, $_TEMP["username"], $_TEMP["password"]); 
         */
        if (!empty($this->_conexion)) {
            $bd = mssql_select_db(DB_NAME, $this->_conexion);
            if ($bd != 1) {
                throw new Exception('Base de datos no encontrada');
            } else {
                //mysql_set_charset('ISO-8859-1',$this->_conexion);
                return TRUE;
            }
        } else {
            throw new Exception('No se pudo conectar a la Base de datos');
        }
    }

    public function consulta($query) {
        //echo $query; exit;
        $rs = mssql_query($query, $this->_conexion);
        if (empty($rs)) {
            return FALSE;
        } else {
            return $rs;
        }
    }

    public function fetchAll($consulta) {
        $arrayFetch = array();
        while ($reg = mssql_fetch_array($consulta)) {
            $arrayFetch[] = $reg;
        }

        return $arrayFetch;
    }

    public function numRows($consulta) {
        return mssql_num_rows($consulta);
    }

    public function closeConex() {
        return mssql_close($this->conexion);
    }

}