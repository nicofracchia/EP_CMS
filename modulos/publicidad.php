<?php
	include_once('../classes/config.php');
	$Publicidad = new Publicidad();
	$mensajeAlerta = "";
	if(isset($_REQUEST['msj']) and $_REQUEST['msj'] != ''){
		$mensajeAlerta = $_REQUEST['msj'];
	}
	$params = Array();
	if(isset($_REQUEST['accion']) and $_REQUEST['accion'] == 'GUARDAR'){
		$SQL = "UPDATE publicidad SET orden = '".$_REQUEST['orden']."', link = '".$_REQUEST['link']."' WHERE id = '".$_REQUEST['idPublicidad']."'";
		if($RS = mysqli_query($conexion, $SQL)){
			$mensajeAlerta = "La publicidad se guardó correctamente";
		}
	}
	if(isset($_REQUEST['accion']) and $_REQUEST['accion'] == 'HABILITAR'){
		$SQL = "UPDATE publicidad SET habilitada = '1' WHERE id = '".$_REQUEST['idPublicidad']."'";
		if($RS = mysqli_query($conexion, $SQL)){
			$mensajeAlerta = "La publicidad fue habilitada correctamente.";
		}
	}
	if(isset($_REQUEST['accion']) and $_REQUEST['accion'] == 'DESHABILITAR'){
		$SQL = "UPDATE publicidad SET habilitada = '0' WHERE id = '".$_REQUEST['idPublicidad']."'";
		if($RS = mysqli_query($conexion, $SQL)){
			$mensajeAlerta = "La publicidad fue deshabilitada correctamente.";
		}
	}
	if(isset($_REQUEST['accion']) and $_REQUEST['accion'] == 'ELIMINAR'){
		$SQL = "UPDATE publicidad SET eliminada = '1' WHERE id = '".$_REQUEST['idPublicidad']."'";
		if($RS = mysqli_query($conexion, $SQL)){
			$mensajeAlerta = "La publicidad fue eliminada correctamente.";
		}
	}
	if(isset($_REQUEST['accion']) and $_REQUEST['accion'] == 'NUEVA'){
		$nomBD = '';
		if(isset($_FILES["imagen"]) and $_FILES['imagen']['size'] != 0){
			$file = $_FILES["imagen"];
			$ruta_provisional = $file["tmp_name"];
			$nombre = time();
			$extencion = explode('/',$file["type"]);
			$extencion = $extencion[1];
			$carpeta = "../images/publicidad/";
			$src = $carpeta.$nombre.'.'.$extencion;
			$nomBD = explode('modulos/',$_SERVER["HTTP_REFERER"]);
			$nomBD = $nomBD[0].'images/publicidad/'.$nombre.'.'.$extencion;
			move_uploaded_file($ruta_provisional, $src);
		}
		if($nomBD != ''){
			$SQL = "INSERT INTO publicidad (imagen, link) VALUES ('".$nomBD."', '".$_REQUEST['link']."')";
			if($RS = mysqli_query($conexion, $SQL)){
				$mensajeAlerta = "La publicidad se cargó correctamente.";
			}else{
				$mensajeAlerta = "Se produjo un error cargando la publicidad.";
			}
		}else{
			$mensajeAlerta = "Debe cargar una imagen para guardar la publicidad.";
		}
	}
?>
<!DOCTYPE html>
<html lang='es'> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link rel="shortcut icon" type="images/png" href="images/favicon.png">
		<link href="https://fonts.googleapis.com/css?family=Abel" rel="stylesheet" async="true" /> 
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
		<title> Clientes - CMS </title>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="../css/generales.css" />	
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="../js/oGen.js"></script>
		<style>
			.tituloPublicidad{
				font-size: 20px;
				color: #e93400;
				padding: 10px;
				padding-bottom: 10px;
				border-bottom: solid 1px;
				margin-bottom: 10px;
			}
			.lineaPublicidad{
				display: table;
				width: 100%;
				margin-bottom: 10px;
			}
			.lineaPublicidad .orden,
			.lineaPublicidad .img,
			.lineaPublicidad .link,
			.lineaPublicidad .guardar,
			.lineaPublicidad .habilitar,
			.lineaPublicidad .deshabilitar,
			.lineaPublicidad .eliminar{
				display:table-cell;
				padding:10px;
				vertical-align: middle;
			}
			.lineaPublicidad .link input{
				width:100%;
				padding:10px;
				font-size:15px;
				border:solid 1px #CCC;
				border-radius:10px;
			}
			.lineaPublicidad .orden{
				width:85px;
			}
			.lineaPublicidad .orden input{
				width:65px;
				padding:10px;
				font-size:15px;
				border:solid 1px #CCC;
				border-radius:10px;
			}
			.lineaPublicidad .img{
				width:30%;
			}
			.lineaPublicidad .img img{
				max-width:500px;
			}
			.lineaPublicidad .guardar{
				width:50px;
				color:#3fe1cf;
			}
			.lineaPublicidad .deshabilitar{
				width:50px;
				color:#ef582f;
			}
			.lineaPublicidad .habilitar{
				width:50px;
				color:#2dad3d;
			}
			.lineaPublicidad .eliminar{
				width:50px;
				color:#F00;
			}
			i{
				cursor:pointer;
			}
		</style>
	</head>
	<body>
		<div class='tituloPublicidad'>Nueva publicidad</div>
		<form action='publicidad.php' method='post' enctype='multipart/form-data'>
			<input type='hidden' name='accion' value='NUEVA' />
			<table>
				<tr>
					<td><input type='file' name='imagen' /></td>
					<td><input type='text' name='link' placeholder='URL de la publicidad' style='width:100%;padding:5px 10px;font-size:15px;border:solid 1px #CCC;border-radius:10px;' /></td>
					<td><input type='submit' class='botonNaranja' value='Guardar' />
				</tr>
			</table>
		</form>
		<?php
			if($mensajeAlerta != ''){
				echo "<script type='text/javascript'>oGen.fnAlert('".$mensajeAlerta."');</script>";
			}
			$Publicidad->listadoPublicidades($params);
		?>
		<script>
			function fnPubli(ID, tipo){
				if(tipo == 1){
					$('#accion_'+ID).val('GUARDAR');
					$('#frm_'+ID).submit();
				}
				if(tipo == 2){
					$('#accion_'+ID).val('DESHABILITAR');
					$('#frm_'+ID).submit();
				}
				if(tipo == 3){
					if(confirm('Seguro que quiere eliminar esta publicidad?')){
						$('#accion_'+ID).val('ELIMINAR');
						$('#frm_'+ID).submit();
					}
				}
				if(tipo == 4){
					$('#accion_'+ID).val('HABILITAR');
					$('#frm_'+ID).submit();
				}
			}
		</script>
	</body>
</html>