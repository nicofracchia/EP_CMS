<?php
	include_once('../classes/config.php');
	$Noticias = new Noticias();
	$mensajeAlerta = "";
	if(isset($_REQUEST['msj']) and $_REQUEST['msj'] != ''){
		$mensajeAlerta = $_REQUEST['msj'];
	}
	$params = Array();
	// GUARDAR ADJUNTOS
	if(isset($_REQUEST['guardarAdjunto']) and $_REQUEST['guardarAdjunto'] == '1'){
		if (!file_exists("../adjuntosNoticias/".$_REQUEST['editar']) or !is_dir("../adjuntosNoticias/".$_REQUEST['editar'])) {
			mkdir("../adjuntosNoticias/".$_REQUEST['editar']);
		}
		move_uploaded_file($_FILES["archivoAdjunto"]["tmp_name"], "../adjuntosNoticias/".$_REQUEST['editar']."/".$_FILES["archivoAdjunto"]["name"]);
	}
	// ELIMINAR ADJUNTOS
	if(isset($_REQUEST['eliminarAdjunto']) and $_REQUEST['eliminarAdjunto'] == '1'){
		unlink("../adjuntosNoticias/".$_REQUEST['editar']."/".$_REQUEST["archivoParaEliminar"]);
	}
	// PARAMETROS - FILTROS LISTADO
	if(isset($_REQUEST['fTitulo']) and $_REQUEST['fTitulo'] != ''){$params['fTitulo'] = $_REQUEST['fTitulo'];}
	if(isset($_REQUEST['fTema']) and $_REQUEST['fTema'] != ''){$params['fTema'] = $_REQUEST['fTema'];}
	if(isset($_REQUEST['fLegislatura']) and $_REQUEST['fLegislatura'] != ''){$params['fLegislatura'] = $_REQUEST['fLegislatura'];}
	if(isset($_REQUEST['fFechaDesde']) and $_REQUEST['fFechaDesde'] != ''){$params['fFechaDesde'] = $_REQUEST['fFechaDesde'];}
	if(isset($_REQUEST['fFechaHasta']) and $_REQUEST['fFechaHasta'] != ''){$params['fFechaHasta'] = $_REQUEST['fFechaHasta'];}
	if(isset($_REQUEST['fTipo']) and $_REQUEST['fTipo'] != '0'){$params['fTipo'] = $_REQUEST['fTipo'];}
	if(isset($_REQUEST['fEstado']) and $_REQUEST['fEstado'] != '0'){$params['fEstado'] = $_REQUEST['fEstado'];}
	if(isset($_REQUEST['editar']) and $_REQUEST['editar'] != ''){$params['idEdicion'] = $_REQUEST['editar'];}

	// PARAMETROS - GUARDAR / EDITAR
	if(isset($_FILES["imagen"]) and $_FILES['imagen']['size'] != 0){
		$file = $_FILES["imagen"];
		$ruta_provisional = $file["tmp_name"];
		$nombre = time();
		$extencion = explode('/',$file["type"]);
		$extencion = $extencion[1];
		$carpeta = "../images/noticias/";
		$src = $carpeta.$nombre.'.'.$extencion;
		$nomBD = explode('modulos/',$_SERVER["HTTP_REFERER"]);
		$nomBD = $nomBD[0].'images/noticias/'.$nombre.'.'.$extencion;
		$params['gImagen'] = $nomBD;
		move_uploaded_file($ruta_provisional, $src);
	}else{
		if(isset($_REQUEST['bkpNomImg']) and $_REQUEST['bkpNomImg'] != ''){
			$params['gImagen'] = $_REQUEST['bkpNomImg'];
		}else{
			$params['gImagen'] = '';
		}
	}
	if(isset($_REQUEST['idNoticia']) and $_REQUEST['idNoticia'] != ''){$params['gIdNoticia'] = $_REQUEST['idNoticia'];}else{$params['gIdNoticia'] = '';}
	if(isset($_REQUEST['fecha']) and $_REQUEST['fecha'] != ''){$params['gFecha'] = $_REQUEST['fecha'];}else{$params['gFecha'] = '';}
	if(isset($_REQUEST['titulo']) and $_REQUEST['titulo'] != ''){$params['gTitulo'] = $_REQUEST['titulo'];}else{$params['gTitulo'] = '';}
	if(isset($_REQUEST['tema']) and $_REQUEST['tema'] != ''){$params['gTema'] = $_REQUEST['tema'];}else{$params['gTema'] = '';}
	//if(isset($_REQUEST['imagen']) and $_REQUEST['imagen'] != ''){$params['gImagen'] = $_REQUEST['imagen'];}else{$params['gImagen'] = '';}
	if(isset($_REQUEST['texto']) and $_REQUEST['texto'] != ''){$params['gTexto'] = $_REQUEST['texto'];}else{$params['gTexto'] = '';}
	if(isset($_REQUEST['secciones']) and $_REQUEST['secciones'] != ''){$params['gSecciones'] = implode('|',$_REQUEST['secciones']);}else{$params['gSecciones'] = '';}
	if(isset($_REQUEST['personas']) and $_REQUEST['personas'] != ''){$params['gPersonas'] = $_REQUEST['personas'];}else{$params['gPersonas'] = '';}
	if(isset($_REQUEST['distrito']) and $_REQUEST['distrito'] != ''){$params['gDistrito'] = $_REQUEST['distrito'];}else{$params['gDistrito'] = '';}
	if(isset($_REQUEST['legislaturas']) and $_REQUEST['legislaturas'] != ''){$params['gLegislaturas'] = implode('|',$_REQUEST['legislaturas']);}else{$params['gLegislaturas'] = '';}
	if(isset($_REQUEST['tipo']) and $_REQUEST['tipo'] != ''){$params['gTipo'] = $_REQUEST['tipo'];}else{$params['gTipo'] = '';}
	if(isset($_REQUEST['clientes']) and $_REQUEST['clientes'] != ''){$params['gClientes'] = implode('|',$_REQUEST['clientes']);}else{$params['gClientes'] = '';}
	if(isset($_REQUEST['clientesPush']) and $_REQUEST['clientesPush'] != ''){$params['gClientesPush'] = implode('|',$_REQUEST['clientesPush']);}else{$params['gClientesPush'] = '';}
	if(isset($_REQUEST['estado']) and $_REQUEST['estado'] != ''){$params['gEstado'] = $_REQUEST['estado'];}else{$params['gEstado'] = '';}
	if(isset($_REQUEST['keywords']) and $_REQUEST['keywords'] != ''){$params['gKeywords'] = $_REQUEST['keywords'];}else{$params['gKeywords'] = '';}

	if(isset($_REQUEST['guardar']) and $_REQUEST['guardar'] == 'Guardar'){
		$_REQUEST['guardar'] == '';
		$_REQUEST['guardarSeguir'] == '';
		$idGuardado = $Noticias->guardarNoticia($params);
		if($idGuardado == 0){
			$mensajeAlerta = "No se pudo guardar la noticia. Intente nuevamente más tarde.";
		}else{
			header("Location:noticias.php?msj=La noticia se guardó correctamente!");
		}
	}
	if(isset($_REQUEST['guardarSeguir']) and ($_REQUEST['guardarSeguir'] == 'Guardar y cargar adjuntos' or $_REQUEST['guardarSeguir'] == 'Guardar y seguir')){
		$_REQUEST['guardar'] == '';
		$_REQUEST['guardarSeguir'] == '';
		$idGuardado = $Noticias->guardarNoticia($params);
		if($idGuardado == 0){
			$mensajeAlerta = "No se pudo guardar la noticia. Intente nuevamente más tarde.";
		}else{
			header("Location:noticias.php?editar=".$idGuardado."&scrolToBottom=1");
		}
	}
	
	// PARAMETROS ELIMINAR
	if(isset($_REQUEST['eliminar']) and $_REQUEST['eliminar'] != ''){
		if($Noticias->eliminarNoticia($_REQUEST['eliminar']) == 1){
			$mensajeAlerta = "La noticia se eliminó correctamente.";
		}else{
			$mensajeAlerta = "No se pudo eliminar la noticia. Intente nuevamente más tarde";
		}
		$_REQUEST['eliminar'] == '';
	}
	
	// GUARDAR ORDEN
	if(isset($_REQUEST['accion']) and $_REQUEST['accion'] == 'guardaOrden'){
		$SQL = "UPDATE noticias SET orden = '".$_REQUEST['orden']."' WHERE id = '".$_REQUEST['ID']."'";
		$RS = mysqli_query($conexion, $SQL);
	}
?>
<!DOCTYPE html>
<html lang='es'> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link rel="shortcut icon" type="images/png" href="images/favicon.png">
		<link href="https://fonts.googleapis.com/css?family=Abel" rel="stylesheet" async="true" /> 
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
		<title> Noticias - CMS </title>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="../css/generales.css" />	
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="../js/oGen.js"></script>
		<script src='../ckeditor/ckeditor.js'></script>
		<style>
			.cke_button__paste,
			.cke_button__pastetext,
			.cke_button__pastefromword{
				display: none !important;
			}
		</style>
	</head>
	<body>
		<?php
			if($mensajeAlerta != ''){
				echo "<script type='text/javascript'>oGen.fnAlert('".$mensajeAlerta."');</script>";
			}
			if(isset($_REQUEST['scrolToBottom']) and $_REQUEST['scrolToBottom'] == '1'){
				echo "<script type='text/javascript'>\$(window).load(function() {\$('html, body').animate({ scrollTop: \$(document).height() }, 0);});</script>";
			}
			if(!isset($_REQUEST['editar']) or $_REQUEST['editar'] == ''){
				$Noticias->listadoNoticias($params);
			}else{
				$Noticias->ABMNoticias($params);
			}
		?>
		<script>
			function fnCambiaCheckGrupoClientes(self){
				var chk = $(self).prop('checked');
				$(self).parent().find('input[type=checkbox]').prop('checked', chk);
			}
			function fnValidaChkGrupal(self){
				var chk = $(self).prop('checked');
				if(chk == false || chk == 'false'){
					$(self).parent().find('.chkGrupoClientes').prop('checked', false);
				}
			}
			function fnValidaChkGrupalPush(self){
				var chk = $(self).prop('checked');
				if(chk == false || chk == 'false'){
					$(self).parent().find('.chkGrupoClientesPush').prop('checked', false);
				}
			}
		</script>
	</body>
</html>