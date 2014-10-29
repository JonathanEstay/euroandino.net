<?php

/* 
 * Proyecto : Euroandino.net
 * Autor    : Tsyacom Ltda.
 * Fecha    : Miercoles, 10 de octubre de 2014
 */

class indexController extends Controller
{
    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
        //$this->_view->titulo='Iniciar sesi&oacute;n';
        //$this->_view->renderizaPrincipal('login');
        header('Location: ' . BASE_URL . 'login');
    }
}

?>