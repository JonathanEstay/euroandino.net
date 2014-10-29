<?php

/* 
 * Proyecto : Euroandino.net
 * Autor    : Tsyacom Ltda.
 * Fecha    : Miercoles, 10 de octubre de 2014
 */

class Model
{
    protected $_db;
    public function __construct() {
        $this->_db= new Database;
    }
}