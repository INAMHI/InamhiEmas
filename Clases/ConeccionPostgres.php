<?php
class ConeccionPostgres{
//----------------------------------------------------------------------------------------------------------------------------------------------------------//
//<editor-fold defaultstate="collapsed" desc="VARIABLES NECESARIAS PARA LA CONECCION">    
const HOST= '192.168.1.226';
const DBNAME= 'bandahm';
const USER= 'postgres';
const PASSWORD= 'inamhidb';

//const HOST= 'localhost';
//const DBNAME= 'prueba';
//const USER = 'postgres';
//const PASSWORD = 'postgres';

const PORT = '5432';
private $_objPDO;
//</editor-fold>    
//----------------------------------------------------------------------------------------------------------------------------------------------------------//







    
//----------------------------------------------------------------------------------------------------------------------------------------------------------//
//<editor-fold defaultstate="collapsed" desc="CONECCION A LA BDD">    
public function conectar(){
    try    {
        
//        $opciones = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',);
        $this->_objPDO=new PDO('pgsql:host='.ConeccionPostgres::HOST
                                .';port='.ConeccionPostgres::PORT
                                .';dbname='.ConeccionPostgres::DBNAME, ConeccionPostgres::USER, ConeccionPostgres::PASSWORD);
//                                .', charset=UTF8');
        //$this->dbh = parent::__construct("pgsql:host=$this->host;port=$this->port;dbname=$this->dbname;user=$this->user;password=$this->pass");
        return $this->_objPDO;
    } 
    catch(PDOException $e) {   echo 'existe un error en la conexion'; exit(0); }
}
//</editor-fold>
//------------------------------------------------------------------------------------------------------------------------------------------------------------//
    
    
    
        
    
    
    
    
//----------------------------------------------------------------------------------------------------------------------------------------------------------//
//<editor-fold defaultstate="collapsed" desc="DESCONECCION A LA BDD">    
public function desconectar(){
    if($this->_objPDO)
        $this->_objPDO=NULL;
    else
        echo 'No se ha realizado ninguna conexion';            
}
//</editor-fold>
//------------------------------------------------------------------------------------------------------------------------------------------------------------// 



}
?>