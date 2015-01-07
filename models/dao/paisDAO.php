<?php

/* 
 * Proyecto : Euroandino.net
 * Autor    : Tsyacom Ltda.
 * Fecha    : Miercoles, 10 de octubre de 2014
 */

class paisDAO extends Model 
{
    public function __construct() {
        parent::__construct();
    }
    
    public function getPais($name) {
        $sql = 'SELECT codigo, nombre FROM pais WHERE nombre = "'.$name.'" ';
        $datos = $this->_db->consulta($sql);
        if($this->_db->numRows($datos)) {
            
        } else {
            return false;
        }
    }
}