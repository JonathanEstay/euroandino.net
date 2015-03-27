<?php

/* 
 * Proyecto : Euroandino.net
 * Autor    : Tsyacom Ltda.
 * Fecha    : Miercoles, 10 de octubre de 2014
 */

class Registry
{
    private static $_instancia;
    private $_data;
     
   //No se puede instanciar la clase (Solamente se puede instanciar desde dentro de la clase)
    private function __construct() { }
    
    //Singleton
    public static function getInstancia() {
        if(!self::$_instancia instanceof self) { //Si no contiene una instancia del registro
            self::$_instancia = new Registry();
        }
        
        return self::$_instancia;
    }
    
    public function __set($name, $value) { //Sobreescribiendo metodo magico set
        $this->_data[$name] = $value;
    }
    
    public function __get($name) {
        if(isset($this->_data[$name])) {
            return $this->_data[$name];
        }
        
        return false;
    }
}