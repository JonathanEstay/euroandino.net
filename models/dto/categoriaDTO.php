<?php

/* 
 * Proyecto : Euroandino.net
 * Autor    : Tsyacom Ltda.
 * Fecha    : Miercoles, 10 de octubre de 2014
 */

class categoriaDTO
{
    private $_codigo;
    private $_nombre;
    
    public function setCodigo($cod)
    {
        $this->_codigo= $cod;
    }
    public function getCodigo()
    {
        return $this->_codigo;
    }
    
    public function setNombre($nombre)
    {
        $this->_nombre= $nombre;
    }
    public function getNombre()
    {
        return $this->_nombre;
    }
}