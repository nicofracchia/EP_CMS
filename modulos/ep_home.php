<?php
	include_once('../classes/config.php');
	
	// VACIAR TABLA PARA GUARDAR LO NUEVO
	if(isset($_REQUEST['accion']) and $_REQUEST['accion'] == 'vaciarTabla'){
		if(mysqli_query($conexion, "DELETE FROM ep_home")){
			echo 'OK!';
		}else{
			echo 'ERROR!';
		}
		exit();
		die();
	}
	// GUARDAR DATOS
	if(isset($_REQUEST['accion']) and $_REQUEST['accion'] == 'guardar'){
		if(mysqli_query($conexion, "INSERT INTO ep_home (numSlide, texto, orden) VALUES ('".$_REQUEST['item']."','".utf8_decode($_REQUEST['txt'])."','".$_REQUEST['orden']."')")){
			echo 'OK!';
		}else{
			echo 'ERROR!';
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
		<title> Home Esfera Publica - CMS </title>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="../css/generales.css" />	
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="../js/oGen.js"></script>
	</head>
	<body>
		<div class='tituloCMSEP'>
			FRASES HOME ESFERA PÚBLICA
			<input type='button' class='botonNaranja' value='Agregar slide' onclick='oHome.fnAgregaSlide();' />
		</div>
		<?php
			$SQL_HOME = "SELECT * FROM ep_home ORDER BY numSlide,orden ASC";
			$RS_HOME = mysqli_query($conexion, $SQL_HOME);
			$slides = Array();
			while($s = mysqli_fetch_object($RS_HOME)){
				if(!isset($slides[$s->numSlide])){
					$slides[$s->numSlide] = Array();
				}
				if(!isset($slides[$s->numSlide]['txt']) or $slides[$s->numSlide]['txt'] == ''){
					$slides[$s->numSlide]['txt']  = "<input type='text' value='".utf8_encode($s->texto)."' />";
				}else{
					$slides[$s->numSlide]['txt'] .= "<input type='text' value='".utf8_encode($s->texto)."' />";
				}
			}
			$i = 0;
			foreach($slides as $sl){
				$i++;
				echo "<div class='bloque'>";
				echo "	<div class='tituloSlider'>Slide ".$i;
				echo "		<i class='material-icons' title='Agregar renglón' onclick='oHome.fnAgregaRenglon(this);'>add_circle</i>";
				echo "		<i class='material-icons' title='Eliminar slide' onclick='$(this).parent().parent().remove();'>delete</i>";
				echo "	</div>";
				echo 	$sl['txt'];
				echo "</div>";
			}
		?>
		<div class='botonesEP' id='botonesEP'>
			<div class='botonCancelar'><input type='button' class='botonNaranja' value='Cancelar' onclick='window.location = window.location.href;' /></div>
			<div class='botonGuardar'><input type='button' class='botonNaranja' value='Guardar' onclick='oHome.fnGuardar();' /></div>
		</div>
		
		<script type='text/javascript'>
			var oHome = oHome || {};
			oHome.fnAgregaSlide = function(){
				var cantSlides = $('.bloque').length + 1;
				HTML  = "<div class='bloque'>";
				HTML += "	<div class='tituloSlider'>Slide "+cantSlides+"<i class='material-icons' title='Agregar renglón' onclick='oHome.fnAgregaRenglon(this);'>add_circle</i><i class='material-icons' title='Agregar renglón' onclick='$(this).parent().parent().remove();'>delete</i></div>";
				HTML += "		<input type='text' />";
				HTML += "</div>";
				$(HTML).insertBefore("#botonesEP");
				$("html, body").animate({ scrollTop: $(document).height() }, 400);
			}
			oHome.fnAgregaRenglon = function(self){
				$(self).parent().parent().append("<input type='text' />");
			}
			oHome.fnGuardar = function(){
				// VACIO LA TABLA
				$.ajax({
					url: "ep_home.php",
					data: {
						accion: 'vaciarTabla'
					},
					success: function(data){
						//console.log('DATA VACIAR TABLA:',data);
						// CARGO LO NUEVO
						var i = 0;
						$('.bloque').each(function(){
							i++;
							var o = 0;
							$(this).find('input').each(function(){
								if($(this).val() != ''){
									o++;
									$.ajax({
										url: "ep_home.php",
										data: {
											accion: 'guardar',
											txt: $(this).val(),
											orden: o,
											item: i
										},
										success: function(data){
											//console.log('DATA GUARDAR:',data);
										}
									});
								}
							});
						});
						oGen.fnAlert('Los cambios se guardaron correctamente.');
					}
				});
			}
		</script>
	</body>
</html>