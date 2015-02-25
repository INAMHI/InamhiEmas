<?php

class ConsultaDatos {

    private $_objPDO;

    //----------------------------------------------------------------------------------------------------------------------------------------------------//
    //<editor-fold defaultstate="collapsed" desc="CONSTRUCTOR DE CONECCION A LA BDD NECESARIA PARA EL FUNCIONAMIENTO DE TODA LA CLASE">
    public function __construct($coneccion) {
        $this->_objPDO = $coneccion;
    }

    //</editor-fold>
    //----------------------------------------------------------------------------------------------------------------------------------------------------//
    //----------------------------------------------------------------------------------------------------------------------------------------------------//
    //<editor-fold defaultstate="collapsed" desc="ConsultarUmbralesEstacionParametro">
    public function ConsultarUmbralesEstacionParametro($Metodo, $id_copa, $nombre_nemonico, $id_estacion, $id_parametro, $codigo_estacion) {
        try {
            $vector = array();
            $strQuery = "SELECT maximo, minimo from vta__unidad_medida_parametros_tiempos_estadisticos";

            //Control de variables del tipo de metodo de busqueda
            if ($Metodo == 'MetodoIdParametroIdEstacion')
                $strQuery = $strQuery . " WHERE id_parametro =:id_parametro AND id_estacion = :id_estacion ";

            if ($Metodo == 'MetodoIdParametroCodigoEstacion')
                $strQuery = $strQuery . " WHERE id_parametro =:id_parametro AND codigo_estacion = :codigo_estacion ";

            if ($Metodo == 'MetodoIdCopa')
                $strQuery = $strQuery . " WHERE id_copa =:id_copa ";


            $objStatement = $this->_objPDO->prepare($strQuery);
            if ($Metodo == 'MetodoIdParametroIdEstacion') {
                $objStatement->bindParam(':id_estacion', $id_estacion, PDO::PARAM_INT);
                $objStatement->bindParam(':id_parametro', $id_parametro, PDO::PARAM_INT);
            }

            if ($Metodo == 'MetodoIdParametroCodigoEstacion') {
                $objStatement->bindParam(':id_parametro', $id_parametro, PDO::PARAM_INT);
                $objStatement->bindParam(':codigo_estacion', $codigo_estacion, PDO::PARAM_STR);
            }

            if ($Metodo == 'MetodoIdCopa')
                $objStatement->bindParam(':id_copa', $id_copa, PDO::PARAM_INT);

            if ($Metodo == 'MetodoNombreNemonico')
                $objStatement->bindParam(':nombre_nemonico', $nombre_nemonico, PDO::PARAM_STR);


            $objStatement->execute();
            $cont_filas = 0;
            while ($arRow = $objStatement->fetch(PDO::FETCH_ASSOC)) {
                foreach ($arRow as $key => $value)
                    $vector[$key][] = $value;

                $cont_filas++;
            }
            $vector['num_rows'] = $cont_filas;
            return $vector;
        } catch (PDOException $ex) {
            echo 'Error: ' . $ex;
            exit(0);
        }
    }

    //</editor-fold>
    //----------------------------------------------------------------------------------------------------------------------------------------------------//
    //----------------------------------------------------------------------------------------------------------------------------------------------------//
    //<editor-fold defaultstate="collapsed" desc="ConsultarDecimalesParametro">
    public function ConsultarDecimalesParametro($id_parametro) {
        try {
            $vector = array();
            $strQuery = "SELECT enteros, decimales from vta__parametro_decimales where id_parametro = :id_parametro";
            $objStatement = $this->_objPDO->prepare($strQuery);
            $objStatement->bindParam(':id_parametro', $id_parametro, PDO::PARAM_INT);
            $objStatement->execute();

            $cont_filas = 0;
            while ($arRow = $objStatement->fetch(PDO::FETCH_ASSOC)) {
                foreach ($arRow as $key => $value)
                    $vector[$key][] = $value;

                $cont_filas++;
            }
            $vector['num_rows'] = $cont_filas;
            return $vector;
        } catch (PDOException $ex) {
            echo 'Error: ' . $ex;
            exit(0);
        }
    }

    //</editor-fold>
    //----------------------------------------------------------------------------------------------------------------------------------------------------//
    //----------------------------------------------------------------------------------------------------------------------------------------------------//
    //<editor-fold defaultstate="collapsed" desc="EjecutarCualquierOperacionSobreBaseDatos">
    function EjecutarCualquierOperacionSobreBaseDatos($query1) {
        $objStatement = $this->_objPDO->prepare($query1);
        $objStatement->execute();
    }

    //</editor-fold>
    //----------------------------------------------------------------------------------------------------------------------------------------------------//
    //----------------------------------------------------------------------------------------------------------------------------------------------------//
    //<editor-fold defaultstate="collapsed" desc="EjecutarCualquierConsultaSobreBaseDatos">
    public function EjecutarConsulta($strQuery) {
        try {
            $i = 0;
            $vector = array();
            $vectorNombres = array();
            $objStatement = $this->_objPDO->prepare($strQuery . " ");
            $objStatement->execute();
            $cont_filas = 0;
            while ($arRow = $objStatement->fetch(PDO::FETCH_ASSOC)) {
                foreach ($arRow as $key => $value) {
                    $vector[$key][] = $value;
                    if ($i == 0)
                        $vectorNombres[] = $key;
                }
                $i++;
                $cont_filas++;
            }
            $vector['num_rows'] = $cont_filas;
            $vector['nombre'] = $vectorNombres;
            return $vector;
        } catch (PDOException $ex) {
            echo 'Error: ' . $ex;
            exit(0);
        }
    }

    //</editor-fold>
    //----------------------------------------------------------------------------------------------------------------------------------------------------//



    public function EjecutarConsulta2($strQuery) {
        try {
            $vector = array();
            $objStatement = $this->_objPDO->prepare($strQuery . " ");
            $objStatement->execute();
            $i = 0;
            while ($arRow = $objStatement->fetch(PDO::FETCH_ASSOC)) {
                $vector[$i++] = $arRow;
            }
            return $vector;
        } catch (PDOException $ex) {
            echo 'Error: ' . $ex;
            exit(0);
        }
    }

}

?>