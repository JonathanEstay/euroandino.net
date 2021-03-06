<?php

/* 
 * Proyecto : Euroandino.net
 * Autor    : Tsyacom Ltda.
 * Fecha    : Miercoles, 10 de octubre de 2014
 */

class programaDAO extends Model
{
    public function __construct() {
        parent::__construct();
    }
    
    public function getPackages($cod)
    {
        $sql='SELECT * FROM packages WHERE codigo = "'.$cod.'" ';
        $datos= $this->_db->consulta($sql);
        if($this->_db->numRows($datos)>0)
        {
            $objetosPack= array();
            $arrayPackages= $this->_db->fetchAll($datos);
            
            foreach ($arrayPackages as $packDB)
            {
                $objPackages= new programaDTO();
                
                $objPackages->setCodigo(trim($packDB['codigo']));
                $objPackages->setNombre(trim($packDB['nombre']));
                
                $objetosPack[]= $objPackages;
            }
            
            return $objetosPack;
        }
        else
        {
            return false;
        }
    }
    
    public function getIncluye($idPRG)
    {
        $sql="exec WEB_TraeDetalle '".$idPRG."' ";
        $datos= $this->_db->consulta($sql);
        if($this->_db->numRows($datos)>0)
        {
            $objetosInc= array();
            $arrayIncluye= $this->_db->fetchAll($datos);
            
            foreach ($arrayIncluye as $incDB)
            {
                $objIncluye= new incluyeDTO();
                
                $objIncluye->setCodigo(trim($incDB['codsvr']));
                $objIncluye->setNombre(trim($incDB['nombre']));
                $objIncluye->setCiudad(trim($incDB['ciudadHotel']));
                $objIncluye->setNoches((int)trim($incDB['Noches']));
                
                $objetosInc[]= $objIncluye;
            }
            
            return $objetosInc;
        }
        else
        {
            return false;
        }
    }
    
    public function getAdmProgramas($ciudad=0, $codProg=0)
    {
        $and='';
        $sql='SELECT P.id, P.nombre, P.codigo, C.nombre AS nombreC
            FROM h2h_programa P
            JOIN ciudad	C ON (C.codigo = P.Ciudad)
            WHERE ';
        if(!empty($ciudad))
        {
            $sql.=' C.nombre = "'.trim($ciudad).'" ';
            $and=' AND ';
        }

        if(!empty($codProg))
        {
            $sql.=$and.' P.codigo="'.$codProg.'" ';
        }

        $sql.='ORDER BY P.nombre ASC ';
        
        
        $datos= $this->_db->consulta($sql);
        if($this->_db->numRows($datos)>0)
        {
            $objetosPack= array();
            $arrayPackages= $this->_db->fetchAll($datos);
            
            foreach ($arrayPackages as $packDB)
            {
                $objPackages= new programaDTO();
                
                $objPackages->setCodigo(trim($packDB['codigo']));
                $objPackages->setNombre(trim($packDB['nombre']));
                $objPackages->setId(trim($packDB['id']));
                $objPackages->setCiudad(trim($packDB['nombreC']));
                
                $objetosPack[]= $objPackages;
            }
            
            return $objetosPack;
        }
        else
        {
            return false;
        }
    }
    
    public function exeTraeProgramas($sql, $inc=false)
    {
        $datos= $this->_db->consulta($sql);
        if($this->_db->numRows($datos)>0)
        {
            $objetosPack= array();
            $arrayPackages= $this->_db->fetchAll($datos);
            
            foreach ($arrayPackages as $packDB)
            {
                $hotel= array();
                $codHotel= array();
                $PA= array();
                $TH= array();
                $codTH= array();
                $cat= array();
                $ciudad= array();
                $incluye= array();
                $valorHab= array();
                
                
                $objPackages= new programaDTO();
                
                if(trim(isset($packDB['Error'])))
                {
                    $objPackages->setERROR(trim($packDB['Error']));
                    $objPackages->setLINEA(trim($packDB['Linea']));
                    $objPackages->setMENSAJE(trim($packDB['Mensaje']));
                }
                else
                {
                    $objPackages->setId(trim($packDB['idPRG']));
                    $objPackages->setNombre(trim($packDB['nombrePRG']));
                    $objPackages->setNota(trim($packDB['notaPRG']));
                    $objPackages->setIdOpc(trim($packDB['idOpcion']));
                    $objPackages->setDesde(trim($packDB['desde']));
                    $objPackages->setTramo(trim($packDB['Tramo']));
                    $objPackages->setNotaOpc(trim($packDB['notaOPC']));
                    $objPackages->setMoneda(trim($packDB['moneda']));
                    $objPackages->setItiVuelo(trim($packDB['itinerarioVuelo']));
                    
                    /* VALOR HABITACION */
                    for ($i=1; $i<=3; $i++)
                    {
                        $valorHab[]=trim($packDB['vHab_'.$i]);
                    }
                    $objPackages->setValorHab($valorHab);
                    /* VALOR HABITACION */
                    
                    
                    /* HOTELES */
                    for($i=1; $i<=5; $i++)
                    {
                        $hotel[]=trim($packDB['hotel_'.$i]);
                        $codHotel[]=trim($packDB['codHotel_'.$i]);
                        $PA[]=trim($packDB['PlanAlimenticio_'.$i]);
                        $TH[]=trim($packDB['TipoHabitacion_'.$i]);
                        $codTH[]=trim($packDB['codTipoHabitacion_'.$i]);
                        $cat[]=trim($packDB['cat_'.$i]);
                        $ciudad[]=trim($packDB['ciudad_'.$i]);
                    }
                    
                    $objPackages->setHoteles($hotel);
                    $objPackages->setCodHoteles($codHotel);
                    $objPackages->setPA($PA);
                    $objPackages->setTH($TH);
                    $objPackages->setCodTH($codTH);
                    $objPackages->setCat($cat);
                    $objPackages->setCiudad($ciudad);
                    /* HOTELES */
                    
                    if($inc)
                    {
                        $incluye[]= $this->getIncluye(trim($packDB['idPRG']));
                        $objPackages->setIncluye($incluye);
                    }
                    //$objPackages->setXXXX(trim($packDB['xxxxx']));
                }
                
                $objetosPack[]= $objPackages;
                //sleep(1);
            }
            
            return $objetosPack;
        }
        else
        {
            return false;
        }
    }
    
    public function getNota($id)
    {
        $sql="SELECT REPLACE(convert(varchar(MAX), nota), Char(13), '<br />') as nota "
            . "FROM h2h_Programa WHERE Id=".$id;
        
        $datos= $this->_db->consulta($sql);
        if($this->_db->numRows($datos)>0)
        {
            $objetosPack= array();
            $arrayPackages= $this->_db->fetchAll($datos);
            
            $objPackages= new programaDTO();
            
            $objPackages->setNota(trim($arrayPackages[0]['nota']));
            $objetosPack[]= $objPackages;
            
            return $objetosPack;
        }
        else
        {
            return false;
        }
    }
    
    public function getItinerarioVuelo($idOpc)
    {
        $sql="SELECT REPLACE(convert(varchar(MAX), notas), Char(13), '<br />') as notas "
            . "FROM bloqueos B JOIN h2h_programaOpc PO ON (B.record_c = PO.record_c) "
            . "WHERE PO.IdOpc=$idOpc";
        
        $datos= $this->_db->consulta($sql);
        if($this->_db->numRows($datos)>0)
        {
            $objetosPack= array();
            $arrayPackages= $this->_db->fetchAll($datos);
            
            $objPackages= new programaDTO();
            
            $objPackages->setItiVuelo(trim($arrayPackages[0]['notas']));
            $objetosPack[]= $objPackages;
            
            return $objetosPack;
        }
        else
        {
            return false;
        }
    }
    
    public function getNotaOpc($idOpc)
    {
        $sql="SELECT REPLACE(convert(varchar(MAX), nota), Char(13), '<br />') as nota "
            . "FROM h2h_ProgramaOpc WHERE IdOpc=$idOpc";
        
        $datos= $this->_db->consulta($sql);
        if($this->_db->numRows($datos)>0)
        {
            $objetosPack= array();
            $arrayPackages= $this->_db->fetchAll($datos);
            
            $objPackages= new programaDTO();
            
            //$objPackages->setNotaOpc(trim($arrayPackages[0]['nota']));
            $objPackages->setNota(trim($arrayPackages[0]['nota']));
            $objetosPack[]= $objPackages;
            
            return $objetosPack;
        }
        else
        {
            return false;
        }
    }
    
    public function validaPrograma($id, $idOpc)
    {
        $sql='SELECT P.codigo, P.nombre
            FROM h2h_programaOpc PO
            JOIN h2h_programa P ON (PO.IdProg=P.Id)
            WHERE P.Id=' . $id . ' AND PO.IdOpc='.$idOpc;
        
        $datos= $this->_db->consulta($sql);
        if($this->_db->numRows($datos)>0)
        {
            $objetosPack= array();
            $arrayPackages= $this->_db->fetchAll($datos);
            
            $objPackages= new programaDTO();
            
            $objPackages->setCodigo(trim($arrayPackages[0]['codigo']));
            $objPackages->setNombre(trim($arrayPackages[0]['nombre']));
            
            $objetosPack[]= $objPackages;
            
            return $objetosPack;
        }
        else
        {
            return false;
        }
    }
    
    public function exeSQL($sql) {
        $datos= $this->_db->consulta($sql);
        if($this->_db->numRows($datos)>0) {
            return $this->_db->fetchAll($datos);
        } else {
            return false;
        }
    }
    
    
    public function exeTS_GET_PROGRAMAS($sql) {
        $datos = $this->_db->consulta($sql);
        if($this->_db->numRows($datos)>0) {
            
            $objetosProg = array();
            $arrayProgramas= $this->_db->fetchAll($datos);
            
            foreach ($arrayProgramas as $progDB) {
                $objProg = new programaDTO();

                $objProg->setId(trim($progDB['idProg']));
                $objProg->setCodigo(trim($progDB['codProg']));
                
                if(isset($progDB['desde'])) {
                    $objProg->setDesde(trim($progDB['desde']));
                }
                
                if(isset($progDB['hasta'])) {
                    $objProg->setHasta(trim($progDB['hasta']));
                }
                $objProg->setNoches(trim($progDB['ntsProg']));
                $objProg->setTipoP(trim($progDB['tipoProg']));
                $objProg->setCodBloq(trim($progDB['codBloq']));
                $objProg->setEspacios(trim($progDB['espacios']));
                $objProg->setPais(trim($progDB['paisPRG']));
                $objProg->setCiudad(trim($progDB['ciuPRG']));
                $objProg->setTitulo(trim($progDB['titulo']));
                $objProg->setEpigrafe(trim($progDB['epigrafe']));
                $objProg->setMoneda(trim($progDB['moneda']));
                $objProg->setTcambio(trim($progDB['tcambio']));
                $objProg->setValorDesde(trim($progDB['valdesde']));
                $objProg->setAereo(trim($progDB['aereo']));
                $objProg->setHotel(trim($progDB['hotel']));
                $objProg->setTraslado(trim($progDB['traslado']));
                $objProg->setAllInclu(trim($progDB['allInclu']));
                $objProg->setRAuto(trim($progDB['rauto']));
                $objProg->setCrucero(trim($progDB['crucero']));
                $objProg->setAsis(trim($progDB['asisten']));
                $objProg->setIti(trim($progDB['itinera']));
                
                $ext = Functions::getExtensionImagen(ROOT . 'public' . DS . 'img'. DS . 'programas'. DS . 'upl_' . str_replace(' ', '_', trim($progDB['codProg'])));
                if($ext) {
                    $objProg->setImagen('upl_' . str_replace(' ', '_', trim($progDB['codProg'])) . $ext);
                } else {
                    $objProg->setImagen('');
                }
                
                $objProg->setNota(trim($progDB['nota']));
                $objProg->setIncluye(trim($progDB['incluye']));
                $objProg->setDescrip(html_entity_decode(trim($progDB['descrip'])));
                
                if(isset($progDB['CatEstrella'])) {
                    $objProg->setCatEstrella(trim($progDB['CatEstrella']));
                }
                
                if(isset($progDB['iata'])) {
                    $objProg->setIata(trim($progDB['iata']));
                }
                $objetosProg[] = $objProg;
            }

            return $objetosProg;
            
        } else {
            return false;
        }
    }
    
    
    
    public function exeTS_GET_DETALLEPROG($sql) {
        $datos = $this->_db->consulta($sql);
        if($this->_db->numRows($datos)>0) {
            
            $objetosDetProg = array();
            $arrayDetProgramas= $this->_db->fetchAll($datos);
            
            //echo 'VAR:: '.var_dump($arrayDetProgramas); exit;
            foreach ($arrayDetProgramas as $detProgDB) {
                
                $objDetProg = new detalleProgramaDTO();
                
                if(isset($detProgDB['Error'])) {

                    $objDetProg->setError(trim($detProgDB['Error']));
                    $objDetProg->setMensaje(trim($detProgDB['Mensaje']));
                    
                } else {
                    $nomHotel= array();
                    $codHotel= array();
                    $dirHotel= array();
                    $catHotel= array();
                    $starHotel= array();
                    $codTH= array();
                    $TH= array();

                    

                    $objDetProg->setCodProg(trim($detProgDB['codProg']));
                    $objDetProg->setCodBloq(trim($detProgDB['codBloq']));
                    $objDetProg->setClaveOpc(trim($detProgDB['claveOpc']));
                    $objDetProg->setFechaBloq(trim($detProgDB['fecBloq']));
                    $objDetProg->setDesde(trim($detProgDB['desde']));
                    $objDetProg->setDesde(trim($detProgDB['hasta']));
                    $objDetProg->setCupBloq(trim($detProgDB['cupBloq']));
                    $objDetProg->setCodAero(trim($detProgDB['codAero']));
                    $objDetProg->setAerolin(trim($detProgDB['aerolin']));
                    $objDetProg->setMoneda(trim($detProgDB['moneda']));
                    $objDetProg->setTCambio(trim($detProgDB['tcambio']));
                    $objDetProg->setVVenSGL(trim($detProgDB['vVen_SGL']));
                    $objDetProg->setVNetoSGL(trim($detProgDB['vNeto_SGL']));
                    $objDetProg->setVVenDBL(trim($detProgDB['vVen_DBL']));
                    $objDetProg->setVNetoDBL(trim($detProgDB['vNeto_DBL']));
                    $objDetProg->setVVenTPL(trim($detProgDB['vVen_TPL']));
                    $objDetProg->setVNetoTPL(trim($detProgDB['vNeto_TPL']));
                    //$objDetProg->setXxx(trim($detProgDB['xxx']));

                    /* HOTELES */
                    for($i=1; $i<=5; $i++) {
                        if(trim($detProgDB['nomHotel_'.$i])) {
                            $nomHotel[]=trim($detProgDB['nomHotel_'.$i]);
                            $codHotel[]=trim($detProgDB['codHotel_'.$i]);
                            $dirHotel[]=trim($detProgDB['dirHotel_'.$i]);
                            $catHotel[]=trim($detProgDB['catHot_'.$i]);
                            $starHotel[]=trim($detProgDB['catEstrella_'.$i]);
                            $codTH[]=trim($detProgDB['codTH_'.$i]);
                            $TH[]=trim($detProgDB['tipoHab_'.$i]);
                        }
                    }

                    $objDetProg->setNombreHotel($nomHotel);
                    $objDetProg->setCodHotel($codHotel);
                    $objDetProg->setDir($dirHotel);
                    $objDetProg->setCat($catHotel);
                    $objDetProg->setEstrellas($starHotel);
                    $objDetProg->setCodTH($codTH);
                    $objDetProg->setTipoHab($TH);
                    
                    
                    
                    $objDetProg->setEstado(trim($detProgDB['ESTADO']));
                    /* HOTELES */
                }
                
                $objetosDetProg[] = $objDetProg;
            }

            return $objetosDetProg;
            
        } else {
            return false;
        }
    }
    
    
    
    public function exeTS_RESERVAR($sql) {
        $datos = $this->_db->consulta($sql);
        if($this->_db->numRows($datos)>0) {
            
            $objetosDetProg = array();
            $arrayDetProgramas= $this->_db->fetchAll($datos);
            
            //echo 'VAR:: '.var_dump($arrayDetProgramas); exit;
            foreach ($arrayDetProgramas as $detProgDB) {
                
                $objDetProg = new detalleProgramaDTO();
                
                $objDetProg->setFile(trim($detProgDB['FILE']));
                $objDetProg->setError(trim($detProgDB['CODIGO']));
                $objDetProg->setMensaje(trim($detProgDB['MENSAJE']));
                
                $objetosDetProg[] = $objDetProg;
            }

            return $objetosDetProg;
            
        } else {
            return false;
        }
    }
    
    
    public function getPrograma($cod) {
        $sql = 'SELECT * FROM h2h_PdfProg WHERE codigo = "' . $cod . '"';
        $datos = $this->_db->consulta($sql);
        if($this->_db->numRows($datos)>0) {
            
            $objetos = array();
            $array= $this->_db->fetchAll($datos);
            foreach ($array as $objDB) {
                //$obj = new detalleProgramaDTO();
                //$obj->setFile(trim($objDB['descripcion']));
                $objetos[] = trim($objDB['descripcion']);
            }

            return $objetos;
        } else {
            return false;
        }
    }
    
    public function comentario($cod, $desc, $nuevo = false) {
        if ($nuevo) {
            $sql = 'INSERT INTO h2h_PdfProg (codigo, descripcion) VALUES ("' . $cod . '", "' . str_replace('\\', '', htmlentities($desc)) . '")';
        } else {
            $sql = 'UPDATE h2h_PdfProg SET descripcion = "' . str_replace('\\', '', htmlentities($desc)) . '" WHERE codigo = "' . $cod . '"';
        }
        //echo $sql;
        $this->_db->consulta($sql);
        return true;
    }
    
}