<html>
    <head>
        <title>ESTACIONES AUTOMATICAS</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="css/mapa.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="./js/OpenLayers.js"></script>
        <script type="text/javascript" src="./js/CargaDatos.js" charset="utf-8"></script>
        <script>

            $(document).ready(function() {
                $.estaciones = new Array();
                var selectControl;

                $.ajax({
                    url: 'http://186.42.174.236/InamhiEmas/json.php',
                    //                    url: 'http://localhost/InamhiEmas/json.php',
                    type: 'post',
                    dataType: 'jsonp',
                    jsonp: 'callback',
                    error: function(xhr, status, error) {
                        alert("error");
                    },
                    success: function(jsonp) {
                        if (jsonp.length > 0) {
                            for (i = 0; i < jsonp.length; i++) {
                                $.estaciones[i] = jsonp[i];
                            }

                            mapa = new OpenLayers.Map('mapa');
                            osm = new OpenLayers.Layer.OSM("Simple OSM Map");
                            mapa.addLayer(osm);
                            mapa.addControl(new OpenLayers.Control.LayerSwitcher());
                            mapa.addControl(new OpenLayers.Control.MousePosition());


                            var point_style = OpenLayers.Util.extend({}, OpenLayers.Feature.Vector.style['default']);
                            point_style.graphicName = "star";
                            point_style.graphicOpacity = 1;
                            point_style.externalGraphic = "${iconx}";
                            point_style.pointRadius = 30;
                            point_style.rotation = 0;
                            point_style.fillColor = "#ffa500";
                            point_style.fillOpacity = 1;
                            point_style.strokeColor = "#000011";
                            point_style.strokeWidth = 2;
                            point_style.strokeLinecap = "butt";
                            point_style.graphic = true;
                            point_style.label = "${codigo}";
                            point_style.fontColor = "#000099";
                            point_style.fontOpacity = 0.8;
                            point_style.fontFamily = "Verdana, Geneva, sans-serif";
                            point_style.fontSize = "12";
                            point_style.fontWeight = "bold";
                            point_style.labelAlign = "lm";
                            point_style.labelXOffset = 15;
                            point_style.labelYOffset = 1;
                            point_style.graphicWidth = 10;
                            point_style.graphicHeight = 10;
                            point_style.cursor = 'pointer';




                            var style = new OpenLayers.Style(point_style, {
                                rules: [
                                    new OpenLayers.Rule({
                                        maxScaleDenominator: 10000000,
                                        symbolizer: {
                                            pointRadius: 20,
                                            fontSize: "11px",
                                            labelXOffset: 22,
                                            graphicWidth: 20,
                                            graphicHeight: 20
                                        }})
                                ]
                            });

                            var styleMap = new OpenLayers.StyleMap(style);
                            vectorLayerMeteo = new OpenLayers.Layer.Vector("Estaciones Meteorológicas", {styleMap: styleMap});
                            vectorLayerHidro = new OpenLayers.Layer.Vector("Estaciones Hidrológicas", {styleMap: styleMap});


                            //Agregar los puntos

                            for (i = 0; i < $.estaciones.length; i++) {
                                if ($.estaciones[i].catenomb == 'METEOROLOGICA') {
                                    var coord = new OpenLayers.LonLat($.estaciones[i].coorlong, $.estaciones[i].coorlati).transform(
                                            new OpenLayers.Projection("EPSG:4326"),
                                            new OpenLayers.Projection("EPSG:900913")
                                            );
                                    var point = new OpenLayers.Geometry.Point(coord.lon, coord.lat);
                                    var pointFeature = new OpenLayers.Feature.Vector(point, {
                                        estacion: $.estaciones[i].puobnomb,
                                        codigo: $.estaciones[i].puobcodi,
                                        latitud: $.estaciones[i].coorlati,
                                        longitud: $.estaciones[i].coorlong,
                                        altitud: $.estaciones[i].cooraltu,
                                        estado: $.estaciones[i].estenomb,
                                        esta__id: $.estaciones[i].esta__id,
                                        iconx: $.estaciones[i].esteicon});

                                    // Agregar los puntos a la capa vectorial
                                    vectorLayerMeteo.addFeatures([pointFeature]);
                                } else {
                                    var coord = new OpenLayers.LonLat($.estaciones[i].coorlong, $.estaciones[i].coorlati).transform(
                                            new OpenLayers.Projection("EPSG:4326"),
                                            new OpenLayers.Projection("EPSG:900913")
                                            );
                                    var point = new OpenLayers.Geometry.Point(coord.lon, coord.lat);
                                    var pointFeature = new OpenLayers.Feature.Vector(point, {
                                        estacion: $.estaciones[i].puobnomb,
                                        codigo: $.estaciones[i].puobcodi,
                                        latitud: $.estaciones[i].coorlati,
                                        longitud: $.estaciones[i].coorlong,
                                        altitud: $.estaciones[i].cooraltu,
                                        estado: $.estaciones[i].estenomb,
                                        esta__id: $.estaciones[i].esta__id,
                                        iconx: $.estaciones[i].esteicon});

                                    // Agregar los puntos a la capa vectorial
                                    vectorLayerHidro.addFeatures([pointFeature]);

                                }
                            }


                            mapa.addLayer(vectorLayerMeteo);
                            mapa.addLayer(vectorLayerHidro);

                            var arrayTipos = [];
                            vectorLayerMeteo.events.on({
                                'featureselected': onFeatureSelect,
                                'featureunselected': onFeatureUnselect
                            });
                            vectorLayerHidro.events.on({
                                'featureselected': onFeatureSelect,
                                'featureunselected': onFeatureUnselect
                            });
                            arrayTipos.push(vectorLayerMeteo);
                            arrayTipos.push(vectorLayerHidro);


                            selectControl = new OpenLayers.Control.SelectFeature(
                                    arrayTipos,
                                    {
                                        clickout: true, toggle: false,
                                        multiple: false, hover: false,
                                        toggleKey: "ctrlKey", // ctrl key removes from selection
                                        multipleKey: "shiftKey" // shift key adds to selection
                                    }
                            );

                            mapa.addControl(selectControl);
                            selectControl.activate();


                            //                            var selectControl = new OpenLayers.Control.SelectFeature(vectorLayerHidro, {hover: false, autoActivate: true});
                            //
                            //                            mapa.addControl(selectControl);
                            //                            selectControl.activate();
                            //                            vectorLayerHidro.events.on({
                            //                                'featureselected': onFeatureSelect,
                            //                                'featureunselected': onFeatureUnselect
                            //                            });

                            mapa.setCenter(new OpenLayers.LonLat(-78.44, -1.65).transform(
                                    new OpenLayers.Projection("EPSG:4326"),
                                    new OpenLayers.Projection("EPSG:900913")), 7.45);



                        }
                    }
                });
                function onPopupClose(evt) {
                    // 'this' is the popup.
                    var feature = this.feature;
                    if (feature.layer) { // The feature is not destroyed
                        selectControl.unselect(feature);
                    } else { // After "moveend" or "refresh" events on POIs layer all 
                        //     features have been destroyed by the Strategy.BBOX
                        this.destroy();
                    }
                }

                function onFeatureUnselect(evt) {
                    feature = evt.feature;
                    if (feature.popup) {
                        popup.feature = null;
                        mapa.removePopup(feature.popup);
                        feature.popup.destroy();
                        feature.popup = null;
                    }
                }

                function onFeatureSelect(evt) {
                    feature = evt.feature;
                    popup = new OpenLayers.Popup.FramedCloud("featurePopup",
                            feature.geometry.getBounds().getCenterLonLat(),
                            new OpenLayers.Size(100, 100),
                            "<p><h4>" + feature.attributes.estacion + " </h4></p>" +
                            "<p>Latitud: " + feature.attributes.latitud + "</p>" +
                            "<p>Longitud: " + feature.attributes.longitud + "</p>" +
                            "<p>Altura: " + feature.attributes.altitud + " metros</p>" +
                            "<p>Estado: " + feature.attributes.estado + "</p>" +
                            "<a href=\"javascript:cargarDatosEstaciones('" + feature.attributes.esta__id + "','" + feature.attributes.estacion + "')\">VER DATOS</a>",
                            //                    "<input type=\"button\" />",
                            null, true, onPopupClose);
                    feature.popup = popup;
                    popup.feature = feature;
                    mapa.addPopup(popup, true);
                }
            });
        </script>
    </head>
    <body>
        <header>
            <div align="left" style="width: 100%; height: 100px; position: relative; background-color:#FFFFFF;">
                <div align="right"  style=" z-index: 10;width: 100%; height: 100%; position: absolute;top: 3;left: -3;  position: absolute; "><img src="images/inamhilogo.gif" /></div>
                <p class="p1" ><b><br/>DATOS DE ESTACIONES AUTOMATICAS<br/>(ULTIMAS 24 HORAS)</b></p>
            </div>                
        </header>
        <article>
            <div id="mapa" >

            </div>
        </article>

        <div align="left" style="width: 99%; bottom: 0%; position: absolute;">
            <p  align="center"><b><br/>DEPARTAMENTO DE DESARROLLO DE SISTEMAS DE INFORMACION</b><br/>
                <b>TLF: (+593)(02)3971100 EXT: 2039 </b><br/>
                <b>INSTITUTO NACIONAL DE METEOROLOGIA E HIDROLOGIA (INAMHI)<br/> </b>
                <b>QUITO-ECUADOR </b><br/><br/></p>
        </div>   


    </body>
</html>
