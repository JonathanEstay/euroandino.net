<?php

/* 
 * Proyecto : Euroandino.net
 * Autor    : Tsyacom Ltda.
 * Fecha    : Miercoles, 10 de octubre de 2014
 */

class View
{
    private $_controlador;
    private $_js;
    
    public function __construct(Request $peticion) { //$peticion es un objeto de Request
        $this->_controlador= $peticion->getControlador();
        $this->_js=array();
    }
    
    
    public function setJs(array $js)
    {
        if(is_array($js) && count($js)){
            for($i=0; $i < count($js); $i++){
                //$this->_js[] = BASE_URL . 'views/' . $this->_controlador . '/js/' . $js[$i] . '.js';
                $this->_js[] = BASE_URL . 'public/js/' . $js[$i] . '.js';
            }
        } else {
            throw new Exception('Error de js');
        }
    }
    
    
    public function renderingMain($vista, $item=false) //principal
    {
        //se incluye directamente el '/' ya que estas rutas siempre van a ser asi
        $_layoutParams= array(
            'ruta_css' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/css/', 
            'ruta_img' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/img/', 
            'ruta_js' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/js/'
        );
        $rutaView= ROOT . 'views' . DS . $this->_controlador . DS . $vista . '.phtml';
        
        if(is_readable($rutaView))
        {
            //include_once ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'header.php';
            //include_once ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'menu-left.php';
            include_once $rutaView;
            //include_once ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'footer.php';
        }
        else
        {
            throw new Exception('Error de vista: '.$rutaView);
        }
    }
    
    
    
    
    
    public function renderingSystem($vista, $item=false)
    {
        $js = array();
        
        if(count($this->_js)){
            $js = $this->_js;
        }
        
        //se incluye directamente el '/' ya que estas rutas siempre van a ser asi
        $_layoutParams= array(
            'ruta_css' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/css/', 
            'ruta_img' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/img/', 
            'ruta_js' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/js/',
            'ruta_hoteles' => BASE_URL . 'public/img/hoteles/',
            'js' => $js
        );
        $rutaView= ROOT . 'views' . DS . $this->_controlador . DS . $vista . '.phtml';
        
        if(is_readable($rutaView))
        {
            include_once ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'header.php';
            include_once ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'left.php';
            include_once $rutaView;
            include_once ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'footer.php';
        }
        else
        {
            throw new Exception('Error de vista: '.$rutaView);
        }
    }
    
    
    
    
    public function renderingCenterBox($vista, $item=false)
    {
        //se incluye directamente el '/' ya que estas rutas siempre van a ser asi
        $_layoutParamsCB= array(
            'ruta_css' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/css/', 
            'ruta_img' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/img/', 
            'ruta_js' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/js/',
            'ruta_pdf' => BASE_URL . 'public/pdf/',
            'ruta_voucher' => BASE_URL . 'public/img/voucher/',
            'ruta_fotos_hab' => BASE_URL . 'public/img/tipo_habitacion/',
            'ruta_fotos_hotel' => BASE_URL . 'public/img/hoteles/',
            'ruta_fotos_hotel_thumb' => BASE_URL . 'public/img/hoteles/thumb/thumb_',
            'ruta_iconos_hotel' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/img/hotel/'
        );
        
        $rutaView= ROOT . 'views' . DS . $this->_controlador . DS . 'centerBox' . DS . $vista . '.phtml';
        if(is_readable($rutaView))
        {
            include_once $rutaView;
        }
        else
        {
            throw new Exception('Error de vista AJAX');
        }
    }
    
}