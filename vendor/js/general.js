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
                limpiaForm("#formPaciente");
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
    document.getElementById("confirmacion").innerHTML=
    "<p><span class='ui-icon ui-icon-alert' style='float:left; margin:12px 12px 20px 0;'></span>¿Está seguro de eliminar el Paciente?</p>";

    $( "#confirmacion" ).dialog({
      resizable: false,
      height: "auto",
      width: 400,
      modal: true,
      buttons: {
        "Eliminar": function(){
            $.ajax({
            data:"request=eliminar&id="+id,
            url: '../Controllers/PacienteController.php',
            type:'post',
            success: function(resp){
                if( resp.indexOf("Paciente eliminado") > "-1" ){ // elimino correctamente
                    document.getElementById("eliminado").innerHTML="<p>Paciente Eliminado!.</p>";
                    // $( "#eliminado" ).dialog();
                    $( "#eliminado" ).dialog({
                      modal: true,
                      buttons: {
                        Ok: function() {
                          $( this ).dialog( "close" );
                           window.location='listarPaciente.php';
                        }
                      }
                    });
                   
                }else if( resp.indexOf("No se encontro") > "-1" ){
                    alert("No se encontro el paciente");
                }
            },
            error: function(resp){
                alert(resp);
            }
            });
          $( this ).dialog( "close" );
        },
        Cancelar: function() {
          $( this ).dialog( "close" );
        }
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

function listarPacienteSimple(){ 
    $.ajax({
        data:"request=listarSimple",

        url: '../Controllers/PacienteController.php',
        type:'post',
        success: function(resp){
            document.getElementById("cmbPacientes").innerHTML = resp;
            // $('#pacienteTable').DataTable({
            //     language:espanol,
            //     destroy:true
            // }); 
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

function getEspecialidades(tipo=""){
    
    $.ajax({
        data:"request=getEspecialidades",

        url: '../Controllers/TerapeutaController.php',
        type:'post',
        success: function(resp){
            // alert(resp);
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


function getDataConsulta(){
    listarPacienteSimple();
    getEspecialidadeSimple();

}

function getEspecialidadeSimple(){
    $.ajax({
        data:"request=getEspecialidades&tipo=encabezado",

        url: '../Controllers/TerapeutaController.php',
        type:'post',
        success: function(resp){
            // alert(resp);
            document.getElementById("cmbEspecialidades").innerHTML=resp;
            // $("#cmbEspecialidades").chosen({
            //     no_results_text:"Nada se encontró!"
            //     // max_selected_options:4
            // });
        },
        error: function(resp){
            alert(resp);
        }
    });
}

function getTableConsultas(id){
    var idPaciente = document.getElementById("cmbPacientes").value;

    $.ajax({
        data:"request=getTableConsultas&idEspecialidad="+id+"&idPaciente="+idPaciente,
        url: '../Controllers/ConsultaController.php',
        type:'post',
        success: function(resp){
            // alert(resp);
            document.getElementById("TableConsultas").style.display = "inline";
            document.getElementById("TableConsultasBody").innerHTML=resp;
            $('#TableConsultas').DataTable({
                "drawCallback": function() {
                    // document.getElementById("TableConsultasBody").innerHTML='';
                    document.getElementById("TableConsultasBody").innerHTML=resp;
                    // alert( 'DataTables has redrawn the table' );
                },
                language:espanol,
                paging:true,
                dom:'flrtip',
                destroy:true,
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
    document.getElementById("confirmacion").innerHTML=
    "<p><span class='ui-icon ui-icon-alert' style='float:left; margin:12px 12px 20px 0;'></span>¿Está seguro de eliminar el Terapeuta?</p>";

    $("#confirmacion").dialog({
      resizable: false,
      height: "auto",
      width: 400,
      modal: true,
      buttons: {
        "Eliminar": function(){
            $.ajax({
            data:"request=eliminar&id="+id,
            url: '../Controllers/TerapeutaController.php',
            type:'post',
            success: function(resp){
                if( resp.indexOf("Terapeuta eliminado") > "-1" ){ // elimino correctamente
                    document.getElementById("eliminado").innerHTML="<p>Terapeuta Eliminado!.</p>";
                    // $( "#eliminado" ).dialog();
                    $( "#eliminado" ).dialog({
                      modal: true,
                      buttons: {
                        Ok: function() {
                          $( this ).dialog( "close" );
                           window.location='listarTerapeuta.php';
                        }
                      }
                    });
                   
                }else if( resp.indexOf("No se encontro") > "-1" ){
                    alert("No se encontro el terapeuta");
                }
            },
            error: function(resp){
                alert(resp);
            }
            });
          $( this ).dialog( "close" );
        },
        Cancelar: function() {
          $( this ).dialog( "close" );
        }
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

function reservar(dataHora){
    // alert(dataHora.value);
    var dataConsulta = dataHora.value;
    var valEsp = document.getElementById("cmbEspecialidades").value; 
    $.ajax({
        data:"request=reservar&consulta="+dataConsulta,

        url: '../Controllers/ConsultaController.php',
        type:'post',
        success: function(resp){
            // alert(resp);
            if(resp.trim()=='agrego'){
                alert( "Su consulta fue agregada exitosamente!" );
                getTableConsultas(valEsp);
            }else if( resp.trim()=='existe' ){
                alert( "Ya existe una consulta tomada en ese horario!" );
            }
            // document.getElementById("cmbPacientes").innerHTML=resp;
            //  $("#cmbPacientes").chosen({
            //     no_results_text:"Nada se encontró!"
            //     // max_selected_options:4
            // });
        },
        error: function(resp){
            alert(resp);
        }
    });
}

function listarConsulta(){ 
    $.ajax({
        data:"request=listar",
        url: '../Controllers/ConsultaController.php',
        type:'post',
        success: function(resp){
            // alert(resp);
            document.getElementById("consultaBody").innerHTML = resp;
            $('#consultaTable').DataTable({
                language:espanol,
                destroy:true
            }); 
        },
        error: function(resp){
            alert(resp);
        }
    });
}

function eliminarConsulta(id){
    document.getElementById("confirmacion").innerHTML=
    "<p><span class='ui-icon ui-icon-alert' style='float:left; margin:12px 12px 20px 0;'></span>¿Está seguro de eliminar la Consulta?</p>";

    $("#confirmacion").dialog({
      resizable: false,
      height: "auto",
      width: 400,
      modal: true,
      buttons: {
        "Eliminar": function(){
            $.ajax({
            data:"request=eliminar&id="+id,
            url: '../Controllers/ConsultaController.php',
            type:'post',
            success: function(resp){
                if( resp.indexOf("Consulta eliminada") > "-1" ){ // elimino correctamente
                    document.getElementById("eliminado").innerHTML="<p>Consulta Eliminada!.</p>";
                    // $( "#eliminado" ).dialog();
                    $( "#eliminado" ).dialog({
                      modal: true,
                      buttons: {
                        Ok: function() {
                          $( this ).dialog( "close" );
                           window.location='listarConsulta.php';
                        }
                      }
                    });
                   
                }else if( resp.indexOf("No se encontro") > "-1" ){
                    alert("No se encontro la consulta");
                }
            },
            error: function(resp){
                alert(resp);
            }
            });
          $( this ).dialog( "close" );
        },
        Cancelar: function() {
          $( this ).dialog( "close" );
        }
      }
    });

    // $.ajax({
    //     data:"request=eliminar&id="+id,
    //     url: '../Controllers/ConsultaController.php',
    //     type:'post',
    //     success: function(resp){
    //         if( resp.indexOf("Consulta eliminada") > "-1" ){ // elimino correctamente
    //             window.location='listarConsulta.php';
    //         }else if( resp.indexOf("No se encontro") > "-1" ){
    //             alert("No se encontro la consulta");
    //         }
    //     },
    //     error: function(resp){
    //         alert(resp);
    //     }
    // });
}

