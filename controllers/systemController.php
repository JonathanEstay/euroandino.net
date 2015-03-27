<?php

/* 
 * Proyecto : Euroandino.net
 * Autor    : Tsyacom Ltda.
 * Fecha    : Miercoles, 10 de octubre de 2014
 */

class systemController extends Controller
{
    private $_pdf;
    private $_ciudad;
    
    public function __construct() {
        parent::__construct();
        $this->_ciudad= $this->loadModel('ciudad');
        $this->_loadLeft();
    }
    
    public function horaServer()
    {
        //echo ((int)date('H')+1) . ':' . date('i:s');
        echo date('H:i:s');
    }
    
    
    
    /*******************************************************************************
    *                                                                              *
    *                                METODOS VIEWS                                 *
    *                                                                              *
    *******************************************************************************/
    public function index()
    {
        Session::acceso('Usuario');
        //$this->_view->setJS(array(''));
        
        $this->_view->objCiudades= $this->_ciudad->getCiudadesBloq();
        $this->_view->objCiudadesPRG= $this->_ciudad->getCiudades();
        
        $this->_view->index=true;
        $this->_view->currentMenu=0;
        $this->_view->titulo='ORISTRAVEL';
        $this->_view->renderingSystem('index');
    }
    
    
    public function consultarReserva()
    {
        Session::acceso('Usuario');
        $reserva= $this->loadModel('reserva');
        
        $this->_view->objCiudades= $this->_ciudad->getCiudadesBloq();
        $this->_view->objCiudadesPRG= $this->_ciudad->getCiudades();
        
        $this->_view->rdbRes=false;
        
        if(Session::get('sess_CR_fechaDesde') && Session::get('sess_CR_fechaHasta'))
        {
            $this->_view->CR_fechaIni= Session::get('sess_CR_fechaDesde');
            $this->_view->CR_fechaFin= Session::get('sess_CR_fechaHasta');
            
            if(Session::get("sess_CR_tipoFecha")==1)
            {
                $this->_view->rdbRes='checked';
            }
            else
            {
                $this->_view->rdbVia='checked';
            }
            
            $this->_view->objReservas= $reserva->getReservas(
                Functions::invertirFecha(Session::get('sess_CR_fechaDesde'), '/', '-'),
                Functions::invertirFecha(Session::get('sess_CR_fechaHasta'), '/', '-'),
                Session::get('sess_CR_tipoFecha'),
                Session::get('sess_sp_acceso'),
                Session::get('sess_clave_usuario')
                );
            
        }
        else
        {
            $this->_view->objReservas=false;
            $this->_view->CR_fechaIni= Session::get('sess_fechaDefault');
            $this->_view->CR_fechaFin= Functions::sumFecha(Session::get('sess_fechaDefault'), 0, 3);

            if(Session::get('sess_CR_tipoFecha')==1)
            {
                $this->_view->rdbRes='checked';
            }
            else
            {
                $this->_view->rdbVia='checked';
            }
        }
        
        
        $this->_view->currentMenu=1;
        $this->_view->titulo='ORISTRAVEL';
        $this->_view->renderingSystem('consultarReserva');
    }
    
    
    public function hoteles()
    {
        Session::acceso('Usuario');
        $categorias= $this->loadModel('categoria');
        $hoteles= $this->loadModel('hotel');
        
        $this->_view->objCiudades= $this->_ciudad->getCiudadesBloq();
        $this->_view->objCiudadesPRG= $this->_ciudad->getCiudades();


        $this->_view->objCategorias= $categorias->getCategorias();
        
        if(Session::get('sess_H_nombre') || Session::get('sess_H_ciudad') || Session::get('sess_H_cat'))
        {
            $this->_view->objHoteles= $hoteles->getHoteles(Session::get('sess_H_nombre'), Session::get('sess_H_ciudad'), Session::get('sess_H_cat'));
        }
        else
        {
            $this->_view->objHoteles=false;
        }
        
        
        
        $this->_view->currentMenu=2;
        $this->_view->titulo='ORISTRAVEL';
        $this->_view->renderingSystem('hoteles');
    }
    
    
    public function adminProgramas()
    {
        Session::acceso('Usuario');
        
        $this->_view->objCiudades= $this->_ciudad->getCiudadesBloq();
        $this->_view->objCiudadesPRG= $this->_ciudad->getCiudades();
        
        
        if(Session::get('sess_AP_ciudad'))
        {
            $programas= $this->loadModel('programa');
            //getAdmProgramas
            $this->_view->objProgramas= $programas->getAdmProgramas(Session::get('sess_AP_ciudad'));
        }
        
        
        $this->_view->currentMenu=3;
        $this->_view->titulo='ORISTRAVEL';
        $this->_view->renderingSystem('adminProgramas');
    }
    
    
    public function imagenes()
    {
        Session::acceso('Usuario');
        $agencia= $this->loadModel('agencia');
        
        $this->_view->objCiudades= $this->_ciudad->getCiudadesBloq();
        $this->_view->objCiudadesPRG= $this->_ciudad->getCiudades();
        
        $this->_view->objAgencias= $agencia->getAgencias();
        
        $this->_view->currentMenu=4;
        $this->_view->titulo='ORISTRAVEL';
        $this->_view->renderingSystem('imagenes');
    }
    
    
    public function contactenos()
    {
        Session::acceso('Usuario');
        
        
        $this->getLibrary('class.phpmailer');
        
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        
        //$mail->Port = 25;
        $mail->Host = "mail.oristravel.com";
        $mail->Username = "oris@oristravel.com";
        $mail->Password = "tsyacom01";
        
        $mail->From = 'oris@oristravel.com';
        $mail->FromName = 'Solicitud de Contacto WEB-ORIS';
        $mail->CharSet = CHARSET;
        $mail->Subject = 'Confirmacion de reserva online: ';
        	
        $body  = "Un mensaje HTML";
        $mail->MsgHTML($body);

        
        $mail->AddAddress('jestay@tsyacom.cl', "");
        //$mail->AddAddress('jjreyes.romero88@gmail.com', "");
        if (!$mail->Send()) {
            echo "Error al enviar: " . $mail->ErrorInfo;
        } else {
            echo "Mensaje enviado!";
        }

        
        
        $this->_view->currentMenu=5;
        $this->_view->titulo='ORISTRAVEL';
        //$this->_view->renderizaSistema('contactenos');
    }
    
    
    public function verPDF($id = false)
    {
        Session::acceso('Usuario');
        
        //$this->getLibrary('fpdf');
        $this->getLibrary('pdf_html');
        
        $pdf= new PDF('P', 'mm','letter');
        $ruta_img= ROOT . 'public' . DS . 'img' . DS;
        
        require_once ROOT . 'views' . DS . 'sistema' . DS . 'pdf' . DS . 'voucherPDF.php';
        
        //$this->_pdf= new FPDF();
        //$this->_pdf->AddPage();
        //$this->_pdf->SetFont('Arial','B',16);
        //$this->_pdf->Cell(40,10, utf8_decode('¡Hola, Mundo!'));
        //$this->_pdf->Cell(40,10,'¡Hola, Mundo!');
        //$this->_pdf->Output();
    }
    
    
    public function verPDF_HTML($numFile)
    {
        $ruta_img= 'views/layout/' . DEFAULT_LAYOUT . '/img/';
        
        ob_start();
        require_once ROOT . 'views' . DS . 'system' . DS .'pdf' . DS . 'vouchea.php';
        $content = ob_get_clean();
        
        $this->getLibrary('html2pdf.class');
        try
        {
            $html2pdf = new HTML2PDF('P', 'A4', 'fr');
                    //$html2pdf->setModeDebug();
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            $html2pdf->Output('Voucher_N_'.$numFile.'.pdf');
        }
        catch(HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }
    
    
    public function bloqueos()
    {
        Session::acceso('Usuario');
        //$this->_view->setJS(array(''));
        
        //$this->getLibrary('kint/Kint.class');
        
        $this->_view->ML_fechaIni= Session::get('sess_BP_fechaIn');
        $this->_view->ML_fechaFin= Session::get('sess_BP_fechaOut');
        
        $this->_view->objCiudades= $this->_ciudad->getCiudadesBloq();
        $this->_view->objCiudadesPRG= $this->_ciudad->getCiudades();
        if(Session::get('sess_BP_ciudadDes'))
        {
            $this->loadDTO('incluye');
            $programas= $this->loadModel('bloqueo');
            $sql="exec WEB_TraeProgramas_Oficial '".Session::get('sess_BP_ciudadDes')."', "
                    . "'".Functions::invertirFecha(Session::get('sess_BP_fechaIn'), '/', '-')."', "
                    . "'".Functions::invertirFecha(Session::get('sess_BP_fechaOut'), '/', '-')."', "
                    //. "'".str_replace('/', '-', Session::get('sess_BP_fechaIn'))."', "
                    //. "'".str_replace('/', '-', Session::get('sess_BP_fechaOut'))."', "
                    . "'".Session::get('sess_BP_cntPas')."', '".Session::get('sess_BP_hotel')."'";
            for($i=1; $i<=3; $i++)
            {
                $sql.= ", '".Session::get('sess_BP_Adl_'.$i)."', '".Session::get('sess_BP_edadChd_1_'.$i)."', 
                        '".Session::get('sess_BP_edadChd_2_'.$i)."', '".Session::get('sess_BP_Inf_'.$i)."'"; //habitaciones
            }
            
            Session::set('sess_TraeProg', $sql);
            //echo $sql; exit;
            
            $this->_view->objProgramas= $programas->exeTraeProgramas($sql, true);
            $this->_view->objProgramasCNT = count($this->_view->objProgramas);
        }
        
        Session::destroy('sess_BP_ciudadDes_PRG');
        $this->_view->currentMenu=0;
        $this->_view->procesoTerminado=false;
        $this->_view->titulo='ORISTRAVEL';
        $this->_view->renderingSystem('bloqueos');
    }
    
    
    
    
    
    public function programas()
    {
        Session::acceso('Usuario');
        //$this->_view->setJS(array(''));
        
        //$this->getLibrary('kint/Kint.class');
        
        $this->_view->ML_fechaIni_PRG= Session::get('sess_BP_fechaIn_PRG');
        $this->_view->ML_fechaFin_PRG= Session::get('sess_BP_fechaOut_PRG');
        
        $this->_view->objCiudades= $this->_ciudad->getCiudadesBloq();
        $this->_view->objCiudadesPRG= $this->_ciudad->getCiudades();
        if(Session::get('sess_BP_ciudadDes_PRG'))
        {
            //$this->loadDTO('incluye');
            $programas= $this->loadModel('programa');
            
            //WEB
            //$sql="EXEC TS_GET_PROGRAMAS '".Session::get('sess_BP_ciudadDes_PRG')."', '', '".Functions::invertirFecha(Session::get('sess_BP_fechaIn_PRG'), '/', '-')."' ";
            
            //Local
            $sql="EXEC TS_GET_PROGRAMAS '".Session::get('sess_BP_ciudadDes_PRG')."', '', '".str_replace('/', '-', Session::get('sess_BP_fechaIn_PRG'))."' ";
            
            Session::set('sess_TS_GET_PROGRAMAS', $sql);
            //echo $sql; exit;
            
            $this->_view->objCiudadBs= $this->_ciudad->getCiudades(Session::get('sess_BP_ciudadDes_PRG'));
            $this->_view->objProgramas= $programas->exeTS_GET_PROGRAMAS($sql);
            //$this->_view->objProgramasCNT = count($this->_view->objProgramas);
        }
        
        
        
        Session::destroy('sess_BP_ciudadDes');
        $this->_view->currentMenu=0;
        $this->_view->procesoTerminado=false;
        $this->_view->titulo='ORISTRAVEL';
        $this->_view->renderingSystem('programas');
    }
    
    
    
    
    
    
    
    
    
    
    
    /*******************************************************************************
    *                                                                              *
    *                          METODOS VIEWS CENTER BOX                            *
    *                                                                              *
    *******************************************************************************/
    public function detalleProg()
    {
        $programas= $this->loadModel('programa');
        
        
        /*
         * 
         */
        $this->_ciudad= array();
        $this->_alert();
        
        //WEB
        //$sql="";
        
        if($this->getInt('__SP_id__')) {
            $sql="EXEC TS_GET_PROGRAMAS_ID " . $this->getInt('__SP_id__');
            //Session::set('sess_TS_GET_PROGRAMAS_ID', $sql);
            
            $this->_view->objProgramas= $programas->exeTS_GET_PROGRAMAS($sql);
            
            
            //Local
            $sql="EXEC TS_GET_DETALLEPROG ".$this->getInt('__SP_id__')." ";

            //Session::set('sess_TS_GET_DETALLEPROG', $sql);
            //echo $sql; exit;

            $objOpcProgramas= $programas->exeTS_GET_DETALLEPROG($sql);
            if($objOpcProgramas[0]->getError()) {
                throw new Exception('<b>Error</b>: ['.$objOpcProgramas[0]->getError().'] <br> <b>Mensaje</b>: ['.$objOpcProgramas[0]->getMensaje().']');
            } else {
                $this->_view->objOpcProgramas= $objOpcProgramas;
                $this->_view->renderingCenterBox('detalleProg');
            }
        } else {
            throw new Exception('Error al cargar las opciones');
        }
    }


    
    
    
    public function cartaConfirmacion()
    {
        //Cargando modelos
        $M_file= $this->loadModel('reserva');
        $M_bloqueos= $this->loadModel('bloqueo');
        $M_packages= $this->loadModel('programa');
        
        //Rescatando post
        $nFile= $this->getTexto('CR_n_file');
        $codPRG= $this->getTexto('CR_cod_prog');
        $codBloq= $this->getTexto('CR_cod_bloq');
        
        if(!$nFile) {
            throw new Exception('File no recibido');
            exit;
        }
        //Creando los objetos para las View
        $objsFile= $M_file->getFile($nFile);
        
        $this->_view->CC_objsDetFile= $M_file->getDetFile($nFile);
        
        $objsBloq= $M_bloqueos->getBloqueos($codBloq);
        
        $this->_view->CC_objsDetBloq= $M_bloqueos->getDetBloq($codBloq, $nFile);
        
        $objsPackages= $M_packages->getPackages($codPRG);
        
        
        if($objsFile!=false)
        {
            $this->_view->CC_agencia=$objsFile[0]->getAgencia();
            $this->_view->CC_vage= $objsFile[0]->getVage();
            $this->_view->CC_nomPax= $objsFile[0]->getNomPax();
            $this->_view->CC_nPax= $objsFile[0]->getNPax();
            $this->_view->CC_fviaje= $objsFile[0]->getFViaje();
            $this->_view->CC_moneda= $objsFile[0]->getMoneda();
            $this->_view->CC_totventa= $objsFile[0]->getTotVenta();
            $this->_view->CC_cambio= $objsFile[0]->getCambio();
            $this->_view->CC_comag= $objsFile[0]->getComag();
            
            $this->_view->CC_datos= $objsFile[0]->getDatos();
            $this->_view->CC_ajuste= $objsFile[0]->getAjuste();
            $this->_view->CC_tcomi= $objsFile[0]->getTComi();
        }
        
        if($objsPackages!=false)
        {
            $this->_view->CC_nombreProg=$objsPackages[0]->getNombre();
        }
        
        if($objsBloq!=false)
        {
            $this->_view->CC_notas= str_replace("\n", "<br>", $objsBloq[0]->getNotas());
        }
        else
        {
            $this->_view->CC_notas=false;
        }
        
        $this->_view->numFile= $nFile;
        $this->_view->codigoPRG= $codPRG;
        $this->_view->codigoBloq= $codBloq;
        
        
        
        $this->_view->renderingCenterBox('cartaConfirm');
    }
    
    
    
    /*
     * RESERVA PROGRAMA
     */
    public function reservaPrograma()
    {
        if(strtolower($this->getServer('HTTP_X_REQUESTED_WITH'))=='xmlhttprequest')
        {
            $RP_rdbOpc = false;
            $RP_idProg = false;
            
            $tags= array_keys($this->getPOST());
            if(!empty($tags[1])) { 
                $RP_rdbOpc= $this->getTexto($tags[1]);
                $RP_idProg= $this->getTexto($tags[0]);
            }
            
            
            if(!$RP_rdbOpc)
            {
                throw new Exception('Seleccione una opcion para poder reservar.');
            }
            else if($RP_idProg)
            {
                Session::set('sessRP_rdbOpc', $RP_rdbOpc);
                Session::set('sessRP_idPrograma', $RP_idProg);
            
                $programa= $this->loadModel('programa');
                $this->_view->objPrograma= $programa->validaPrograma($RP_idProg, $RP_rdbOpc);
                if($this->_view->objPrograma)
                {
                    $this->_view->objOpcionPrograma= $programa->exeTraeProgramas(Session::get('sess_TraeProg'));
                    $cnt= count($this->_view->objOpcionPrograma);
                    for($i=1; $i<$cnt; $i++)
                    {
                        if($this->_view->objOpcionPrograma[$i]->getIdOpc()==$RP_rdbOpc)
                        {
                            $this->_view->objOpcionProg[]= $this->_view->objOpcionPrograma[$i];
                            break;
                        }
                    }
                    
                    //Formateando valores
                    $this->_view->fechaSalida= Functions::invertirFecha($this->_view->objOpcionProg[0]->getDesde(), '/', '/');
                    
                    $exp_fechaSalida= explode('/', $this->_view->objOpcionProg[0]->getDesde());
                    $this->_view->anoSalida= $exp_fechaSalida[0];
                    $this->_view->mesSalida= $exp_fechaSalida[1];
                    $this->_view->diaSalida= $exp_fechaSalida[2];
                    
                    $valorHab= $this->_view->objOpcionProg[0]->getValorHab();
                    $this->_view->precio= Functions::getTipoMoneda($this->_view->objOpcionProg[0]->getMoneda()).' '.Functions::formatoValor($this->_view->objOpcionProg[0]->getMoneda(), ($valorHab[0]+$valorHab[1]+$valorHab[2]));
                    
                    $this->_view->hoteles= $this->_view->objOpcionProg[0]->getHoteles();
                    $this->_view->hotelesCNT= count($this->_view->hoteles);
                    $this->_view->renderingCenterBox('reservarPrograma');
                }
                else
                {
                    throw new Exception('Existe un error en el armado de programas, favor actualize la busqueda.');
                }
            }
        }
        else
        {
            throw new Exception('Error inesperado, intente nuevamente. Si el error persiste comuniquese con el administrador');
        }
    }
    
    public function procesoReserva()
    {
        
        /*$param="CR_n_file=190306&CR_cod_prog=CH14FLN01-2&CR_cod_bloq=2014FLN019";
        $html= $this->curlPOST($param, BASE_URL . 'system/cartaConfirmacion');
        echo $html; exit;*/
        
        if(strtolower($this->getServer('HTTP_X_REQUESTED_WITH'))=='xmlhttprequest')
        {
            $programa= $this->loadModel('programa');
            
            require_once ROOT . 'controllers' . DS . 'include' . DS .'procesoReserva.php';
            $param="CR_n_file=$n_file&CR_cod_prog=$cod_prog&CR_cod_bloq=$cod_bloq";
            //$param="CR_n_file=190306&CR_cod_prog=CH14FLN01-2&CR_cod_bloq=2014FLN019";
            $html= $this->curlPOST($param, BASE_URL . 'system/cartaConfirmacion');
            
            if($pRP_error==TRUE)
            {
                echo $pRP_msg;
            }
            else
            {
                //$this->getLibrary('class.phpmailer');
                $this->mailReserva($n_file, $html);
                echo 'OK' . '&' . $n_file . '&' . $cod_prog . '&' . $cod_bloq;
            }
        }
        else
        {
            throw new Exception('Error inesperado, intente nuevamente. Si el error persiste comuniquese con el administrador');
        }
    }
    
    
    

    public function itinerarioVuelo()
    {
        $idOpc= $this->getTexto('varCenterBox');
        if($idOpc)
        {
            $IV_programa= $this->loadModel('programa');
            
            $this->_view->objPrograma= $IV_programa->getItinerarioVuelo($idOpc);
            $this->_view->renderingCenterBox('itinerarioVuelo');
        }
        else
        {
            throw new Exception('Error al ver el itinerario.');
        }
    }
    
    public function condicionesGenerales()
    {
        $idPrg= $this->getTexto('varCenterBox');
        if($idPrg)
        {
            $CG_programa= $this->loadModel('programa');
            
            $this->_view->objPrograma= $CG_programa->getNota($idPrg);
            $this->_view->renderingCenterBox('condicionesGenerales');
        }
        else
        {
            throw new Exception('Error al ver las condiciones generales.');
        }
    }
    
    public function notas()
    {
        $idOpc= $this->getTexto('varCenterBox');
        if($idOpc)
        {
            $N_programa= $this->loadModel('programa');
            
            $this->_view->objPrograma= $N_programa->getNotaOpc($idOpc);
            $this->_view->renderingCenterBox('notas');
        }
        else
        {
            throw new Exception('Error al ver la nota.');
        }
    }
    
    public function fotosHotel()
    {
        $FH_codHotel= $this->getTexto('varCenterBox');
        if($FH_codHotel)
        {
            $FH_hotel= $this->loadModel('hotel');
            
            $this->_view->objHotel= $FH_hotel->getFotos($FH_codHotel);
            if($this->_view->objHotel)
            {
                $this->_view->renderingCenterBox('fotosHotel');
            }
            else
            {
                throw new Exception('Error al desplegar las fotos, favor intente nuevamente.');
            }
        }
        else
        {
            throw new Exception('Error al ver las fotos del hotel.');
        }
    }
    
    public function mapas()
    {
        $M_codHotel= $this->getTexto('varCenterBox');
        if($M_codHotel)
        {
            $M_hotel= $this->loadModel('hotel');
            
            $this->_view->objHotel= $M_hotel->getMapa($M_codHotel);
            if($this->_view->objHotel)
            {
                $this->_view->renderingCenterBox('mapas');
            }
            else
            {
                throw new Exception('Error al mostrar el mapa, favor intente nuevamente.');
            }
        }
        else
        {
            throw new Exception('Error al ver el mapa.');
        }
    }
    
    public function servicios()
    {
        $S_codHotel= $this->getTexto('varCenterBox');
        if($S_codHotel)
        {
            $S_hotel= $this->loadModel('hotel');
            
            $this->_view->objHotel= $S_hotel->getHotel($S_codHotel);
            if($this->_view->objHotel)
            {
                $this->_view->renderingCenterBox('servicios');
            }
            else
            {
                throw new Exception('Error al desplegar los servicios, favor intente nuevamente.');
            }
        }
        else
        {
            throw new Exception('Error al ver los servicios');
        }
    }
    
    
    

    
    /*
     * TIPO HABITACION
     */
    public function editarTipoHab()
    {
        $ETH_codHotel= $this->getTexto('H_codHotel');
        if($ETH_codHotel)
        {
            Session::set('sessMOD_ETH_codHotel', $ETH_codHotel);
            $ETH_hotel= $this->loadModel('hotel');
            $ETH_tHab= $this->loadModel('tipoHab');
            
            
            $ETH_objHotel= $ETH_hotel->getHotel($ETH_codHotel);
            $this->_view->ETH_nombreHotel= $ETH_objHotel[0]->getHotel();
            
            
            $this->_view->ETH_objsTipoHab= $ETH_tHab->getTipoHab();
        
            
            $this->_view->renderingCenterBox('editarTipoHab');
        }
        else
        {
            throw new Exception('Error al editar Tipo habitaci&oacute;n');
        }
    }
    
    public function detalleTipoHab()
    {
        $DTH_codTiHab= $this->getTexto('_PCD_');
        if($DTH_codTiHab)
        {
            Session::set('sessMOD_DTH_codTipoHab', $DTH_codTiHab);
            $DTH_tHab= $this->loadModel('tipoHab');
            
            $DTH_objsTipoHab= $DTH_tHab->getTipoHab($DTH_codTiHab);
            $this->_view->DTH_nombreDTipoHab= $DTH_objsTipoHab[0]->getNombre();
            
            $DTH_objsDetTipoHab= $DTH_tHab->getDetTipoHab($DTH_codTiHab, Session::get('sessMOD_ETH_codHotel'));
            if($DTH_objsDetTipoHab!=false)
            {
                Session::set('sess_DTH_cntFotos', 1);
                $this->_view->DTH_foto1= $DTH_objsDetTipoHab[0]->getFoto1();
                $this->_view->DTH_foto2= $DTH_objsDetTipoHab[0]->getFoto2();
                $this->_view->DTH_foto3= $DTH_objsDetTipoHab[0]->getFoto3();
                $this->_view->DTH_foto4= $DTH_objsDetTipoHab[0]->getFoto4();
                
                Session::set('sessMOD_DTH_img1', $this->_view->DTH_foto1);
                Session::set('sessMOD_DTH_img2', $this->_view->DTH_foto2);
                Session::set('sessMOD_DTH_img3', $this->_view->DTH_foto3);
                Session::set('sessMOD_DTH_img4', $this->_view->DTH_foto4);
            }
            else
            {
                Session::set('sess_DTH_cntFotos', 0);
                $this->_view->DTH_foto1=false;
                $this->_view->DTH_foto2=false;
                $this->_view->DTH_foto3=false;
                $this->_view->DTH_foto4=false;
                
                Session::set('sessMOD_DTH_img1', $this->_view->DTH_foto1);
                Session::set('sessMOD_DTH_img2', $this->_view->DTH_foto2);
                Session::set('sessMOD_DTH_img3', $this->_view->DTH_foto3);
                Session::set('sessMOD_DTH_img4', $this->_view->DTH_foto4);
            }
            
            $this->_view->renderingCenterBox('detalleTipoHab');
        }
        else
        {
            throw new Exception('Error al cargar el detalle de tipo habitaci&oacute;n');
        }
    }
    
    public function modificarTipoHab()
    {
        if(strtolower($this->getServer('HTTP_X_REQUESTED_WITH'))=='xmlhttprequest')
        {
            $MTH_tHab= $this->loadModel('tipoHab');
            //$this->getLibrary('upload' . DS . 'class.upload');
            $rutaImg= ROOT . 'public' . DS . 'img' . DS .'tipo_habitacion' . DS;

            $cntFotos=0;
            $ML_status=true;
            $ML_sqlIns='INSERT INTO fotos_hoteles (tipoh, cod_hotel';
            for($i=1; $i<=4; $i++)
            {
                if(isset($_FILES['DTH_flImagen' . $i]['name']))
                {
                    if($_FILES['DTH_flImagen' . $i]['name'])
                    {
                        if(Functions::validaFoto($_FILES['DTH_flImagen' . $i]['type'])==false)
                        {
                            $ML_status=false;
                            echo 'La Imagen '. $i .' debe ser formato [.JPG] [.GIF] [.PNG]';
                            break;
                        }

                        if($_FILES['DTH_flImagen' . $i]['size'] > 524288) //512KB
                        {
                            $ML_status=false;
                            echo 'La Imagen '. $i .' debe ser menor a <b>500kb</b>';
                            break;
                        }
                        
                        $ML_sqlIns.=', foto' . $i;
                    }
                    else
                    {
                        ++$cntFotos;
                    }
                }
                else
                {
                    ++$cntFotos;
                }
            }
            
            
            
            
            
            
            if($ML_status)
            {
                $ML_sqlUpd='UPDATE fotos_hoteles SET ';
                $ML_sqlIns.=') VALUES ( "'.Session::get('sessMOD_DTH_codTipoHab').'", "'.Session::get('sessMOD_ETH_codHotel').'" ';
                $ML_c='';
                for($i=1; $i<=4; $i++)
                {
                    if(isset($_FILES['DTH_flImagen' . $i]['name']))
                    {
                        if($_FILES['DTH_flImagen' . $i]['name'])
                        {
                            $upload= new upload($_FILES['DTH_flImagen' . $i], 'es_ES');
                            $upload->allowed= array('image/jpg', 'image/jpeg', 'image/png', 'image/gif');
                            $upload->file_max_size = '524288'; // 512KB
                            $upload->file_new_name_body= 'upl_' . sha1(uniqid());
                            $upload->process($rutaImg);
                            
                            $ML_sqlIns.= ', "' . $upload->file_dst_name . '" ';
                            $ML_sqlUpd.= $ML_c . ' foto' . $i . '= "' . $upload->file_dst_name . '" ';
                            $ML_c=','; 
                            /*if($upload->processed)
                            {   //THUMBNAILS
                                $imagen= $upload->file_dst_name; //nombre de la imagen
                                $thumb= new upload($upload->file_dst_pathname);
                                $thumb->image_resize= true;
                                $thumb->image_x= 100;
                                $thumb->image_y= 100;
                                $thumb->file_name_body_pre= 'thumb_';
                                $thumb->process($rutaImg . 'thumb' . DS);
                            }
                            else
                            {
                                echo '(Imagen ' . $i . ')'.$upload->error . '<br>';
                            }*/
                        }
                    }
                    else
                    {
                        if($this->getTexto('chkDTH_flImagen' . $i)=='on')
                        {
                            $cntFotos=0;
                            Functions::eliminaFile($rutaImg . Session::get('sessMOD_DTH_img' . $i));
                            $ML_sqlUpd.= $ML_c . ' foto' . $i . '= "" ';
                            $ML_c=',';
                        }
                    }
                    
                }
                
                
                if($cntFotos==4)
                {
                    $ML_status=false;
                    echo 'Para modificar debe realizar al menos un cambio. ';
                }
                else
                {
                    if(Session::get('sess_DTH_cntFotos')==1)
                    {
                        $ML_sqlUpd.=' WHERE tipoh = "'.Session::get('sessMOD_DTH_codTipoHab').'" AND cod_hotel = "'.Session::get('sessMOD_ETH_codHotel').'"';
                        //echo $ML_sqlUpd;
                        $MTH_tHab->exeSQL($ML_sqlUpd);
                    }
                    else
                    {
                        $ML_sqlIns.=')';
                        $MTH_tHab->exeSQL($ML_sqlIns);
                    }
                    echo 'OK';
                }
                
            }
        }
        else
        {
            throw new Exception('Error inesperado, intente nuevamente. Si el error persiste comuniquese con el administrador');
        }
    }
    

    
    /*
     * HOTEL
     */
    public function editarHotel()
    {
        $EH_codHotel= $this->getTexto('H_codHotel');
        if($EH_codHotel)
        {
            Session::set('sessMOD_EH_codHotel', $EH_codHotel);
            $hoteles= $this->loadModel('hotel');
            $categorias= $this->loadModel('categoria');

            $this->_view->objCategorias= $categorias->getCategorias();
            
            
            $objHotel= $hoteles->getHotel($EH_codHotel);
            if($objHotel)
            {
                $this->_view->EH_hotel= $objHotel[0]->getHotel();
                $this->_view->EH_cat= $objHotel[0]->getCat();
                $this->_view->EH_lat= $objHotel[0]->getLat();
                $this->_view->EH_lon= $objHotel[0]->getLon();
                $this->_view->EH_direc= $objHotel[0]->getDirec();
                $this->_view->EH_web= $objHotel[0]->getSitioWeb();
                
                $this->_view->EH_imgEnc= $objHotel[0]->getImgEnc();
                $this->_view->EH_imgCont= $objHotel[0]->getImgCont();
                $this->_view->EH_imgCont2= $objHotel[0]->getImgCont2();
                $this->_view->EH_imgCont3= $objHotel[0]->getImgCont3();
                $this->_view->EH_imgCont4= $objHotel[0]->getImgCont4();
                
                /* SERVICIOS HOTEL*/
                if($objHotel[0]->getRestaurante()==1){ $this->_view->EH_rest='checked="checked"'; }
                if($objHotel[0]->getLavanderia()==1){ $this->_view->EH_lavan='checked="checked"'; }
                if($objHotel[0]->getBar()==1){ $this->_view->EH_bar='checked="checked"'; }
                if($objHotel[0]->getCafeteria()==1){ $this->_view->EH_cafe='checked="checked"'; }
                if($objHotel[0]->getServHab()==1){ $this->_view->EH_servHab='checked="checked"'; }
                if($objHotel[0]->getBusiness()==1){ $this->_view->EH_business='checked="checked"'; }
                if($objHotel[0]->getInterHotel()==1){ $this->_view->EH_intHot='checked="checked"'; }
                if($objHotel[0]->getEstaciona()==1){ $this->_view->EH_est='checked="checked"'; }
                if($objHotel[0]->getPiscinaCub()==1){ $this->_view->EH_pCub='checked="checked"'; }
                if($objHotel[0]->getPiscinaDes()==1){ $this->_view->EH_pDes='checked="checked"'; }
                if($objHotel[0]->getGym()==1){ $this->_view->EH_gym='checked="checked"'; }
                if($objHotel[0]->getSpa()==1){ $this->_view->EH_spa='checked="checked"'; }
                if($objHotel[0]->getTenis()==1){ $this->_view->EH_tenis='checked="checked"'; }
                if($objHotel[0]->getGuarderia()==1){ $this->_view->EH_guard='checked="checked"'; }
                if($objHotel[0]->getSalasReu()==1){ $this->_view->EH_salas='checked="checked"'; }
                if($objHotel[0]->getJardin()==1){ $this->_view->EH_jardin='checked="checked"'; }
                if($objHotel[0]->getDiscapacitados()==1){ $this->_view->EH_disca='checked="checked"'; }
                if($objHotel[0]->getBoutique()==1){ $this->_view->EH_bou='checked="checked"'; }
                
                
                /* SERVICIOS HABITACION */
                if($objHotel[0]->getAcondicionado()==1){ $this->_view->EH_acon='checked="checked"'; }
                if($objHotel[0]->getCalefaccion()==1){ $this->_view->EH_cale='checked="checked"'; }
                if($objHotel[0]->getNoFuma()==1){ $this->_view->EH_noFuma='checked="checked"'; }
                if($objHotel[0]->getCajaFuerte()==1){ $this->_view->EH_cajaF='checked="checked"'; }
                if($objHotel[0]->getMiniBar()==1){ $this->_view->EH_mBar='checked="checked"'; }
                if($objHotel[0]->getTV()==1){ $this->_view->EH_tv='checked="checked"'; }
                if($objHotel[0]->getTvCable()==1){ $this->_view->EH_tvC='checked="checked"'; }
                if($objHotel[0]->getInterHab()==1){ $this->_view->EH_intHab='checked="checked"'; }
                if($objHotel[0]->getSecador()==1){ $this->_view->EH_seca='checked="checked"'; }
                if($objHotel[0]->getBarraSeg()==1){ $this->_view->EH_barra='checked="checked"'; }
                if($objHotel[0]->getTelefono()==1){ $this->_view->EH_fono='checked="checked"'; }            
            }
            
            $this->_view->renderingCenterBox('editarHotel');
        }
        else
        {
            throw new Exception('Error al tratar de editar el hotel');
        }
    }
    
    public function modificarHotel()
    {
        if(strtolower($this->getServer('HTTP_X_REQUESTED_WITH'))=='xmlhttprequest')
        {
            $MH_nombreHotel= $this->getTexto('txtEH_nombreHotel');
            $MH_direc= $this->getTexto('txtEH_direc');
            $MH_cate= $this->getTexto('cmbEH_categoria');
            $MH_lat= $this->getTexto('txtEH_latitud');
            $MH_lon= $this->getTexto('txtEH_longitud');
            $MH_sitWeb= $this->getTexto('txtEH_sitioWeb');
            
            
            if(!$MH_nombreHotel)
            {
                echo 'Debe ingresar un nombre de hotel'; exit;
            }
            else if(!$MH_cate)
            {
                echo 'El hotel debe tener una categor&iacute;a'; exit;
            }
            else if(!$MH_direc)
            {
                echo 'Debe ingresar una direcci&oacute;n para el hotel'; exit;
            }
            
            
            
            $MH_Hotel= $this->loadModel('hotel');
            //$this->getLibrary('upload' . DS . 'class.upload');
            $rutaImg= ROOT . 'public' . DS . 'img' . DS .'hoteles' . DS;

            
            for ($i = 1; $i <= 5; $i++) {
                if (isset($_FILES['flImagen' . $i]['name'])) {
                    if ($_FILES['flImagen' . $i]['name']) {
                        if (Functions::validaFoto($_FILES['flImagen' . $i]['type']) == false) {
                            echo 'La Imagen ' . $i . ' debe ser formato [.JPG] [.GIF] [.PNG]';
                            exit;
                        }

                        if ($_FILES['flImagen' . $i]['size'] > 524288) { //512KB
                            echo 'La Imagen ' . $i . ' debe ser menor a <b>500kb</b>';
                            exit;
                        }
                    }
                }
            }





            //Servicios Hotel
            $MH_chkRest= Functions::validaChk($this->getTexto('chkEH_rest'));
            $MH_chkLavan= Functions::validaChk($this->getTexto('chkEH_lavan'));
            $MH_chkPisDesc= Functions::validaChk($this->getTexto('chkEH_pisDesc'));
            $MH_chkCanTenis= Functions::validaChk($this->getTexto('chkEH_cTenis'));
            $MH_chkBar= Functions::validaChk($this->getTexto('chkEH_bar'));
            $MH_chkBusCen= Functions::validaChk($this->getTexto('chkEH_busCen'));
            $MH_chkSpa= Functions::validaChk($this->getTexto('chkEH_spa'));
            $MH_chkGuard= Functions::validaChk($this->getTexto('chkEH_guarderia'));
            $MH_chkCafe= Functions::validaChk($this->getTexto('chkEH_cafe'));
            $MH_chkInterHot= Functions::validaChk($this->getTexto('chkEH_interHot'));
            $MH_chkGym= Functions::validaChk($this->getTexto('chkEH_gym'));
            $MH_chkSaReu= Functions::validaChk($this->getTexto('chkEH_sReu'));
            $MH_chkServHab= Functions::validaChk($this->getTexto('chkEH_servHab'));
            $MH_chkEst= Functions::validaChk($this->getTexto('chkEH_estaciona'));
            $MH_chkPisCub= Functions::validaChk($this->getTexto('chkEH_pisCub'));
            $MH_chkJar= Functions::validaChk($this->getTexto('chkEH_jardin'));
            $MH_chkDisca= Functions::validaChk($this->getTexto('chkEH_disca'));
            $MH_chkBou= Functions::validaChk($this->getTexto('chkEH_bou'));


            //Servicios Habitacion
            $MH_chkAirAcond= Functions::validaChk($this->getTexto('chkEH_airAcond'));
            $MH_chkCaFuerte= Functions::validaChk($this->getTexto('chkEH_cFuerte'));
            $MH_chkTvCable= Functions::validaChk($this->getTexto('chkEH_tvCable'));
            $MH_chkSecPelo= Functions::validaChk($this->getTexto('chkEH_sPelo'));
            $MH_chkCalef= Functions::validaChk($this->getTexto('chkEH_calefac'));
            $MH_chkMinBar= Functions::validaChk($this->getTexto('chkEH_mBar'));
            $MH_chkFono= Functions::validaChk($this->getTexto('chkEH_fono'));
            $MH_chkBarraSeg= Functions::validaChk($this->getTexto('chkEH_barraSeg'));
            $MH_chkNoFumar= Functions::validaChk($this->getTexto('chkEH_noFumar'));
            $MH_chkTV= Functions::validaChk($this->getTexto('chkEH_tv'));
            $MH_chkInterHab= Functions::validaChk($this->getTexto('chkEH_interHab'));


            $MH_sql='UPDATE hotel 
                    SET hotel="'.mb_convert_encoding($MH_nombreHotel, "ISO-8859-1", "UTF-8").'", direc="'.mb_convert_encoding($MH_direc, "ISO-8859-1", "UTF-8").'", cat="'.$MH_cate.'", SWEB="'.$MH_sitWeb.'", estado="", 
                    lat="'.$MH_lat.'", lon="'.$MH_lon.'", restaurante='.$MH_chkRest.', bar='.$MH_chkBar.', cafeteria='.$MH_chkCafe.', 
                    s_habitacion='.$MH_chkServHab.', busness_center='.$MH_chkBusCen.', internet_hotel='.$MH_chkInterHot.', estacionamiento='.$MH_chkEst.', 
                    piscina_cub='.$MH_chkPisCub.', piscina_des='.$MH_chkPisDesc.', gym='.$MH_chkGym.', spa='.$MH_chkSpa.', tenis='.$MH_chkCanTenis.', 
                    guarderia='.$MH_chkGuard.', salas_reunion='.$MH_chkSaReu.', jardin='.$MH_chkJar.', discapacitados='.$MH_chkDisca.', 
                    bautique='.$MH_chkBou.', acondicionado='.$MH_chkAirAcond.', calefaccion='.$MH_chkCalef.', no_fuma='.$MH_chkNoFumar.', 
                    caja_fuerte='.$MH_chkCaFuerte.', mini_bar='.$MH_chkMinBar.', television='.$MH_chkTV.', tv_cable='.$MH_chkTvCable.', 
                    inter_hab='.$MH_chkInterHab.',	secador_pelo='.$MH_chkSecPelo.', barra_seguridad='.$MH_chkBarraSeg.', 
                    lavanderia='.$MH_chkLavan.', telefono='.$MH_chkFono;

            

            
            for($i=1; $i<=5; $i++)
            {
                if(isset($_FILES['flImagen' . $i]['name']))
                {
                    if($_FILES['flImagen' . $i]['name'])
                    {
                        $upload= new upload($_FILES['flImagen' . $i], 'es_ES');
                        $upload->allowed= array('image/jpg', 'image/jpeg', 'image/png', 'image/gif');
                        $upload->file_max_size = '524288'; // 512KB
                        $upload->file_new_name_body= 'upl_' . sha1(uniqid());
                        $upload->process($rutaImg);

                        if($upload->processed)
                        {   //THUMBNAILS
                            $imagen= $upload->file_dst_name; //nombre de la imagen
                            //$MH_sql.= ', foto' . $i . '= "' . $imagen . '" ';

                            $thumb= new upload($upload->file_dst_pathname);
                            $thumb->image_resize= true;
                            $thumb->image_x= 150;
                            $thumb->image_y= 150;
                            $thumb->file_name_body_pre= 'thumb_';
                            $thumb->process($rutaImg . 'thumb' . DS);
                            
                            if($i==1)
                            {	
                                $MH_sql.=', img_encabezado = "'.$imagen.'", mini_img_encabezado = "'.$imagen.'" ';
                            }
                            else if($i==2)
                            {	
                                $MH_sql.=', img_contenido = "'.$imagen.'", mini_img_contenido = "'.$imagen.'" ';
                            }
                            else
                            {
                                $MH_sql.=', img_contenido'.($i-1).' = "'.$imagen.'", mini_img_contenido'.($i-1).' = "'.$imagen.'" ';
                            }
                        }
                        else
                        {
                            echo '(Imagen ' . $i . ')'.$upload->error . '<br>';
                        }
                    }
                }
                else
                {
                    if($i==1)
                    {	
                        if($this->getTexto('chkEH_flImagen' . $i)=='on')
                        {
                            //Functions::eliminaFile($rutaImg . Session::get('sessMOD_DTH_img' . $i));
                            //Functions::eliminaFile($rutaImg . 'thumb' . DS . Session::get('sessMOD_DTH_img' . $i));
                            $MH_sql.=', img_encabezado = "", mini_img_encabezado = "" ';
                        }
                    }
                    else if($i==2)
                    {	
                        if($this->getTexto('chkEH_flImagen' . $i)=='on')
                        {
                            //Functions::eliminaFile($rutaImg . Session::get('sessMOD_DTH_img' . $i));
                            //Functions::eliminaFile($rutaImg . 'thumb' . DS . Session::get('sessMOD_DTH_img' . $i));
                            $MH_sql.=', img_contenido = "", mini_img_contenido = "" ';
                        }
                    }
                    else
                    {
                        if($this->getTexto('chkEH_flImagen' . $i)=='on')
                        {
                            //Functions::eliminaFile($rutaImg . Session::get('sessMOD_DTH_img' . $i));
                            Functions::eliminaFile($rutaImg . 'thumb' . DS . Session::get('sessMOD_DTH_img' . $i));
                            $MH_sql.=', img_contenido'.($i-1).' = "", mini_img_contenido'.($i-1).' = "" ';
                        }
                    }
                    
                }
            }

            
            $MH_sql.=' WHERE codigo='.$_SESSION['sessMOD_EH_codHotel'];
            
            //echo $MH_sql; exit;
            $MH_Hotel->exeSQL($MH_sql);
            echo 'OK';
        }
        else
        {
            throw new Exception('Error inesperado, intente nuevamente. Si el error persiste comuniquese con el administrador.');
        }
    }
    
    
    
    /*
     * PROGRAMAS
     */
    public function editarPrograma()
    {
        Session::destroy('sessMOD_EP_codPRG');
        $AP_codigoPrg= $this->getTexto('varCenterBox');
        if($AP_codigoPrg)
        {
            $EP_programa= $this->loadModel('programa');
            Session::set('sessMOD_EP_codPRG', $AP_codigoPrg);
            
            $EP_objPrograma= $EP_programa->getAdmProgramas(0, $AP_codigoPrg);
            if($EP_objPrograma)
            {
                $this->_view->EP_nombreProg= $EP_objPrograma[0]->getNombre();
                $rutaPDF=ROOT . 'public' . DS . 'pdf' . DS . 'upl_' . $EP_objPrograma[0]->getCodigo() . '.pdf';
                if(is_readable($rutaPDF))
                {
                    
                    $this->_view->EP_PDF= 'upl_' . $EP_objPrograma[0]->getCodigo() . '.pdf';
                }
                else
                {
                    $this->_view->EP_PDF= false;
                }

                $this->_view->renderingCenterBox('editarPrograma');
            }
            else
            {
                throw new Exception('Error al intentar editar programa. (Metodo)');
            }
        }
        else
        {
            throw new Exception('Error al intentar editar programa');
        }
    }
    
    public function modificarPrograma()
    {
        if(strtolower($this->getServer('HTTP_X_REQUESTED_WITH'))=='xmlhttprequest')
        {
            $rutaPDF= ROOT . 'public' . DS . 'pdf' . DS;
            $MP_programa= $this->loadModel('programa');
            
            if(isset($_FILES['flPDF']['name']))
            {
                if($_FILES['flPDF']['name'])
                {
                    //$this->getLibrary('upload' . DS . 'class.upload');

                    $upload= new upload($_FILES['flPDF'], 'es_ES');
                    $upload->allowed= array('application/pdf');
                    $upload->file_max_size = '2097152'; // 2MB
                    //$upload->file_new_name_body= 'upl_' . uniqid();
                    $upload->file_new_name_body= 'upl_' . Session::get('sessMOD_EP_codPRG');
                    $upload->process($rutaPDF);

                    if($upload->processed)
                    {
                        echo 'OK';
                    }
                    else
                    {
                        throw new Exception( $upload->error );
                    }
                }
                else
                {
                    throw new Exception('Debe seleccionar un archivo desde su computador');
                }
            }
            else
            {
                if($this->getTexto('chkEP_flPDF'))
                {
                    if($this->getTexto('chkEP_flPDF')=='on')
                    {
                        if(Functions::eliminaFile($rutaPDF . 'upl_' . Session::get('sessMOD_EP_codPRG') . '.pdf'))
                        {
                            echo 'OK';
                        }
                        else
                        {
                            throw new Exception('Error al eliminar el archivo, intente nuevamente');
                        }
                    }
                    else
                    {
                        throw new Exception('Debe seleccionar un archivo a eliminar');
                    }
                }
                else
                {
                    throw new Exception('Debe seleccionar un archivo desde su computador');
                }
            }
        }
        else
        {
            throw new Exception('Error inesperado, intente nuevamente. Si el error persiste comuniquese con el administrador');
        }
    }
    
    
    
    /*
     * LOGO VOUCHER
     */
    public function logoVoucher()
    {
        Session::destroy('sessMOD_LV_idAgen');
        Session::destroy('sessMOD_LV_imagen');
        $I_idAgen= $this->getTexto('_PCD_');
        if($I_idAgen)
        {
            Session::set('sessMOD_LV_idAgen', $I_idAgen);
            $agencia= $this->loadModel('agencia');
            $LV_objAgencia= $agencia->getAgencias($I_idAgen);
            if($LV_objAgencia)
            {
                $this->_view->I_nombre= $LV_objAgencia[0]->getNombre();
                $this->_view->I_imagen= $LV_objAgencia[0]->getImagen();
                Session::set('sessMOD_LV_imagen', $this->_view->I_imagen);
                
                $this->_view->renderingCenterBox('logoVoucher');
            }
            else
            {
                throw new Exception('Error al intentar ver agencia');
            }
        }
    }
    
    public function modificarLogoVoucher()
    {
        if(strtolower($this->getServer('HTTP_X_REQUESTED_WITH'))=='xmlhttprequest')
        {
            $rutaIMG= ROOT . 'public' . DS . 'img' . DS . 'voucher' . DS;
            $MLV_agencia= $this->loadModel('agencia');
            
            if(isset($_FILES['flImagenVouAgen']['name']))
            {
                if($_FILES['flImagenVouAgen']['name'])
                {
                    //$this->getLibrary('upload' . DS . 'class.upload');

                    $upload= new upload($_FILES['flImagenVouAgen'], 'es_ES');
                    $upload->allowed= array('image/jpg', 'image/jpeg', 'image/png', 'image/gif');
                    $upload->file_max_size = '524288'; // 512KB
                    //$upload->file_new_name_body= 'upl_' . uniqid();
                    $upload->file_new_name_body= 'upl_' . Session::get('sessMOD_LV_idAgen');
                    $upload->process($rutaIMG);

                    if($upload->processed)
                    {
                        $MLV_agencia->actualizaVoucherAgen(Session::get('sessMOD_LV_idAgen'), $upload->file_dst_name);
                        echo 'OK';
                    }
                    else
                    {
                        throw new Exception( $upload->error );
                    }
                }
                else
                {
                    throw new Exception('Debe seleccionar un archivo desde su computador');
                }
            }
            else
            {
                if($this->getTexto('chk_flImagenVouAgen'))
                {
                    if($this->getTexto('chk_flImagenVouAgen')=='on')
                    {
                        if(Functions::eliminaFile($rutaIMG . Session::get('sessMOD_LV_imagen')))
                        {
                            $MLV_agencia->actualizaVoucherAgen(Session::get('sessMOD_LV_idAgen'), '');
                            echo 'OK';
                        }
                        else
                        {
                            throw new Exception('Error al eliminar el archivo, intente nuevamente');
                        }
                    }
                    else
                    {
                        throw new Exception('Debe seleccionar un archivo a eliminar');
                    }
                }
                else
                {
                    throw new Exception('Debe seleccionar un archivo desde su computador');
                }
            }
        }
        else
        {
            throw new Exception('Error inesperado, intente nuevamente. Si el error persiste comuniquese con el administrador');
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /*******************************************************************************
    *                                                                              *
    *                               METODOS PRIVADOS                               *
    *                                                                              *
    *******************************************************************************/
    private function _loadLeft()
    {
        $this->_view->ML_fechaIni=  Session::get('sess_fechaDefault');
        $this->_view->ML_fechaFin=  Session::get('sess_fechaDefault');
        $this->_view->ML_fechaIni_PRG=  Session::get('sess_fechaDefault');
        $this->_view->ML_fechaFin_PRG=  Session::get('sess_fechaDefault');
    }
    
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
    public function buscarBloqueos()
    {
        $BP_cntHab= $this->getInt('mL_cmbHab');
        $BP_ciudadDes= $this->getTexto('mL_txtCiudadDestino');
        $BP_fechaIn= $this->getTexto('mL_txtFechaIn');
        $BP_fechaOut= $this->getTexto('mL_txtFechaOut');
        $BP_hotel= $this->getTexto('mL_txtHotel');

        if($BP_ciudadDes)
        {
            Session::set('sess_BP_ciudadDes', $BP_ciudadDes);
        }

        Session::set('sess_BP_cntHab', $BP_cntHab);
        Session::set('sess_BP_fechaIn', $BP_fechaIn);
        Session::set('sess_BP_fechaOut', $BP_fechaOut);
        Session::set('sess_BP_hotel', $BP_hotel);


        Session::set('sess_BP_cntPas', 0);
        Session::set('sess_BP_cntAdl', 0);
        Session::set('sess_BP_cntChd', 0);
        Session::set('sess_BP_cntInf', 0);
        for($i=1; $i<=3; $i++)
        {
            if($i<=$BP_cntHab)
            {
                Session::set('sess_BP_Adl_'.$i, $this->getInt('mL_cmbAdultos_'.$i));
                Session::set('sess_BP_Chd_'.$i, $this->getInt('mL_child_'.$i));
                Session::set('sess_BP_Inf_'.$i, $this->getInt('mL_inf_'.$i));


                if(Session::get('sess_BP_Adl_'.$i)>0)
                {
                    Session::set('sess_BP_cntAdl', (Session::get('sess_BP_cntAdl')+1));
                }
                if(Session::get('sess_BP_Chd_'.$i)>0)
                {
                    Session::set('sess_BP_cntChd', (Session::get('sess_BP_cntChd')+1));
                }
                if(Session::get('sess_BP_Inf_'.$i)>0)
                {
                    Session::set('sess_BP_cntInf', (Session::get('sess_BP_cntInf')+1));
                }


                for($x=1; $x<=2; $x++)
                {
                    Session::set('sess_BP_edadChd_'.$x.'_' . $i, $this->getInt('mL_edadChild_'.$x.'_'.$i));
                }

                Session::set('sess_BP_cntPas', (Session::get('sess_BP_cntPas')+Session::get('sess_BP_Adl_'.$i)+Session::get('sess_BP_Chd_'.$i)));
            }
            else
            {
                Session::set('sess_BP_Adl_'.$i, 0);
                Session::set('sess_BP_Chd_'.$i, 0);
                Session::set('sess_BP_Inf_'.$i, 0);

                Session::set('sess_BP_edadChd_1_'.$i, 0);
                Session::set('sess_BP_edadChd_2_'.$i, 0);
            }
        }

        $this->redireccionar('system/bloqueos');
    }
    
    public function buscarProgramas()
    {
        $BP_cntHab= $this->getInt('mL_cmbHab_PRG');
        $BP_ciudadDes= $this->getTexto('mL_txtCiudadDestino_PRG');
        $BP_fechaIn= $this->getTexto('mL_txtFechaIn_PRG');
        $BP_fechaOut= $this->getTexto('mL_txtFechaOut_PRG');
        $BP_hotel= $this->getTexto('mL_txtHotel_PRG');

        if($BP_ciudadDes)
        {
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
        for($i=1; $i<=3; $i++)
        {
            if($i<=$BP_cntHab)
            {
                Session::set('sess_BP_Adl_'.$i, $this->getInt('mL_cmbAdultos_'.$i));
                Session::set('sess_BP_Chd_'.$i, $this->getInt('mL_child_'.$i));
                Session::set('sess_BP_Inf_'.$i, $this->getInt('mL_inf_'.$i));


                if(Session::get('sess_BP_Adl_'.$i)>0)
                {
                    Session::set('sess_BP_cntAdl', (Session::get('sess_BP_cntAdl')+1));
                }
                if(Session::get('sess_BP_Chd_'.$i)>0)
                {
                    Session::set('sess_BP_cntChd', (Session::get('sess_BP_cntChd')+1));
                }
                if(Session::get('sess_BP_Inf_'.$i)>0)
                {
                    Session::set('sess_BP_cntInf', (Session::get('sess_BP_cntInf')+1));
                }


                for($x=1; $x<=2; $x++)
                {
                    Session::set('sess_BP_edadChd_'.$x.'_' . $i, $this->getInt('mL_edadChild_'.$x.'_'.$i));
                }

                Session::set('sess_BP_cntPas', (Session::get('sess_BP_cntPas')+Session::get('sess_BP_Adl_'.$i)+Session::get('sess_BP_Chd_'.$i)));
            }
            else
            {
                Session::set('sess_BP_Adl_'.$i, 0);
                Session::set('sess_BP_Chd_'.$i, 0);
                Session::set('sess_BP_Inf_'.$i, 0);

                Session::set('sess_BP_edadChd_1_'.$i, 0);
                Session::set('sess_BP_edadChd_2_'.$i, 0);
            }
        }

        $this->redireccionar('system/programas');
    }
    
    public function buscarAdmProgramas()
    {
        Session::set('sess_AP_ciudad', $this->getTexto('AP_cmbCiudadDestino'));
        $this->redireccionar('system/adminProgramas');
    }
    
    public function buscarHotel()
    {
        Session::set('sess_H_nombre', $this->getTexto('txtNombre-Hot'));
        Session::set('sess_H_ciudad', $this->getTexto('cmbCiudad-Hot'));
        Session::set('sess_H_cat', $this->getTexto('cmbCategoria'));
        
        $this->redireccionar('system/hoteles');
    }
    
    public function buscarReservas()
    {
        Session::set('sess_CR_fechaDesde', $this->getTexto('txtFechaDesde-ConsRes'));
        Session::set('sess_CR_fechaHasta', $this->getTexto('txtFechaHasta-ConsRes'));
        Session::set('sess_CR_tipoFecha', $this->getInt('rdbFecha'));
        
        $this->redireccionar('system/consultarReserva');
    }
    
    public function salir()
    {
        Session::destroy();
        header('Location: ' . BASE_URL . 'login?ex');
        exit;
    }
}
?>