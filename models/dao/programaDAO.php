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
            
            $objPackages->setNotaOpc(trim($arrayPackages[0]['nota']));
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
    
    public function exeSQL($sql)
    {
        $datos= $this->_db->consulta($sql);
        if($this->_db->numRows($datos)>0)
        {
            return $this->_db->fetchAll($datos);
        }
        else
        {
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
                $objProg->setDesde(trim($progDB['desde']));
                $objProg->setHasta(trim($progDB['hasta']));
                $objProg->setNoches(trim($progDB['ntsProg']));
                $objProg->setTipoP(trim($progDB['tipoProg']));
                $objProg->setCodBloq(trim($progDB['codBloq']));
                $objProg->setEspacios(trim($progDB['espacios']));
                $objProg->setPais(trim($progDB['paisPRG']));
                $objProg->setCiudad(trim($progDB['ciudadPRG']));
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
                $objProg->setImagen(trim($progDB['imagen']));
                $objProg->setNota(trim($progDB['nota']));
                $objProg->setIncluye(trim($progDB['incluye']));
                $objProg->setDescrip(trim($progDB['descrip']));
                $objProg->setCatEstrella(trim($progDB['CatEstrella']));
                $objProg->setIata(trim($progDB['iata']));
                
                $objetosProg[] = $objProg;
            }

            return $objetosProg;
            
        } else {
            return false;
        }
    }
    
}