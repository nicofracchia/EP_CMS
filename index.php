<?php
	session_start();
	if(!isset($_SESSION['tipoUsuario'])){
		header('Location:login.php');
	}
?>
<!DOCTYPE html>
<html lang='es'> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link rel="shortcut icon" type="images/png" href="images/favicon.png">
		<link href="https://fonts.googleapis.com/css?family=Abel" rel="stylesheet" async="true" /> 
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
		<title> Appdate Legislativo - CMS </title>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="css/generales.css" />	
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="js/oGen.js"></script>
	</head>
	<body style='overflow:hidden;'>
		<?php
			include_once('menu.php');
		?>
		<table id='contenedorGeneral'>
			<tr>
				<td colspan=3 id='encabezado'>
					<img src='images/logo.png' id='logo' alt='Proweb Solutions - CMS' />
					<div id='bienvenido'>Bienvenido <span><?php echo $_SESSION['nombre'].' '.$_SESSION['apellido']; ?></span> | <a href='login.php'>Cerrar sesión</a></div>
				</td>
			</tr>
			<tr><td colspan=3 class='separador'></td></tr>
			<tr>
				<td class='columnaMenu'></td>
				<td id='contenedorPestanias'></td>
				<td class='espacioDerecha'></td>
			</tr>
			<tr>
				<td class='columnaMenu'></td>
				<td id='contenedorContenido'></td>
				<td class='espacioDerecha'></td>
			</tr>
			<tr><td colspan=3 class='separador'></td></tr>
		</table>
	</body>
</html>