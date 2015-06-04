<?php

/* 
 * Proyecto : Euroandino.net
 * Autor    : Tsyacom Ltda.
 * Fecha    : Miercoles, 10 de octubre de 2014
 */

class programasController extends Controller
{
    private $_ciudad;
    
    public function __construct() {
        parent::__construct();
        $this->_ciudad= $this->loadModel('ciudad');
        $this->_loadLeft();
    }
    
    
    
    
    
    /*******************************************************************************
    *                                                                              *
    *                                METODOS VIEWS                                 *
    *                                                                              *
    *******************************************************************************/
    public function index() {
        Session::acceso('Usuario');
        $this->_view->setJS(array('validaCampos', 'programas'));
        
        //$this->getLibrary('kint/Kint.class');
        
        $this->_view->ML_fechaIni_PRG= Session::get('sess_BP_fechaIn_PRG');
        $this->_view->ML_fechaFin_PRG= Session::get('sess_BP_fechaOut_PRG');
        
        $this->_view->objCiudades= $this->_ciudad->getCiudadesBloq();
        $this->_view->objCiudadesPRG= $this->_ciudad->getCiudades();
        if(Session::get('sess_BP_ciudadDes_PRG')) {
            
            //$this->loadDTO('incluye');
            $programas= $this->loadModel('programa');
            
            //WEB
            //$sql="EXEC TS_GET_PROGRAMAS '".Session::get('sess_BP_ciudadDes_PRG')."', '', '".Functions::invertirFecha(Session::get('sess_BP_fechaIn_PRG'), '/', '-')."' ";
            
            //Local
            $sql="EXEC TS_GET_PROGRAMAS '".Session::get('sess_BP_ciudadDes_PRG')."', '', '".str_replace('/', '-', Session::get('sess_BP_fechaIn_PRG'))."' ";
            
            Session::set('sess_TS_GET_PROGRAMAS', $sql);
            //IDecho $sql; exit;
            
            //Kint::dump( $programas->exeTS_GET_PROGRAMAS($sql) );
            $this->_view->objCiudadBs= $this->_ciudad->getCiudades(Session::get('sess_BP_ciudadDes_PRG'));
            $this->_view->objProgramas= $programas->exeTS_GET_PROGRAMAS($sql);
            //$this->_view->objProgramasCNT = count($this->_view->objProgramas);
        }
        
        
        
        //Session::destroy('sess_BP_ciudadDes');
        $this->_view->currentMenu=22;
        $this->_view->procesoTerminado=false;
        $this->_view->titulo='ORISTRAVEL';
        $this->_view->renderingSystem('programas');
    }
    
    
    public function admin() {
        Session::acceso('Usuario');
        
        $this->_view->objCiudades= $this->_ciudad->getCiudadesBloq();
        $this->_view->objCiudadesPRG= $this->_ciudad->getCiudades();
        
        
        if(Session::get('sess_AP_ciudad')) {
            $programas= $this->loadModel('programa');
            //getAdmProgramas
            $this->_view->objProgramas= $programas->getAdmProgramas(Session::get('sess_AP_ciudad'));
        }
        
        
        $this->_view->currentMenu=3;
        $this->_view->titulo='ORISTRAVEL';
        $this->_view->renderingSystem('adminProgramas');
    }
    
    
    
    
    
    
    
    
    
    
    /*******************************************************************************
    *                                                                              *
    *                          METODOS VIEWS CENTER BOX                            *
    *                                                                              *
    *******************************************************************************/
    public function detalle() {
        
        Session::acceso('Usuario');
        $programas= $this->loadModel('programa');
        
        if($this->getInt('__SP_id__')) {
            $sql="EXEC TS_GET_PROGRAMAS_ID " . $this->getInt('__SP_id__');
            //Session::set('sess_TS_GET_PROGRAMAS_ID', $sql);
            //echo $sql; exit;
            $this->_view->objProgramas= $programas->exeTS_GET_PROGRAMAS($sql);
            
            
            //Local
            $sql="EXEC TS_GET_DETALLEPROG " . $this->getInt('__SP_id__');
            
            Session::set('sess_TS_GET_DETALLEPROG', $sql);
            //echo $sql; //exit;
            
            $objOpcProgramas= $programas->exeTS_GET_DETALLEPROG($sql);
            if($objOpcProgramas) {
                if($objOpcProgramas[0]->getError()) {
                    throw new Exception('<b>Error</b>: [' . $objOpcProgramas[0]->getError() . '] <br> <b>Mensaje</b>: ['.$objOpcProgramas[0]->getMensaje().']');
                } else {
                    
                    $this->_view->objOpcProgramas= $objOpcProgramas;
                    //$this->_view->hoteles= $this->_view->objOpcProgramas[0]->getNombreHotel();
                    $this->_view->renderingCenterBox('detalleProg');
                    
                }
            } else {
                throw new Exception('Error al cargar las opciones');
            }
            
        } else {
            throw new Exception('Error al cargar las opciones');
        }
    }
    
    
    public function pasajeros() {
        Session::acceso('Usuario');
        if(strtolower($this->getServer('HTTP_X_REQUESTED_WITH'))=='xmlhttprequest') {
            
            if($this->getInt('_PP_')) {
                $this->_view->cntP= $this->getInt('_PP_');
                Session::set('sess_SGL', $this->getInt('_SGL_'));
                Session::set('sess_DBL', $this->getInt('_DBL_'));
                Session::set('sess_TPL', $this->getInt('_TPL_'));
                Session::set('sess_moneda', $this->getTexto('_MON_'));
                Session::set('sess_claveOpc', $this->getTexto('_OPC_'));
                
                $this->_view->renderingCenterBox('pasajeros');
            } else {
                throw new Exception('Error al cargar las opciones');
            }
            
        } else {
            throw new Exception('Error al cargar los pasajeros');
        }
    }
    
    
    public function detallePasajeros() {
        Session::acceso('Usuario');
        if(strtolower($this->getServer('HTTP_X_REQUESTED_WITH'))=='xmlhttprequest') {
            $totalPago=0;
            if($this->getInt('DP_cmbHab')) {
                for($i = 1; $i <= $this->getInt('DP_cmbHab'); $i++) {
                    Session::set('sess_DP_cmbAdultos_' . $i, $this->getInt('DP_cmbAdultos_' . $i));
                    Session::set('sess_DP_cmbChild_' . $i, $this->getInt('DP_cmbChild_' . $i));
                }
                
                Session::set('sess_distribucionPax', $this->_distribucionPax($this->getInt('DP_cmbHab')));
                
                $totalPago = $this->_valorTotal($this->getInt('DP_cmbHab'));
                Session::set('sess_DP_cntHab', $this->getInt('DP_cmbHab'));
                
                $this->_view->totalPago = $totalPago;
                $this->_view->cntHab = $this->getInt('DP_cmbHab');
                $this->_view->renderingCenterBox('detallePasajeros');
            } else {
                throw new Exception('Debe ingresar la cantidad de habitaciones');
            }
            
        } else {
            throw new Exception('Error al cargar los pasajeros');
        }
    }
    
    
    /**
     * Metodo CenterBox: Proceso de reserva de un programa.
     * <PRE>
     * -.Creado: 20/05/2015
     * </PRE>
     * @return String OK
     * @author Jonathan Estay
     */
    public function procesoReserva() {
        Session::acceso('Usuario');
        if(strtolower($this->getServer('HTTP_X_REQUESTED_WITH'))=='xmlhttprequest') {
            $programas= $this->loadModel('programa');
            
            $pasajeros = $this->_validaPasajeros();
            if($pasajeros) {
                
                $habitacion = explode(';', Session::get('sess_distribucionPax'));
                $hab2 = (isset($habitacion[1])) ? $habitacion[1] : '';
                $hab3 = (isset($habitacion[2])) ? $habitacion[2] : '';
                
                $objOpcPrograma= $programas->exeTS_GET_DETALLEPROG(Session::get('sess_TS_GET_DETALLEPROG'));
                foreach($objOpcPrograma as $objOpcProg) {
                    
                    if(Session::get('sess_claveOpc') == $objOpcProg->getClaveOpc()) {
                    
                        $sql = 'exec TS_RESERVAR_PRG '
                        . '"' . Session::get('sess_codigoPrograma') . '", '
                        . '"' . $objOpcProg->getClaveOpc() . '", '
                        . '"", '
                        . '"' . Session::get('sess_BP_fechaIn_PRG') . '", '
                        . '"' . $habitacion[0] . '", '
                        . '"' . $hab2 . '", '
                        . '"' . $hab3 . '", '
                        . '"datos", '
                        . '"", '
                        . '"", '
                        . '"", '
                        . '"", '
                        . '"' . Session::get('sess_rut') . '", ' //@rut_cliente
                        . 'NULL, '
                        . 'NULL, '
                        . 'NULL, '
                        . '"SM1", '
                        . '"IT", '
                        . '"A", ' //@estado
                        . '0, ' //@tcambio
                        . '0, ' //@comag
                        . '0, ' //@tcomi
                        . '"' . date('d-m-Y') . '", ' //@F_contab
                        . '"' . $this->getTexto('DP_txtNombre_1_1') . ' ' .  $this->getTexto('DP_txtApellido_1_1') . '"'; //@vage    
                        $sql .= $pasajeros;
                    }
                }
                
                //echo $sql; exit;
                $objResPrograma = $programas->exeTS_RESERVAR($sql);
                
                foreach ($objResPrograma as $objRes) {
                    if(!$objRes->getFile()) {
                        throw new Exception('<b>Codigo:</b> [ ' . $objRes->getError() . ' ],<br>'
                            . '<b>Mensaje:</b> ' . $objRes->getMensaje());
                    } else {
                        echo 'OK';
                    }
                }
                
            }
            
        } else {
            throw new Exception('Error al cargar las opciones');
        }
    }
    
    
    
    
    
    
    
    
    
    
    /*
     * Begin: Administracion
     */
    
    
    /**
     * Metodo CenterBox: Formulario para editar un programa.
     * <PRE>
     * -.Creado: 15/04/2015
     * </PRE>
     * @return String Vista
     * @author Jonathan Estay
     */
    public function editar() {
        Session::destroy('sessMOD_EP_codPRG');
        $AP_codigoPrg = $this->getTexto('varCenterBox');
        if ($AP_codigoPrg) {
            $EP_programa = $this->loadModel('programa');
            Session::set('sessMOD_EP_codPRG', $AP_codigoPrg);

            $EP_objPrograma = $EP_programa->getAdmProgramas(0, $AP_codigoPrg);
            if ($EP_objPrograma) {
                $this->_view->EP_nombreProg = $EP_objPrograma[0]->getNombre();
                $rutaPDF = ROOT . 'public' . DS . 'pdf' . DS . 'upl_' . str_replace(' ', '_', $EP_objPrograma[0]->getCodigo()) . '.pdf';
                
                if (is_readable($rutaPDF)) {
                    $this->_view->EP_PDF = 'upl_' . str_replace(' ', '_', $EP_objPrograma[0]->getCodigo()) . '.pdf';
                } else {
                    $this->_view->EP_PDF = false;
                }

                $this->_view->renderingCenterBox('editarPrograma');
            } else {
                throw new Exception('Error al intentar editar programa. (Metodo)');
            }
        } else {
            throw new Exception('Error al intentar editar programa');
        }
    }

    
    /**
     * Metodo procesador: Modifica un programa en base al codigo.
     * <PRE>
     * -.Creado: 15/04/2015
     * </PRE>
     * @return String OK
     * @author Jonathan Estay
     */
    public function modificar() {

        if (strtolower($this->getServer('HTTP_X_REQUESTED_WITH')) == 'xmlhttprequest') {
            $rutaPDF = ROOT . 'public' . DS . 'pdf' . DS;
            $MP_programa = $this->loadModel('programa');

            if (isset($_FILES['flPDF']['name'])) {

                if ($_FILES['flPDF']['name']) {

                    //$this->getLibrary('upload' . DS . 'class.upload');

                    $upload = new upload($_FILES['flPDF'], 'es_ES');
                    $upload->allowed = array('application/pdf');
                    $upload->file_max_size = '2097152'; // 2MB
                    //$upload->file_new_name_body= 'upl_' . uniqid();
                    $upload->file_new_name_body = 'upl_' . Session::get('sessMOD_EP_codPRG');
                    $upload->process($rutaPDF);

                    if ($upload->processed) {
                        echo 'OK';
                    } else {
                        throw new Exception($upload->error);
                    }
                } else {
                    throw new Exception('Debe seleccionar un archivo desde su computador');
                }
            } else {

                if ($this->getTexto('chkEP_flPDF')) {

                    if ($this->getTexto('chkEP_flPDF') == 'on') {
                        //echo Session::get('sessMOD_EP_codPRG'); exit;
                        if (Functions::eliminaFile($rutaPDF . 'upl_' . str_replace(' ', '_', Session::get('sessMOD_EP_codPRG')) . '.pdf')) {
                            echo 'OK';
                        } else {
                            throw new Exception('Error al eliminar el archivo, intente nuevamente');
                        }
                    } else {
                        throw new Exception('Debe seleccionar un archivo a eliminar');
                    }
                } else {
                    throw new Exception('Debe seleccionar un archivo desde su computador');
                }
            }
        } else {
            throw new Exception('Error inesperado, intente nuevamente. Si el error persiste comuniquese con el administrador');
        }
    }
    
    
    /*
     * End: Administracion
     */
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /*******************************************************************************
    *                                                                              *
    *                             METODOS PRIVADOS                                 *
    *                                                                              *
    *******************************************************************************/
    
    /**
     * Metodo privado: Retorna la distribucion de los pasajeros.
     * <PRE>
     * -.Creado: 02/06/2015
     * -.Modificado: 03/06/2015
     * </PRE>
     * @return String Contiene la distribucion ((01SGL, 01DBL, 01TPL) + 01CHD + 01CH2) a pasar al Stored Procedure 
     * @author Jonathan Estay
     */
    private function _distribucionPax($hab) {
        
        $sgl=0; $dbl=0; $tpl=0;
        $distribucion='';
        $distribucionTMP='';
        for($i = 1; $i <= $hab; $i++) {
            
            if(Session::get('sess_DP_cmbAdultos_' . $i) == 1) {
                $sgl++; $distribucionTMP = '0' . $sgl . 'SGL';
            } else if(Session::get('sess_DP_cmbAdultos_' . $i) == 2) {
                $dbl++; $distribucionTMP = '0' . $dbl . 'DBL';
            } else if(Session::get('sess_DP_cmbAdultos_' . $i) == 3) {
                $tpl++; $distribucionTMP = '0' . $tpl . 'TPL';
            }
            
            //CHD
            if(Session::get('sess_DP_cmbChild_' . $i) == 1) {
                $distribucionTMP .= ' + 01CHD';
            }else if(Session::get('sess_DP_cmbChild_' . $i) == 2) {
                $distribucionTMP .= ' + 01CHD + 01CH2';
            }
            $distribucion .= $distribucionTMP . ';';
        }
        
        return $distribucion;
    }
    
    
    
    /**
     * Metodo privado: Valida todos los pasajeros antes de realizar la reserva de un programa.
     * <PRE>
     * -.Creado: 19/05/2015
     * -.Modificado: 20/05/2015
     * </PRE>
     * @return String Contiene la cantidad de pasajeros a pasar al Stored Procedure 
     * @author Jonathan Estay
     */
    private function _validaPasajeros() {
        
        $cnt=0;
        $pasajeros='';
        for($i = 1; $i <= Session::get('sess_DP_cntHab'); $i++) {
            /*
             * Begin: Validacion Adulto
             */
            for($j = 1; $j <= Session::get('sess_DP_cmbAdultos_' . $i); $j++) {
                $cnt++;
                if(!$this->getTexto('DP_txtNombre_' . $i . '_' . $j)) {
                    throw new Exception("Debe ingresar un <b>Nombre</b> para el pasajero [" . $j . "], de la habitaci&oacute;n [" . $i . "]");
                }
                if(!$this->getTexto('DP_txtApellido_' . $i . '_' . $j)) {
                    throw new Exception("Debe ingresar un <b>Apellido</b> para el pasajero [" . $j . "], de la habitaci&oacute;n [" . $i . "]");
                }
                
                
                $pasajeros.= ', "' . $this->getTexto('DP_txtNombre_' . $i . '_' . $j) . ' ' . $this->getTexto('DP_txtApellido_' . $i . '_' . $j) . '"';
                
                
                
                if(!$this->getCheckbox('DP_chkPasaporte_' . $i . '_' . $j)) {
                
                    if(!$this->getTexto('DP_txtRut_' . $i . '_' . $j)) {
                        throw new Exception("Debe ingresar un <b>Rut</b> para el pasajero [" . $j . "], de la habitaci&oacute;n [" . $i . "]");
                    }
                    if(!Functions::validaRut($this->getTexto('DP_txtRut_' . $i . '_' . $j))) {
                        throw new Exception("<b>Rut</b> incorrecto del pasajero [" . $j . "], de la habitaci&oacute;n [" . $i . "]");
                    }
                    
                    $pasajeros.= ', "' . $this->getTexto('DP_txtRut_' . $i . '_' . $j) . '"';
                    
                } else {
                    if(!$this->getTexto('DP_txtPasaporte_' . $i . '_' . $j)) {
                        throw new Exception("Debe ingresar un <b>Pasaporte</b> para el pasajero [" . $j . "], de la habitaci&oacute;n [" . $i . "]");
                    }
                    
                    $pasajeros.= ', "' . $this->getTexto('DP_txtPasaporte_' . $i . '_' . $j) . '"';
                }
                
                
                if(!$this->getTexto('DP_txtFecha_' . $i . '_' . $j)) {
                    throw new Exception("Debe ingresar una <b>Fecha de nacimiento</b> para el pasajero [" . $j . "], de la habitaci&oacute;n [" . $i . "]");
                }
                
                $pasajeros.= ', "' . $this->getTexto('DP_txtFecha_' . $i . '_' . $j) . '", "A"';
            }
            /*
             * End: Validacion Adulto
             */
            
            
            /*
             * Begin: Validacion Child
             */
            //if(Session::get('sess_DP_cmbChild_' . $i)) {
            for($k = 1; $k <= Session::get('sess_DP_cmbChild_' . $i); $k++) {
                $cnt++;
                if(!$this->getTexto('DP_txtNombreC_' . $i . '_' . $k)) {
                    throw new Exception("Debe ingresar un <b>Nombre</b> para el child [" . $k . "], de la habitaci&oacute;n [" . $i . "]");
                }
                if(!$this->getTexto('DP_txtApellidoC_' . $i . '_' . $k)) {
                    throw new Exception("Debe ingresar un <b>Apellido</b> para el child [" . $k . "], de la habitaci&oacute;n [" . $i . "]");
                }

                $pasajeros.= ', "' . $this->getTexto('DP_txtNombreC_' . $i . '_' . $k) . ' ' . $this->getTexto('DP_txtApellidoC_' . $i . '_' . $k) . '"';



                if(!$this->getCheckbox('DP_chkPasaporteC_' . $i . '_' . $k)) {

                    if(!$this->getTexto('DP_txtRutC_' . $i . '_' . $k)) {
                        throw new Exception("Debe ingresar un <b>Rut</b> para el child [" . $k . "], de la habitaci&oacute;n [" . $i . "]");
                    }
                    if(!Functions::validaRut($this->getTexto('DP_txtRutC_' . $i . '_' . $k))) {
                        throw new Exception("<b>Rut</b> incorrecto del child [" . $k . "], de la habitaci&oacute;n [" . $i . "]");
                    }

                    $pasajeros.= ', "' . $this->getTexto('DP_txtRutC_' . $i . '_' . $k) . '"';

                } else {
                    if(!$this->getTexto('DP_txtPasaporteC_' . $i . '_' . $k)) {
                        throw new Exception("Debe ingresar un <b>Pasaporte</b> para el child [" . $k . "], de la habitaci&oacute;n [" . $i . "]");
                    }

                    $pasajeros.= ', "' . $this->getTexto('DP_txtPasaporteC_' . $i . '_' . $k) . '"';
                }


                if(!$this->getTexto('DP_txtFechaC_' . $i . '_' . $k)) {
                    throw new Exception("Debe ingresar una <b>Fecha de nacimiento</b> para el child [" . $k . "], de la habitaci&oacute;n [" . $i . "]");
                }
                $pasajeros.= ', "' . $this->getTexto('DP_txtFechaC_' . $i . '_' . $k) . '", "C"';
            }
            /*
             * End: Validacion Child
             */
            
        }
        
        for($l = $cnt; $l < 12; $l++) {
            $pasajeros.= ', "", "", "", ""';
        }
        
        return $pasajeros;
    }
    
    
    
    /**
     * Metodo privado: Calcula el valor total a pagar antes de reservar un programa.
     * <PRE>
     * -.Creado: 19/05/2015
     * </PRE>
     * @param $hab Cantidad de habitaciones
     * @return int valor total de la habitacion
     * @author Jonathan Estay
     */
    private function _valorTotal($hab) {
        $total=0;
        for($i = 1; $i <= $hab; $i++) {
            if(Session::get('sess_DP_cmbAdultos_' . $i) == 1) {
                $total = $total + (Session::get('sess_DP_cmbAdultos_' . $i)*Session::get('sess_SGL'));
            } else if(Session::get('sess_DP_cmbAdultos_' . $i) == 2) {
                $total = $total + (Session::get('sess_DP_cmbAdultos_' . $i)*Session::get('sess_DBL'));
            } else if(Session::get('sess_DP_cmbAdultos_' . $i) == 3) {
                $total = $total + (Session::get('sess_DP_cmbAdultos_' . $i)*Session::get('sess_TPL'));
            }
        }
        
        return $total;
    }
    
    
    
    
    
    
    
    
    
    
    /*******************************************************************************
    *                                                                              *
    *                             METODOS PROCESADORES                             *
    *                                                                              *
    *******************************************************************************/
    
    /**
     * Metodo procesador: Procesa los datos de la busqueda de programas.
     * <PRE>
     * -.Creado: 19/05/2015
     * </PRE>
     * @author Jonathan Estay
     */
    public function buscar() {
        $BP_cntHab = $this->getInt('mL_cmbHab_PRG');
        $BP_ciudadDes = $this->getTexto('mL_txtCiudadDestino_PRG');
        $BP_fechaIn = $this->getTexto('mL_txtFechaIn_PRG');
        $BP_fechaOut = $this->getTexto('mL_txtFechaOut_PRG');
        $BP_hotel = $this->getTexto('mL_txtHotel_PRG');

        if ($BP_ciudadDes) {
            Session::set('sess_BP_ciudadDes_PRG', $BP_ciudadDes);
        }

        Session::set('sess_BP_cntHab_PRG', $BP_cntHab);
        Session::set('sess_BP_fechaIn_PRG', $BP_fechaIn);
        Session::set('sess_BP_fechaOut_PRG', $BP_fechaOut);
        Session::set('sess_BP_hotel_PRG', $BP_hotel);


        Session::set('sess_BP_cntPas', 0);
        Session::set('sess_BP_cntAdl', 0);
        Session::set('sess_BP_cntChd', 0);
        Session::set('sess_BP_cntInf', 0);
        
        for ($i = 1; $i <= 3; $i++) {
            if ($i <= $BP_cntHab) {
                Session::set('sess_BP_Adl_' . $i, $this->getInt('mL_cmbAdultos_' . $i));
                Session::set('sess_BP_Chd_' . $i, $this->getInt('mL_child_' . $i));
                Session::set('sess_BP_Inf_' . $i, $this->getInt('mL_inf_' . $i));


                if (Session::get('sess_BP_Adl_' . $i) > 0) {
                    Session::set('sess_BP_cntAdl', (Session::get('sess_BP_cntAdl') + 1));
                }
                if (Session::get('sess_BP_Chd_' . $i) > 0) {
                    Session::set('sess_BP_cntChd', (Session::get('sess_BP_cntChd') + 1));
                }
                if (Session::get('sess_BP_Inf_' . $i) > 0) {
                    Session::set('sess_BP_cntInf', (Session::get('sess_BP_cntInf') + 1));
                }


                for ($x = 1; $x <= 2; $x++) {
                    Session::set('sess_BP_edadChd_' . $x . '_' . $i, $this->getInt('mL_edadChild_' . $x . '_' . $i));
                }

                Session::set('sess_BP_cntPas', (Session::get('sess_BP_cntPas') + Session::get('sess_BP_Adl_' . $i) + Session::get('sess_BP_Chd_' . $i)));
            } else {
                Session::set('sess_BP_Adl_' . $i, 0);
                Session::set('sess_BP_Chd_' . $i, 0);
                Session::set('sess_BP_Inf_' . $i, 0);

                Session::set('sess_BP_edadChd_1_' . $i, 0);
                Session::set('sess_BP_edadChd_2_' . $i, 0);
            }
        }

        $this->redireccionar('programas');
    }
    
    
    
    /**
     * Metodo procesador: Guarda en sesion la ciudad buscada en la administracion de programas.
     * <PRE>
     * -.Creado: 19/05/2015
     * </PRE>
     * @author Jonathan Estay
     */
    public function buscarAdm() {
        Session::set('sess_AP_ciudad', $this->getTexto('AP_cmbCiudadDestino'));
        $this->redireccionar('programas/admin');
    }
}