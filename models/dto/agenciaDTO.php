<?php

/* 
 * Proyecto : Euroandino.net
 * Autor    : Tsyacom Ltda.
 * Fecha    : Miercoles, 10 de octubre de 2014
 */

class agenciaDTO
{
    private $_id;
    private $_nombre;
    private $_imagen;
    
    public function setId($id)
    {
        $this->_id=$id;
    }
    public function getId()
    {
        return $this->_id;
    }
    
    public function setNombre($nombre)
    {
        $this->_nombre=$nombre;
    }
    public function getNombre()
    {
        return $this->_nombre;
    }
    
    public function setImagen($img)
    {
        $this->_imagen=$img;
    }
    public function getImagen()
    {
        return $this->_imagen;
    }
}