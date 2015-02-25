<html>
    <head>
        <meta charset="utf-8">

        <link rel="stylesheet" type="text/css" href="css/dataTables.jqueryui.css">
        <link rel="stylesheet" type="text/css" href="css/dataTables.tableTools.css">
        <link rel="stylesheet" type="text/css" href="css/datos.css">
        <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
        <script type="text/javascript" charset="utf8" src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" charset="utf8" src="js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" charset="utf8" src="js/dataTables.jqueryui.js"></script>
        <script type="text/javascript" charset="utf8" src="js/dataTables.fixedColumns.js"></script>
        <script type="text/javascript" charset="utf8" src="js/dataTables.tableTools.js"></script>
        <script type="text/javascript" charset="utf8" src="js/highcharts.js"></script>
        <script type="text/javascript" charset="utf8" src="js/exporting.js"></script>
        <script type="text/javascript" charset="utf8" src="js/highcharts-more.js"></script>
        <script type='text/javascript' src='js/jquery.simplemodal.js'></script>


        <!-- Contact Form CSS files -->

        <!-- Add the script to the HEAD of your document -->
        <script >

            var scrl = "ESTACION " + "<?php
$estanomb = $_GET['estanomb'];
echo $estanomb;
?> ";
            function scrlsts() {
                scrl = scrl.substring(1, scrl.length) + scrl.substring(0, 1);
                document.title = scrl;
                setTimeout("scrlsts()", 300);
            }

        </script>

        <script>
            $(document).ready(function() {
                var table = $('#datos').DataTable({
                    scrollX: 600,
                    scrollY: 600,
                    scrollCollapse: false,
                    paging: false


                });
                table.columns.adjust().draw();
                new $.fn.dataTable.FixedColumns(table, {
                    leftColumns: 1
                });
                var tableTools = new $.fn.dataTable.TableTools(table, {
                    "buttons": [
                        "copy",
                        "csv",
                        "xls",
                        "pdf",
                        {"type": "print", "buttonText": "Print me!"}

                    ],
                    "sSwfPath": "./swf/copy_csv_xls_pdf.swf"
                });
                $("#info").append($(tableTools.fnContainer()));

                //                $( tableTools.fnContainer() ).insertBefore('#info');
            });
        </script>
        <script>
            var dialog;
            function combo(thelist, theinput)
            {

                dialog = document.getElementById(thelist.value);
                dialog.style.display = 'block';

            }
            function cerrarModal()
            {
                dialog.style.display = 'none';
            }





        </script>
        <style>

            p {
                font-family:Arial;font-size:14px;font-style:normal;font-weight:bold;text-decoration:blink;text-transform:uppercase;color:000080;background-color:FFFFFF;
            }

            .texto {
                font-family:Arial;
                font-size:12px;
                font-style:normal;
                font-weight:bold;
                position: absolute; 
                color:000080;

            }

        </style>
        <title></title>

    </head>
    <body onLoad="scrlsts()" >

        <header>
            <div align="left" style="width: 100%; height: 100px; position: relative;  background-color:#FFFFFF;">
                <div align="right"  style=" z-index: 10;width: 100%; height: 100%; position: absolute;top: 3;left: -3;  position: absolute; "><img src="images/inamhilogo.gif" /></div>
                <div align="left"  style=" z-index: 10;width: 50%; height: 50%; position: absolute;top: 15;left: 12;  position: absolute;  "><a href="index.php"><img src="images/back.png" width="50" height="50"  /></a><p class="texto">Regresar<p/></div>
                <p class="p1"><b><br/>CONDICIONES ACTUALES DEL TIEMPO</b><br/>
                    ESTACION: <?php
                    $estanomb = $_GET['estanomb'];
                    echo $estanomb;
                    ?>
                </p>
            </div>                
        </header>
        <?php
        require './Clases/ConeccionPostgres.php';
        require './Clases/ConsultaDatos.php';
        require './Functions.php';
        require 'array_column.php';

        $objFunctions = new Functions();
        $esta__id = $_GET['esta__id'];
//        $estanomb = $_GET['estanomb'];
//echo '$esta__id: '.$esta__id;
//$copas[0] = 68;
//$copas[1] = 77;
//$copas[2] = 78;
        date_default_timezone_set('Etc/GMT+5');

        $queryCopas = "SELECT * FROM processed_data.cpesta WHERE esta__id = " . $esta__id . "ORDER BY copa__id";


        $objConeccion = new ConeccionPostgres();
        $objConsultaDatos = new ConsultaDatos($objConeccion->conectar());

        $copas = $objConsultaDatos->EjecutarConsulta($queryCopas);
        $fechaHoraActual = date('Y-m-j H:i:s');
        $fechaInicio = strtotime('-24 hour', strtotime($fechaHoraActual));
        $fechaInicio = date('Y-m-j H:i:s', $fechaInicio);

        $queryDetalleCopas = "SELECT * FROM administrative.vta__unidad_medida_parametros_tiempos_estadisticos WHERE id_copa IN(" . implode(',', $copas['copa__id']) . ") ORDER BY nombre_parametro,nombre_estadistico";
        $datosCopas = $objConsultaDatos->EjecutarConsulta($queryDetalleCopas);
//
//        echo '<pre>';
//        print_r($datosCopas);
//        echo '</pre>';

        $nemonicos = $datosCopas['nemonico_copa'];


        $query = $objFunctions->construirQueryConsulta($esta__id, $fechaInicio, $fechaHoraActual, $datosCopas['id_copa']);
//        echo $query;
        $datosaux = $objConsultaDatos->EjecutarConsulta($query);
//        echo '<pre>';
//        print_r($nemonicos);
//        echo '</pre>';
//


        $numeroColumnas = count($datosCopas['nombre_parametro']) + 1;


        echo '<div id="info"></div>';

        echo '<table id=datos class="display"   >';

        echo '<thead>';
        echo '<tr><th>GRAFICAR:
            <select name="thelist" onChange="combo(this, \'theinput\')" >
            <option>Seleccione una</option>
            <option>Temperatura</option>
            <option>Precipitacion</option>
            <option>Humedad</option>
            <option>Viento</option>
            <option>Nivel</option>
             </select></th></tr>';

        for ($i = 0; $i < count($datosCopas['nombre_parametro']); $i++) {
            if ($i == 0) {
                echo '<th  >FECHA HORA</th>';
            }
            echo '<th >' . $datosCopas['nombre_parametro'][$i] . '<br/>(' . $datosCopas['simbolo_unidad_medida'][$i] . ')<br/>' . $datosCopas['simbolo_estadistico'][$i] . '</th>';
        }
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        for ($i = 0; $i < ($datosaux['num_rows']); $i++) {
            echo '<tr>';
            for ($j = 0; $j < (count($datosaux) - 2); $j++) {
                if ($j == 0)
                    echo '<td align="center">' . $datosaux['data1hfetd'][$i] . '</td>';
                else
                    echo '<td  align="center">  ' . $datosaux['p' . ($j)][$i] . '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';


        $i = 0;
        foreach ($datosaux as $k => $v) {

            if ($i > 0) {
                unset($datosaux[$k]);
                $new_key = $nemonicos[$i - 1];
                $datosaux[$new_key] = $v;
            }
            $i++;
        }

//        echo '<pre>';
//        print_r($datosaux);
//        echo '</pre>';
        $parametro1 = "[0]";
        $parametro2 = "[0]";
        $parametro3 = "[0]";
        $temperatura1 = "[0]";
        $temperatura2 = "[0]";
        $vientoDireccion = "[0]";
        $vientoVelocidad = "[0]";
        $precipitacion = "[0]";
        $precipitacionAcum = "[0]";
        $nivelAgua = "[0]";

        if (count($datosaux['91161h']) > 0) {
            $parametro1Aux = array_reverse($datosaux['91161h']);
            $parametro1 = json_encode($parametro1Aux);
        }
        if (count($datosaux['9111h']) > 0) {
            $parametro2Aux = array_reverse($datosaux['9111h']);
            $parametro2 = json_encode($parametro2Aux);
        }
        if (count($datosaux['9121h']) > 0) {
            $parametro3Aux = array_reverse($datosaux['9121h']);
            $parametro3 = json_encode($parametro3Aux);
        }
        if (count($datosaux['293161h']) > 0) {
            $temperatura1Aux = array_reverse($datosaux['293161h']);
            $temperatura1 = json_encode($temperatura1Aux);
        }
        if (count($datosaux['29311h']) > 0) {
            $temperatura2Aux = array_reverse($datosaux['29311h']);
            $temperatura2 = json_encode($temperatura2Aux);
        }
        if (count($datosaux['29321h']) > 0) {
            $temperatura3Aux = array_reverse($datosaux['29321h']);
            $temperatura3 = json_encode($temperatura3Aux);
        }
        if (count($datosaux['42161h']) > 0) {
//        $vientoDireccionAux = array_reverse($datosaux['p10']);
            $vientoDireccion = json_encode($datosaux['42161h']);
        }
        if (count($datosaux['3711161h']) > 0) {
//        $vientoVelocidadAux = array_reverse($datosaux['p11']);
            $vientoVelocidad = json_encode($datosaux['3711161h']);
        }
        if (count($datosaux['171481h']) > 0) {
            $precipitacionAux = array_reverse($datosaux['171481h']);
            $precipitacion = json_encode($precipitacionAux);
        }
        if (count($datosaux['1111481h']) > 0) {
            $precipitacionAcumAux = array_reverse($datosaux['1111481h']);
            $precipitacionAcum = json_encode($precipitacionAcumAux);
        }

        if (count($datosaux['1410161h']) > 0) {
            $nivelAguaAux = array_reverse($datosaux['1410161h']);
            $nivelAgua = json_encode($nivelAguaAux);
        }

        if (count($datosaux['data1hfetd']) > 0) {
            $fechasAux = array_reverse($datosaux['data1hfetd']);
            $fechas = json_encode($fechasAux);
            $fechasViento = json_encode($datosaux['data1hfetd']);
        }



        $i = 0;
        foreach ($datosCopas['nombre_parametro'] as $k => $v) {
            unset($datosCopas['nombre_parametro'][$k]);
            $new_key = $nemonicos[$i];
            $datosCopas['nombre_parametro'][$new_key] = $v;
            $i++;
        }

        $i = 0;
        foreach ($datosCopas['simbolo_unidad_medida'] as $k => $v) {
            unset($datosCopas['simbolo_unidad_medida'][$k]);
            $new_key = $nemonicos[$i];
            $datosCopas['simbolo_unidad_medida'][$new_key] = $v;
            $i++;
        }

        $i = 0;
        foreach ($datosCopas['simbolo_estadistico'] as $k => $v) {
            unset($datosCopas['simbolo_estadistico'][$k]);
            $new_key = $nemonicos[$i];
            $datosCopas['simbolo_estadistico'][$new_key] = $v;
            $i++;
        }
        ?>

        <script >
            $(function() {
                $('#humedad1').highcharts({
                    title: {
                        text: '<?php echo $datosCopas['nombre_parametro']['91161h'] ?>',
                        x: -20 //center
                    },
                    xAxis: {
                        type: 'datetime',
                        labels: {
                            step: 2 // displays every second category
                        },
                        categories: <?php echo $fechas ?>,
                        dateTimeLabelFormats: {// don't display the dummy year
                            month: '%e. %b',
                            year: '%b'
                        }

                    },
                    yAxis: {
                        title: {
                            text: '<?php echo $datosCopas['nombre_parametro']['91161h'] . '(' . $datosCopas['simbolo_unidad_medida']['91161h'] . ')' ?>'
                        },
                        plotLines: [{
                                value: 0,
                                width: 1,
                                color: '#808080'
                            }]
                    },
                    tooltip: {
                        valueSuffix: '<?php echo $datosCopas['simbolo_unidad_medida']['91161h'] ?>'
                    },
                    scrollbar: {
                        enabled: true
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle',
                        borderWidth: 0
                    },
                    series: [{
                            name: '<?php echo $datosCopas['nombre_parametro']['91161h'] . '(' . $datosCopas['simbolo_estadistico']['91161h'] . ')' ?>',
                            data: <?php echo str_replace("\"", "", $parametro1); ?>
                        },
                        {
                            name: '<?php echo $datosCopas['nombre_parametro']['9111h'] . '(' . $datosCopas['simbolo_estadistico']['9111h'] . ')' ?>',
                            data: <?php echo str_replace("\"", "", $parametro2); ?>
                        },
                        {
                            name: '<?php echo $datosCopas['nombre_parametro']['9121h'] . '(' . $datosCopas['simbolo_estadistico']['9121h'] . ')' ?>',
                            data: <?php echo str_replace("\"", "", $parametro3); ?>
                        }
                    ]
                });
            });
        </script>
        <script >
            $(function() {
                $('#temperatura1').highcharts({
                    title: {
                        text: '<?php echo $datosCopas['nombre_parametro']['293161h'] ?>',
                        x: -20 //center
                    },
                    xAxis: {
                        type: 'datetime',
                        labels: {
                            step: 2 // displays every second category
                        },
                        categories: <?php echo $fechas ?>,
                        dateTimeLabelFormats: {// don't display the dummy year
                            month: '%e. %b',
                            year: '%b'
                        }

                    },
                    yAxis: {
                        title: {
                            text: '<?php echo $datosCopas['nombre_parametro']['293161h'] . '(' . $datosCopas['simbolo_unidad_medida']['293161h'] . ')' ?>'
                        },
                        plotLines: [{
                                value: 0,
                                width: 1,
                                color: '#808080'
                            }]
                    },
                    tooltip: {
                        valueSuffix: '<?php echo $datosCopas['simbolo_unidad_medida']['293161h'] ?>'
                    },
                    scrollbar: {
                        enabled: true
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle',
                        borderWidth: 0
                    },
                    series: [{
                            name: '<?php echo $datosCopas['nombre_parametro']['293161h'] . '(' . $datosCopas['simbolo_estadistico']['293161h'] . ')' ?>',
                            data: <?php echo str_replace("\"", "", $temperatura1); ?>
                        },
                        {
                            name: '<?php echo $datosCopas['nombre_parametro']['29311h'] . '(' . $datosCopas['simbolo_estadistico']['29311h'] . ')' ?>',
                            data: <?php echo str_replace("\"", "", $temperatura2); ?>
                        },
                        {
                            name: '<?php echo $datosCopas['nombre_parametro']['29321h'] . '(' . $datosCopas['simbolo_estadistico']['29321h'] . ')' ?>',
                            data: <?php echo str_replace("\"", "", $temperatura3); ?>

                        }
                    ]
                });
            });
        </script>
        <script >
            $(function() {
                $('#precipitacion1').highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: '<?php echo $datosCopas['nombre_parametro']['171481h'] ?>',
                        x: -20 //center
                    },
                    xAxis: {
                        type: 'datetime',
                        labels: {
                            step: 2 // displays every second category
                        },
                        categories: <?php echo $fechas ?>,
                        dateTimeLabelFormats: {// don't display the dummy year
                            month: '%e. %b',
                            year: '%b'
                        }

                    },
                    yAxis: {
                        title: {
                            text: '<?php echo $datosCopas['nombre_parametro']['171481h'] . '(' . $datosCopas['simbolo_unidad_medida']['171481h'] . ')' ?>'
                        },
                        plotLines: [{
                                value: 0,
                                width: 1,
                                color: '#808080'
                            }]
                    },
                    tooltip: {
                        valueSuffix: '<?php echo $datosCopas['simbolo_unidad_medida']['171481h'] ?>'
                    },
                    scrollbar: {
                        enabled: true
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle',
                        borderWidth: 0
                    },
                    series: [{
                            name: '<?php echo $datosCopas['nombre_parametro']['171481h'] . '(' . $datosCopas['simbolo_estadistico']['171481h'] . ')' ?>',
                            data: <?php echo str_replace("\"", "", $precipitacion); ?>
                        },
                        {
                            name: '<?php echo $datosCopas['nombre_parametro']['1111481h'] . '(' . $datosCopas['simbolo_estadistico']['1111481h'] . ')' ?>',
                            data: <?php echo str_replace("\"", "", $precipitacionAcum); ?>
                        }
                    ]
                });
            });
        </script>
        <script>
            $(function() {
                var categories = ['N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW'];
                inicializarViento();
                $('#windrose').highcharts({
                    series: [{
                            name: '<?php echo $datosCopas['nombre_parametro']['3711161h'] . '(' . $datosCopas['simbolo_estadistico']['3711161h'] . ')' ?>',
                            data: windDataJSON

                        }

                    ],
                    chart: {
                        polar: true,
                        type: 'column'
                    },
                    title: {
                        text: 'ROSA DE LOS VIENTOS'
                    },
                    pane: {
                        size: '85%'
                    },
                    legend: {
                        align: 'right',
                        verticalAlign: 'top',
                        y: 100,
                        layout: 'vertical'
                    },
                    xAxis: {
                        min: 0,
                        max: 360,
                        type: '',
                        tickInterval: 22.5,
                        tickmarkPlacement: 'on',
                        labels: {
                            formatter: function() {

                                return categories[this.value / 22.5] + '°';
                            }
                        }

                    },
                    yAxis: {
                        min: 0,
                        endOnTick: false,
                        showLastLabel: true,
                        title: {
                            text: 'Velocidad (m/s)'
                        },
                        labels: {
                            formatter: function() {
                                return this.value + 'm/s';
                            }
                        },
                        reversedStacks: true
                    },
                    tooltip: {
                        valueSuffix: 'm/s'

                    },
                    plotOptions: {
                        series: {
                            stacking: 'normal',
                            shadow: false,
                            groupPadding: 0,
                            pointPlacement: 'on'
                        }
                    }
                });
            });
        </script>
        <script >
            $(function() {
                $('#nivel').highcharts({
                    title: {
                        text: '<?php echo $datosCopas['nombre_parametro']['1410161h'] ?>',
                        x: -20 //center
                    },
                    xAxis: {
                        type: 'datetime',
                        labels: {
                            step: 2 // displays every second category
                        },
                        categories: <?php echo $fechas ?>,
                        dateTimeLabelFormats: {// don't display the dummy year
                            month: '%e. %b',
                            year: '%b'
                        }

                    },
                    yAxis: {
                        title: {
                            text: '<?php echo $datosCopas['nombre_parametro']['1410161h'] . '(' . $datosCopas['simbolo_unidad_medida']['1410161h'] . ')' ?>'
                        },
                        plotLines: [{
                                value: 0,
                                width: 1,
                                color: '#808080'
                            }]
                    },
                    tooltip: {
                        valueSuffix: '<?php echo $datosCopas['simbolo_unidad_medida']['1410161h'] ?>'
                    },
                    scrollbar: {
                        enabled: true
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle',
                        borderWidth: 0
                    },
                    series: [{
                            name: '<?php echo $datosCopas['nombre_parametro']['1410161h'] . '(' . $datosCopas['simbolo_estadistico']['1410161h'] . ')' ?>',
                            data: <?php echo str_replace("\"", "", $nivelAgua); ?>
                        }
                    ]
                });
            });
        </script>
        <script language="javascript">
            // code to create a data set that looks like data: [[5, 2], [6, 3], [8, 2]]
            var windDirection, windSpeed, fechas, windDirectionJSON, windSpeedJSON, windDataJSON, fechasJSON;
            function inicializarViento() {
                windDirection = "<?php echo str_replace("\"", "", $vientoDireccion); ?>";
                windSpeed = "<?php echo str_replace("\"", "", $vientoVelocidad); ?>";
                fechas =<?php echo json_encode($fechasViento); ?>;
                fechasJSON = JSON.parse(fechas);
                windDirectionJSON = JSON.parse(windDirection);
                windSpeedJSON = JSON.parse(windSpeed);
                //            alert(windDirectionJSON);

                windDataJSON = [];
                for (i = 0; i < fechasJSON.length; i++) {

                    var item = {
                        name: fechasJSON[i],
                        x: windDirectionJSON[i],
                        y: windSpeedJSON[i]
                    };
                    //                windDataJSON.push([ windDirectionJSON[i], windSpeedJSON[i] ]);
                    windDataJSON.push(item);
                }

                windDataJSON.sort(function(a, b) {
                    return a[0] - b[0];
                });             // the function returns the product of p1 and p2
            }




        </script>

        <div id="Humedad" class="modal" hidden="true">
            <div  >
                <div id="humedad1" style="width: 1000px; height: 400px; " ></div>
                <button onclick="cerrarModal()">Cerrar</button>
            </div>
        </div>


        <div id="Temperatura" class="modal" hidden="true">
            <div >
                <div id="temperatura1" style="width: 1000px; height: 400px; " ></div>
                <button onclick="cerrarModal()">Cerrar</button>
            </div>
        </div>


        <div id="Precipitacion" class="modal" hidden="true">
            <div >
                <div id="precipitacion1" style="width: 1000px; height: 400px; "></div>
                <button onclick="cerrarModal()">Cerrar</button>
            </div >
        </div>

        <div id="Viento" class="modal" hidden="true">
            <div >
                <div id="windrose" style="width: 1000px; height: 600px; "></div>
                <button onclick="cerrarModal()">Cerrar</button>
            </div >

        </div>

        <div id="Nivel" class="modal" hidden="true">
            <div  >
                <div id="nivel" style="width: 1000px; height: 400px; " ></div>
                <button onclick="cerrarModal()">Cerrar</button>
            </div>
        </div>

    </body>
    <footer>
        <p  align="center"><br/>
            IMPORTANTE: Los datos presentados pueden variar con la realidad. <br/>  </p>

    </footer>     
</html>