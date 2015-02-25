<?php

class Functions {

    function construirQueryConsulta($esta, $fechaInicio, $fechaFin, $copas) {

        $resultado = "";
        $join = " FULL OUTER JOIN ";
        $consultaTotal = "";
        $i_1 = 1;
        $j = 1;

        for ($i = 0; $i < count($copas); $i++) {

            if (count($copas) > 1) {

                $copa__id = $copas[$i];
                $query = "(SELECT data1hfetd,data1hvalo as p" . $j . " from processed_data.data1h where esta__id=" . $esta . " and copa__id=" . $copa__id . " and data1hfetd >='" . $fechaInicio . "' and data1hfetd<='" . $fechaFin . "' order by data1hfetd) d" . $i_1 . "";
                if ($i_1 == 1) {
                    $resultado = $resultado . $query . $join;
                    $i_1 = 2;
                } else {
                    $i_1 = 1;
                    if (isset($copas[$i + 1])) {
                        $resultado = $resultado . $query . " ON d1.data1hfetd=d2.data1hfetd) d" . $i_1 . "";
                        $seleccion = "(SELECT COALESCE(d1.data1hfetd,d2.data1hfetd) AS data1hfetd,";
                        $parametros = "";
                        for ($a = 1; $a <= $j; $a++) {
                            if ($a == $j) {
                                $parametros = $parametros . "d2.p" . $a . " ";
                            } else {
                                $parametros = $parametros . "d1.p" . $a . ",";
                            }
                        }
                        $seleccion = $seleccion . $parametros . " FROM ";
                        $consultaTotal = $seleccion . $resultado;
                        $resultado = $consultaTotal . $join;
                        $i_1 = 2;
                    } else {
                        $resultado = $resultado . $query . " ON d1.data1hfetd=d2.data1hfetd ORDER BY data1hfetd DESC";
                        $seleccion = "SELECT COALESCE(d1.data1hfetd,d2.data1hfetd) AS data1hfetd,";
                        $parametros = "";
                        for ($a = 1; $a <= $j; $a++) {
                            if ($a == $j) {
                                $parametros = $parametros . "d2.p" . $a . " ";
                            } else {
                                $parametros = $parametros . "d1.p" . $a . ",";
                            }
                        }
                        $seleccion = $seleccion . $parametros . " FROM ";
                        $consultaTotal = $seleccion . $resultado;
                    }
                }
                $j++;
            } else {
                $consultaTotal = "SELECT data1hfetd,data1hvalo as p" . $j . " from processed_data.data1h where esta__id=" . $esta . " and copa__id=" . $copas[0] . " and data1hfetd >='" . $fechaInicio . "' and data1hfetd<='" . $fechaFin . "' order by data1hfetd DESC";
            }
        }
        return $consultaTotal;
    }

}

?>