<?php

require './Clases/ConeccionPostgres.php';
require './Clases/ConsultaDatos.php';
//require './Functions.php';
//
//$objFunctions = new Functions();
//$copas[0] = 68;
//$copas[1] = 77;
//$copas[2] = 78;
$query = "select * from administrative.vta__lista_estaciones WHERE capto__id=2 ORDER BY estanomb ";
$objConeccion = new ConeccionPostgres();
$objConsultaDatos = new ConsultaDatos($objConeccion->conectar());
$datosaux = $objConsultaDatos->EjecutarConsulta($query);


//echo '<pre>';
//print_r($datosaux);
//echo '</pre>';
//echo 'numero de columnas: '.count( $datosaux);


echo
'<html>'
 . '<head>'
 . '<title>ESTACIONES AUTOMATICAS'
 . '</title>'
 . '<meta charset="utf-8">'
 . '<link rel="stylesheet" type="text/css" href="css/estilos1.css">'
 . '<script type="text/javascript" src="./js/CargaDatos.js" charset="utf-8"></script>'
 . '<style>
body {
    background-image: url("images/blue-sky.jpg");
    -moz-background-size: cover;
-webkit-background-size: cover;
background-size: cover;
background-position: top center !important;
background-repeat: no-repeat !important;
background-attachment: fixed;
}
p {
   font-family:Arial;font-size:14px;font-style:normal;font-weight:bold;text-decoration:blink;text-transform:uppercase;color:000080;background-color:FFFFFF;
   }
   .p1 {
   font-family:Arial;
   font-size:25px;
   font-style:italic;
   font-weight:bold;
   text-decoration:blink;
   text-transform:uppercase;
   color:000080;
   background-color:FFFFFF;
   height: 100%;
   
  }
</style>'
 . '</head>'
 . '<body>'
 . '<header>
                <div align="left" style="width: 100%; height: 100px; position: relative; background-image: url(./images/header.jpg); ">
                    <div align="right"  style=" z-index: 10;width: 100%; height: 100%; position: absolute;top: 3;left: -3;  position: absolute; "><img src="images/inamhilogo.gif" /></div>
                    <p class="p1" align="center"><b><br/>DATOS DE ESTACIONES AUTOMATICAS<br/>
                    (ULTIMAS 24 HORAS)                  
</b></p>
                </div>                
            </header>'
;

echo '<table class="CSSTableGenerator align="center" width="70%" border="1">';
echo '<tr>'
 . '<td width="10%" align="center">Codigo</td>'
 . '<td width="10%" align="center">Nombre</td>'
 . '<td width="10%" align="center">Latitud</td>'
 . '<td width="10%" align="center">Longitud</td>'
 . '<td width="10%" align="center">Altura(m)</td>'
 . '<td width="10%" align="center">Provincia</td>'
 . '<td width="10%" align="center">Canton</td>'
 . '<td width="10%" align="center">Parroquia</td>'
 . '</tr>';

for ($i = 0; $i < $datosaux['num_rows']; $i++) {
    echo '<tr>';
    echo '<td width="10%" align="center">' . $datosaux['estacodi'][$i] . '</td>';
    echo '<td width="10%" align="center"><a href="javascript:cargarDatosEstaciones(' . $datosaux['esta__id'][$i] . ',\'' . $datosaux['estanomb'][$i] . '\')">' . $datosaux['estanomb'][$i] . '</a></td>';
    echo '<td width="10%" align="center">' . $datosaux['coorlati'][$i] . '</td>';
    echo '<td width="10%" align="center">' . $datosaux['coorlong'][$i] . '</td>';
    echo '<td width="10%" align="center">' . $datosaux['cooraltu'][$i] . '</td>';
    echo '<td width="10%" align="center">' . $datosaux['provnomb'][$i] . '</td>';
    echo '<td width="10%" align="center">' . $datosaux['cantnomb'][$i] . '</td>';
    echo '<td width="10%" align="center">' . $datosaux['parrnomb'][$i] . '</td>';
    echo '</tr>';
}

echo '</table>';








echo '</body>
 <footer>
  <div align="left" style="width: 100%; height: 15%; position: relative;  ">
                    <p  align="center"><b><br/>DEPARTAMENTO DE DESARROLLO DE SISTEMAS DE INFORMACION</b><br/>
  <b>TLF: (+593)(02)3971100 EXT: 2039 </b><br/>
  <b>INSTITUTO NACIONAL DE METEOROLOGIA E HIDROLOGIA (INAMHI)<br/> </b>
  <b>QUITO-ECUADOR </b><br/><br/></p>
                </div>   
  
</footer>     
</html>';




//echo 'jhdsjfgb';
//echo $objFunctions->construirQueryConsulta(1, '2014-12-15 00:00:00', '2014-12-15 23:59:59' , $copas);
?>