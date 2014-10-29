<?php

/* 
 * Proyecto : Euroandino.net
 * Autor    : Tsyacom Ltda.
 * Fecha    : Miercoles, 10 de octubre de 2014
 */

class bloqueoDTO
{
    private $_notas;
    
    public function setNotas($notas)
    {
        $this->_notas=$notas;
    }
    public function getNotas()
    {
        return $this->_notas;
    }
}