<?php

/* 
 * Proyecto : Euroandino.net
 * Autor    : Tsyacom Ltda.
 * Fecha    : Miercoles, 10 de octubre de 2014
 */

class categoriaDAO extends Model
{
    public function getCategorias()
    {
        $sql='SELECT codigo, nombre FROM cath ORDER BY nombre ASC';
        
        $datos= $this->_db->consulta($sql);
        if($this->_db->numRows($datos)>0)
        {
            $categoriaArray= $this->_db->fetchAll($datos);
            $objetosCategoria= array();
            
            foreach ($categoriaArray as $catDB)
            {
                $categoriaObj= new categoriaDTO();
                $categoriaObj->setCodigo(trim($catDB['codigo']));
                $categoriaObj->setNombre(trim($catDB['nombre']));
                
                $objetosCategoria[]= $categoriaObj;
            }
            
            return $objetosCategoria;
        }
        else
        {
            return false;
        }
    }
}