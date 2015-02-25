function getXMLHTTPRequest(){
    try{
        req= new XMLHttpRequest();
    }
    catch(err1){
        try{
            req= new ActiveXObject("Microsoft.XMLHTTP");
        }catch(err3){
            req= false;
        }
    }
{
    return req;
}
}
var httpGrupos= new getXMLHTTPRequest();









//CODIGOS PARA EDICION DE DATOS X JUGADOR ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
function Busca_Datos_Personas_Autocompletar(id_presona){
    var myurl= '../BuscaDatos/BuscaDatos.php';
    var myrand= parseInt(Math.random()*999999999999999);
    var modurl= myurl+'?rand='+myrand;
                       
    httpGrupos.open("POST", modurl, true);
    httpGrupos.onreadystatechange= Busca_Datos_Personas_Autocompletar1;
    httpGrupos.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    httpGrupos.send('&Accion=Buscar_Datos_Personas_Con_Id'+'&id_presona='+id_presona);
}
function Busca_Datos_Personas_Autocompletar1(){    
    if(httpGrupos.readyState == 4){
        if(httpGrupos.status == 200){
            var info=httpGrupos.responseText;            
            var colInfo=info.split("|");            
            document.getElementById('oculto_id_persona').value = colInfo[0];
            document.getElementById('txt_cedula').value = colInfo[1];
            document.getElementById('txt_nombres').value = colInfo[2];
            document.getElementById('txt_apellidos').value = colInfo[3];
            
            document.getElementById('div_contenidos_buscar_personas').innerHTML = ''; 
            Buscar_Personas('Id_personas', colInfo[0], '', '', '');
        }
    }
}
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://










//BUSQUEDA DE DATOS TODAS LAS PERSONAS :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
function Buscar_Personas(accion, parametro, fecha_desde, fecha_hasta, tipo_fecha){
    document.getElementById('div_contenidos_buscar_personas').innerHTML=''; 
    var myurl= 'ServidorEdicion.php';
    var myrand= parseInt(Math.random()*999999999999999);
    var modurl= myurl+'?rand='+myrand;
                       
    httpGrupos.open("POST", modurl, true);
    httpGrupos.onreadystatechange= Buscar_Personas_1;
    httpGrupos.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    httpGrupos.send('&accion='+accion+'&id='+parametro+'&fecha_desde='+fecha_desde+'&fecha_hasta='+fecha_hasta+'&tipo_fecha='+tipo_fecha);
}
function Buscar_Personas_1(){
    if(httpGrupos.readyState == 4){
        if(httpGrupos.status == 200){
            var info=httpGrupos.responseText;
            document.getElementById('div_contenidos_buscar_personas').innerHTML=info;      
        }
    }
}
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::







//FUNCIONES PARA VENTANA MODAL:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
function show(url){
    document.getElementById('sombra').className='sombraLoad';
    document.getElementById('window').className='windowLoad';
    document.getElementById("mi_marco").src=url;
}
function hide(){
    document.getElementById('sombra').className='sombraUnload';
    document.getElementById('window').className='windowUnload';
    document.getElementById("mi_marco").src=""; 
    
    var accion = document.getElementById('oculto_accion').value;
    var parametro = document.getElementById('oculto_parametro').value;
    var fecha_desde = document.getElementById('oculto_fecha_desde').value;
    var fecha_hasta = document.getElementById('oculto_fecha_hasta').value;
    var tipo_fecha = document.getElementById('oculto_tipo_fechas').value;
//Buscar_Personas(accion, parametro, fecha_desde, fecha_hasta, tipo_fecha)
}
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://








//FUNCIONES PARA VENTANA MODAL:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
function showPequenio(url){
    document.getElementById('sombra').className='sombraLoad';
    document.getElementById('window').className='windowLoadPequenio';
    document.getElementById("mi_marco").src=url;
}
function hidePequenio(){
    document.getElementById('sombra').className='sombraUnload';
    document.getElementById('window').className='windowUnload';
    document.getElementById("mi_marco").src="";    
    SeleccionandoCombo('cmb_oficio_profesion', 'Cargar_Oficios_Profesiones');
}
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://









//CODIGOS PARA EDICION DE DATOS X JUGADOR ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
function Selecciona_Genero_Estado_Civil_Profesion(id_profesion, id_genero, id_estado_civil, nombre_conyugue){
    document.getElementById('cmb_oficio_profesion').value = id_profesion;
    document.getElementById('cmb_genero').value = id_genero;
    document.getElementById('cmb_estado_civil').value = id_estado_civil;
    
    if(id_estado_civil == 'C'){
        document.getElementById('txt_nombre_conyugue').value = nombre_conyugue;
        document.getElementById('div_etiqueta_nombre_conyugue').style.visibility = "visible";
        document.getElementById('txt_nombre_conyugue').style.visibility = "visible";    
    }
}
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://







//FUNCION PARA REALIZAR EL INGRESO A LA BDD DATOS DE PERSONAS
///:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
function EdicionPersonas(form)
{
    //Obtengo lod datos del formulario para realizar el ingreso 
    var cedula = document.getElementById('txt_cedula').value;
    var cedula_original = document.getElementById('oculto_cedula_personas').value;
    var nombres = document.getElementById('txt_nombres').value;
    var apellidos = document.getElementById('txt_apellidos').value;
    var id_oficio_profesion = document.getElementById('cmb_oficio_profesion').value;    
    var id_genero = document.getElementById('cmb_genero').value;
    var id_estado_civil = document.getElementById('cmb_estado_civil').value;
    var nombre_conyugue = document.getElementById('txt_nombre_conyugue').value;

    //Valido para que los campos necesario no esten vacios
    var mensaje_error = '';
    
    if(cedula == '')
        mensaje_error = mensaje_error+'Cedula, ';    
    if(nombres == '')
        mensaje_error = mensaje_error+'nombres, ';
    if(apellidos == '')
        mensaje_error = mensaje_error+'apellidos, ';    
    if(id_oficio_profesion == 0)
        mensaje_error = mensaje_error+'oficio/profesion, ';
    if(id_genero == 0)
        mensaje_error = mensaje_error+'genero, ';
    if(id_estado_civil == 0)
        mensaje_error = mensaje_error+'estado civil, ';
    if(id_estado_civil == "C" && nombre_conyugue == "")
        mensaje_error = mensaje_error+' nombre del conyugue';
    
    //VERIFICO SI LA CEDULA ESTA REPETIDA
    if(mensaje_error=='' )
        RevisaCedulasEdicion(form, cedula, cedula_original);           
    else
        alert('los siguientes campos: \n\n['+mensaje_error+'] ******* No deben estar vacios o sin seleccion');    
}
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://







//FUNCION QUE VERIFICA QUE LA CEDULA NO ESTE REPETIDA
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
function RevisaCedulasEdicion(form, cedula, cedula_original){
    $.ajax({
        type: 'get',
        dataType: 'json',
        url: '../BuscaDatos/BuscaDatos.php',
        data: {
            Accion:'RevisarCedulaEdicion', 
            cedula:cedula, 
            cedula_original:cedula_original
        },
        success: function(json)
        {
            RevisaCedulasEdicion1(json, form);
        }                                        
    });
}
function RevisaCedulasEdicion1(json, form){
    if(json[0].existencia == 0)            
        form.submit();        
    else 
        alert('Cedula repetida... \n'+'Pertenece a: ['+json[0].referencia+']\n\nPor favor rectifique.');
}
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://





//inicio Seleccion estado civil::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
function Selecciona_Estado_Civil(id_control, id_etiqueta, id_text){
    var Estado_Civil = document.getElementById(id_control).value;
    if(Estado_Civil=='C'){
        document.getElementById(id_etiqueta).style.visibility = "visible";
        document.getElementById(id_text).style.visibility = "visible";        
    }
    
    else{
        document.getElementById(id_etiqueta).style.visibility = "hidden";
        document.getElementById(id_text).style.visibility = "hidden";        
    }   
}
//fin Autenticacion::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::





function ejemplo(){
    alert('jueladas');
}


function cargarDatosEstaciones(id,nombre,tipo){
    //    alert('llamando al server');
    location.href = "datos.php?esta__id="+id+"&estanomb="+nombre+"&tipo="+tipo;
}