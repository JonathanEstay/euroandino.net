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
    public function index()
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
            $sql="EXEC TS_GET_PROGRAMAS_TEST '".Session::get('sess_BP_ciudadDes_PRG')."', '', '".str_replace('/', '-', Session::get('sess_BP_fechaIn_PRG'))."' ";
            
            Session::set('sess_TS_GET_PROGRAMAS', $sql);
            //echo $sql; exit;
            
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
    
    
    public function admin()
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
    
    
    
    
    
    
    
    
    
    
    /*******************************************************************************
    *                                                                              *
    *                          METODOS VIEWS CENTER BOX                            *
    *                                                                              *
    *******************************************************************************/
    public function detalle()
    {
        Session::acceso('Usuario');
        $programas= $this->loadModel('programa');
        
        if($this->getInt('__SP_id__')) {
            $sql="EXEC TS_GET_PROGRAMAS_ID_TEST " . $this->getInt('__SP_id__');
            //Session::set('sess_TS_GET_PROGRAMAS_ID', $sql);
            //echo $sql; exit;
            $this->_view->objProgramas= $programas->exeTS_GET_PROGRAMAS($sql);
            
            
            //Local
            $sql="EXEC TS_GET_DETALLEPROG ".$this->getInt('__SP_id__')." ";

            //Session::set('sess_TS_GET_DETALLEPROG', $sql);
            //echo $sql; exit;

            $objOpcProgramas= $programas->exeTS_GET_DETALLEPROG($sql);
            if($objOpcProgramas) {
                if($objOpcProgramas[0]->getError()) {
                    throw new Exception('<b>Error</b>: ['.$objOpcProgramas[0]->getError().'] <br> <b>Mensaje</b>: ['.$objOpcProgramas[0]->getMensaje().']');
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
    
    
    public function procesoReserva()
    {
        Session::acceso('Usuario');
        if(strtolower($this->getServer('HTTP_X_REQUESTED_WITH'))=='xmlhttprequest')
        {
            $programas= $this->loadModel('programa');
            echo 'TODO BIEN';
        } else {
            throw new Exception('Error al cargar las opciones');
        }
    }
    
    
    public function editar()
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
                $rutaPDF=ROOT . 'public' . DS . 'pdf' . DS . 'upl_' . str_replace(' ', '_', $EP_objPrograma[0]->getCodigo()) . '.pdf';
                //echo $rutaPDF; exit;
                if(is_readable($rutaPDF))
                {
                    $this->_view->EP_PDF= 'upl_' . str_replace(' ', '_', $EP_objPrograma[0]->getCodigo()) . '.pdf';
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
    
    
    public function modificar()
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
                        //echo Session::get('sessMOD_EP_codPRG'); exit;
                        if(Functions::eliminaFile($rutaPDF . 'upl_' . str_replace(' ', '_', Session::get('sessMOD_EP_codPRG')) . '.pdf'))
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
    
    
    
    
    
    
    
    
    
    
    /*******************************************************************************
    *                                                                              *
    *                             METODOS PROCESADORES                             *
    *                                                                              *
    *******************************************************************************/
    public function buscar()
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

        $this->redireccionar('programas');
    }
    
    
    public function buscarAdm()
    {
        Session::set('sess_AP_ciudad', $this->getTexto('AP_cmbCiudadDestino'));
        $this->redireccionar('programas/admin');
    }
}