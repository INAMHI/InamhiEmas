
<?php

require './Clases/ConeccionPostgres.php';
require './Clases/ConsultaDatos.php';
//

$queryEstaciones = "SELECT esta__id, puobnomb, puobcodi, coorlati, coorlong, cooraltu, esteicon, estenomb, catenomb 
FROM administrative.vta__lista_estaciones_automaticas where estavex=true";


$objConeccion = new ConeccionPostgres();
$objConsultaDatos = new ConsultaDatos($objConeccion->conectar());

$listaEstaciones = $objConsultaDatos->EjecutarConsulta2($queryEstaciones);

if (isset($_GET['callback'])) {
    echo $_GET['callback'] . '(' . json_encode($listaEstaciones) . ')';

}

?>