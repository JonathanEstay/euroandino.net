<?php

/* 
 * Proyecto : Euroandino.net
 * Autor    : Tsyacom Ltda.
 * Fecha    : Miercoles, 10 de octubre de 2014
 */

class Request
{
    private $_controlador;
    private $_metodo;
    private $_argumentos;
    
    public function __construct() {
        
        if(isset($_GET['url']))
        {
            $url= filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);//Limpia url
            $url= explode('/', $url);
            $url= array_filter($url); //arreglos no validos los elimina, (elimina '/' sobrantes)
            
            $this->_controlador= strtolower(array_shift($url)); //extrae el primer elemento del arreglo, y lo asigna
            $this->_metodo= strtolower(array_shift($url)); //extrae el primer elemento del arreglo, y lo asigna, viene sin el ontrolador
            $this->_argumentos= $url; //viene sin controlador ni metodo
        }
        
        if(!$this->_controlador)
        {
            $this->_controlador= DEFAULT_CONTROLLER;
        }

        if(!$this->_metodo)
        {
            $this->_metodo= 'index';
        }

        if(!$this->_argumentos)
        {
            $this->_argumentos= array();
        }
    }
    
    
    public function getControlador()
    {
        return $this->_controlador;
    }
    
    public function getMetodo()
    {
        return $this->_metodo;
    }
    
    public function getArgs()
    {
        return $this->_argumentos;
    }
}