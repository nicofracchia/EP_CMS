<?php
	include_once('../classes/config.php');
	

	// VACIAR TABLA PARA GUARDAR LO NUEVO
	if(isset($_REQUEST['accion']) and $_REQUEST['accion'] == 'vaciarTabla'){
		if(mysqli_query($conexion, "DELETE FROM ep_nosotros")){
			echo 'OK!';
		}else{
			echo 'ERROR!';
		}
		exit();
		die();
	}
	// GUARDAR DATOS
	if(isset($_REQUEST['accion']) and $_REQUEST['accion'] == 'guardar'){
		if(mysqli_query($conexion, "INSERT INTO ep_nosotros (idServicio, texto, orden) VALUES ('".$_REQUEST['idServicio']."','".utf8_decode($_REQUEST['texto'])."','".$_REQUEST['orden']."')")){
			echo 'OK!';
		}else{
			echo 'ERROR!';
		}
		exit();
		die();
	}
	// GUARDAR BIO LORENA
	if(isset($_REQUEST['accion']) and $_REQUEST['accion'] == 'guardarBioL'){
		$SQL_LORENA = "UPDATE ep_nosotros_bio SET nombre='".utf8_decode($_REQUEST['nombre'])."', puesto = '".utf8_decode($_REQUEST['puesto'])."', titulos = '".utf8_decode($_REQUEST['titulos'])."', texto = '".utf8_decode($_REQUEST['texto'])."' WHERE id = '1'";
		if(mysqli_query($conexion, $SQL_LORENA)){
			echo 'OK!';
		}else{
			echo 'ERROR!';
		}
		exit();
		die();
	}
	// GUARDAR BIO DORIS
	if(isset($_REQUEST['accion']) and $_REQUEST['accion'] == 'guardarBioD'){
		$SQL_DORIS = "UPDATE ep_nosotros_bio SET nombre='".utf8_decode($_REQUEST['nombre'])."', puesto = '".utf8_decode($_REQUEST['puesto'])."', titulos = '".utf8_decode($_REQUEST['titulos'])."', texto = '".utf8_decode($_REQUEST['texto'])."' WHERE id = '2'";
		if(mysqli_query($conexion, $SQL_DORIS)){
			echo 'OK!';
		}else{
			echo 'ERROR!';
		}
		exit();
		die();
	}
	// SUBIR IMAGEN LORENA
	if(isset($_REQUEST['accion']) and $_REQUEST['accion'] == 'subeImagenLorena'){
		if (isset($_FILES["inputImagenLorena"])){
			$file = $_FILES["inputImagenLorena"];
			$ruta_provisional = $file["tmp_name"];
			move_uploaded_file($ruta_provisional, "../../images/nosotros/lorena.png");
		}
		exit();
		die();
	}
	// SUBIR IMAGEN DORIS
	if(isset($_REQUEST['accion']) and $_REQUEST['accion'] == 'subeImagenDoris'){
		if (isset($_FILES["inputImagenDoris"])){
			$file = $_FILES["inputImagenDoris"];
			$ruta_provisional = $file["tmp_name"];
			move_uploaded_file($ruta_provisional, "../../images/nosotros/doris.png");
		}
		exit();
		die();
	}
?>
<!DOCTYPE html>
<html lang='es'> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link rel="shortcut icon" type="images/png" href="images/favicon.png">
		<link href="https://fonts.googleapis.com/css?family=Abel" rel="stylesheet" async="true" /> 
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
		<title> Nosotros Esfera Publica - CMS </title>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="../css/generales.css" />	
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="../js/oGen.js"></script>
	</head>
	<body>
		<div class='tituloCMSEP'>
			NOSOTROS ESFERA PÚBLICA
		</div>
		<div class='bloque'>
			<table class='contServicios' id='contQS'>
				<tr>
					<td class='tituloSlider' colspan=2 onclick="$('.trQS').fadeToggle('fast');">¿Quiénes somos?</td>
				</tr>
				<tr class='trQS'>
					<td class='contBotonLargo' colspan=2><input type='button' class='botonNaranja' value='Agregar Ítem' style='float:right;' onclick="oNosotros.fnAgregaItem('contQS','trQS');" /></td>
				</tr>
				<?php
					$SQL_QS = "SELECT * FROM ep_nosotros WHERE idServicio = '1' ORDER BY orden ASC";
					$RS_QS = mysqli_query($conexion, $SQL_QS);
					$i=0;
					while($sa = mysqli_fetch_object($RS_QS)){
						$i++;
						echo "<tr class='trQS datosCampoServicio'>";
						echo "	<td class='inputServicios'><input type='text' placeholder='Ítem' value='".utf8_encode($sa->texto)."' class='txtServicio' data-tipo='2' /></td>";
						echo "	<td class='acciones'><input type='hidden' class='orden' value='".$i."' /><i class='material-icons' title='Eliminar' onclick='oNosotros.fnEliminar(this);'>delete</i></td>";
						echo "</tr>";
					}
				?>
			</table>
		</div>
		<div class='bloque'>
			<table class='contServicios' id='contQND'>
				<tr>
					<td class='tituloSlider' colspan=2 onclick="$('.trQND').fadeToggle('fast');">¿Qué nos diferencia?</td>
				</tr>
				<tr class='trQND'>
					<td class='contBotonLargo' colspan=2><input type='button' class='botonNaranja' value='Agregar Ítem' style='float:right;' onclick="oNosotros.fnAgregaItem('contQND','trQND');" /></td>
				</tr>
				<?php
					$SQL_QND = "SELECT * FROM ep_nosotros WHERE idServicio = '2' ORDER BY orden ASC";
					$RS_QND = mysqli_query($conexion, $SQL_QND);
					$i=0;
					while($sa = mysqli_fetch_object($RS_QND)){
						$i++;
						echo "<tr class='trQND datosCampoServicio'>";
						echo "	<td class='inputServicios'><input type='text' placeholder='Ítem' value='".utf8_encode($sa->texto)."' class='txtServicio' data-tipo='2' /></td>";
						echo "	<td class='acciones'><input type='hidden' class='orden' value='".$i."' /><i class='material-icons' title='Eliminar' onclick='oNosotros.fnEliminar(this);'>delete</i></td>";
						echo "</tr>";
					}
				?>
			</table>
		</div>
		<div class='bloque'>
			<?php
				$SQL_BIO = "SELECT * FROM ep_nosotros_bio";
				$RS_BIO = mysqli_query($conexion, $SQL_BIO);
				while($bio = mysqli_fetch_object($RS_BIO)){
					if($bio->id == 1){//DATOS LORENA
						$nombreL = utf8_encode($bio->nombre);
						$puestoL = utf8_encode($bio->puesto);
						$titulosL = utf8_encode($bio->titulos);
						$textoL = utf8_encode($bio->texto);
					}
					if($bio->id == 2){//DATOS DORIS
						$nombreD = utf8_encode($bio->nombre);
						$puestoD = utf8_encode($bio->puesto);
						$titulosD = utf8_encode($bio->titulos);
						$textoD = utf8_encode($bio->texto);
					}
				}
			?>
			<div class='bloque'>
				<table class='contServicios' id='contBIOL'>
					<tr>
						<td class='tituloSlider' colspan=2 onclick="$('.trBIOL').fadeToggle('fast');">BIO LORENA ZAPATA</td>
					</tr>
					<tr class='trBIOL'>
						<td class='imagen'>
							<img src='../../images/nosotros/lorena.png' alt='LORENA ZAPATA' id='imagenLorena' /><br/>
							<form id='FRML' enctype="multipart/form-data">
								<input type='hidden' name='accion' value='subeImagenLorena' />
								<input type='file' placeholder='Seleccionar imágen' name='inputImagenLorena' id='inputImagenLorena' onchange='oNosotros.fnVistaPreviaL(this);' accept="image/gif,image/jpeg,image/jpg,image/png" />
							</form>
							<span id='volverImgLorena' onclick='oNosotros.fnCancelarImgL();'>Cancelar</span>
						</td>
						<td class='datos'>
							<div><input type='text' placeholder='Nombre' id='bioNombreL' value='<?php echo $nombreL; ?>' /></div>
							<div><input type='text' placeholder='Puesto' id='bioPuestoL' value='<?php echo $puestoL; ?>' /></div>
							<div class='tituloSlider'>Títulos: <i class="material-icons" title="Agregar título" onclick="oNosotros.fnAgregarTitulo('titulosLorena','tituloLorena');">add_circle</i></div>
							<div id='titulosLorena'>
								<?php
									foreach(explode('<br/>',$titulosL) as $tl){
										if($tl != ''){
											echo "<input type='text' class='tituloLorena' value='".$tl."' placeholder='Título' />";
										}
									}
								?>
							</div>
						</td>
					</tr>
					<tr class='trBIOL'>
						<td id='textoLorena' colspan=2>
							<div class='tituloSlider'>Texto: <i class="material-icons" title="Agregar párrafo" onclick="oNosotros.fnAgregarTexto('textoLorena','textoLorena');">add_circle</i></div>
							<?php
								foreach(explode('<p>',$textoL) as $tl){
									$txt = str_replace('<p>','',str_replace('</p>','',$tl));
									if($txt != ''){
										echo "<textarea class='textoLorena' placeholder='Parrafo'>".$txt."</textarea>";
									}
								}
							?>
						</td>
					</tr>
				</table>
			</div>
			<div class='bloque'>
				<table class='contServicios' id='contBIOD'>
					<tr>
						<td class='tituloSlider' colspan=2 onclick="$('.trBIOD').fadeToggle('fast');">BIO DORIS STAUBER</td>
					</tr>
					<tr class='trBIOD'>
						<td class='imagen'>
							<img src='../../images/nosotros/doris.png' alt='DORIS STAUBER' id='imagenDoris' /><br/>
							<form id='FRMD' enctype="multipart/form-data">
								<input type='hidden' name='accion' value='subeImagenDoris' />
								<input type='file' placeholder='Seleccionar imágen' name='inputImagenDoris' id='inputImagenDoris' onchange='oNosotros.fnVistaPreviaD(this);' accept="image/gif,image/jpeg,image/jpg,image/png" />
							</form>
							<span id='volverImgDoris' onclick='oNosotros.fnCancelarImgD();'>Cancelar</span>
						</td>
						<td class='datos'>
							<div><input type='text' placeholder='Nombre' id='bioNombreD' value='<?php echo $nombreD; ?>' /></div>
							<div><input type='text' placeholder='Puesto' id='bioPuestoD' value='<?php echo $puestoD; ?>' /></div>
							<div class='tituloSlider'>Títulos: <i class="material-icons" title="Agregar título" onclick="oNosotros.fnAgregarTitulo('titulosDoris','tituloDoris');">add_circle</i></div>
							<div id='titulosDoris'>
								<?php
									foreach(explode('<br/>',$titulosD) as $td){
										if($td != ''){
											echo "<input type='text' class='tituloDoris' value='".$td."' placeholder='Título' />";
										}
									}
								?>
							</div>
						</td>
					</tr>
					<tr class='trBIOD'>
						<td id='textoDoris' colspan=2>
							<div class='tituloSlider'>Texto: <i class="material-icons" title="Agregar párrafo" onclick="oNosotros.fnAgregarTexto('textoDoris','textoDoris');">add_circle</i></div>
							<?php
								foreach(explode('<p>',$textoD) as $td){
									$txt = str_replace('<p>','',str_replace('</p>','',$td));
									if($txt != ''){
										echo "<textarea class='textoDoris' placeholder='Parrafo'>".$txt."</textarea>";
									}
								}
							?>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class='botonesEP' id='botonesEP'>
			<div class='botonCancelar'><input type='button' class='botonNaranja' value='Cancelar' onclick='window.location = window.location.href;' /></div>
			<div class='botonGuardar'><input type='button' class='botonNaranja' value='Guardar' onclick='oNosotros.fnGuardar();' /></div>
		</div>
		
		<script type='text/javascript'>
			var oNosotros = oNosotros || {};
			
			oNosotros.fnEliminar = function(self){
				$(self).parent().parent().remove();
			}
			
			oNosotros.fnAgregaItem = function(ID,TR){
				var orden = $('.'+TR).length + 1;
				var HTML  = "<tr class='"+TR+" datosCampoServicio' style='display: table-row;'>";
					HTML += "	<td class='inputServicios'><input type='text' placeholder='Ítem' value='' class='txtServicio' data-tipo='2' /></td>";
					HTML += "	<td class='acciones'><input type='hidden' class='orden' value='"+orden+"' /><i class='material-icons' title='Eliminar' onclick='oNosotros.fnEliminar(this);'>delete</i></td>";
					HTML += "</tr>";
				$('#'+ID).append(HTML);
			}
			
			oNosotros.fnGuardar = function(){
				$.ajax({
					url: "ep_nosotros.php",
					data: {
						accion: 'vaciarTabla'
					},
					success: function(data){
						$('#contQS .datosCampoServicio').each(function(){
							var idServicio = 1;
							var texto = $(this).find('.txtServicio').val();
							var orden = $(this).find('.orden').val();
							$.ajax({
								url: "ep_nosotros.php",
								data: {
									accion: 'guardar',
									idServicio: idServicio,
									texto: texto,
									orden: orden
								},
								success: function(data){}
							});
						});
						$('#contQND .datosCampoServicio').each(function(){
							var idServicio = 2;
							var texto = $(this).find('.txtServicio').val();
							var orden = $(this).find('.orden').val();
							$.ajax({
								url: "ep_nosotros.php",
								data: {
									accion: 'guardar',
									idServicio: idServicio,
									texto: texto,
									orden: orden
								},
								success: function(data){}
							});
						});
						oNosotros.fnGuardaBioLorena();
						oNosotros.fnGuardaBioDoris();
						if($('#imagenLorena').attr('src') != "../../images/nosotros/lorena.png"){
							oNosotros.fnSubeNuevaImagenL();
						}
						if($('#imagenDoris').attr('src') != "../../images/nosotros/doris.png"){
							oNosotros.fnSubeNuevaImagenD();
						}
						oGen.fnAlert('Los cambios se guardaron correctamente.');
					}
				});
			}
		
			oNosotros.fnAgregarTitulo = function(ID,nom){
				$('#'+ID).append("<input type='text' class='"+nom+"' value='' placeholder='Título' />");				
			}
			
			oNosotros.fnAgregarTexto = function(ID,nom){
				$('#'+ID).append("<textarea class='"+nom+"' placeholder='Parrafo'></textarea>");
			}
		
			oNosotros.fnGuardaBioLorena = function(){
				var titulos = '';
				var texto = '';
				$('.tituloLorena').each(function(){
					titulos += $(this).val()+'<br/>';
				});
				$('.textoLorena').each(function(){
					texto += '<p>'+$(this).val()+'</p>';
				});
				$.ajax({
					url: "ep_nosotros.php",
					data: {
						accion: 'guardarBioL',
						nombre: $('#bioNombreL').val(),
						puesto: $('#bioPuestoL').val(),
						titulos: titulos,
						texto: texto
					},
					success: function(data){}
				});
			}
			
			oNosotros.fnGuardaBioDoris = function(){
				var titulos = '';
				var texto = '';
				$('.tituloDoris').each(function(){
					titulos += $(this).val()+'<br/>';
				});
				$('.textoDoris').each(function(){
					texto += '<p>'+$(this).val()+'</p>';
				});
				$.ajax({
					url: "ep_nosotros.php",
					data: {
						accion: 'guardarBioD',
						nombre: $('#bioNombreD').val(),
						puesto: $('#bioPuestoD').val(),
						titulos: titulos,
						texto: texto
					},
					success: function(data){}
				});
			}
		
			oNosotros.fnVistaPreviaL = function(input){
				if (input.files && input.files[0]) {
					var reader = new FileReader();
					reader.onload = function(e) {
						$('#imagenLorena').attr('src', e.target.result);
						$('#volverImgLorena').show();
						$('#inputImagenLorena').hide();
					}
					reader.readAsDataURL(input.files[0]);
				}
			}
			
			oNosotros.fnVistaPreviaD = function(input){
				if (input.files && input.files[0]) {
					var reader = new FileReader();
					reader.onload = function(e) {
						$('#imagenDoris').attr('src', e.target.result);
						$('#volverImgDoris').show();
						$('#inputImagenDoris').hide();
					}
					reader.readAsDataURL(input.files[0]);
				}
			}
		
			oNosotros.fnCancelarImgL = function(){
				$('#imagenLorena').attr('src', '../../images/nosotros/lorena.png');
				$('#volverImgLorena').hide();
				$('#inputImagenLorena').show();
				$('#inputImagenLorena').val('');
			}
			
			oNosotros.fnCancelarImgD = function(){
				$('#imagenLorena').attr('src', '../../images/nosotros/doris.png');
				$('#volverImgDoris').hide();
				$('#inputImagenDoris').show();
				$('#inputImagenDoris').val('');
			}
			
			oNosotros.fnSubeNuevaImagenL = function(){
				var formData = new FormData($("#FRML")[0]);
				$.ajax({
					url: "ep_nosotros.php",
					type: "POST",
					data: formData,
					contentType: false,
					processData: false,
					success: function(datos){}
				});
			}
			
			oNosotros.fnSubeNuevaImagenD = function(){
				var formData = new FormData($("#FRMD")[0]);
				$.ajax({
					url: "ep_nosotros.php",
					type: "POST",
					data: formData,
					contentType: false,
					processData: false,
					success: function(datos){}
				});
			}
		</script>
	</body>
</html>