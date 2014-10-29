<?php

/* 
 * Proyecto : Euroandino.net
 * Autor    : Tsyacom Ltda.
 * Fecha    : Miercoles, 10 de octubre de 2014
 */

class loginController extends Controller
{
    private $_login;
    
    public function __construct() {
        parent::__construct();
        $this->_login= $this->loadModel('usuario'); // para cargar el modelo para todo el controller
    }
    
    /*******************************************************************************
    *                                                                              *
    *                                METODOS VIEWS                                 *
    *                                                                              *
    *******************************************************************************/
    
    public function index()
    {
        $this->_view->titulo='Iniciar sesi&oacute;n';
        $this->_view->renderingMain('login');
        $this->_alertDestroy();
    }
    
    public function cambioPass()
    {
        $this->_view->titulo='Cambio de contrase&ntilde;a';
        $this->_view->renderingMain('cambio_pass');
        $this->_alertDestroy();
    }
    
    
    
    
    
    
    /*******************************************************************************
    *                                                                              *
    *                               METODOS PRIVADOS                               *
    *                                                                              *
    *******************************************************************************/
    private function _alert($tipo=false, $msg=false)
    {
        Session::set('sess_alerts', $tipo); //Tipo alerta
        Session::set('sess_alerts_msg', $msg);
    }
    
    private function _alertDestroy()
    {
        Session::destroy('sess_alerts');
        Session::destroy('sess_alerts_msg');
    }
    
    
    
    
    
    
    
    /*******************************************************************************
    *                                                                              *
    *                             METODOS PROCESADORES                             *
    *                                                                              *
    *******************************************************************************/
    
    public function ingresar()
    {
        $LC_user= strtolower($this->getTexto('txtUser'));
        $LC_pass= strtolower($this->getTexto('txtPass'));        
        
        if(!empty($LC_user) && !empty($LC_pass))
        {   
            $objUsuarios= $this->_login->getUsuarios($LC_user);
            
            if($objUsuarios!=false)
            {
                if(strtolower($objUsuarios[0]->getClave())==$LC_user && $objUsuarios[0]->getPassword()==$LC_pass)
                {
                    
                    ############################################################################
                    //EJECUTANDO STORED PROCEDURE
                    $sp_perfilClave= $this->_login->sp_perfilClave($LC_user, 'FILEWEB');
                    if($sp_perfilClave!=false)
                    {
                        if(trim($sp_perfilClave[0]['acceso']) != 'S')
                        {
                            $this->_alert(2, 'Error al verificar usuario <b>ACCESO</b>.');
                            $this->redireccionar(); //nopermited1
                        }
                    }
                    else
                    {
                        $this->_alert(2, 'Error al verificar usuario <b>FILEWEB</b>.');
                        $this->redireccionar(); //nopermited2
                    }
                    ############################################################################
                    
                    
                    
                    if($objUsuarios[0]->getCambioPass()=='1')
                    {
                        Session::set('sess_User', $LC_user);
                        Session::set('sess_nombreUser', $objUsuarios[0]->getNombre());
                        
                        
                        $this->redireccionar('login/cambioPass/' . session_id());
                    }
                    
                    //echo '..::fin::..'; exit;
                    

                    Session::set('Autenticado', true);
                    Session::set('sess_key_', md5(uniqid()));
                    Session::set('sess_ip', $_SERVER["REMOTE_ADDR"]);
                    Session::set('sess_fechaLogin', date("d/m/Y H:i:s"));
                    Session::set('sess_fechaDefault', Functions::fechaActual(1));
                    
                    
                    Session::set('sess_clave_usuario', $objUsuarios[0]->getClave());
                    Session::set('sess_nombre', $objUsuarios[0]->getNombre());
                    Session::set('sess_cod_ven', $objUsuarios[0]->getCodigo());
                    
                    Session::set('sess_dctod', $objUsuarios[0]->getDoctoD());
                    Session::set('sess_dctoh', $objUsuarios[0]->getDoctoH());

                    Session::set('sess_agencia', $objUsuarios[0]->getAgencia());
                    Session::set('sess_id_agen', $objUsuarios[0]->getIdAgen());
                    
                    Session::set('sess_markup', $objUsuarios[0]->getMarkup());
                    Session::set('sess_fecpass', $objUsuarios[0]->getFechaPass());
                    
                    Session::set('sess_depto', $objUsuarios[0]->getDepto());
                    Session::set('sess_atipoa', $objUsuarios[0]->getAtipoa());
                    
                    Session::set('sess_firma', $objUsuarios[0]->getFirma());
                    Session::set('sess_email', $objUsuarios[0]->getEmail());
                    Session::set('sess_email_opera', $objUsuarios[0]->getEmailOpera());
                    
                    

                    Session::set('level', 'Admin');//Validar tipo de usuario
                    Session::set('tiempo', time());
                        
                    
                    
                    ############################################################################
                    //EJECUTANDO STORED PROCEDURE
                    $sp_perfilClave= $this->_login->sp_perfilClave($LC_user, 'ADMWEB');
                    if($sp_perfilClave!=false)
                    {
                        Session::set('sess_sp_acceso', trim($sp_perfilClave[0]['acceso']));
                    }
                    else
                    {
                        Session::set('sess_sp_acceso', 0);
                    }
                    ############################################################################
                    
                    $this->redireccionar('system');
                }
                else
                {
                    $this->_alert(2, '<b>Usuario</b> o <b>Contrase&ntilde;a</b> Incorrectos.');
                    $this->redireccionar(); //Error Usuario o Pass
                }
            }
            else
            {
                $this->_alert(2, 'El usuario <b>no &eacute;xiste</b> o No tiene <b>agencia asignada</b>.');
                $this->redireccionar(); //No existe
            }
        }
        else
        {
            $this->_alert(2, 'Debe ingresar su <b>Usuario</b> y <b>Contrase&ntilde;a</b>.');
            
            $this->redireccionar(); //Ingrese un usuario o Pass
        }
    }
    
    public function recoveryPass()
    {
        $LC_user= strtolower($this->getTexto('txtUser'));
        
        if(!empty($LC_user))
        {   
            $objUsuarios= $this->_login->getUsuarios($LC_user);
            
            if($objUsuarios!=false)
            {
                if(strtolower($objUsuarios[0]->getClave())==$LC_user)
                {
                    
                    $rutaHTML= ROOT . 'views' . DS . 'mail' . DS . 'recovery_pass.html';
                    if(is_readable($rutaHTML))
                    {
                        //PARSEA MAIL HTML
                        $LC_HTML=file_get_contents($rutaHTML);
                        $LC_nodosHTML["nombre"]=$objUsuarios[0]->getNombre();
                        $LC_nodosHTML["user"]=$LC_user;
                        $LC_nodosHTML["pass"]=$objUsuarios[0]->getPassword();
                        foreach($LC_nodosHTML as $LC_nombreNodo=>$LC_valorNodo):
                            $LC_HTML= str_replace('{'.$LC_nombreNodo.'}', $LC_valorNodo, $LC_HTML);
                        endforeach;

                        //echo $LC_HTML; exit;
                        //echo $objUsuarios[0]->getEmail(); exit;
                        $this->getLibrary('class.phpmailer');
                        $mail = new PHPMailer();
                        $mail->Host = trim("190.196.23.232");
                        $mail->Port = 25;
                        $mail->From = 'allways@online.oristravel.com';
                        $mail->FromName = "Allways Travel Group";
                        $mail->CharSet = 'UTF-8';
                        $mail->Subject = 'Recuperar password';
                        $mail->MsgHTML($LC_HTML);

                        $mail->AddAddress($objUsuarios[0]->getEmail(), "");
                        //$mail->AddAddress("destino2@correo.com","Nombre 02"); //Para

                        //$mail->AddCC('destino@correo.com'); //Copia
                        //$mail->AddCC('destino2@correo.com'); //Copia
                        //$mail->AddBCC('destino@correo.com'); //Copia oculta

                        $mail->SMTPAuth = true;
                        $mail->Username = trim("online@panamericanaturismo.cl");
                        $mail->Password = trim("Fe90934");
                        $mail->Send();
                        
                        $this->redireccionar('login');
                    }
                    else
                    {
                        throw new Exception('Error al cargar el MAIL de recuperar contrase&ntilde;a', $rutaDTO);
                    }
                }
                else
                {
                    $this->redireccionar('login/cambioPass');
                }
            }
            else
            {
                $this->redireccionar('login/cambioPass');
            }
        }
        else
        {
            $this->redireccionar('login/cambioPass');
        }
    }
}

?>