<?php
	include_once('../classes/config.php');
	

	// VACIAR TABLA PARA GUARDAR LO NUEVO
	if(isset($_REQUEST['accion']) and $_REQUEST['accion'] == 'vaciarTabla'){
		if(mysqli_query($conexion, "DELETE FROM ep_servicios")){
			echo 'OK!';
		}else{
			echo 'ERROR!';
		}
		exit();
		die();
	}
	// GUARDAR DATOS
	if(isset($_REQUEST['accion']) and $_REQUEST['accion'] == 'guardar'){
		if(mysqli_query($conexion, "INSERT INTO ep_servicios (idServicio, tipo, texto, orden) VALUES ('".$_REQUEST['idServicio']."','".$_REQUEST['tipo']."','".utf8_decode($_REQUEST['texto'])."','".$_REQUEST['orden']."')")){
			echo 'OK!';
		}else{
			echo 'ERROR!';
		}
		exit();
		die();
	}
	
	// CAMBIAR NOMBRE DE SERVICIO
	if(isset($_REQUEST['accion']) and $_REQUEST['accion'] == 'cambioNombreServicio'){
		mysqli_query($conexion, "UPDATE ep_serviciosnombre SET nombre = '".$_REQUEST['cambioServicioNombre']."' WHERE id = '".$_REQUEST['idServicio']."'");
	}
	
	// CARGO NOMBRES DE SERVICIO
	$SQL_NOMBRES = "SELECT * FROM ep_serviciosnombre";
	$RS_NOMBRES = mysqli_query($conexion, $SQL_NOMBRES);
	while($ns = mysqli_fetch_object($RS_NOMBRES)){
		if($ns->id == 1){$servicio1 = $ns->nombre;}
		if($ns->id == 2){$servicio2 = $ns->nombre;}
		if($ns->id == 3){$servicio3 = $ns->nombre;}
		if($ns->id == 4){$servicio4 = $ns->nombre;}
	}
?>
<!DOCTYPE html>
<html lang='es'> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link rel="shortcut icon" type="images/png" href="images/favicon.png">
		<link href="https://fonts.googleapis.com/css?family=Abel" rel="stylesheet" async="true" /> 
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
		<title> Servicios Esfera Publica - CMS </title>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="../css/generales.css" />	
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="../js/oGen.js"></script>
	</head>
	<body>
		<div class='tituloCMSEP'>
			SERVICIOS ESFERA PÚBLICA
		</div>
		<div class='bloque'>
			<table class='contServicios' id='contAPPDATE'>
				<tr>
					<td class='tituloSlider' colspan=2 onclick="$('.trAPPDATE').fadeToggle('fast');">
						<span><?php echo $servicio1; ?></span>
						<i class="material-icons" onclick='oServicios.fnCambiaNombreServicio(this, 1);'>mode_edit</i>
					</td>
				</tr>
				<tr class='trAPPDATE'>
					<td class='contBotonLargo'><input type='button' class='botonNaranja' value='Agregar Título' style='float:right;' onclick="oServicios.fnAgregaTitulo('contAPPDATE','trAPPDATE');" /></td>
					<td class='acciones'><input type='button' class='botonNaranja' value='Agregar Parrafo' onclick="oServicios.fnAgregaTexto('contAPPDATE','trAPPDATE');"></td>
				</tr>
				<?php
					$SQL_APPDATE = "SELECT * FROM ep_servicios WHERE idServicio = '1' ORDER BY orden ASC";
					$RS_APPDATE = mysqli_query($conexion, $SQL_APPDATE);
					$i=0;
					while($sa = mysqli_fetch_object($RS_APPDATE)){
						$i++;
						echo "<tr class='trAPPDATE datosCampoServicio'>";
						if($sa->tipo == 1){
							echo "	<td class='inputServicios'><textarea placeholder='Parrafo' class='txtServicio' data-tipo='1'>".utf8_encode($sa->texto)."</textarea></td>";
						}
						if($sa->tipo == 2){
							echo "	<td class='inputServicios'><input type='text' placeholder='Título' value='".utf8_encode($sa->texto)."' class='txtServicio' data-tipo='2' /></td>";
						}
						echo "	<td class='acciones'><input type='hidden' class='orden' value='".$i."' /><i class='material-icons' title='Eliminar' onclick='oServicios.fnEliminar(this);'>delete</i></td>";
						echo "</tr>";
					}
				?>
			</table>
		</div>
		<div class='bloque'>
			<table class='contServicios' id='contMONITOREO'>
				<tr>
					<td class='tituloSlider' colspan=2 onclick="$('.trMONITOREO').fadeToggle('fast');">
						<span><?php echo $servicio2; ?></span>
						<i class="material-icons" onclick='oServicios.fnCambiaNombreServicio(this, 2);'>mode_edit</i>
					</td>
				</tr>
				<tr class='trMONITOREO'>
					<td class='contBotonLargo'><input type='button' class='botonNaranja' value='Agregar Título' style='float:right;' onclick="oServicios.fnAgregaTitulo('contMONITOREO','trMONITOREO');" /></td>
					<td class='acciones'><input type='button' class='botonNaranja' value='Agregar Parrafo' onclick="oServicios.fnAgregaTexto('contMONITOREO','trMONITOREO');"></td>
				</tr>
				<?php
					$SQL_MONITOREO = "SELECT * FROM ep_servicios WHERE idServicio = '2' ORDER BY orden ASC";
					$RS_MONITOREO = mysqli_query($conexion, $SQL_MONITOREO);
					$i=0;
					while($sm = mysqli_fetch_object($RS_MONITOREO)){
						$i++;
						echo "<tr class='trMONITOREO datosCampoServicio'>";
						if($sm->tipo == 1){
							echo "	<td class='inputServicios'><textarea placeholder='Parrafo' class='txtServicio' data-tipo='1'>".utf8_encode($sm->texto)."</textarea></td>";
						}
						if($sm->tipo == 2){
							echo "	<td class='inputServicios'><input type='text' placeholder='Título' value='".utf8_encode($sm->texto)."' class='txtServicio' data-tipo='2' /></td>";
						}
						echo "	<td class='acciones'><input type='hidden' class='orden' value='".$i."' /><i class='material-icons' title='Eliminar' onclick='oServicios.fnEliminar(this);'>delete</i></td>";
						echo "</tr>";
					}
				?>
			</table>
		</div>
		<div class='bloque'>
			<table class='contServicios' id='contCONSULTORIA'>
				<tr>
					<td class='tituloSlider' colspan=2 onclick="$('.trCONSULTORIA').fadeToggle('fast');">
						<span><?php echo $servicio3; ?></span>
						<i class="material-icons" onclick='oServicios.fnCambiaNombreServicio(this, 3);'>mode_edit</i>
					</td>
				</tr>
				<tr class='trCONSULTORIA'>
					<td class='contBotonLargo'><input type='button' class='botonNaranja' value='Agregar Título' style='float:right;' onclick="oServicios.fnAgregaTitulo('contCONSULTORIA','trCONSULTORIA');" /></td>
					<td class='acciones'><input type='button' class='botonNaranja' value='Agregar Parrafo' onclick="oServicios.fnAgregaTexto('contCONSULTORIA','trCONSULTORIA');"></td>
				</tr>
				<?php
					$SQL_CONSULTORIA = "SELECT * FROM ep_servicios WHERE idServicio = '3' ORDER BY orden ASC";
					$RS_CONSULTORIA = mysqli_query($conexion, $SQL_CONSULTORIA);
					$i=0;
					while($sc = mysqli_fetch_object($RS_CONSULTORIA)){
						$i++;
						echo "<tr class='trCONSULTORIA datosCampoServicio'>";
						if($sc->tipo == 1){
							echo "	<td class='inputServicios'><textarea placeholder='Parrafo' class='txtServicio' data-tipo='1'>".utf8_encode($sc->texto)."</textarea></td>";
						}
						if($sc->tipo == 2){
							echo "	<td class='inputServicios'><input type='text' placeholder='Título' value='".utf8_encode($sc->texto)."' class='txtServicio' data-tipo='2' /></td>";
						}
						echo "	<td class='acciones'><input type='hidden' class='orden' value='".$i."' /><i class='material-icons' title='Eliminar' onclick='oServicios.fnEliminar(this);'>delete</i></td>";
						echo "</tr>";
					}
				?>
			</table>
		</div>
		<div class='bloque'>
			<table class='contServicios' id='contINCIDENCIA'>
				<tr>
					<td class='tituloSlider' colspan=2 onclick="$('.trINCIDENCIA').fadeToggle('fast');">
						<span><?php echo $servicio4; ?></span>
						<i class="material-icons" onclick='oServicios.fnCambiaNombreServicio(this, 4);'>mode_edit</i>
					</td>
				</tr>
				<tr class='trINCIDENCIA'>
					<td class='contBotonLargo'><input type='button' class='botonNaranja' value='Agregar Título' style='float:right;' onclick="oServicios.fnAgregaTitulo('contINCIDENCIA','trINCIDENCIA');" /></td>
					<td class='acciones'><input type='button' class='botonNaranja' value='Agregar Parrafo' onclick="oServicios.fnAgregaTexto('contINCIDENCIA','trINCIDENCIA');"></td>
				</tr>
				<?php
					$SQL_INCIDENCIA = "SELECT * FROM ep_servicios WHERE idServicio = '4' ORDER BY orden ASC";
					$RS_INCIDENCIA = mysqli_query($conexion, $SQL_INCIDENCIA);
					$i=0;
					while($si = mysqli_fetch_object($RS_INCIDENCIA)){
						$i++;
						echo "<tr class='trINCIDENCIA datosCampoServicio'>";
						if($si->tipo == 1){
							echo "	<td class='inputServicios'><textarea placeholder='Parrafo' class='txtServicio' data-tipo='1'>".utf8_encode($si->texto)."</textarea></td>";
						}
						if($si->tipo == 2){
							echo "	<td class='inputServicios'><input type='text' placeholder='Título' value='".utf8_encode($si->texto)."' class='txtServicio' data-tipo='2' /></td>";
						}
						echo "	<td class='acciones'><input type='hidden' class='orden' value='".$i."' /><i class='material-icons' title='Eliminar' onclick='oServicios.fnEliminar(this);'>delete</i></td>";
						echo "</tr>";
					}
				?>
			</table>
		</div>
		<div class='botonesEP' id='botonesEP'>
			<div class='botonCancelar'><input type='button' class='botonNaranja' value='Cancelar' onclick='window.location = window.location.href;' /></div>
			<div class='botonGuardar'><input type='button' class='botonNaranja' value='Guardar' onclick='oServicios.fnGuardar();' /></div>
		</div>
		<div class='contModalCambioNombreServicio'>
			<div class='frmModalCambioNombreServicio'>
				<div class='titulo'>Cambiar nombre de servicio:</div>
				<form action='' method='post'>
					<input type='text' name='cambioServicioNombre' id='cambioServicioNombre' />
					<input type='hidden' name='idServicio' id='idServicioNombreNuevo' />
					<input type='hidden' name='accion' value='cambioNombreServicio' />
					<input type='button' value='Cancelar' onclick="$(this).parent().parent().parent().fadeOut('slow');" />
					<input type='submit' value='Guardar' />
				</form>	
			</div>
		</div>
		
		<script type='text/javascript'>
			var oServicios = oServicios || {};
			
			oServicios.fnCambiaNombreServicio = function(self, idServicio){
				$('#idServicioNombreNuevo').val(idServicio);
				var nombre = $(self).parent().find('span').html();
				$('#cambioServicioNombre').val(nombre);
				$('.contModalCambioNombreServicio').fadeIn('slow');
			}
			
			oServicios.fnEliminar = function(self){
				$(self).parent().parent().remove();
			}
			
			oServicios.fnAgregaTitulo = function(ID,TR){
				var orden = $('.'+TR).length + 1;
				var HTML  = "<tr class='"+TR+" datosCampoServicio' style='display: table-row;'>";
					HTML += "	<td class='inputServicios'><input type='text' placeholder='Título' value='' class='txtServicio' data-tipo='2' /></td>";
					HTML += "	<td class='acciones'><input type='hidden' class='orden' value='"+orden+"' /><i class='material-icons' title='Eliminar' onclick='oServicios.fnEliminar(this);'>delete</i></td>";
					HTML += "</tr>";
				$('#'+ID).append(HTML);
			}
			
			oServicios.fnAgregaTexto = function(ID,TR){
				var orden = $('.'+TR).length + 1;
				var HTML  = "<tr class='"+TR+" datosCampoServicio' style='display: table-row;'>";
					HTML += "	<td class='inputServicios'><textarea placeholder='Parrafo' class='txtServicio' data-tipo='1'></textarea></td>";
					HTML += "	<td class='acciones'><input type='hidden' class='orden' value='"+orden+"' /><i class='material-icons' title='Eliminar' onclick='oServicios.fnEliminar(this);'>delete</i></td>";
					HTML += "</tr>";
				$('#'+ID).append(HTML);
			}
			
			oServicios.fnGuardar = function(){
				$.ajax({
					url: "ep_servicios.php",
					data: {
						accion: 'vaciarTabla'
					},
					success: function(data){
						$('#contAPPDATE .datosCampoServicio').each(function(){
							var idServicio = 1;
							var tipo = $(this).find('.txtServicio').attr('data-tipo');
							var texto = $(this).find('.txtServicio').val();
							var orden = $(this).find('.orden').val();
							$.ajax({
								url: "ep_servicios.php",
								data: {
									accion: 'guardar',
									idServicio: idServicio,
									tipo: tipo,
									texto: texto,
									orden: orden
								},
								success: function(data){}
							});
						});
						$('#contMONITOREO .datosCampoServicio').each(function(){
							var idServicio = 2;
							var tipo = $(this).find('.txtServicio').attr('data-tipo');
							var texto = $(this).find('.txtServicio').val();
							var orden = $(this).find('.orden').val();
							$.ajax({
								url: "ep_servicios.php",
								data: {
									accion: 'guardar',
									idServicio: idServicio,
									tipo: tipo,
									texto: texto,
									orden: orden
								},
								success: function(data){}
							});
						});
						$('#contCONSULTORIA .datosCampoServicio').each(function(){
							var idServicio = 3;
							var tipo = $(this).find('.txtServicio').attr('data-tipo');
							var texto = $(this).find('.txtServicio').val();
							var orden = $(this).find('.orden').val();
							$.ajax({
								url: "ep_servicios.php",
								data: {
									accion: 'guardar',
									idServicio: idServicio,
									tipo: tipo,
									texto: texto,
									orden: orden
								},
								success: function(data){}
							});
						});
						$('#contINCIDENCIA .datosCampoServicio').each(function(){
							var idServicio = 4;
							var tipo = $(this).find('.txtServicio').attr('data-tipo');
							var texto = $(this).find('.txtServicio').val();
							var orden = $(this).find('.orden').val();
							$.ajax({
								url: "ep_servicios.php",
								data: {
									accion: 'guardar',
									idServicio: idServicio,
									tipo: tipo,
									texto: texto,
									orden: orden
								},
								success: function(data){}
							});
						});
						oGen.fnAlert('Los cambios se guardaron correctamente.');
					}
				});
			}
		</script>
	</body>
</html>