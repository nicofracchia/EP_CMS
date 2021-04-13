<?php

	class Clientes {
		function listadoClientes($params){
			global $conexion;
			
			// VARIABLES PARA FILTROS
			$fNombre = "";
			$fApellido = "";
			$fCelular = "";
			$fMail = "";
			$fGrupo = "";
			$fEstadoHabilitado = "";
			$fEstadoDeshabilitado = "";
			
			if(isset($params['fNombre'])){$fNombre = $params['fNombre'];}
			if(isset($params['fApellido'])){$fApellido = $params['fApellido'];}
			if(isset($params['fCelular'])){$fCelular = $params['fCelular'];}
			if(isset($params['fGrupo'])){$fGrupo = $params['fGrupo'];}
			if(isset($params['fMail'])){$fMail = $params['fMail'];}
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
			$HTML .= "		<th>Celular</th>";
			$HTML .= "		<th>Mail</th>";
			$HTML .= "		<th>Grupo</th>";
			$HTML .= "		<th>Estado</th>";
			$HTML .= "		<th><input type='button' value='Nuevo Cliente' class='botonNaranja' onclick='oGen.fnRedireccionEditar(\"clientes\",0);' /></th>";
			$HTML .= "	</tr>";
			$HTML .= "	<form action='clientes.php' method='post'>";
			$HTML .= "	<tr class='filtros'>";
			$HTML .= "		<td><input type='text' name='fNombre' id='fNombre' class='inputFiltros' placeholder='Nombre' value='".$fNombre."' /></td>";
			$HTML .= "		<td><input type='text' name='fApellido' id='fApellido' class='inputFiltros' placeholder='Apellido' value='".$fApellido."' /></td>";
			$HTML .= "		<td><input type='text' name='fCelular' id='fCelular' class='inputFiltros' placeholder='Celular' value='".$fCelular."' /></td>";
			$HTML .= "		<td><input type='text' name='fMail' id='fMail' class='inputFiltros' placeholder='Mail' value='".$fMail."' /></td>";
			$HTML .= "		<td>";
			$HTML .= "			<select name='fGrupo' id='fGrupo'>";
			$HTML .= "				<option value=''>Todos los grupos</option>";
			if($fGrupo == '0'){$HTML .= "<option value='0' selected>- Sin grupo -</option>";}else{$HTML .= "<option value='0'>- Sin grupo -</option>";}
			$RS_GRUPOS_CLIENTES = mysqli_query($conexion, "SELECT * FROM clientesgrupos WHERE eliminado = '0'");
			while($g = mysqli_fetch_object($RS_GRUPOS_CLIENTES)){
				$selGr = '';
				if($g->id == $fGrupo){$selGr = ' selected';}
				$HTML .= "			<option value='".$g->id."' ".$selGr.">".$g->grupo."</option>";
			}
			$HTML .= "			</select>";
			$HTML .= "		</td>";
			$HTML .= "		<td><select name='fEstado' id='fEstado'><option value=''>Estado</option><option value=1 ".$fEstadoHabilitado.">Habilitado</option><option value=0 ".$fEstadoDeshabilitado.">Deshabilitado</option></select></td>";
			$HTML .= "		<td><input type='submit' value='Aplicar Filtros' class='botonNaranja' /></td>";
			$HTML .= "	</tr>";
			$HTML .= "	</form>";
			
			// CONTENIDO
			$FILTROS_SQL = "WHERE c.eliminado = '0'";
			if(isset($params['fNombre'])){$FILTROS_SQL .= " AND c.nombre LIKE '%".$params['fNombre']."%' ";}
			if(isset($params['fApellido'])){$FILTROS_SQL .= " AND c.apellido LIKE '%".$params['fApellido']."%' ";}
			if(isset($params['fCelular'])){$FILTROS_SQL .= " AND c.celular LIKE '%".$params['fCelular']."%' ";}
			if(isset($params['fMail'])){$FILTROS_SQL .= " AND c.mail LIKE '%".$params['fMail']."%' ";}
			if(isset($params['fGrupo'])){$FILTROS_SQL .= " AND c.idGrupo = '".$params['fGrupo']."' ";}
			if(isset($params['fEstado'])){$FILTROS_SQL .= " AND c.habilitado = '".$params['fEstado']."' ";}
			$JOIN = " LEFT JOIN clientesgrupos AS cg ON c.idGrupo = cg.id ";
			$SQL_LISTADO = "SELECT c.*, cg.grupo FROM clientes AS c ".$JOIN.$FILTROS_SQL." ORDER BY c.nombre ASC";
			$RS_LISTADO = mysqli_query($conexion, $SQL_LISTADO);
			while($c = mysqli_fetch_object($RS_LISTADO)){
				if($c->grupo == ''){$grupo = '- Sin grupo -';}else{$grupo = $c->grupo;}
				if($c->habilitado == 1){$estado = 'Habilitado';}else{$estado = 'Deshabilitado';}
				$SQL_NOTICIASCOMPARTIDAS = "SELECT imagen, titulo, tema, fecha FROM noticias WHERE usuarios LIKE '%|".$c->id."|%' AND eliminada = 0 ORDER BY fecha DESC";
				$RS_NOTICIASCOMPARTIDAS = mysqli_query($conexion, $SQL_NOTICIASCOMPARTIDAS);
				$SQL_NOTICIASPUSH = "SELECT n.imagen, n.titulo, n.tema, p.fecha FROM clientespush AS p INNER JOIN noticias AS n ON p.idNoticia = n.id WHERE n.eliminada = '0' AND p.idCliente = '".$c->id."' ORDER BY fecha DESC";
				$RS_NOTICIASPUSH = mysqli_query($conexion, $SQL_NOTICIASPUSH);
				$HTML .= "	<tr class='cliente'>";
				$HTML .= "		<td class='clienteNombre'>".$c->nombre."</td>";
				$HTML .= "		<td class='clienteApellido'>".$c->apellido."</td>";
				$HTML .= "		<td class='clienteCelular'>".$c->telefono."</td>";
				$HTML .= "		<td class='clienteMail'>".$c->mail."</td>";
				$HTML .= "		<td class='clienteGrupo'>".$grupo."</td>";
				$HTML .= "		<td class='clienteEstado'>".$estado."</td>";
				$HTML .= "		<td class='acciones'>";
				$next_segun_NC = '';
				if(mysqli_num_rows($RS_NOTICIASCOMPARTIDAS) > 0){
					$HTML .= "			<i class='material-icons' title='Ver noticias enviadas' onclick=\"$(this).parent().parent().next().toggle('fast');\">share</i>";
					$next_segun_NC = '.next()';
				}
				if(mysqli_num_rows($RS_NOTICIASPUSH) > 0){
					$HTML .= "			<i class='material-icons' title='Ver notificaciones enviadas' onclick=\"$(this).parent().parent()".$next_segun_NC.".next().toggle('fast');\">notifications</i>";
				}
				$HTML .= "			<i class='material-icons' title='Editar' onclick='oGen.fnRedireccionEditar(\"clientes\",".$c->id.")'>mode_edit</i>";
				$HTML .= "			<i class='material-icons' title='Eliminar' onclick=\"oGen.fnAlert('Seguro que desea eliminar el cliente?','oGen.fnEliminarCliente(".$c->id.")',1)\">delete</i>";
				$HTML .= "		</td>";
				$HTML .= "	</tr>";
				if(mysqli_num_rows($RS_NOTICIASCOMPARTIDAS) > 0){
					$HTML .= "	<tr class='cliente_noticias' style='display:none;'>";
					$HTML .= "		<td colspan=5 style='border:solid 1px #CCC; border-radius:15px; padding:20px;'>";
					$HTML .= "			<table style='width:100%;'>";
					while($nc = mysqli_fetch_object($RS_NOTICIASCOMPARTIDAS)){
						$HTML .= "				<tr>";
						$HTML .= "					<td><img src='".$nc->imagen."' style='width:50px;' alt='' /></td>";
						$HTML .= "					<td>".$nc->titulo."</td>";
						$HTML .= "					<td>".$nc->tema."</td>";
						$HTML .= "					<td>".invierteFecha($nc->fecha)."</td>";
						$HTML .= "				</tr>";
					}
					$HTML .= "			</table>";
					$HTML .= "		</td>";
					$HTML .= "		<td></td>";
					$HTML .= "	</tr>";
				}
				if(mysqli_num_rows($RS_NOTICIASPUSH) > 0){
					$HTML .= "	<tr class='cliente_noticias' style='display:none;'>";
					$HTML .= "		<td colspan=5 style='border:solid 1px #CCC; border-radius:15px; padding:20px;'>";
					$HTML .= "			<table style='width:100%;'>";
					while($np = mysqli_fetch_object($RS_NOTICIASPUSH)){
						$HTML .= "				<tr>";
						$HTML .= "					<td><img src='".$np->imagen."' style='width:50px;' alt='' /></td>";
						$HTML .= "					<td>".$np->titulo."</td>";
						$HTML .= "					<td>".$np->tema."</td>";
						$HTML .= "					<td>".invierteFecha(substr($np->fecha, 0, 10))."</td>";
						$HTML .= "				</tr>";
					}
					$HTML .= "			</table>";
					$HTML .= "		</td>";
					$HTML .= "		<td></td>";
					$HTML .= "	</tr>";
				}
			}
			
			// CIERRE
			$HTML .= "</table>";
			echo utf8_encode($HTML);
		}
		
		function ABMClientes($params){
			global $conexion;
			
			$nombre = "";
			$apellido = "";
			$celular = "";
			$mail = "";
			$grupo = "";
			$clave = "";
			$habilitado = "1";
			if($params['idEdicion'] != '0'){
				$SQL_CLIENTE = "SELECT * FROM clientes WHERE id = '".$params['idEdicion']."'";
				$RS_CLIENTE = mysqli_query($conexion, $SQL_CLIENTE);
				$U = mysqli_fetch_object($RS_CLIENTE);
				
				$nombre = $U->nombre;
				$apellido = $U->apellido;
				$celular = $U->telefono;
				$mail = $U->mail;
				$grupo = $U->idGrupo;
				$clave = $U->clave;
				$habilitado = $U->habilitado;
			}
			$HTML  = "<form action='clientes.php?editar=".$params['idEdicion']."' method='post'>";
			$HTML  .= "	<input type='hidden' name='idCliente' value='".$params['idEdicion']."' />";
			$HTML  .= "<table class='tablaEditar'>";
			$HTML .= "	<tr>";
			$HTML .= "		<td class='input'><input type='text' name='nombre' placeholder='Nombre' value='".$nombre."' /></td>";
			$HTML .= "	</tr>";
			$HTML .= "	<tr>";
			$HTML .= "		<td class='input'><input type='text' name='apellido' placeholder='Apellido' value='".$apellido."' /></td>";
			$HTML .= "	</tr>";
			$HTML .= "	<tr>";
			$HTML .= "		<td class='input'><input type='text' name='celular' placeholder='Celular' value='".$celular."' /></td>";
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
			$HTML .= "			<select name='idGrupo'>";
			$HTML .= "				<option value='0'>- Sin grupo -</option>";
			$RS_GRUPOS = mysqli_query($conexion, "SELECT * FROM clientesgrupos WHERE eliminado ='0'");
			while($g = mysqli_fetch_object($RS_GRUPOS)){
				$selGr = '';
				if($g->id == $grupo){$selGr = ' selected';}
				$HTML .= "			<option value='".$g->id."' ".$selGr.">".$g->grupo."</option>";
			}
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
			$HTML .= "			<input type='button' class='botonCancelar' value='Volver' onclick='window.location=\"clientes.php\";' />";
			$HTML .= "			<input type='submit' class='botonGuardar' name='guardar' value='Guardar' />";
			$HTML .= "			<input type='button' class='botonEliminar' value='Eliminar' onclick='window.history.back();' />";
			$HTML .= "		</td>";
			$HTML .= "	</tr>";
			$HTML .= "</table>";
			$HTML .= "</form>";
			
			echo utf8_encode($HTML);
		}
		
		function guardarCliente($params){
			global $conexion;
			
			if($params['gIdCliente'] == 0){
				$SQL  = "INSERT INTO clientes (nombre, apellido, telefono, mail, clave, idGrupo, habilitado) VALUES ('".$params['gNombre']."', '".$params['gApellido']."', '".$params['gCelular']."', '".$params['gMail']."', '".$params['gClave']."', '".$params['gGrupo']."', '".$params['ghabilitado']."')";
			}else{
				$SQL  = "UPDATE clientes SET ";
				$SQL .= " nombre = '".$params['gNombre']."', ";
				$SQL .= " apellido = '".$params['gApellido']."', ";
				$SQL .= " telefono = '".$params['gCelular']."', ";
				$SQL .= " mail = '".$params['gMail']."', ";
				$SQL .= " clave = '".$params['gClave']."', ";
				$SQL .= " idGrupo = '".$params['gGrupo']."', ";
				$SQL .= " habilitado = '".$params['gHabilitado']."' ";
				$SQL .= " WHERE id = '".$params['gIdCliente']."'";
			}
			
			if(mysqli_query($conexion, utf8_decode($SQL))){
				if($params['gIdCliente'] == 0){
					$ID = mysqli_fetch_object(mysqli_query($conexion, "SELECT MAX(id) AS nuevoId FROM clientes"));
					$ID = $ID->nuevoId;
				}else{
					$ID = $params['gIdCliente'];
				}
				return $ID;
			}else{
				return 0;
			}
		}
		
		function eliminarCliente($ID){
			global $conexion;
			
			if(mysqli_query($conexion, "UPDATE clientes SET eliminado = '1' WHERE id = '".$ID."'")){
				return 1;
			}else{
				return 0;
			}
		}
	}