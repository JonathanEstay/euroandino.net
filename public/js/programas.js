/* 
 * Proyecto : Euroandino.net
 * Autor    : Tsyacom Ltda.
 * Fecha    : Miercoles, 10 de octubre de 2014
 */

//Clase programa
function Programa() {
    this.P_nombre = ''; 
}

Programa.prototype.getNombre = function() {
  return this.P_nombre;
};
Programa.prototype.setNombre = function(nombre) {
  this.P_nombre = nombre;
};


Programa.prototype.validaPasaporte = function(id, rut, passport) {
    if($("#"+id).is(':checked')) {
        $('#' + rut).delay( 20 ).fadeOut( 50 );
        $('#' + rut).animate({
                            'display': 'none'
                        });
        $('#' + passport).delay( 80 ).fadeIn( 50 );
        $('#' + passport).animate({
                'display': 'block'
        });
    } else {
        $('#' + passport).delay( 20 ).fadeOut( 50 );
        $('#' + passport).animate({
                            'display': 'none'
                        });
        $('#' + rut).delay( 80 ).fadeIn( 50 );
        $('#' + rut).animate({
                'display': 'block'
        });
    }
};


Programa.prototype.pasajerosProg = function(valor, div1, div2, php, sgl, dbl, tpl, mon, opc) {
    $('#' + div1).delay( 10 ).fadeOut( 400 );
    $('#' + div1).animate({
            'display': 'none'
    });
    
    if(valor) {
        $.post(php,  {
            _PP_: valor,
            _SGL_: sgl,
            _DBL_: dbl,
            _TPL_: tpl,
            _MON_: mon,
            _OPC_: opc
        }, function(data) {
            $('#' + div2).html(data);
            $('#' + div2).delay( 100 ).fadeIn( 400 );
            $('#' + div2).animate({
                    'display': 'block'
            });
            
        });
    } else {
        $('#' + div2).delay( 100 ).fadeIn( 400 );
        $('#' + div2).animate({
                'display': 'block'
        });
    }
};

Programa.prototype.procesoDetallePasajeros = function(classFrm, php, btn, div)
{
    $("#"+btn).attr('disabled', 'disabled');
    initLoad();

    //$("#" + div).html("");
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
            $("#" + div).html(data);
            endLoad();
        },

        //si ha ocurrido un error
        error: function()
        {
            $("#" + div).html("Ha ocurrido un error");
        }
    });
}
//Programa.prototype.validaPasaporte();

/*function crearPersona(){
    var persona1 = new Programa();
    persona1.setNombre("Jonathan Estay");
    alert(persona1.getNombre());
    
    var persona2 = new Programa();
    persona2.setNombre("Sergio Orellana");
    alert(persona2.getNombre());
}*/

//crearPersona();