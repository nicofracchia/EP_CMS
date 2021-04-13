<?php
	session_start();
	$mensajeAlerta = '';
	$mail = '';
	$clave = '';
	
	if(isset($_REQUEST['login'])){
		if(!isset($_REQUEST['usuario']) or $_REQUEST['usuario'] == ''){
			$mensajeAlerta = "Debe ingresar su mail para iniciar sesión.";
		}else{
			$mail = $_REQUEST['usuario'];
			if(!isset($_REQUEST['clave']) or $_REQUEST['clave'] == ''){
				$mensajeAlerta = "Debe ingresar su contraseña para iniciar sesión.";
			}else{
				$clave = $_REQUEST['clave'];
				include_once('classes/config.php');
				//$SQL_LOGIN = "SELECT * FROM usuarios WHERE eliminado = 0 AND habilitado = '1' AND mail='".$mail."' AND clave='".hash('sha256',hash('sha256',$clave))."'"; // ---> NO CODIFICA MAS LAS CONTRASEÑAS
				$SQL_LOGIN = "SELECT * FROM usuarios WHERE eliminado = 0 AND habilitado = '1' AND mail='".$mail."' AND clave='".$clave."'";
				$RS_LOGIN = mysqli_query($conexion, $SQL_LOGIN);
				if(mysqli_num_rows($RS_LOGIN) != 1){
					$mensajeAlerta = "Usuario o contraseña inválidos.";
				}else{
					$datos = mysqli_fetch_array($RS_LOGIN);
					$_SESSION['idUsuario'] = $datos['id'];
					$_SESSION['nombre'] = $datos['nombre'];
					$_SESSION['apellido'] = $datos['apellido'];
					$_SESSION['mail'] = $datos['mail'];
					$_SESSION['tipoUsuario'] = $datos['tipo'];
					header('location:index.php');
				}
			}
		}
	}else{
		$_SESSION = Array();
		session_destroy();
	}
?>
<!DOCTYPE html>
<html lang='es'> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link rel="shortcut icon" type="images/png" href="images/favicon.png">
		<link href="https://fonts.googleapis.com/css?family=Abel" rel="stylesheet" async="true" /> 
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
		<title> AppDate Legislativo - CMS </title>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script>
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
		</script>
		<style type='text/css'>
			.logoLogin{
				width:100%;
				text-align:center;
				padding:10px;
				margin-bottom:40px;
			}
			.logoLogin img{
				width:auto;
			}
			.contInputLogin{
				width:100%;
				text-align:center;
				padding:10px;
			}
			.contInputLogin input[type=text],
			.contInputLogin input[type=password]{
				border:solid 1px #CCC;
				border-radius:15px;
				background:#EEE;
				width:80%;
				max-width:400px;
				padding:5px 10px;
				font-size:25px;
			}
			.contInputLogin input[type=submit]{
				border:solid 1px #e93400;
				border-radius:15px;
				background:#e93400;
				width:80%;
				max-width:400px;
				padding:5px 10px;
				font-size:25px;
				color:#FFF;
				cursor:pointer;
			}
		</style>
	</head>
	<body>
		<div class='logoLogin'><img src='images/logoAppdate.png' alt='AppDate Legislativo - CMS' /></div>
		<form action='#' method='post'>
			<div class='contInputLogin'><input type='text' name='usuario' placeholder='E-mail' value='<?php echo $mail; ?>' /></div>
			<div class='contInputLogin'><input type='password' name='clave' placeholder='Contraseña' value='<?php echo $clave; ?>' /></div>
			<div class='contInputLogin'><input type='submit' name='login' value='INGRESAR' /></div>
		</form>
		<?php
			if($mensajeAlerta != ''){
				echo "<script type='text/javascript'>oGen.fnAlert('".$mensajeAlerta."');</script>";
			}
		?>
	</body>
</html>