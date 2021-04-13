<?php
	include_once('../classes/config.php');
	$Clientes = new Clientes();
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
	if(isset($_REQUEST['fGrupo']) and $_REQUEST['fGrupo'] != ''){$params['fGrupo'] = $_REQUEST['fGrupo'];}
	if(isset($_REQUEST['fEstado']) and $_REQUEST['fEstado'] != ''){$params['fEstado'] = $_REQUEST['fEstado'];}
	
	// PARAMETROS - GUARDAR / EDITAR
	if(isset($_REQUEST['editar']) and $_REQUEST['editar'] != ''){$params['idEdicion'] = $_REQUEST['editar'];}
	if(isset($_REQUEST['idCliente']) and $_REQUEST['idCliente'] != ''){$params['gIdCliente'] = $_REQUEST['idCliente'];}else{$params['gIdCliente'] = '';}
	if(isset($_REQUEST['nombre']) and $_REQUEST['nombre'] != ''){$params['gNombre'] = $_REQUEST['nombre'];}else{$params['nombre'] = '';}
	if(isset($_REQUEST['apellido']) and $_REQUEST['apellido'] != ''){$params['gApellido'] = $_REQUEST['apellido'];}else{$params['gApellido'] = '';}
	if(isset($_REQUEST['celular']) and $_REQUEST['celular'] != ''){$params['gCelular'] = $_REQUEST['celular'];}else{$params['gCelular'] = '';}
	if(isset($_REQUEST['mail']) and $_REQUEST['mail'] != ''){$params['gMail'] = $_REQUEST['mail'];}else{$params['gMail'] = '';}
	if(isset($_REQUEST['clave']) and $_REQUEST['clave'] != ''){$params['gClave'] = $_REQUEST['clave'];}else{$params['gClave'] = '';}
	if(isset($_REQUEST['clave2']) and $_REQUEST['clave2'] != ''){$params['gClave2'] = $_REQUEST['clave2'];}else{$params['gClave2'] = '';}
	if(isset($_REQUEST['idGrupo']) and $_REQUEST['idGrupo'] != ''){$params['gGrupo'] = $_REQUEST['idGrupo'];}else{$params['gGrupo'] = '0';}
	if(isset($_REQUEST['habilitado']) and $_REQUEST['habilitado'] != ''){$params['gHabilitado'] = $_REQUEST['habilitado'];}else{$params['gHabilitado'] = '';}
	if(isset($_REQUEST['guardar']) and $_REQUEST['guardar'] == 'Guardar'){
		if($params['gIdCliente'] == 0 and $params['gClave'] == ''){
			$mensajeAlerta = "Debe ingtresar una contraseña para este cliente.";
		}else{
			if($params['gIdCliente'] == 0){// valida q el mail del usuario nuevo no este en la tabla de clientes ni usuarios (solo para insert, no para edicion)
				$RS_CHK_USUARIOS = mysqli_query($conexion, "SELECT COUNT(*) AS cantMailReg FROM usuarios WHERE mail = '".$params['gMail']."' AND eliminado = '0'");
				$RES_CHK_USUARIOS = mysqli_fetch_object($RS_CHK_USUARIOS);
				$RS_CHK_CLIENTES = mysqli_query($conexion, "SELECT COUNT(*) AS cantMailReg FROM clientes WHERE mail = '".$params['gMail']."' AND eliminado = '0'");
				$RES_CHK_CLIENTES = mysqli_fetch_object($RS_CHK_CLIENTES);
				if($RES_CHK_USUARIOS->cantMailReg == 0 and $RES_CHK_CLIENTES->cantMailReg == 0){
					$_REQUEST['guardar'] == '';
					$idGuardado = $Clientes->guardarCliente($params);
					if($idGuardado == 0){
						$mensajeAlerta = "No se pudo guardar el cliente. Intente nuevamente más tarde.";
					}else{
						header("Location:clientes.php?msj=El cliente se guardó correctamente!");
					}
				}else{
					$mensajeAlerta = "El mail ingresado ya se encuentra registrado.";
				}
			}else{
				$_REQUEST['guardar'] == '';
				$idGuardado = $Clientes->guardarCliente($params);
				if($idGuardado == 0){
					$mensajeAlerta = "No se pudo guardar el cliente. Intente nuevamente más tarde.";
				}else{
					header("Location:clientes.php?msj=El cliente se guardó correctamente!");
				}
			}
		}
	}
	// PARAMETROS ELIMINAR
	if(isset($_REQUEST['eliminar']) and $_REQUEST['eliminar'] != ''){
		if($Clientes->eliminarCliente($_REQUEST['eliminar']) == 1){
			$mensajeAlerta = "El cliente se eliminó correctamente.";
		}else{
			$mensajeAlerta = "No se pudo eliminar el cliente. Intente nuevamente más tarde";
		}
		$_REQUEST['eliminar'] == '';
	}
	
	// CAMBIAR NOMBRE GRUPO
	if(isset($_REQUEST['CambiarNombreGrupo']) and $_REQUEST['CambiarNombreGrupo'] == 1){
		if(mysqli_query($conexion, "UPDATE clientesgrupos SET grupo = '".utf8_decode($_REQUEST['nombreGrupo'])."' WHERE id = '".$_REQUEST['id']."'")){
			$mensajeAlerta = "El nombre del grupo se modificó correctamente.";
		}else{
			$mensajeAlerta = "No se pudo modificar el nombre del grupo. Por favor, intente nuevamente más tarde.";
		}
	}
	
	// ELIMINAR GRUPO
	if(isset($_REQUEST['EliminarGrupo']) and $_REQUEST['EliminarGrupo'] != 0){
		if(mysqli_query($conexion, "UPDATE clientesgrupos SET eliminado = '1' WHERE id = '".$_REQUEST['EliminarGrupo']."'")){
			mysqli_query($conexion, "UPDATE clientes SET idGrupo = '0' WHERE idGrupo = '".$_REQUEST['EliminarGrupo']."'");
			$mensajeAlerta = "El grupo se eliminó correctamente.";
		}else{
			$mensajeAlerta = "No se pudo eliminar el grupo. Por favor, intente nuevamente más tarde.";
		}
	}
	
	// NUEVO GRUPO
	if(isset($_REQUEST['NuevoGrupo']) and $_REQUEST['NuevoGrupo'] == 1){
		if(mysqli_query($conexion, "INSERT INTO clientesgrupos (grupo,eliminado) VALUES ('".utf8_decode($_REQUEST['nombreGrupo'])."','0')")){
			$mensajeAlerta = "El grupo se agregó correctamente.";
		}else{
			$mensajeAlerta = "No se pudo agregar el grupo. Por favor, intente nuevamente más tarde.";
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
	</head>
	<body>
		<?php
			if($mensajeAlerta != ''){
				echo "<script type='text/javascript'>oGen.fnAlert('".$mensajeAlerta."');</script>";
			}
			if(!isset($_REQUEST['editar']) or $_REQUEST['editar'] == ''){
				$Clientes->listadoClientes($params);
			}else{
				echo "<div class='nuevoGrupo'><input type='button' onclick='oClientes.fnAdministrarGrupos();' value='Administrar grupos' /></div>";
				$Clientes->ABMClientes($params);
		?>
			<div class='contGruposClientes'>
				<div class='gruposClientes'>
					<i class='material-icons iconoCerrar' onclick="$('.contGruposClientes').fadeOut('slow');">close</i>
					<div class='titulo'>Listado de grupos:</div>
					<table class='itemGrupo'>
						<tr>
							<td class='col1'><input type='text' id='grupo_0' value='' placeholder='Nuevo grupo' /></td>
							<td class='col2'><i class='material-icons' onclick='oClientes.fnNuevoGrupo();' title='Crear grupo'>save</i></td>
							<td class='col3'><i class='material-icons' style='color:transparent;cursor:default;visibility:hidden;'>delete</i></td>
						</tr>
					</table>
					<?php
						$SQL_GRUPOS = "SELECT * FROM clientesgrupos WHERE eliminado = '0'";
						$RS_GRUPOS = mysqli_query($conexion, $SQL_GRUPOS);
						while($g = mysqli_fetch_object($RS_GRUPOS)){
							echo "<table class='itemGrupo'>";
							echo "	<tr>";
							echo "		<td class='col1'><input type='text' id='grupo_".$g->id."' value='".utf8_encode($g->grupo)."' /></td>";
							echo "		<td class='col2'><i class='material-icons' onclick='oClientes.fnCambiaNombreGrupo(".$g->id.");' title='Cambiar nombre'>save</i></td>";
							echo "		<td class='col3'><i class='material-icons' onclick='oClientes.fnEliminaGrupo(".$g->id.");' title='Eliminar'>delete</i></td>";
							echo "	</tr>";
							echo "</table>";
						}
					?>
				</div>
			</div>
			<script>
				var oClientes = oClientes || {};
				
				oClientes.fnAdministrarGrupos = function(){
					$('.contGruposClientes').fadeIn('slow');
				}
				
				oClientes.fnNuevoGrupo = function(ID){
					var nombreGrupo = $('#grupo_0').val();
					window.location = "clientes.php?editar=<?php echo $_REQUEST['editar']; ?>&NuevoGrupo=1&nombreGrupo="+nombreGrupo;
				}
				
				oClientes.fnCambiaNombreGrupo = function(ID){
					var nombreGrupo = $('#grupo_'+ID).val();
					window.location = "clientes.php?editar=<?php echo $_REQUEST['editar']; ?>&CambiarNombreGrupo=1&nombreGrupo="+nombreGrupo+"&id="+ID;
				}
				
				oClientes.fnEliminaGrupo = function(ID){
					var nombreGrupo = $('#grupo_'+ID).val();
					var texto = "Seguro que desea eliminar el grupo "+nombreGrupo+"?<br/><br/>Los usuarios asignados a este grupo quedaran con el valor default (- Sin grupo -).";
					oGen.fnAlert(texto, "oClientes.fnEliminaGrupoRed(<?php echo $_REQUEST['editar']; ?>,"+ID+")", 1);
				}
				
				oClientes.fnEliminaGrupoRed = function(editar, ID){
					window.location = "clientes.php?editar="+editar+"&EliminarGrupo="+ID;
				}
			</script>
		<?php
			}
		?>
	</body>
</html>