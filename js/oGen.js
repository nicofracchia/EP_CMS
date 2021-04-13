var oGen = oGen || {};

/** ALERT **/
oGen.fnAlert = function(texto,cb,tipo){
	cb = cb || 0;
	tipo = tipo || 0;
	HTML  = "<div id='contenedorAlert'>";
	HTML += "	<div class='cajaAlert'>";
	HTML += "		<div class='txtAlert'>"+texto+"</div>";
	if(tipo == 1){
		HTML += "		<div class='btnAlertCancelar' onclick='oGen.fnAlertCerrar();'>Cancelar</div>";
	}
	HTML += "		<div class='btnAlert' onclick='oGen.fnAlertCerrar();"+cb+"'>Aceptar</div>";
	HTML += "	</div>";
	HTML += "</div>"
		
	$('body').prepend(HTML);
	setTimeout(function(){$('#contenedorAlert').fadeIn('fast');},100);
};
oGen.fnAlertCerrar = function(texto){
	$('#contenedorAlert').fadeOut('fast');
	setTimeout(function(){$('#contenedorAlert').remove();},100);
};

/** MENU PRINCIPAL **/
oGen.fnMuestraSubMenu = function(self,alto){
	var altoActual = $(self).height();
	var altoCerrado = 40;
	if(altoActual == altoCerrado){
		$(self).height(alto);
		setTimeout(function(){$(self).addClass('itemMenuSeleccionado');},300);
	}else{
		$(self).height(altoCerrado);
		setTimeout(function(){$(self).removeClass('itemMenuSeleccionado');},300);
	}
}
oGen.fnMuestraSubMenu_cierreGral = function(){
	if(window.innerWidth > 1000){
		var altoCerrado = 37;
		setTimeout(function(){$('.itemMenu').removeClass('itemMenuSeleccionado');},300);
		$('.itemMenu').height(altoCerrado);
	}
}

/** PESTAÑAS **/
oGen.fnAbrePestania = function(self, tipo, evt){
	// TIPO 1 = SPAN; TIPO 2 = OPCION PPAL
	if(tipo == 1){
		var iconito = $(self).parent().find('.material-icons').html();
		var txt = $(self).html();
	}
	if(tipo == 2){
		var iconito = $(self).find('.material-icons').html();
		var txt = $(self).find('.tituloItemSeccion').html();
	}
	var ID = $(self).attr('data-idPestania');
	if($('#'+ID).length == 0){// Si la pestaña no existe la creo
		var HTML = "<div class='pestania pestaniaActiva' onclick='oGen.fnCambiaPestania(this);' id='"+ID+"'>";
			HTML += "	<div class='icono'><i class='material-icons'>"+iconito+"</i></div>";
			HTML += "	<div class='texto'>"+txt+"</div>";
			HTML += "	<div class='cerrar' onclick='oGen.fnCerrarPestania(this);'><i class='material-icons'>close</i></div>";
			HTML += "</div>";
		$('.pestania').removeClass('pestaniaActiva');
		$('#contenedorPestanias').append(HTML);
		
		// Agrega iframe para pestaña
		var IDIFRAME = $(self).attr('data-idPestania').split('_');
		var IFRAME = "<iframe class='iframeContenido' src='"+$(self).attr('data-archivoPestania')+"' id='iframe_"+IDIFRAME[1]+"' onload='oGen.ajustaIframe(this);'></iframe>";
		$('#contenedorContenido .iframeContenido').fadeOut('fast');
		$('#contenedorContenido').append(IFRAME);
	}else{// Si ya existe la selecciono
		oGen.fnCambiaPestania($('#'+ID));
	}
}
oGen.fnCambiaPestania = function(self){
	$('.pestania').removeClass('pestaniaActiva');
	$(self).addClass('pestaniaActiva');
	var idIframe = $(self).prop('id').split('_');
	$('#contenedorContenido .iframeContenido').hide('fast');
	$('#iframe_'+idIframe[1]).show('slow');
}
oGen.fnCerrarPestania = function(self){
	$('#iframe_'+$(self).parent().prop('id').split('_')[1]).remove();
	$(self).parent().remove();
}
oGen.ajustaIframe = function(obj){
	return true;
	obj.style.height = 0;
	obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}

/** NOTICIAS **/
oGen.fnCargaVistaPrevia = function(){
	var cosas = "";
	var secciones = "";
	var legislaturas = "";
	var imagen = "";
	$("input[name='secciones[]']").each(function(){
		if($(this).prop('checked') == 'true' || $(this).prop('checked') == true){
			var IDSeccion = $(this).prop('id');
			var txtSeccion = $("label[for='"+IDSeccion+"']").html();
			secciones += txtSeccion+', ';
		}
	});
	if(secciones != ''){
		cosas += "SECCIONES: <span>"+secciones.slice(0,-2)+"</span>";
	}
	if($("input[name='personas']").val() != ''){
		cosas += " | EN ESTA NOTA: <span>"+$("input[name='personas']").val()+"</span> ";
	}
	if($("input[name='distrito']").val() != ''){
		cosas += " | OTRO: <span>"+$("input[name='distrito']").val()+"</span> ";
	}
	$("input[name='legislaturas[]']").each(function(){
		if($(this).prop('checked') == 'true' || $(this).prop('checked') == true){
			var IDLegislatura = $(this).prop('id');
			var txtLegislatura = $("label[for='"+IDLegislatura+"']").html();
			legislaturas += txtLegislatura+', ';
		}
	});
	if(legislaturas != ''){
		cosas += " | DISTRITOS: <span>"+legislaturas.slice(0,-2)+"</span>";
	}
	if($('#imagenMuestra').length > 0){
		imagen = "<img src='"+$('#imagenMuestra').prop('src')+"' alt='' />";
	}
	if(typeof CKEDITOR.instances.textoNoticia != 'undefined' && typeof CKEDITOR.instances.textoNoticia != undefined){
		$("textarea[name='texto']").val(CKEDITOR.instances.textoNoticia.getData());
	}
	// INTERNA
	$('#rojo1').html($("input[name='fecha']").val());
	$('#azul1').html(' '+$("input[name='tema']").val());
	$('#titulo1').html($("input[name='titulo']").val());
	$('#cosas').html(cosas);
	$('#imagen').html(imagen);
	$('#texto1').html($("textarea[name='texto']").val());
	// LISTADO
	$('#azul').html(legislaturas.slice(0,-2));
	$('#rojo').html(' '+$("input[name='tema']").val());
	$('#titulo').html(' '+$("input[name='titulo']").val());
	if(imagen != ''){
		$('#texto').html(' '+$("textarea[name='texto']").val().substr(0,100)+"...");
	}else{
		$('#texto').html(' '+$("textarea[name='texto']").val().substr(0,200)+"...");
	}
	$('#fecha').html(' '+$("input[name='fecha']").val());
	$('#vpl3').html(imagen);
}
oGen.fnMuestraImagenCargada = function(evt){
	var selectedFile = evt.target.files[0];
	var reader = new FileReader();

	var imgtag = document.getElementById("imagenNoticia");
	imgtag.title = selectedFile.name;

	reader.onload = function(evt) {
		$('#imagenMuestra').prop('src',evt.target.result);
		$('#borrarImagenNoticia').show();
		oGen.fnCargaVistaPrevia();
	};

	reader.readAsDataURL(selectedFile);
}
oGen.fnBorrarImagenNoticia = function(){
	$('#imagenMuestra').prop('src','');
	$('#bkpNomIMg').val('');
	$('#imagenNoticia').val('');
	$('#borrarImagenNoticia').hide();
	oGen.fnCargaVistaPrevia();
}
oGen.fnToggleClientesNoticia = function(){
	if($('#tipoNoticias').val() == 2 || $('#tipoNoticias').val() == '2'){
		$('#listadoClientesNoticias').fadeIn('fast');
	}else{
		$('#listadoClientesNoticias').fadeOut('fast');
		$('#listadoClientesNoticias input').prop('checked',false);
	}
}
oGen.fnToggleClientesPushNoticia = function(){
	$('#listadoClientesPushNoticias').toggle('fast');
}

/** FUNCIONES EDITAR **/
oGen.fnRedireccionEditar = function(seccion, ID){
	window.location=seccion+'.php?editar='+ID;
}

/** FUNCIONES ELIMINAR **/
oGen.fnEliminarNoticia = function(ID){
	window.location = 'noticias.php?eliminar='+ID;
}
oGen.fnEliminarCliente = function(ID){
	window.location = 'clientes.php?eliminar='+ID;
}
oGen.fnEliminarUsuario = function(ID){
	window.location = 'usuarios.php?eliminar='+ID;
}

/** READY **/
$(document).ready(function(){
	// Stop propagation menu PPAL
	$('.subItemMenu').click(function(event){
		event.stopPropagation();
		oGen.fnAbrePestania(this,1);
	});
	// calendarios datepicker
	$(".calendario").datepicker({
		dateFormat: "dd-mm-yy",
		monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"]
	}).prop('readonly',true);
	// NOTICIAS
	if($('#vistaPreviaInterna').length > 0){
		oGen.fnCargaVistaPrevia();
	}
	$("input[name='fecha']").change(function(){
		oGen.fnCargaVistaPrevia();
	});
	$("input[name='titulo']").keyup(function(){
		oGen.fnCargaVistaPrevia();
	});
	$("textarea[name='texto']").keyup(function(){
		oGen.fnCargaVistaPrevia();
	});
	$("input[name='tema']").keyup(function(){
		oGen.fnCargaVistaPrevia();
	});
	$("input[name='secciones[]']").change(function(){
		oGen.fnCargaVistaPrevia();
	});
	$("input[name='legislaturas[]']").change(function(){
		oGen.fnCargaVistaPrevia();
	});
	$("input[name='distrito']").keyup(function(){
		oGen.fnCargaVistaPrevia();
	});
	$("input[name='personas']").keyup(function(){
		oGen.fnCargaVistaPrevia();
	});
	$('#imagenNoticia').change(function(evt){
		oGen.fnMuestraImagenCargada(evt);
	});
});
