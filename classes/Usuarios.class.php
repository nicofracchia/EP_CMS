<?php

	class Usuarios {
		function listadoUsuarios($params){
			global $conexion;
			
			// VARIABLES PARA FILTROS
			$fNombre = "";
			$fApellido = "";
			$fCelular = "";
			$fMail = "";
			$fTipoAdmin = "";
			$fTipoRedactor = "";
			$fEstadoHabilitado = "";
			$fEstadoDeshabilitado = "";
			
			if(isset($params['fNombre'])){$fNombre = $params['fNombre'];}
			if(isset($params['fApellido'])){$fApellido = $params['fApellido'];}
			if(isset($params['fCelular'])){$fCelular = $params['fCelular'];}
			if(isset($params['fMail'])){$fMail = $params['fMail'];}
			if(isset($params['fTipo'])){
				if($params['fTipo'] == 1){
					$fTipoAdmin = 'selected';
				}
				if($params['fTipo'] == 0){
					$fTipoRedactor = 'selected';
				}
			}
			if(isset($params['fEstado'])){
				if($params['fEstado'] == 1){
					$fEstadoHabilitado = 'selected';
				}
				if($params['fEstado'] == 0){
					$fEstadoDeshabilitado = 'selected';
				}
			}
			
			// TABLA - ENCABEZADOS - FILTROS
			$HTML  = "<table class='tablaListados'>";
			$HTML .= "	<tr class='encabezados'>";
			$HTML .= "		<th>Nombre</th>";
			$HTML .= "		<th>Apellido</th>";
			$HTML .= "		<th>Mail</th>";
			$HTML .= "		<th>Tipo</th>";
			$HTML .= "		<th>Estado</th>";
			$HTML .= "		<th><input type='button' value='Nuevo Usuario' class='botonNaranja' onclick='oGen.fnRedireccionEditar(\"usuarios\",0);' /></th>";
			$HTML .= "	</tr>";
			$HTML .= "	<form action='usuarios.php' method='post'>";
			$HTML .= "	<tr class='filtros'>";
			$HTML .= "		<td><input type='text' name='fNombre' id='fNombre' class='inputFiltros' placeholder='Nombre' value='".$fNombre."' /></td>";
			$HTML .= "		<td><input type='text' name='fApellido' id='fApellido' class='inputFiltros' placeholder='Apellido' value='".$fApellido."' /></td>";
			$HTML .= "		<td><input type='text' name='fMail' id='fMail' class='inputFiltros' placeholder='Mail' value='".$fMail."' /></td>";
			$HTML .= "		<td><select name='fTipo' id='fTipo'><option value=''>Tipo</option><option value=1 ".$fTipoAdmin.">Administrador</option><option value=0 ".$fTipoRedactor.">Redactor</option></select></td>";
			$HTML .= "		<td><select name='fEstado' id='fEstado'><option value=''>Estado</option><option value=1 ".$fEstadoHabilitado.">Habilitado</option><option value=0 ".$fEstadoDeshabilitado.">Deshabilitado</option></select></td>";
			$HTML .= "		<td><input type='submit' value='Aplicar Filtros' class='botonNaranja' /></td>";
			$HTML .= "	</tr>";
			$HTML .= "	</form>";
			
			// CONTENIDO
			$FILTROS_SQL = "WHERE eliminado = '0'";
			if(isset($params['fNombre'])){$FILTROS_SQL .= " AND nombre LIKE '%".$params['fNombre']."%' ";}
			if(isset($params['fApellido'])){$FILTROS_SQL .= " AND apellido LIKE '%".$params['fApellido']."%' ";}
			if(isset($params['fCelular'])){$FILTROS_SQL .= " AND celular LIKE '%".$params['fCelular']."%' ";}
			if(isset($params['fMail'])){$FILTROS_SQL .= " AND mail LIKE '%".$params['fMail']."%' ";}
			if(isset($params['fTipo'])){$FILTROS_SQL .= " AND tipo = '".$params['fTipo']."' ";}
			if(isset($params['fEstado'])){$FILTROS_SQL .= " AND habilitado = '".$params['fEstado']."' ";}
			$SQL_LISTADO = "SELECT * FROM usuarios ".$FILTROS_SQL." ORDER BY nombre ASC";
			$RS_LISTADO = mysqli_query($conexion, $SQL_LISTADO);
			while($u = mysqli_fetch_object($RS_LISTADO)){
				if($u->tipo == 1){$tipo = 'Administrador';}else{$tipo = 'Redactor';}
				if($u->habilitado == 1){$estado = 'Habilitado';}else{$estado = 'Deshabilitado';}
				$HTML .= "	<tr class='usuario'>";
				$HTML .= "		<td class='usuarioNombre'>".$u->nombre."</td>";
				$HTML .= "		<td class='usuarioApellido'>".$u->apellido."</td>";
				$HTML .= "		<td class='usuarioMail'>".$u->mail."</td>";
				$HTML .= "		<td class='usuarioTipo'>".$tipo."</td>";
				$HTML .= "		<td class='usuarioEstado'>".$estado."</td>";
				$HTML .= "		<td class='acciones'>";
				$HTML .= "			<i class='material-icons' title='Editar' onclick='oGen.fnRedireccionEditar(\"usuarios\",".$u->id.")'>mode_edit</i>";
				$HTML .= "			<i class='material-icons' title='Eliminar' onclick=\"oGen.fnAlert('Seguro que desea eliminar el usuario?','oGen.fnEliminarUsuario(".$u->id.")',1)\">delete</i>";
				$HTML .= "		</td>";
				$HTML .= "	</tr>";
			}
			
			// CIERRE
			$HTML .= "</table>";
			echo utf8_encode($HTML);
		}
		
		function ABMUsuarios($params){
			global $conexion;
			
			$nombre = "";
			$apellido = "";
			$celular = "";
			$mail = "";
			$clave = "";
			$tipo = "0";
			$habilitado = "1";
			if($params['idEdicion'] != '0'){
				$SQL_USUARIO = "SELECT * FROM usuarios WHERE id = '".$params['idEdicion']."'";
				$RS_USUARIO = mysqli_query($conexion, $SQL_USUARIO);
				$U = mysqli_fetch_object($RS_USUARIO);
				
				$nombre = $U->nombre;
				$apellido = $U->apellido;
				$celular = $U->celular;
				$mail = $U->mail;
				$clave = $U->clave;
				$tipo = $U->tipo;
				$habilitado = $U->habilitado;
			}
			$HTML  = "<form action='usuarios.php?editar=".$params['idEdicion']."' method='post'>";
			$HTML  .= "	<input type='hidden' name='idUsuario' value='".$params['idEdicion']."' />";
			$HTML  .= "<table class='tablaEditar'>";
			$HTML .= "	<tr>";
			$HTML .= "		<td class='input'><input type='text' name='nombre' placeholder='Nombre' value='".$nombre."' /></td>";
			$HTML .= "	</tr>";
			$HTML .= "	<tr>";
			$HTML .= "		<td class='input'><input type='text' name='apellido' placeholder='Apellido' value='".$apellido."' /></td>";
			$HTML .= "	</tr>";
			$HTML .= "	<tr>";
			$HTML .= "		<td class='input'><input type='text' name='mail' placeholder='Mail' value='".$mail."' /></td>";
			$HTML .= "	</tr>";
			$HTML .= "	<tr>";
			$HTML .= "		<td class='input'><input type='text' name='clave' placeholder='Contrase&ntilde;a' value='".$clave."' /></td>";
			$HTML .= "	</tr>";
			//$HTML .= "	<tr>";
			//$HTML .= "		<td class='input'><input type='password' name='clave2' placeholder='Volver a escribir Contrase&ntilde;a' value='' /></td>";
			//$HTML .= "	</tr>";
			$HTML .= "	<tr>";
			$HTML .= "		<td class='input'>";
			$HTML .= "			<select name='tipo'>";
			if($tipo == '1'){$tAdm = ' selected'; $tRed = '';}else{$tAdm = ''; $tRed = ' selected';}
			$HTML .= "				<option value='1' ".$tAdm.">Administrador</option>";
			$HTML .= "				<option value='0' ".$tRed.">Redactor</option>";
			$HTML .= "			</select>";
			$HTML .= "		</td>";
			$HTML .= "	</tr>";
			$HTML .= "	<tr>";
			$HTML .= "		<td class='input'>";
			$HTML .= "			<select name='habilitado'>";
			if($habilitado == '1'){$hHab = ' selected'; $hDes = '';}else{$hHab = ''; $hDes = ' selected';}
			$HTML .= "				<option value='1' ".$hHab.">Habilitado</option>";
			$HTML .= "				<option value='0' ".$hDes.">Deshabilitado</option>";
			$HTML .= "			</select>";
			$HTML .= "		</td>";
			$HTML .= "	</tr>";
			$HTML .= "	<tr>";
			$HTML .= "		<td class='input' colspan=2>";
			$HTML .= "			<input type='button' class='botonCancelar' value='Volver' onclick='window.location=\"usuarios.php\";' />";
			$HTML .= "			<input type='submit' class='botonGuardar' name='guardar' value='Guardar' />";
			$HTML .= "			<input type='button' class='botonEliminar' value='Eliminar' onclick='window.history.back();' />";
			$HTML .= "		</td>";
			$HTML .= "	</tr>";
			$HTML .= "</table>";
			$HTML .= "</form>";
			
			echo utf8_encode($HTML);
		}
		
		function guardarUsuario($params){
			global $conexion;
			
			if($params['gIdUsuario'] == 0){
				$SQL  = "INSERT INTO usuarios (nombre, apellido, celular, mail, tipo, clave, habilitado) VALUES ('".$params['gNombre']."', '".$params['gApellido']."', '".$params['gCelular']."', '".$params['gMail']."', '".$params['gTipo']."', '".$params['gClave']."', '".$params['gHabilitado']."')";
			}else{
				$SQL  = "UPDATE usuarios SET ";
				$SQL .= " nombre = '".$params['gNombre']."', ";
				$SQL .= " apellido = '".$params['gApellido']."', ";
				$SQL .= " celular = '".$params['gCelular']."', ";
				$SQL .= " mail = '".$params['gMail']."', ";
				$SQL .= " clave = '".$params['gClave']."', ";
				$SQL .= " tipo = '".$params['gTipo']."', ";
				$SQL .= " habilitado = '".$params['gHabilitado']."' ";
				$SQL .= " WHERE id = '".$params['gIdUsuario']."'";
			}
			
			if(mysqli_query($conexion, utf8_decode($SQL))){
				if($params['gIdUsuario'] == 0){
					$ID = mysqli_fetch_object(mysqli_query($conexion, "SELECT MAX(id) AS nuevoId FROM usuarios"));
					$ID = $ID->nuevoId;
				}else{
					$ID = $params['gIdUsuario'];
				}
				return $ID;
			}else{
				return 0;
			}
		}
		
		function eliminarUsuario($ID){
			global $conexion;
			
			if(mysqli_query($conexion, "UPDATE usuarios SET eliminado = '1' WHERE id = '".$ID."'")){
				return 1;
			}else{
				return 0;
			}
		}
	}