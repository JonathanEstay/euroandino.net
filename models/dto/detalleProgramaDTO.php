<?php

/* 
 * Proyecto : Euroandino.net
 * Autor    : Tsyacom Ltda.
 * Fecha    : Miercoles, 10 de octubre de 2014
 */

class detalleProgramaDTO
{
    private $_cod_prog;
    private $_cod_bloq;
    private $_clave_opc;
    private $_fec_bloq;
    private $_desde;
    private $_hasta;
    private $_cup_bloq;
    private $_cod_aero;
    private $_aerolin;
    private $_moneda;
    private $_tcambio;
    private $_vven_sgl;
    private $_vneto_sgl;
    private $_vven_dbl;
    private $_vneto_dbl;
    private $_vven_tpl;
    private $_vneto_tpl;
    private $_estado;
    
    private $_cod_hoteles;
    private $_nom_hoteles;
    private $_dir;
    private $_cat;
    private $_estrellas;
    private $_cod_th;
    private $_th;
    
    private $_error;
    private $_mensaje;
    private $_file;
    
    
    public function getFile() {
        return $this->_file;
    }

    public function setFile($file) {
        $this->_file = $file;
    }
        
    
    public function getError() {
        return $this->_error;
    }
    public function setError($error) {
        $this->_error = $error;
    }
    
    public function getMensaje() {
        return $this->_mensaje;
    }
    public function setMensaje($msg) {
        $this->_mensaje = $msg;
    }
    
    public function getCodProg() {
        return $this->_cod_prog;
    }
    public function setCodProg($cod) {
        $this->_cod_prog = $cod;
    }
    
    public function getCodBloq() {
        return $this->_cod_bloq;
    }
    public function setCodBloq($cod) {
        $this->_cod_bloq = $cod;
    }
    
    public function getClaveOpc() {
        return $this->_clave_opc;
    }
    public function setClaveOpc($clave) {
        $this->_clave_opc = $clave;
    }
    
    public function getFechaBloq() {
        return $this->_fec_bloq;
    }
    public function setFechaBloq($fecha) {
        $this->_fec_bloq = $fecha;
    }
    
    public function getDesde() {
        return $this->_desde;
    }
    public function setDesde($desde) {
        $this->_desde = $desde;
    }
    
    public function getHasta() {
        return $this->_hasta;
    }
    public function setHasta($hasta) {
        $this->_hasta = $hasta;
    }
    
    public function getCupBloq() {
        return $this->_cup_bloq;
    }
    public function setCupBloq($cupos) {
        $this->_cup_bloq = $cupos;
    }
    
    public function getCodAero() {
        return $this->_cod_aero;
    }
    public function setCodAero($cod) {
        $this->_cod_aero = $cod;
    }
    
    public function getAerolin() {
        return $this->_aerolin;
    }
    public function setAerolin($aero) {
        $this->_aerolin = $aero;
    }
    
    public function getMoneda() {
        return $this->_moneda;
    }
    public function setMoneda($m) {
        $this->_moneda = $m;
    }
    
    public function getTCambio() {
        return $this->_tcambio;
    }
    public function setTCambio($cambio) {
        $this->_tcambio = $cambio;
    }
    
    public function getVVenSGL() {
        return $this->_vven_sgl;
    }
    public function setVVenSGL($valor) {
        $this->_vven_sgl = $valor;
    }
    
    public function getVVenDBL() {
        return $this->_vven_dbl;
    }
    public function setVVenDBL($valor) {
        $this->_vven_dbl = $valor;
    }
    
    public function getVVenTPL() {
        return $this->_vven_tpl;
    }
    public function setVVenTPL($valor) {
        $this->_vven_tpl = $valor;
    }
    
    public function getVNetoSGL() {
        return $this->_vneto_sgl;
    }
    public function setVNetoSGL($valor) {
        $this->_vneto_sgl = $valor;
    }
    
    public function getVNetoDBL() {
        return $this->_vneto_dbl;
    }
    public function setVNetoDBL($valor) {
        $this->_vneto_dbl = $valor;
    }
    
    public function getVNetoTPL() {
        return $this->_vneto_tpl;
    }
    public function setVNetoTPL($valor) {
        $this->_vneto_tpl = $valor;
    }
    
    public function getEstado() {
        return $this->_estado;
    }
    public function setEstado($st) {
        $this->_estado = $st;
    }
    
    public function getCodHotel() {
        return $this->_cod_hoteles;
    }
    public function setCodHotel($cod) {
        $this->_cod_hoteles = $cod;
    }
    
    public function getNombreHotel() {
        return $this->_nom_hoteles;
    }
    public function setNombreHotel($hotel) {
        $this->_nom_hoteles = $hotel;
    }
    
    public function getDir() {
        return $this->_dir;
    }
    public function setDir($dir) {
        $this->_dir = $dir;
    }
    
    public function getCat() {
        return $this->_cat;
    }
    public function setCat($cat) {
        $this->_cat = $cat;
    }
    
    public function getEstrellas() {
        return $this->_estrellas;
    }
    public function setEstrellas($star) {
        $this->_estrellas = $star;
    }
    
    public function getCodTH() {
        return $this->_cod_th;
    }
    public function setCodTH($cod) {
        $this->_cod_th = $cod;
    }
    
    public function getTipoHab() {
        return $this->_th;
    }
    public function setTipoHab($th) {
        $this->_th = $th;
    }
}