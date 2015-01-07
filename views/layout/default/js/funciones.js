// JavaScript Document
function procesoDetalleProg(classFrm)
{
	initLoad();

	$("#divPopupPRG").html("");
	var formData= new FormData($("."+classFrm)[0]);
	//hacemos la peticion ajax  
	$.ajax({
		url: BASE_URL_JS + 'system/detalleProg',  
		type: 'POST',
		//Form data
		//datos del formulario
		data: formData,
		//necesario para subir archivos via ajax
		cache: false,
		contentType: false,
		processData: false,
		//mientras enviamos el archivo
		beforeSend: function(){},
		//una vez finalizado correctamente
		success: function(data)
		{
                    $("#divPopupPRG").html(data);
                    endLoad();
		},
		
		//si ha ocurrido un error
		error: function()
		{
                    $("#divPopupPRG").html("Ha ocurrido un error");
		}
	});
}


function procesoEnviaForm(classFrm, php, btn, div)
{
    $("#"+btn).attr('disabled', 'disabled');

    initLoad();

    var formData= new FormData($("."+classFrm)[0]);
    
    //hacemos la peticion ajax  
    $.ajax({
        url: php,  
        type: 'POST',
        //Form data
        //datos del formulario
        data: formData,
        //necesario para subir archivos via ajax
        cache: false,
        contentType: false,
        processData: false,
        //mientras enviamos el archivo
        beforeSend: function(){},
        //una vez finalizado correctamente
        success: function(data)
        {
            endLoad();
            if(data==='OK')
            {
                $("#"+div).delay(1500).queue(function(n)
                {
                    $("#"+div).html('<div class="alert alert-dismissable alert-success"><strong>Terminado</strong><br/><img src="' + RUTA_IMG_JS + 'ok.png" width="32" border="0" /> Proceso realizado con &eacute;xito.</div>');
                    n();
                });
            }
            else
            { 	
                $('#mensajeWar').html(data);
                $('#divAlertWar').delay( 1000 ).fadeIn( 500 );
                $('#divAlertWar').animate({
                        'display': 'block'
                });

                $('#divAlertWar').delay( 5000 ).fadeOut( 500 );
                $('#divAlertWar').animate({
                                            'display': 'none'
                                        });

                $("#"+btn).delay(2000).queue(function(m)
                {
                    $("#"+btn).removeAttr("disabled");
                    m();
                });	
            }
        },

        //si ha ocurrido un error
        error: function()
        {
            endLoad();

            $('#mensajeWar').html('Error error');
            $('#divAlertWar').delay( 1000 ).fadeIn( 500 );
            $('#divAlertWar').animate({
                    'display': 'block'
            });

            $('#divAlertWar').delay( 5000 ).fadeOut( 500 );
            $('#divAlertWar').animate({
                                        'display': 'none'
                                    });
        }
    });
}




function procesoReservaPRG(classFrm, php, btn, div)
{
    $("#"+btn).attr('disabled', 'disabled');

    initLoad();

    for(rP=1; rP>=1; rP++)
    {
        //tipoPas= document.getElementById("tipo_bloq_"+i);
        txtPasaporte= document.getElementById("rP_chkPas_"+rP);
        txtRut= document.getElementById("rP_txtRut_"+rP);
        if(txtRut!=null)
        {
            if(txtPasaporte.checked==false)
            {
                if(txtRut.value.replace(/^\s+|\s+$/g,"")=='')
                {
                    alertError(btn, 'Debe ingresar un rut', 2000);
                    txtRut.focus();
                    return false;
                    break;
                }
                else
                {
                    statusRut= Rut(txtRut, txtRut.value);
                    if(statusRut!=true)
                    {
                        alertError(btn, 'El rut es incorrecto', 2000);
                        txtRut.select();
                        return false;
                        break;
                    }
                }
            }
            else
            {
                if(txtRut.value.replace(/^\s+|\s+$/g,"")=='')
                {
                    alertError(btn, 'Debe ingresar un rut', 2000);
                    txtRut.focus();	
                    return false;
                    break;
                }
            }

            if($.trim($("#rP_txtNom_"+rP).val())=='')
            {
                alertError(btn, 'Debe ingresar un nombre', 2000);
                $("#rP_txtNom_"+rP).focus();
                return false;
                break;
            }

            if($.trim($("#rP_txtApe_"+rP).val())=='')
            {
                alertError(btn, 'Debe ingresar un apellido', 2000);
                $("#rP_txtApe_"+rP).focus();
                return false;
                break;
            }


            if($("#rP_cmbTipoPax_"+rP).val()=='C')
            {
                if($.trim($("#rP_FechaNac_"+rP).val())=='')
                {
                    alertError(btn, 'Debe ingresar una fecha de nacimiento para el Child', 2000);
                    $("#rP_FechaNac_"+rP).focus();
                    return false;
                    break;
                }
            }



            txtPasaporteInf= document.getElementById("rP_chkPasInf_"+rP);
            txtRutInf= document.getElementById("rP_txtRutInf_"+rP);
            if(txtRutInf!=null)
            {
                if(txtPasaporteInf.checked==false)
                {
                    if(txtRutInf.value.replace(/^\s+|\s+$/g,"")=='')
                    {
                        alertError(btn, 'Debe ingresar un rut para el infant', 2000);
                        txtRutInf.focus();	
                        return false;
                        break;
                    }
                    else
                    {
                        statusRutInf= Rut(txtRutInf, txtRutInf.value);
                        if(statusRutInf!=true)
                        {
                            alertError(btn, 'El rut del infant es incorrecto', 2000);
                            txtRutInf.select();
                            return false;
                            break;
                        }
                    }
                }
                else
                {
                    if(txtRutInf.value.replace(/^\s+|\s+$/g,"")=='')
                    {
                        alertError(btn, 'Debe ingresar un rut para el infant', 2000);
                        txtRutInf.focus();	
                        return false;
                        break;
                    }
                }


                if($.trim($("#rP_txtNomInf_"+rP).val())=='')
                {
                    alertError(btn, 'Debe ingresar un nombre para el infant', 2000);
                    $("#rP_txtNomInf_"+rP).focus();
                    return false;
                    break;
                }

                if($.trim($("#rP_txtApeInf_"+rP).val())=='')
                {
                    alertError(btn, 'Debe ingresar un apellido para el infant', 2000);
                    $("#rP_txtApeInf_"+rP).focus();
                    return false;
                    break;
                }



                if($.trim($("#rP_FechaNacInf_"+rP).val())=='')
                {
                    alertError(btn, 'Debe ingresar una fecha de nacimiento para el Infant', 2000);
                    $("#rP_FechaNacInf_"+rP).focus();
                    return false;
                    break;
                }
            }


        }
        else
        {
            break;
        }
    }
	
	
	
	
	
	
    /*Proceso Valida rut*/
    var txtRutNew1, txtRutNew2;
    for(x=1; x<rP; x++)
    {
        txtRutNew1= document.getElementById("rP_txtRut_"+x);
        for(y=1; y<rP; y++)
        {
            if(x!=y)
            {
                txtRutNew2= document.getElementById("rP_txtRut_"+y);
                if(txtRutNew1.value==txtRutNew2.value)
                {
                    alertError(btn, 'El rut del pasajero['+x+'] se repite con el del pasajero['+y+'].', 3000);
                    txtRutNew1.select();
                    return false;
                    break;
                }
            }
        }
    }
    /* --- */
	
	
	
	
	
	
	
	
	
    
    
    var formData= new FormData($("."+classFrm)[0]);
    //hacemos la petición ajax  
    $.ajax({
            url: php,  
            type: 'POST',
            //Form data
            //datos del formulario
            data: formData,
            //necesario para subir archivos via ajax
            cache: false,
            contentType: false,
            processData: false,
            //mientras enviamos el archivo
            beforeSend: function(){},
            //una vez finalizado correctamente
            success: function(data)
            {
                var myArrayData= data.split('&');
                if($.trim(myArrayData[0])=='OK')
                {
                    $('#btnCerrar1PRG').delay( 100 ).fadeOut( 100 );
                    $('#btnCerrar1PRG').animate({
                                                'display': 'none'
                                            });
                                            
                    //alert('TODO OK'); return false;
                    
                    $("#"+div).html('<div class="alert alert-dismissable alert-success"><strong>Terminado</strong><br/><img src="' + RUTA_IMG_JS + 'ok.png" width="32" border="0" /> Estamos abriendo la carta confirmaci&oacute;n, espere un momento...</div>');
                    $.post( BASE_URL_JS + "system/cartaConfirmacion", 
                    {
                        /*n_file: myArrayData[1],
                        cod_prog: myArrayData[2],
                        cod_bloq: myArrayData[3]*/
                        CR_n_file: myArrayData[1],
                        CR_cod_prog: myArrayData[2],
                        CR_cod_bloq: myArrayData[3]
                        
                    }, function(dataRS)
                    {
                        $("#"+div).html(dataRS);
                        endLoad();

                        $('#btnAceptarPRG').delay( 2000 ).fadeIn( 100 );
                        $('#btnAceptarPRG').animate({
                                'display': 'block'
                        });
                    });

                }
                else
                { 	
                    alertError(btn, data, 5000);
                }
            },

            //si ha ocurrido un error
            error: function()
            {
                endLoad();

                $('#mensajeWar').html('Error error');
                $('#divAlertWar').delay( 1000 ).fadeIn( 500 );
                $('#divAlertWar').animate({
                        'display': 'block'
                });

                $('#divAlertWar').delay( 5000 ).fadeOut( 500 );
                $('#divAlertWar').animate({
                                            'display': 'none'
                                        });
            }
    });
}






function procesoCargaDiv(valor, div, php)
{
    $("#"+div).html('');
    if(valor!==0)
    {
        $.post(php, 
        {
            _PCD_: valor
        }, function(data)
        {
            $("#"+div).html(data);
        });
    }
}






function procesoConServ(classFrm, php, btn)
{
    $("#"+btn).attr('disabled', 'disabled');

    initLoad();


    //alertError(btn, 'Error al tratar de confirmar ', 2000);
    //return false;

    /*for(rP=1; rP>=1; rP++)
    {
        txtPasaporte= document.getElementById("rP_chkPas_"+rP);
        txtRut= document.getElementById("rP_txtRut_"+rP);
        if(txtRut!=null)
        {
            if($.trim($("#rP_txtNom_"+rP).val())=='')
            {
                alertError(btn, 'Debe ingresar un nombre', 2000);
                $("#rP_txtNom_"+rP).focus();
                return false;
                break;
            }
        }
        else
        {
                break;
        }
    }*/


    var formData= new FormData($("."+classFrm)[0]);
    //hacemos la petición ajax  
    $.ajax({
            url: php,  
            type: 'POST',
            //Form data
            //datos del formulario
            data: formData,
            //necesario para subir archivos via ajax
            cache: false,
            contentType: false,
            processData: false,
            //mientras enviamos el archivo
            beforeSend: function(){},
            //una vez finalizado correctamente
            success: function(data)
            {
                var myArrayData= data.split('&');
                if($.trim(myArrayData[0])=='OK')
                {
                    endLoad();

                    $('#divAlertExito').delay( 1000 ).fadeIn( 500 );
                    $('#divAlertExito').animate({
                            'display': 'block'
                    });

                    $('#divAlertExito').delay( 1000 ).fadeOut( 500 );
                    $('#divAlertExito').animate({
                                                            'display': 'none'
                                                    });
                }
                else
                { 	
                    alertError(btn, $.trim(myArrayData[1]), 5000);
                }
            },

            //si ha ocurrido un error
            error: function()
            {
                    endLoad();

                    $('#mensajeWar').html('Error error');
                    $('#divAlertWar').delay( 1000 ).fadeIn( 500 );
                    $('#divAlertWar').animate({
                            'display': 'block'
                    });

                    $('#divAlertWar').delay( 5000 ).fadeOut( 500 );
                    $('#divAlertWar').animate({
                                                            'display': 'none'
                                                    });
            }
    });
}


function alertError(btn, msg, time)
{
	endLoad();
				
	$('#mensajeWar').html(msg);
	$('#divAlertWar').delay( 1000 ).fadeIn( 500 );
	$('#divAlertWar').animate({
		'display': 'block'
	});
	
	$('#divAlertWar').delay( time ).fadeOut( 500 );
	$('#divAlertWar').animate({
                                    'display': 'none'
                                });
					
	$("#"+btn).delay(2500).queue(function(m)
	{
		$("#"+btn).removeAttr("disabled");
		m();
	});		
}


function initLoad()
{
    $('#divAlertInfo').fadeIn( 500 );
    $('#divAlertInfo').animate({
                            'display': 'block'
                        });
}

function endLoad()
{
    $('#divAlertInfo').delay( 100 ).fadeOut( 500 );
    $('#divAlertInfo').animate({
                                'display': 'none'
                            });
}


function validaReserva(classFrm, php, div, divTit, titulo)
{
	initLoad();

	$("#"+div).html("");
	$("#"+divTit).html(titulo);
	var formData= new FormData($("."+classFrm)[0]);
	//hacemos la peticion ajax  
	$.ajax({
		url: php,  
		type: 'POST',
		//Form data
		//datos del formulario
		data: formData,
		//necesario para subir archivos via ajax
		cache: false,
		contentType: false,
		processData: false,
		//mientras enviamos el archivo
		beforeSend: function(){},
		//una vez finalizado correctamente
		success: function(data)
		{
                    $("#"+div).html(data);
                    endLoad();
		},
		
		//si ha ocurrido un error
		error: function()
		{
                    $("#"+div).html("Ha ocurrido un error");
		}
	});
}









function buscarCiudad(ciudad, frmBus, ob, id)
{

    var span= document.getElementById(ob);
    var length = ciudad.length;

    if(length >= 3)
    {
        $.post("process/procesoObtieneCiudad.php", 
        {
            post_ciudad: ciudad, 
            post_frmBus: frmBus,
            post_span: ob,
            post_idTxt: id
        }, function(data){
            $("#"+ob).html(data);
            $("#"+span).css("display", "block");
        });
    }
    else
    {
        $("#"+span).css("display", "none");
        ciudad= '';
            $.post("process/procesoObtieneCiudad.php", { post_ciudad: ciudad, post_frmBus: frmBus }, function(data){
            $("#"+ob).html(data);
        });
    }
}








function habitaciones(table, num)
{
    for(var x=1;x<=3;x++)
    {
        $("#"+table+'_'+x).css("display", "none");
    }

    for(var x=1;x<=num;x++)
    {
        var id=table+'_'+x;
        mostrado=0;
        if($('#'+id).css('display') === 'block')
        {
            mostrado=1;
            $('#'+id).css('display', 'none');
        }
        if(mostrado!==1)
        {
            $('#'+table+'_'+x).fadeIn( 1000 );
            $('#'+table+'_'+x).animate({
                    'display': 'block'
            });
        }		
    }
}




function habilitaEdadChild(id,hab)
{
    var i, x;
    status_1 = new Array (true, false, false); 
    status_2 = new Array (true, true, false); 

    for(i=0; i<3; i++)
    {
        if(id==i)
        {
            for(x=1; x<4; x++)
            {
                if(hab==x)
                {
                    $("#mL_edadChild_1_"+x).prop('disabled', status_1[i]);
                    $("#mL_edadChild_2_"+x).prop('disabled', status_2[i]);
                }
            }
        }
    }
}

function soloRut(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
    //alert(charCode);
    if ((charCode >= 48 && charCode<= 57) || (charCode == 45) || (charCode == 107) || (charCode == 75)){
            return true;
    }else{ 
            return false;
    }
}


function checkServ(idChk, nConf, fPPago)
{   
    if($("#"+idChk).is(':checked')) {  
        if($.trim($("#"+nConf).val())==="" && $.trim($("#"+fPPago).val())==="")
        {
            $("#"+idChk).prop("checked", "");
        }
    } else {  
        if($.trim($("#"+nConf).val())!=="" || $.trim($("#"+fPPago).val())!=="")
        {
            $("#"+idChk).prop("checked", "checked");
        }
    }
}

function muestraOculta(id, estado)
{
    if(estado===1)
    {
        $('#'+id).delay( 10 ).fadeIn( 500 );
        $('#'+id).animate({
                'display': 'block'
        });
    }
    else
    {
        $('#'+id).delay( 10 ).fadeOut( 500 );
        $('#'+id).animate({
                            'display': 'none'
                        });
    }
}

function abrePopup(div, docPHP, idTitulo, titulo, val)
{
    initLoad();
    $("#" + div).html('');
    $("#" + idTitulo ).html(titulo);
    $.post(docPHP, 
    {
        varCenterBox: val
    }, function(data)
    {
        $("#" + div).html(data);
        endLoad();
    });
}





    /*BEGIN: Busqueda de Bloqueos */
    $('#btnBuscarBloqueos').on('click',function()
    {
        var mL_Error=0;
        $("#btnBuscarBloqueos").attr('disabled', 'disabled');
        if($('#mL_txtCiudadDestino').val() != 0)
        {
            if($('#mL_cmbHab').val() != 0)
            {
                $(document).skylo('start');

                setTimeout(function(){
                        $(document).skylo('set',50);
                },1000);

                setTimeout(function(){
                        $(document).skylo('end');
                },1500);
                setTimeout(function(){
                   document.getElementById('frmBuscarBloqueos').submit();
                },2500);
            }
            else
            {
                mL_Error=1;
                $('#mensajeWar').html('Debe seleccionar la cantidad de habitaciones');
            }
        }
        else
        {
            mL_Error=1;
            $('#mensajeWar').html('Debe seleccionar una ciudad de destino');	
        }




        if( mL_Error==1 )
        {
            $('#divAlertWar').delay( 10 ).fadeIn( 500 );
            $('#divAlertWar').animate({
                    'display': 'block'
            });

            $('#divAlertWar').delay( 2000 ).fadeOut( 500 );
            $('#divAlertWar').animate({
                                        'display': 'none'
                                    });

            $("#btnBuscarBloqueos").delay(2000).queue(function(dis)
            {
                $("#btnBuscarBloqueos").removeAttr("disabled");
                dis();
            });	
        }
		
    });
    /*END: Busqueda de Bloqueos*/
    
    
    
    
    
    /*BEGIN: Busqueda de Programas */
    $('#btnBuscarProgramas').on('click',function()
    {
        var mL_Error=0;
        $("#btnBuscarProgramas").attr('disabled', 'disabled');
        if($('#mL_txtCiudadDestino_PRG').val() != 0)
        {
            if($('#mL_cmbHab_PRG').val() != 0)
            {
                $(document).skylo('start');

                setTimeout(function(){
                        $(document).skylo('set',50);
                },1000);

                setTimeout(function(){
                        $(document).skylo('end');
                },1500);
                setTimeout(function(){
                   document.getElementById('frmBuscarProgramas').submit();
                },2500);
            }
            else
            {
                mL_Error=1;
                $('#mensajeWar').html('Debe seleccionar la cantidad de habitaciones');
            }
        }
        else
        {
            mL_Error=1;
            $('#mensajeWar').html('Debe seleccionar una ciudad de destino');	
        }




        if( mL_Error==1 )
        {
            $('#divAlertWar').delay( 10 ).fadeIn( 500 );
            $('#divAlertWar').animate({
                    'display': 'block'
            });

            $('#divAlertWar').delay( 2000 ).fadeOut( 500 );
            $('#divAlertWar').animate({
                                        'display': 'none'
                                    });

            $("#btnBuscarProgramas").delay(2000).queue(function(dis)
            {
                $("#btnBuscarProgramas").removeAttr("disabled");
                dis();
            });	
        }
		
    });
    /*END: Busqueda de Programas*/
    
    
    
    
    $('#menuConsRes').on('click',function(){
        $(document).skylo('start');

        setTimeout(function(){
            $(document).skylo('set',50);
        },1000);

        setTimeout(function(){
            $(document).skylo('end');
        },1500);
		setTimeout(function(){
            window.location.href = BASE_URL_JS + 'system/consultarReserva';
        },2500);
    });
    
    
    
    $('#menuHoteles').on('click',function(){
        $(document).skylo('start');

        setTimeout(function(){
            $(document).skylo('set',50);
        },1000);

        setTimeout(function(){
            $(document).skylo('end');
        },1500);
		setTimeout(function(){
            window.location.href = BASE_URL_JS + 'system/hoteles';
        },2500);
    });
    
    
    $('#menuAdminProg').on('click',function(){
        $(document).skylo('start');

        setTimeout(function(){
            $(document).skylo('set',50);
        },1000);

        setTimeout(function(){
            $(document).skylo('end');
        },1500);
		setTimeout(function(){
            window.location.href = BASE_URL_JS + 'system/adminProgramas';
        },2500);
    });
    
    
    $('#menuImagenes').on('click',function(){
        $(document).skylo('start');

        setTimeout(function(){
            $(document).skylo('set',50);
        },1000);

        setTimeout(function(){
            $(document).skylo('end');
        },1500);
		setTimeout(function(){
            window.location.href = BASE_URL_JS + 'system/imagenes';
        },2500);
    });
    
    
    $('#menuContacto').on('click',function(){
        $(document).skylo('start');

        setTimeout(function(){
            $(document).skylo('set',50);
        },1000);

        setTimeout(function(){
            $(document).skylo('end');
        },1500);
		setTimeout(function(){
            window.location.href = BASE_URL_JS + 'system/contacto';
        },2500);
    });
    
    
    
    /* ADMIN PROGRAMAS */
    $('#btnAdmProg').on('click',function(){
        
        if($('#AP_cmbCiudadDestino').val()==='0')
        {
            $('#divAlertWarProg').fadeIn( 1500 );
            $('#divAlertWarProg').animate({
                    'display': 'block'
            });
        }
        else
        {
            $(document).skylo('start');

            setTimeout(function(){
                    $(document).skylo('set',50);
            },1000);

            setTimeout(function(){
                    $(document).skylo('end');
            },1500);
            setTimeout(function(){
                    document.getElementById('frmAdmProg').submit();
            },2500);
        }
    });




