<?php

/* 
 * Proyecto : Euroandino.net
 * Autor    : Tsyacom Ltda.
 * Fecha    : Miercoles, 10 de octubre de 2014
 */

class Model
{
    private $_registry;
    protected $_db;
    
    public function __construct() {
        $this->_registry = Registry::getInstancia();
        $this->_db= $this->_registry->_db;
    }
}