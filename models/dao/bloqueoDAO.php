<?php

/* 
 * Proyecto : Euroandino.net
 * Autor    : Tsyacom Ltda.
 * Fecha    : Miercoles, 10 de octubre de 2014
 */

class bloqueoDAO extends Model
{
    public function __construct() {
        parent::__construct();
    }
    
    public function getBloqueos($cod)
    {
        $sql='SELECT notas FROM bloqueos WHERE record_c = "'.$cod.'"';
        $datos= $this->_db->consulta($sql);
        
        if($this->_db->numRows($datos)>0)
        {
            $objetosBloqueos= array();
            $arrayBloqueos= $this->_db->fetchAll($datos);
            
            foreach ($arrayBloqueos as $dfDB)
            {
                $objBloq= new bloqueoDTO();

                $objBloq->setNotas(trim($dfDB['notas']));
                
                $objetosBloqueos[]= $objBloq;
            }
            
            return $objetosBloqueos;
        }
        else
        {
            return false;
        }
    }
    
    
    public function getDetBloq($codBloq, $nFile)
    {
        $sql = 'SELECT nompax, rut, CONVERT(Nvarchar(10), fchild,103) as fchild, ninfant, rut_inf, CONVERT(Nvarchar(10), finfant, 103) as finfant, '
                . 'tp, tipo_pax, CONVERT(Nvarchar(10), fecha_pag, 103) as fecha_pag, horarobot '
                . 'FROM det_bloq '
                . 'WHERE record_c = "'.$codBloq.'" and num_file = '.$nFile;
        //echo $sql;
        $datos= $this->_db->consulta($sql);
        if($this->_db->numRows($datos)>0)
        {
            $objetosDetBloq= array();
            $arrayDetBloq= $this->_db->fetchAll($datos);
            
            foreach ($arrayDetBloq as $detB)
            {
                $objDetB= new detalleBloqueoDTO();
                
                $objDetB->setNomPax(trim($detB['nompax']));
                $objDetB->setRut(trim($detB['rut']));
                $objDetB->setFChild(trim($detB['fchild']));
                $objDetB->setNInfant(trim($detB['ninfant']));
                $objDetB->setRutInf(trim($detB['rut_inf']));
                $objDetB->setFInfant(trim($detB['finfant']));
                $objDetB->setTp(trim($detB['tp']));
                $objDetB->setTipoPax(trim($detB['tipo_pax']));
                $objDetB->setFechaPag(trim($detB['fecha_pag']));
                $objDetB->setHoraRobot(trim($detB['horarobot']));
                
                $objetosDetBloq[]= $objDetB;
            }
            
            return $objetosDetBloq;
        }
        else
        {
            return false;
        }
    }
}