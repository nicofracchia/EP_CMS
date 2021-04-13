<?php
	session_start();
	if(!isset($_SESSION['tipoUsuario'])){
		//header('Location:../login.php'); // ---> REVISAR PARA QUE ERA ESTO! ESTA BLOQUEANDO LOGIN DEL CMS
	}
	if(isset($_SESSION['tipoUsuario']) and $_SESSION['tipoUsuario'] == '0' and strpos($_SERVER['PHP_SELF'], 'modulos/noticias.php') === false){
		echo "No tiene permiso para entrar a esta secci&oacute;n.";
		exit();
		die();
	}
	
	
	date_default_timezone_set('America/Argentina/Buenos_Aires');
	/* ***** CONEXION ***** */
	if($_SERVER['SERVER_NAME'] == 'localhost'){
		$hostBD = 'localhost';
		$usuarioBD = 'root';
		$passBD = '';
		$baseBD = 'appdateLegislativo';
	}else{
		$hostBD = 'prowebsolutions.com.ar';
		$usuarioBD = 'pwGeneral';
		$passBD = '123456321asd';
		$baseBD = 'appdateLegislativo';
	}
	if($_SERVER['SERVER_NAME'] == 'esferapublica.com.ar'){
		$hostBD = 'localhost';
		$usuarioBD = 'prowebsolutions';
		$passBD = '123456321asd';
		$baseBD = 'appdate';
	}
	$conexion = mysqli_connect($hostBD,$usuarioBD,$passBD,$baseBD);
	
	/* ***** CLASSES ***** */
	include_once('funciones.php');
	include_once('Noticias.class.php');
	include_once('Usuarios.class.php');
	include_once('Clientes.class.php');
	include_once('Publicidad.class.php');