<?php
	include_once('../classes/config.php');
	$Usuarios = new Usuarios();
	$mensajeAlerta = "";
	if(isset($_REQUEST['msj']) and $_REQUEST['msj'] != ''){
		$mensajeAlerta = $_REQUEST['msj'];
	}
	$params = Array();
	// PARAMETROS - FILTROS LISTADO
	if(isset($_REQUEST['fNombre']) and $_REQUEST['fNombre'] != ''){$params['fNombre'] = $_REQUEST['fNombre'];}
	if(isset($_REQUEST['fApellido']) and $_REQUEST['fApellido'] != ''){$params['fApellido'] = $_REQUEST['fApellido'];}
	if(isset($_REQUEST['fCelular']) and $_REQUEST['fCelular'] != ''){$params['fCelular'] = $_REQUEST['fCelular'];}
	if(isset($_REQUEST['fMail']) and $_REQUEST['fMail'] != ''){$params['fMail'] = $_REQUEST['fMail'];}
	if(isset($_REQUEST['fEstado']) and $_REQUEST['fEstado'] != ''){$params['fEstado'] = $_REQUEST['fEstado'];}
	if(isset($_REQUEST['fTipo']) and $_REQUEST['fTipo'] != ''){$params['fTipo'] = $_REQUEST['fTipo'];}
	// PARAMETROS - GUARDAR / EDITAR
	if(isset($_REQUEST['editar']) and $_REQUEST['editar'] != ''){$params['idEdicion'] = $_REQUEST['editar'];}
	if(isset($_REQUEST['idUsuario']) and $_REQUEST['idUsuario'] != ''){$params['gIdUsuario'] = $_REQUEST['idUsuario'];}else{$params['gIdUsuario'] = '';}
	if(isset($_REQUEST['nombre']) and $_REQUEST['nombre'] != ''){$params['gNombre'] = $_REQUEST['nombre'];}else{$params['nombre'] = '';}
	if(isset($_REQUEST['apellido']) and $_REQUEST['apellido'] != ''){$params['gApellido'] = $_REQUEST['apellido'];}else{$params['gApellido'] = '';}
	if(isset($_REQUEST['celular']) and $_REQUEST['celular'] != ''){$params['gCelular'] = $_REQUEST['celular'];}else{$params['gCelular'] = '';}
	if(isset($_REQUEST['mail']) and $_REQUEST['mail'] != ''){$params['gMail'] = $_REQUEST['mail'];}else{$params['gMail'] = '';}
	if(isset($_REQUEST['tipo']) and $_REQUEST['tipo'] != ''){$params['gTipo'] = $_REQUEST['tipo'];}else{$params['gTipo'] = '';}
	if(isset($_REQUEST['clave']) and $_REQUEST['clave'] != ''){$params['gClave'] = $_REQUEST['clave'];}else{$params['gClave'] = '';}
	if(isset($_REQUEST['clave2']) and $_REQUEST['clave2'] != ''){$params['gClave2'] = $_REQUEST['clave2'];}else{$params['gClave2'] = '';}
	if(isset($_REQUEST['habilitado']) and $_REQUEST['habilitado'] != ''){$params['gHabilitado'] = $_REQUEST['habilitado'];}else{$params['gHabilitado'] = '';}
	if(isset($_REQUEST['guardar']) and $_REQUEST['guardar'] == 'Guardar'){
		if($params['gIdUsuario'] == 0 and $params['gClave'] == ''){
			$mensajeAlerta = "Debe ingresar una contraseña para el usuario.";
		}else{
			if($params['gIdUsuario'] == 0){// valida q el mail del usuario nuevo no este en la tabla de clientes ni usuarios (solo para insert, no para edicion)
				$RS_CHK_USUARIOS = mysqli_query($conexion, "SELECT COUNT(*) AS cantMailReg FROM usuarios WHERE mail = '".$params['gMail']."' AND eliminado = '0'");
				$RES_CHK_USUARIOS = mysqli_fetch_object($RS_CHK_USUARIOS);
				$RS_CHK_CLIENTES = mysqli_query($conexion, "SELECT COUNT(*) AS cantMailReg FROM clientes WHERE mail = '".$params['gMail']."' AND eliminado = '0'");
				$RES_CHK_CLIENTES = mysqli_fetch_object($RS_CHK_CLIENTES);
				if($RES_CHK_USUARIOS->cantMailReg == 0 and $RES_CHK_CLIENTES->cantMailReg == 0){
					$_REQUEST['guardar'] == '';
					$idGuardado = $Usuarios->guardarUsuario($params);
					if($idGuardado == 0){
						$mensajeAlerta = "No se pudo guardar el usuario. Intente nuevamente más tarde.";
					}else{
						header("Location:usuarios.php?msj=El usuario se guardó correctamente!");
					}
				}else{
					$mensajeAlerta = "El mail ingresado ya se encuentra registrado.";
				}
			}else{
				$_REQUEST['guardar'] == '';
				$idGuardado = $Usuarios->guardarUsuario($params);
				if($idGuardado == 0){
					$mensajeAlerta = "No se pudo guardar el usuario. Intente nuevamente más tarde.";
				}else{
					header("Location:usuarios.php?msj=El usuario se guardó correctamente!");
				}
			}
		}
	}
	// PARAMETROS ELIMINAR
	if(isset($_REQUEST['eliminar']) and $_REQUEST['eliminar'] != ''){
		if($Usuarios->eliminarUsuario($_REQUEST['eliminar']) == 1){
			$mensajeAlerta = "El usuario se eliminó correctamente.";
		}else{
			$mensajeAlerta = "No se pudo eliminar el usuario. Intente nuevamente más tarde";
		}
		$_REQUEST['eliminar'] == '';
	}
?>
<!DOCTYPE html>
<html lang='es'> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link rel="shortcut icon" type="images/png" href="images/favicon.png">
		<link href="https://fonts.googleapis.com/css?family=Abel" rel="stylesheet" async="true" /> 
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
		<title> Usuarios - CMS </title>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="../css/generales.css" />	
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="../js/oGen.js"></script>
	</head>
	<body>
		<?php
			if($mensajeAlerta != ''){
				echo "<script type='text/javascript'>oGen.fnAlert('".$mensajeAlerta."');</script>";
			}
			if(!isset($_REQUEST['editar']) or $_REQUEST['editar'] == ''){
				$Usuarios->listadoUsuarios($params);
			}else{
				$Usuarios->ABMUsuarios($params);
			}
		?>
	</body>
</html>