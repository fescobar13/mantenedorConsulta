//Idioma
 var espanol = {
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
};

/* ------------------------ PACIENTE ---------------------- */

function agregarPaciente(){ 

    var nombre = document.getElementById("nombre").value;
    var rut = document.getElementById("rut").value;
    var apellido = document.getElementById("apellido").value;
    var edad = document.getElementById("edad").value;
    var telefono = document.getElementById("telefono").value;
    var email = document.getElementById("email").value;

    $.ajax({
        data:"request=agregar&rut="+rut+"&nombre="+nombre+
        "&apellido="+apellido+"&edad="+edad+"&telefono="+telefono+"&email="+email,

        url: '../Controllers/PacienteController.php',
        type:'post',
        success: function(resp){
            if( resp.indexOf("Se agrego el Paciente") > "-1" ){ // agrego correctamente
                document.getElementById("respuesta").innerHTML = "<p style='background:#00b159;color:white;text-align:center; border-radius:5px;'>"+resp+"</p>";

            }else if( resp.indexOf("Paciente ya existe") > "-1" ){
                document.getElementById("respuesta").innerHTML = "<p style='background:#ff8c00;color:white;text-align:center;border-radius:5px'>"+resp+"</p>";
            }
        },
        error: function(resp){
            alert(resp);
        }
    });
}

function requestModificarPaciente(id){
    window.location="../Controllers/PacienteController.php?request=modificarForm&id="+id;
}

function modificarPaciente(){ 

    var nombre = document.getElementById("nombre").value;
    var rut = document.getElementById("rut").value;
    var apellido = document.getElementById("apellido").value;
    var edad = document.getElementById("edad").value;
    var telefono = document.getElementById("telefono").value;
    var email = document.getElementById("email").value;
    var id = document.getElementById("idHidden").value;

    $.ajax({
        data:"request=modificar&rut="+rut+"&nombre="+nombre+
        "&apellido="+apellido+"&edad="+edad+"&telefono="+telefono+"&email="+email+"&id="+id,

        url: '../Controllers/PacienteController.php',
        type:'post',
        success: function(resp){
            if( resp.indexOf("Paciente Modificado") > "-1" ){ // agrego correctamente
                document.getElementById("respuesta").innerHTML="";
                document.getElementById("respuesta").innerHTML = "<p style='background:#00b159;color:white;text-align:center; border-radius:5px;'>"+resp+"</p>";

            }else if( resp.indexOf("No se encontro paciente") > "-1" ){
                document.getElementById("respuesta").innerHTML="";
                document.getElementById("respuesta").innerHTML = "<p style='background:#ff8c00;color:white;text-align:center;border-radius:5px'>"+resp+"</p>";
            }
        },
        error: function(resp){
            alert(resp);
        }
    });
}

function limpiaForm(form){
    $(form)[0].reset();
}

function eliminarPaciente(id){

    $.ajax({
        data:"request=eliminar&id="+id,
        url: '../Controllers/PacienteController.php',
        type:'post',
        success: function(resp){
            if( resp.indexOf("Paciente eliminado") > "-1" ){ // elimino correctamente
                window.location='listarPaciente.php';
            }else if( resp.indexOf("No se encontro") > "-1" ){
                alert("No se encontro el paciente");
            }
        },
        error: function(resp){
            alert(resp);
        }
    });
}

function listarPaciente(){ 

    $.ajax({
        data:"request=listar",

        url: '../Controllers/PacienteController.php',
        type:'post',
        success: function(resp){
            document.getElementById("pacienteBody").innerHTML = resp;
            $('#pacienteTable').DataTable({
                language:espanol,
                destroy:true
            }); 
        },
        error: function(resp){
            alert(resp);
        }
    });
}

function validaFormPaciente(tipoPeticion=''){
    var nombre = document.getElementById("nombre").value;
    var rut = document.getElementById("rut").value;
    if( tipoPeticion == '' ){
        if( nombre != '' && rut !=''){
            agregarPaciente();
        }else{
            document.getElementById("respuesta").innerHTML = 
            "<p style='background:#ff8c00;color:white;text-align:center;border-radius:5px'>Debe llenar al menos rut y nombre</p>";
        } 
    }else if(tipoPeticion == 'modificar'){
        if( nombre != '' && rut !=''){
            modificarPaciente();
        }else{
            document.getElementById("respuesta").innerHTML = 
            "<p style='background:#ff8c00;color:white;text-align:center;border-radius:5px'>Debe llenar al menos rut y nombre</p>";
        } 
    }
}


/* ------------------------ TERAPEUTA ---------------------- */

function getEspecialidades(){
     $.ajax({
        data:"request=getEspecialidades",

        url: '../Controllers/TerapeutaController.php',
        type:'post',
        success: function(resp){
            document.getElementById("cmbEspecialidades").innerHTML=resp;
            $("#cmbEspecialidades").chosen({
                no_results_text:"Nada se encontró!"
                // max_selected_options:4
            });
        },
        error: function(resp){
            alert(resp);
        }
    });
}

function validaFormTerapeuta(tipoPeticion=''){
    var nombre = document.getElementById("nombre").value;
    var rut = document.getElementById("rut").value;
    var especialidad = $("#cmbEspecialidades").val();
    
    if( tipoPeticion == '' ){
        if( nombre != '' && rut !='' && especialidad != ''){
            agregarTerapeuta();
        }else{
            document.getElementById("respuesta").innerHTML ="";
            document.getElementById("respuesta").innerHTML = 
            "<p style='background:#ff8c00;color:white;text-align:center;border-radius:5px'>Debe llenar al menos rut, nombre y especialidad </p>";
        } 
    }else if(tipoPeticion == 'modificar'){
        if( nombre != '' && rut !='' && especialidad != ''){
            modificarTerapeuta();
        }else{
            document.getElementById("respuesta").innerHTML = 
            "<p style='background:#ff8c00;color:white;text-align:center;border-radius:5px'>Debe llenar al menos rut, nombre y especialidad</p>";
        } 
    }
}

function requestModificarTerapeuta(id){
    window.location="../Controllers/TerapeutaController.php?request=modificarForm&id="+id;
}

function agregarTerapeuta(){ 

    var nombre = document.getElementById("nombre").value;
    var rut = document.getElementById("rut").value;
    var apellido = document.getElementById("apellido").value;
    var edad = document.getElementById("edad").value;
    var telefono = document.getElementById("telefono").value;
    var email = document.getElementById("email").value;
    var especialidad = $("#cmbEspecialidades").val();

    $.ajax({
        data:"request=agregar&rut="+rut+"&nombre="+nombre+
        "&apellido="+apellido+"&edad="+edad+"&telefono="+
        telefono+"&email="+email+"&especialidad="+especialidad,

        url: '../Controllers/TerapeutaController.php',
        type:'post',
        success: function(resp){
            // alert(resp);
            if( resp.indexOf("Se agrego el Terapeuta") > "-1" ){ // agrego correctamente
                limpiaForm("#formTerapeuta");
                $( "#cmbEspecialidades" ).val("").trigger("chosen:updated");
                document.getElementById("respuesta").innerHTML = "<p style='background:#00b159;color:white;text-align:center; border-radius:5px;'>"+resp+"</p>";

            }else if( resp.indexOf("Terapeuta ya existe") > "-1" ){
                document.getElementById("respuesta").innerHTML = "<p style='background:#ff8c00;color:white;text-align:center;border-radius:5px'>"+resp+"</p>";
            }
        },
        error: function(resp){
            alert(resp);
        }
    });
}

function eliminarTerapeuta(id){

    $.ajax({
        data:"request=eliminar&id="+id,
        url: '../Controllers/TerapeutaController.php',
        type:'post',
        success: function(resp){
            // alert(resp);
            if( resp.indexOf("Terapeuta eliminado") > "-1" ){ // elimino correctamente
                window.location='listarTerapeuta.php';
            }else if( resp.indexOf("No se encontro") > "-1" ){
                alert("No se encontro el terapeuta");
            }
        },
        error: function(resp){
            alert(resp);
        }
    });
}


function listarTerapeuta(){ 

    $.ajax({
        data:"request=listar",

        url: '../Controllers/TerapeutaController.php',
        type:'post',
        success: function(resp){
            // alert(resp);
            document.getElementById("terapeutaBody").innerHTML = resp;
            $('#terapeutaTable').DataTable({
                language:espanol,
                destroy:true
            }); 
        },
        error: function(resp){
            alert(resp);
        }
    });
}

function modificarTerapeuta(){ 

    var nombre = document.getElementById("nombre").value;
    var rut = document.getElementById("rut").value;
    var apellido = document.getElementById("apellido").value;
    var edad = document.getElementById("edad").value;
    var telefono = document.getElementById("telefono").value;
    var email = document.getElementById("email").value;
    var id = document.getElementById("idHidden").value;
    var especialidad = $("#cmbEspecialidades").val();

    $.ajax({
        data:"request=modificar&rut="+rut+"&nombre="+nombre+
        "&apellido="+apellido+"&edad="+edad+"&telefono="+telefono+"&email="+email+
        "&id="+id+"&especialidad="+especialidad,

        url: '../Controllers/TerapeutaController.php',
        type:'post',
        success: function(resp){
            if( resp.indexOf("Terapeuta Modificado") > "-1" ){ // agrego correctamente
                document.getElementById("respuesta").innerHTML="";
                document.getElementById("respuesta").innerHTML = "<p style='background:#00b159;color:white;text-align:center; border-radius:5px;'>"+resp+"</p>";

            }else if( resp.indexOf("No se encontro paciente") > "-1" ){
                document.getElementById("respuesta").innerHTML="";
                document.getElementById("respuesta").innerHTML = "<p style='background:#ff8c00;color:white;text-align:center;border-radius:5px'>"+resp+"</p>";
            }
        },
        error: function(resp){
            alert(resp);
        }
    });
}

/*  ------------- Consultas -------------- */

function getDatosDisponibles(){
     $.ajax({
        data:"request=getDatosDisponibles",

        url: '../Controllers/ConsultaController.php',
        type:'post',
        success: function(resp){
            alert(resp);
            document.getElementById("cmbPacientes").innerHTML=resp;
             $("#cmbPacientes").chosen({
                no_results_text:"Nada se encontró!"
                // max_selected_options:4
            });
        },
        error: function(resp){
            alert(resp);
        }
    });
}

// $(document).ready(function(){

//     $("#addEsp").on("click", function(){
//         if( $("#cmbEspecialidades").attr("disabled") == undefined ){
           
//             $("#newEspecialidad").css("display", "inline");
//             $("#cmbEspecialidades").attr("disabled", true);

//         }else if( $("#cmbEspecialidades").attr("disabled") == 'disabled'  ){
//             $("#cmbEspecialidades").attr("disabled", false);
//             $("#newEspecialidad").val("");
//             $("#newEspecialidad").css("display", "none");
//         }
//     });
// });