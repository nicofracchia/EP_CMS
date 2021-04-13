<?php

	class Noticias {
		function listadoNoticias($params){
			global $conexion;
			
			// VARIABLES PARA FILTROS
			$fTitulo = "";
			$fTema = "";
			$fLegislatura = "";
			$fFechaDesde = "";
			$fFechaHasta = "";
			$fTipoPublica = "";
			$fTipoPrivada = "";
			$fEstadoPublicada = "";
			$fEstadoDesarrollo = "";
			
			if(isset($params['fTitulo'])){$fTitulo = $params['fTitulo'];}
			if(isset($params['fTema'])){$fTema = $params['fTema'];}
			if(isset($params['fLegislatura'])){$fLegislatura = $params['fLegislatura'];}
			if(isset($params['fFechaDesde'])){$fFechaDesde = $params['fFechaDesde'];}
			if(isset($params['fFechaHasta'])){$fFechaHasta = $params['fFechaHasta'];}
			if(isset($params['fTipo'])){
				if($params['fTipo'] == 1){
					$fTipoPublica = 'selected';
				}
				if($params['fTipo'] == 2){
					$fTipoPrivada = 'selected';
				}
			}
			if(isset($params['fEstado'])){
				if($params['fEstado'] == 1){
					$fEstadoPublicada = 'selected';
				}
				if($params['fEstado'] == 2){
					$fEstadoDesarrollo = 'selected';
				}
			}
			
			// TABLA - ENCABEZADOS - FILTROS
			$HTML  = "<table class='tablaListados'>";
			$HTML .= "	<tr class='encabezados'>";
			$HTML .= "		<th>Orden</th>";
			$HTML .= "		<th>Imágen</th>";
			$HTML .= "		<th>Título</th>";
			$HTML .= "		<th>Tema</th>";
			$HTML .= "		<th>Distrito</th>";
			$HTML .= "		<th>Fecha</th>";
			$HTML .= "		<th>Tipo</th>";
			$HTML .= "		<th>Estado</th>";
			$HTML .= "		<th><input type='button' class='botonNaranja' value='Nueva Noticia' onclick='oGen.fnRedireccionEditar(\"noticias\",0);' /></th>";
			$HTML .= "	</tr>";
			$HTML .= "	<form action='noticias.php' method='post'>";
			$HTML .= "	<tr class='filtros'>";
			$HTML .= "		<td></td>";
			$HTML .= "		<td></td>";
			$HTML .= "		<td><input type='text' name='fTitulo' id='fTitulo' class='inputFiltros' placeholder='Título' value='".$fTitulo."' /></td>";
			$HTML .= "		<td><input type='text' name='fTema' id='fTema' class='inputFiltros' placeholder='Tema' value='".$fTema."' /></td>";
			$HTML .= "		<td><select name='fLegislatura' id='fLegislatura'><option value='0'>Legislaturas</option>".utf8_encode(listadoLegislaturasOption($fLegislatura))."</select></td>";
			$HTML .= "		<td>";
			$HTML .= "			<input type='text' name='fFechaDesde' id='fFechaDesde' class='inputFiltros calendario' placeholder='Fecha desde' value='".$fFechaDesde."' />";
			$HTML .= "			<input type='text' name='fFechaHasta' id='fFechaHasta' class='inputFiltros calendario' placeholder='Fecha hasta' value='".$fFechaHasta."' />";
			$HTML .= "		</td>";
			$HTML .= "		<td><select name='fTipo' id='fTipo'><option value='0'>Tipo</option><option value=1 ".$fTipoPublica.">Pública</option><option value=2 ".$fTipoPrivada.">Privada</option></select></td>";
			$HTML .= "		<td><select name='fEstado' id='fEstado'><option value='0'>Estado</option><option value=1 ".$fEstadoPublicada.">Publicada</option><option value=2 ".$fEstadoDesarrollo.">En desarrollo</option></select></td>";
			$HTML .= "		<td><input type='submit' value='Aplicar Filtros' class='botonNaranja' /></td>";
			$HTML .= "	</tr>";
			$HTML .= "	</form>";
			
			// CONTENIDO
			$FILTROS_SQL = "WHERE eliminada = '0'";
			if(isset($params['fTitulo'])){$FILTROS_SQL .= " AND binary convert(titulo using utf8) LIKE '%".$params['fTitulo']."%' ";}
			if(isset($params['fTema'])){$FILTROS_SQL .= " AND binary convert(tema using utf8) LIKE '%".$params['fTema']."%' ";}
			if(isset($params['fLegislatura']) and $params['fLegislatura'] != '0'){$FILTROS_SQL .= " AND legislaturas LIKE '%|".$params['fLegislatura']."|%' ";}
			if(isset($params['fFechaDesde'])){$FILTROS_SQL .= " AND fecha >= '".invierteFecha($params['fFechaDesde'])."' ";}
			if(isset($params['fFechaHasta'])){$FILTROS_SQL .= " AND fecha <= '".invierteFecha($params['fFechaHasta'])."' ";}
			if(isset($params['fTipo'])){$FILTROS_SQL .= " AND tipo = '".$params['fTipo']."' ";}
			if(isset($params['fEstado'])){$FILTROS_SQL .= " AND status = '".$params['fEstado']."' ";}
			$SQL_LISTADO = "SELECT * FROM noticias ".$FILTROS_SQL." ORDER BY fecha DESC, -orden DESC LIMIT 0, 50";
			$RS_LISTADO = mysqli_query($conexion, $SQL_LISTADO);
			while($n = mysqli_fetch_object($RS_LISTADO)){
				$txtLegislaturas = "";
				
				foreach(explode('|', $n->legislaturas) as $l){
					if($l != ''){
						$SQL_LEGISLATURAS = "SELECT legislatura FROM legislaturas WHERE id = '".$l."'";
						$RS_LEGISLATURAS = mysqli_query($conexion, $SQL_LEGISLATURAS);
						if(mysqli_num_rows($RS_LEGISLATURAS) > 0){
							$res = mysqli_fetch_object($RS_LEGISLATURAS);
							if($txtLegislaturas == ''){
								$txtLegislaturas .= $res->legislatura;
							}else{
								$txtLegislaturas .= " / ".$res->legislatura;
							}
						}
					}
				}
				
				if($n->tipo == 1){$tipo = 'PÚBLICA';}else{$tipo = 'PRIVADA';}
				if($n->status == 1){$estado = 'PUBLICADA';}else{$estado = 'DESARROLLO';}
				$HTML .= "	<tr class='noticia'>";
				$HTML .= "		<td class='ordenNoticia'>";
				$HTML .= "			<form action='noticias.php' method='post'>";
				$HTML .= "				<input type='hidden' name='accion' value='guardaOrden' />";
				$HTML .= "				<input type='hidden' name='ID' value='".$n->id."' />";
				$HTML .= "				<input type='text' name='orden' value='".$n->orden."' placeholder='Orden' />";
				$HTML .= "				<input type='submit' name='guardaOrden' value='Guardar' class='botonNaranja' />";
				$HTML .= "			</form>";
				$HTML .= "		</td>";
				$HTML .= "		<td class='noticiaImagen'><img src='".$n->imagen."' alt='' class='imagenListadoNoticias' /></td>";
				$HTML .= "		<td class='noticiaTitulo'><b>".utf8_encode($n->titulo)."</b></td>";
				$HTML .= "		<td class='noticiaTitulo'>".utf8_encode($n->tema)."</td>";
				$HTML .= "		<td class='noticiaTitulo'>".utf8_encode($txtLegislaturas)."</td>";
				$HTML .= "		<td class='noticiaFecha'>".invierteFecha($n->fecha)."</td>";
				$HTML .= "		<td class='noticiaTipo'>".$tipo."</td>";
				$HTML .= "		<td class='noticiaEstado'>".$estado."</td>";
				$HTML .= "		<td class='acciones'>";
				$HTML .= "			<i class='material-icons' title='Editar' onclick='oGen.fnRedireccionEditar(\"noticias\",".$n->id.")'>mode_edit</i>";
				$HTML .= "			<i class='material-icons' title='Eliminar' onclick=\"oGen.fnAlert('Seguro que desea eliminar la noticia?','oGen.fnEliminarNoticia(".$n->id.")',1)\">delete</i>";
				$HTML .= "		</td>";
				$HTML .= "	</tr>";
			}
			
			// CIERRE
			$HTML .= "</table>";
			echo $HTML;
		}
		
		function ABMNoticias($params){
			global $conexion;
			
			$fecha = date('d-m-Y');
			$titulo = "";
			$tema = "";
			$imagen = "";
			$texto = "";
			$secciones = Array();
			$personas = "";
			$distrito = "";
			$legislaturas = Array();
			$tipo = "";
			$clientes = Array();
			$estado = "";
			$keywords = "";
			if($params['idEdicion'] != '0'){
				$SQL_NOTICIA = "SELECT * FROM noticias WHERE id = '".$params['idEdicion']."'";
				$RS_NOTICIA = mysqli_query($conexion, $SQL_NOTICIA);
				$N = mysqli_fetch_object($RS_NOTICIA);
				
				$fecha = invierteFecha($N->fecha);
				$titulo = $N->titulo;
				$tema = $N->tema;
				$imagen = $N->imagen;
				$texto = $N->texto;
				$secciones = explode('|',$N->secciones);
				$personas = $N->personas;
				$distrito = $N->distrito;
				$legislaturas = explode('|',$N->legislaturas);
				$tipo = $N->tipo;
				$clientes = explode('|',$N->usuarios);
				$estado = $N->status;
				$keywords = $N->keywords;
			}
			
			$SQL_SECCIONES = "SELECT * FROM secciones";
			$RS_SECCIONES = mysqli_query($conexion, $SQL_SECCIONES);
			$HTML_SECCIONES = "";
			$i = 0;
			while($s = mysqli_fetch_object($RS_SECCIONES)){
				$checked = '';
				if(in_array($s->id, $secciones)){$checked = ' checked';}
				if($i == 0){$HTML_SECCIONES .= "<tr>";}
				$HTML_SECCIONES .= "<td><input type='checkbox' name='secciones[]' value='".$s->id."' id='seccion_".$s->id."' ".$checked." /><label for='seccion_".$s->id."'>".$s->seccion."</label></td>";
				if($i == 1){$HTML_SECCIONES .= "<tr>";}
				$i++;
				if($i == 2){$i=0;}
			}
			$SQL_LEGISLATURAS = "SELECT * FROM legislaturas";
			$RS_LEGISLATURAS = mysqli_query($conexion, $SQL_LEGISLATURAS);
			$HTML_LEGISLATURAS = "";
			$i = 0;
			while($s = mysqli_fetch_object($RS_LEGISLATURAS)){
				$checked = '';
				if(in_array($s->id, $legislaturas)){$checked = ' checked';}
				if($i == 0){$HTML_LEGISLATURAS .= "<tr>";}
				$HTML_LEGISLATURAS .= "<td><input type='checkbox' name='legislaturas[]' value='".$s->id."' id='legislatura_".$s->id."' ".$checked." /><label for='legislatura_".$s->id."'>".$s->legislatura."</label></td>";
				if($i == 1){$HTML_LEGISLATURAS .= "<tr>";}
				$i++;
				if($i == 2){$i=0;}
			}
			$SQL_GRUPOSCLIENTES = "SELECT * FROM clientesgrupos WHERE eliminado = '0' ORDER BY grupo";
			$RS_GRUPOSCLIENTES = mysqli_query($conexion, $SQL_GRUPOSCLIENTES);
			$i = 0;
			$HTML_CLIENTES = '';
			$HTML_CLIENTES_PUSH = '';
			$muestraClientes = "style='display:none;'";
			$muestraClientesPush = "style='display:none;'";
			while($g = mysqli_fetch_object($RS_GRUPOSCLIENTES)){
				if($i == 0){$HTML_CLIENTES .= "<tr>";$HTML_CLIENTES_PUSH .= "<tr>";}
				$HTML_CLIENTES .= "<td style='padding:10px;padding-left:20px;vertical-align:top;'>";
				$HTML_CLIENTES .= "	<input type='checkbox' class='chkGrupoClientes' id='grupoClientes_".$g->id."' style='margin-left:-11px;' onchange='fnCambiaCheckGrupoClientes(this);' />";
				$HTML_CLIENTES .= "	<label for='grupoClientes_".$g->id."' style='color:#e93400;'>".$g->grupo."</label><br/>";
				$HTML_CLIENTES_PUSH .= "<td style='padding:10px;padding-left:20px;vertical-align:top;'>";
				$HTML_CLIENTES_PUSH .= "	<input type='checkbox' class='chkGrupoClientesPush' id='grupoClientesPush_".$g->id."' style='margin-left:-11px;' onchange='fnCambiaCheckGrupoClientesPush(this);' />";
				$HTML_CLIENTES_PUSH .= "	<label for='grupoClientesPush_".$g->id."' style='color:#e93400;'>".$g->grupo."</label><br/>";
				
				$SQL_CLIENTES = "SELECT * FROM clientes WHERE habilitado = '1' AND eliminado = '0' AND idGrupo = '".$g->id."' ORDER BY apellido ASC, nombre ASC";
				$RS_CLIENTES = mysqli_query($conexion, $SQL_CLIENTES);
				while($s = mysqli_fetch_object($RS_CLIENTES)){
					$checked = '';
					if(in_array($s->id, $clientes)){$checked = ' checked';$muestraClientes = "";}
					$HTML_CLIENTES .= "<input type='checkbox' name='clientes[]' value='".$s->id."' id='cliente_".$s->id."' ".$checked." onchange='fnValidaChkGrupal(this);' />";
					$HTML_CLIENTES .= "<label for='cliente_".$s->id."'>".$s->apellido.", ".$s->nombre."</label><br/>";
				}
				
				$SQL_CLIENTES_PUSH = "SELECT c.* FROM clientes AS c INNER JOIN tokens AS t ON c.id = t.idCliente WHERE c.habilitado = '1' AND c.eliminado = '0' AND c.idGrupo = '".$g->id."' AND t.permisos = '0' GROUP BY c.id ORDER BY c.apellido ASC, c.nombre ASC";
				$RS_CLIENTES_PUSH = mysqli_query($conexion, $SQL_CLIENTES_PUSH);
				while($s = mysqli_fetch_object($RS_CLIENTES_PUSH)){
					$checked = '';
					if(in_array($s->id, $clientes)){$checked = ' checked';$muestraClientes = "";}
					$HTML_CLIENTES_PUSH .= "<input type='checkbox' name='clientesPush[]' value='".$s->id."' id='clientePush_".$s->id."' ".$checked." onchange='fnValidaChkGrupalPush(this);' />";
					$HTML_CLIENTES_PUSH .= "<label for='clientePush_".$s->id."'>".$s->apellido.", ".$s->nombre."</label><br/>";
				}
				
				$HTML_CLIENTES .= "</td>";
				$HTML_CLIENTES_PUSH .= "</td>";
				$i++;
				if($i == 2){$HTML_CLIENTES .= "</tr>";$HTML_CLIENTES_PUSH .= "</tr>";$i = 0;}
			}
			
			$HTML  = "<form action='noticias.php?editar=".$params['idEdicion']."' method='post' enctype='multipart/form-data'>";
			$HTML  .= "	<input type='hidden' name='idNoticia' value='".$params['idEdicion']."' />";
			$HTML  .= "<table class='tablaEditar'>";
			$HTML .= "	<tr>";
			$HTML .= "		<td class='input'><input type='text' name='fecha' placeholder='Fecha' class='calendario' value='".$fecha."' /></td>";
			$HTML .= "		<td class='vistaPreviaNoticias' rowspan=13>";
			$HTML .= "			<div id='vistaPreviaInterna'><div id='contenedorVistaPreviaInterna'>";
			$HTML .= "				<div id='vpi1'><span id='rojo1'></span><span id='azul1'></span></div><div id='titulo1'></div><div id='cosas'></div>";
			$HTML .= "				<div id='imagen'></div><div id='texto1'></div><div id='masinfo'>MAS INFO</div>";
			$HTML .= "			</div></div>";
			$HTML .= "			<div id='vistaPreviaListado'>";
			$HTML .= "				<table><tr><td colspan=2 id='vpl1'><span id='azul'></span><span id='rojo'></span></td></tr>";
			$HTML .= "				<tr><td id='vpl2'><div id='titulo'></div><div id='texto'></div><div id='fecha'></div></td><td id='vpl3'></td></tr></table>";
			$HTML .= "			</div>";
			$HTML .= "		</td>";
			$HTML .= "	</tr>";
			$HTML .= "	<tr>";
			$HTML .= "		<td class='input'><input type='text' name='titulo' placeholder='Titulo' value='".utf8_encode($titulo)."' /></td>";
			$HTML .= "	</tr>";
			$HTML .= "	<tr>";
			$HTML .= "		<td class='input'><input type='text' name='tema' placeholder='Tema' value='".utf8_encode($tema)."' style='text-transform:uppercase' /></td>";
			$HTML .= "	</tr>";
			$HTML .= "	<tr>";
			$HTML .= "		<td class='input'>";
			$HTML .= "			<img src='".$imagen."' alt='' class='imagenMuestra' id='imagenMuestra' />";
			if($imagen != ''){$estiloBorrarImagen = "style='display:inline-block;'";}else{$estiloBorrarImagen = "style='display:none;'";}
			$HTML .= "			<span onclick='oGen.fnBorrarImagenNoticia();' id='borrarImagenNoticia' ".$estiloBorrarImagen.">Borrar imágen</span>";
			$HTML .= "			<input type='hidden' name='bkpNomImg' id='bkpNomIMg' value='".$imagen."' />";
			$HTML .= "			<input type='file' name='imagen' id='imagenNoticia' placeholder='Seleccione imágen' value='' accept='image/gif, image/jpeg, image/jpg, image/png' />";
			$HTML .= "		</td>";
			$HTML .= "	</tr>";
			$HTML .= "	<tr>";
			$HTML .= "		<td class='input'><textarea name='texto' id='textoNoticia' placeholder='Cuerpo de la noticia'>".utf8_encode($texto)."</textarea></td>";
			$HTML .= "		<script type='text/javascript'>";
			$HTML .= "			CKEDITOR.replace('textoNoticia');";
			$HTML .= "			CKEDITOR.instances.textoNoticia.on('key', function () {oGen.fnCargaVistaPrevia();});";
			$HTML .= "		</script>";
			$HTML .= "	</tr>";
			$HTML .= "	<tr>";
			$HTML .= "		<td class='input'>";
			$HTML .= "				<table class='listadoOpciones'><tr><td colspan=2>Secciones:</td></tr>".utf8_encode($HTML_SECCIONES)."</table>";
			$HTML .= "		</td>";
			$HTML .= "	</tr>";
			$HTML .= "	<tr>";
			$HTML .= "		<td class='input'><input type='text' name='personas' placeholder='En esta nota' value='".utf8_encode($personas)."' /></td>";
			$HTML .= "	</tr>";
			$HTML .= "	<tr>";
			$HTML .= "		<td class='input'>";
			$HTML .= "				<table class='listadoOpciones'><tr><td colspan=2>Distritos:</td></tr>".utf8_encode($HTML_LEGISLATURAS)."</table>";
			$HTML .= "		</td>";
			$HTML .= "	</tr>";
			$HTML .= "	<tr>";
			$HTML .= "		<td class='input'><input type='text' name='distrito' placeholder='Otro' value='".utf8_encode($distrito)."' /></td>";
			$HTML .= "	</tr>";
			if($_SESSION['tipoUsuario'] == '1'){
				$HTML .= "	<tr>";
								if($tipo == 1){$tipoPublica = " selected";}else{$tipoPublica = "";}
								if($tipo == 2){$tipoPrivada = " selected";}else{$tipoPrivada = "";}
				$HTML .= "		<td class='input'><select name='tipo' id='tipoNoticias' onchange='oGen.fnToggleClientesNoticia();'><option value='0'>Tipo de publicación</option><option value='1' ".$tipoPublica.">PÚBLICA</option><option value='2' ".$tipoPrivada.">PRIVADA</option></select>";
				$HTML .= "	</tr>";
				$HTML .= "	<tr>";
				$HTML .= "		<td class='input'>";
				$HTML .= "				<table class='listadoOpciones' id='listadoClientesNoticias' ".$muestraClientes."><tr><td colspan=2>Clientes:</td></tr>".utf8_encode($HTML_CLIENTES)."</table>";
				$HTML .= "		</td>";
				$HTML .= "	</tr>";
				$HTML .= "	<tr>";
				$HTML .= "		<td class='input'>";
				$HTML .= "				<input type='checkbox' id='mostrarClientesPush' onchange='oGen.fnToggleClientesPushNoticia();' /> <label for='mostrarClientesPush'>Enviar push notification</label>";
				$HTML .= "		</td>";
				$HTML .= "	</tr>";
				$HTML .= "	<tr>";
				$HTML .= "		<td class='input'>";
				$HTML .= "				<table class='listadoOpciones' id='listadoClientesPushNoticias' ".$muestraClientesPush."><tr><td colspan=2>Clientes:</td></tr>".utf8_encode($HTML_CLIENTES_PUSH)."</table>";
				$HTML .= "		</td>";
				$HTML .= "	</tr>";
				$HTML .= "	<tr>";
								if($estado == 1){$estadoPublicada = " selected";}else{$estadoPublicada = "";}
								if($estado == 2){$estadoDesarrollo = " selected";}else{$estadoDesarrollo = "";}
				$HTML .= "		<td class='input'><select name='estado'><option value='0'>Estado de la publicación</option><option value='1' ".$estadoPublicada.">PUBLICADA</option><option value='2' ".$estadoDesarrollo.">EN DESARROLLO</option></select>";
				$HTML .= "	</tr>";
			}else{
				$HTML .= "	<input type='hidden' name='tipo' value='1' />";
				$HTML .= "	<input type='hidden' name='estado' value='2' />";
			}
			
			$HTML .= "	<tr class='trBotonesSubmitFrmNoticias'>";
			$HTML .= "		<td class='input' colspan=2 style='padding-bottom:30px;'>";
			$HTML .= "			<input type='button' class='botonCancelar' value='Volver' onclick='window.location=\"noticias.php\";' />";
			$HTML .= "			<input type='submit' class='botonGuardar' name='guardar' value='Guardar' />";
			$HTML .= "			<input type='submit' class='botonGuardar' name='guardarSeguir' value='Guardar y seguir' />";
			if($params['idEdicion'] == '0'){
				$HTML .= "			<input type='submit' class='botonGuardar' name='guardarSeguir' value='Guardar y cargar adjuntos' />";
			}
			$HTML .= "			<input type='button' class='botonEliminar' value='Eliminar' onclick='window.history.back();' />";
			$HTML .= "		</td>";
			$HTML .= "	</tr></form>";
			
			
			if($params['idEdicion'] != '0'){
				$adjuntos = "";
				$ia = 0;
				if(is_dir("../adjuntosNoticias/".$params['idEdicion']."/")){
					foreach(scandir("../adjuntosNoticias/".$params['idEdicion']."/") as $a){
						if($a != '.' and $a != '..'){
							$ia++;
							if($ia == 1){$adjuntos .= "<tr>";}
							$HREF = str_replace('modulos/noticias.php', 'adjuntosNoticias/'.$params['idEdicion'].'/'.$a, $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["SERVER_NAME"].$_SERVER['PHP_SELF']);
							$adjuntos .= "<td>";
							$adjuntos .= "	<form action='noticias.php' method='post'> ";
							$adjuntos .= "	<a href='".$HREF."' target='_blank' style='float:left;margin-right:25px;'>".$a."</a>";
							$adjuntos .= "		<input type='hidden' name='eliminarAdjunto' value='1' />";
							$adjuntos .= "		<input type='hidden' name='editar' value='".$params['idEdicion']."' />";
							$adjuntos .= "		<input type='hidden' name='scrolToBottom' value='1' />";
							$adjuntos .= "		<input type='hidden' name='archivoParaEliminar' value='".$a."' />";
							$adjuntos .= "		<i class='material-icons' title='Eliminar el archivo ".$a."' style='color:red;cursor:pointer;float:left;' onclick='\$(this).parent().submit();'>delete</i>";
							$adjuntos .= "	</form>";
							$adjuntos .= "</td>";
							if($ia == 2){$adjuntos .= "</tr>";}
						}
					}
				}
				if($ia == 1){$adjuntos .= "<td></td></tr>";}
				$HTML .= "	<tr>";
				$HTML .= "		<td class='input'>";
				$HTML .= "			<table class='listadoOpciones'>";
				$HTML .= "				<tr>";
				$HTML .= "					<td colspan=2>Archivos Adjuntos:</td>";
				$HTML .= "				</tr>";
				$HTML .= 				$adjuntos;
				$HTML .= "				<tr>";
				$HTML .= "					<form id='cargarAdjunto' action='noticias.php' method='post' enctype='multipart/form-data'>";
				$HTML .= "					<td style='width:50%;'>";
				$HTML .= "						<input type='hidden' name='guardarAdjunto' value='1' />";
				$HTML .= "						<input type='hidden' name='editar' value='".$params['idEdicion']."' />";
				$HTML .= "						<input type='hidden' name='scrolToBottom' value='1' />";
				$HTML .= "						<input type='file' name='archivoAdjunto' value='' id='cargarArchivoAdjuntoNoticias' />";
				$HTML .= "					</td>";
				$HTML .= "					<td><input type='submit' name='adjuntar' class='botonNaranja' value='Cargar archivo' />";
				$HTML .= "					</td>";
				$HTML .= "					</form>";
				$HTML .= "				</tr>";
				$HTML .= "			</table>";
				$HTML .= "		</td>";
				$HTML .= "	</tr>";
			}
			
			$HTML .= "</table>";
			$HTML .= "<div id='scrltobottom'></div>";
			
			echo $HTML;
		}
		
		function guardarNoticia($params){
			global $conexion;
			
			if($params['gIdNoticia'] == 0){
				$SQL  = "INSERT INTO noticias (titulo, texto, imagen, tema, secciones, personas, distrito, legislaturas, fecha, usuarios, tipo, status, keywords) VALUES ";
				$SQL .= "('".$params['gTitulo']."', '".$params['gTexto']."', '".$params['gImagen']."', '".$params['gTema']."', '|".$params['gSecciones']."|', '";
				$SQL .= $params['gPersonas']."', '".$params['gDistrito']."', '|".$params['gLegislaturas']."|', '".invierteFecha($params['gFecha'])."', '|".$params['gClientes']."|', '";
				$SQL .= $params['gTipo']."', '".$params['gEstado']."', '".$params['gKeywords']."')";
			}else{
				$SQL  = "UPDATE noticias SET ";
				$SQL .= " titulo = '".$params['gTitulo']."', ";
				$SQL .= " texto = '".$params['gTexto']."', ";
				$SQL .= " imagen = '".$params['gImagen']."', ";
				$SQL .= " tema = '".$params['gTema']."', ";
				$SQL .= " secciones = '|".$params['gSecciones']."|', ";
				$SQL .= " personas = '".$params['gPersonas']."', ";
				$SQL .= " distrito = '".$params['gDistrito']."', ";
				$SQL .= " legislaturas = '|".$params['gLegislaturas']."|', ";
				$SQL .= " fecha = '".invierteFecha($params['gFecha'])."', ";
				$SQL .= " usuarios = '|".$params['gClientes']."|', ";
				$SQL .= " tipo = '".$params['gTipo']."', ";
				$SQL .= " status = '".$params['gEstado']."', ";
				$SQL .= " keywords = '".$params['gKeywords']."' ";
				$SQL .= " WHERE id = '".$params['gIdNoticia']."'";
			}
			
			if(mysqli_query($conexion, utf8_decode($SQL))){
				if($params['gIdNoticia'] == 0){
					$ID = mysqli_fetch_object(mysqli_query($conexion, "SELECT MAX(id) AS nuevoId FROM noticias"));
					$ID = $ID->nuevoId;
				}else{
					$ID = $params['gIdNoticia'];
				}
				if($params['gClientesPush'] != ''){
					enviarPushNotification($ID, $params['gClientesPush']);
				}
				return $ID;
			}else{
				return 0;
			}
		}
		
		function eliminarNoticia($ID){
			global $conexion;
			
			if(mysqli_query($conexion, "UPDATE noticias SET eliminada = '1' WHERE id = '".$ID."'")){
				return 1;
			}else{
				return 0;
			}
		}
	}
	
	function enviarPushNotification($idNoticia, $clientes){
		global $conexion;
		
		// DATOS FCM
		$firebase_api = "AAAAUQDTntY:APA91bFfzdn5Kozc3Vd_5wRLFL4NiammXHxrHxsnsgK9zr-1bkPR0bSXBl0LpCom7BXCV0r9k2pA85xYajFYi4GXj1Q8ndeafgD4Wd7SbHg2q1lss30ACd_d-ineX8AvC4R5-vAkgV9w";
		$url = 'https://fcm.googleapis.com/fcm/send';
		$headers = array(
			'Authorization: key=' . $firebase_api,
			'Content-Type: application/json'
		);

		// DATOS NOTICIA
		$SQL_NOTICIA = "SELECT titulo, texto FROM noticias WHERE id = '".$idNoticia."'";
		$RS_NOTICIA = mysqli_query($conexion, $SQL_NOTICIA);
		$RES_NOTICIA = mysqli_fetch_object($RS_NOTICIA);
		$requestData = array();
		$requestData['title'] = utf8_encode($RES_NOTICIA->titulo);
		$requestData['body'] = substr(strip_tags(cambiarCaracteresEspeciales($RES_NOTICIA->texto)), 0, 200).'...';
		$requestData['idNoticia'] = $idNoticia;
		
		// TOKENS Y ENVIO
		$clientes = explode('|', $clientes);
		foreach($clientes as $c){
			$SQL_TOKENS = "SELECT token FROM tokens WHERE (permisos = '0' AND idCliente = '".$c."') OR (permisos = '1') GROUP BY token";
			$RS_TOKENS = mysqli_query($conexion, $SQL_TOKENS);
			$ENVIO_PUSH_OK = '0';
			while($t = mysqli_fetch_object($RS_TOKENS)){
				$fields = array(
					'to' => $t->token,
					'data' => $requestData,
				);
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
				$result = curl_exec($ch);
				if($result !== FALSE){
					$ENVIO_PUSH_OK = '1';
				}
				curl_close($ch);
			}
			if($ENVIO_PUSH_OK == '1'){
				mysqli_query($conexion, "INSERT INTO clientespush (idCliente, idNoticia) VALUES ('".$c."','".$idNoticia."')");
			}
		}
	}
	
	function cambiarCaracteresEspeciales($txt){
		$txt = str_replace("&quot;", '"', $txt);
		$txt = str_replace("&amp;", "&", $txt);
		$txt = str_replace("&lt;", "<", $txt);
		$txt = str_replace("&gt;", ">", $txt);
		$txt = str_replace("&nbsp;", " ", $txt);
		$txt = str_replace("&iexcl;", "¡", $txt);
		$txt = str_replace("&cent;", "¢", $txt);
		$txt = str_replace("&pound;", "£", $txt);
		$txt = str_replace("&curren;", "¤", $txt);
		$txt = str_replace("&yen;", "¥", $txt);
		$txt = str_replace("&brvbar;", "¦", $txt);
		$txt = str_replace("&sect;", "§", $txt);
		$txt = str_replace("&uml;", "¨", $txt);
		$txt = str_replace("&copy;", "©", $txt);
		$txt = str_replace("&ordf;", "ª", $txt);
		$txt = str_replace("&laquo;", "«", $txt);
		$txt = str_replace("&not;", "¬", $txt);
		$txt = str_replace("&reg;", "®", $txt);
		$txt = str_replace("&macr;", "¯", $txt);
		$txt = str_replace("&deg;", "°", $txt);
		$txt = str_replace("&plusmn;", "±", $txt);
		$txt = str_replace("&sup2;", "²", $txt);
		$txt = str_replace("&sup3;", "³", $txt);
		$txt = str_replace("&acute;", "´", $txt);
		$txt = str_replace("&micro;", "µ", $txt);
		$txt = str_replace("&para;", "¶", $txt);
		$txt = str_replace("&middot;", "·", $txt);
		$txt = str_replace("&cedil;", "¸", $txt);
		$txt = str_replace("&sup1;", "¹", $txt);
		$txt = str_replace("&ordm;", "º", $txt);
		$txt = str_replace("&raquo;", "»", $txt);
		$txt = str_replace("&frac14;", "¼", $txt);
		$txt = str_replace("&frac12;", "½", $txt);
		$txt = str_replace("&frac34;", "¾", $txt);
		$txt = str_replace("&iquest;", "¿", $txt);
		$txt = str_replace("&Agrave;", "À", $txt);
		$txt = str_replace("&Aacute;", "Á", $txt);
		$txt = str_replace("&Acirc;", "Â", $txt);
		$txt = str_replace("&Atilde;", "Ã", $txt);
		$txt = str_replace("&Auml;", "Ä", $txt);
		$txt = str_replace("&Aring;", "Å", $txt);
		$txt = str_replace("&AElig;", "Æ", $txt);
		$txt = str_replace("&Ccedil;", "Ç", $txt);
		$txt = str_replace("&Egrave;", "È", $txt);
		$txt = str_replace("&Eacute;", "É", $txt);
		$txt = str_replace("&Ecirc;", "Ê", $txt);
		$txt = str_replace("&Euml;", "Ë", $txt);
		$txt = str_replace("&Igrave;", "Ì", $txt);
		$txt = str_replace("&Iacute;", "Í", $txt);
		$txt = str_replace("&Icirc;", "Î", $txt);
		$txt = str_replace("&Iuml;", "Ï", $txt);
		$txt = str_replace("&ETH;", "Ð", $txt);
		$txt = str_replace("&Ntilde;", "Ñ", $txt);
		$txt = str_replace("&Ograve;", "Ò", $txt);
		$txt = str_replace("&Oacute;", "Ó", $txt);
		$txt = str_replace("&Ocirc;", "Ô", $txt);
		$txt = str_replace("&Otilde;", "Õ", $txt);
		$txt = str_replace("&Ouml;", "Ö", $txt);
		$txt = str_replace("&times;", "×", $txt);
		$txt = str_replace("&Oslash;", "Ø", $txt);
		$txt = str_replace("&Ugrave;", "Ù", $txt);
		$txt = str_replace("&Uacute;", "Ú", $txt);
		$txt = str_replace("&Ucirc;", "Û", $txt);
		$txt = str_replace("&Uuml;", "Ü", $txt);
		$txt = str_replace("&Yacute;", "Ý", $txt);
		$txt = str_replace("&THORN;", "Þ", $txt);
		$txt = str_replace("&szlig;", "ß", $txt);
		$txt = str_replace("&agrave;", "à", $txt);
		$txt = str_replace("&aacute;", "á", $txt);
		$txt = str_replace("&acirc;", "â", $txt);
		$txt = str_replace("&atilde;", "ã", $txt);
		$txt = str_replace("&auml;", "ä", $txt);
		$txt = str_replace("&aring;", "å", $txt);
		$txt = str_replace("&aelig;", "æ", $txt);
		$txt = str_replace("&ccedil;", "ç", $txt);
		$txt = str_replace("&egrave;", "è", $txt);
		$txt = str_replace("&eacute;", "é", $txt);
		$txt = str_replace("&ecirc;", "ê", $txt);
		$txt = str_replace("&euml;", "ë", $txt);
		$txt = str_replace("&igrave;", "ì", $txt);
		$txt = str_replace("&iacute;", "í", $txt);
		$txt = str_replace("&icirc;", "î", $txt);
		$txt = str_replace("&iuml;", "ï", $txt);
		$txt = str_replace("&eth;", "ð", $txt);
		$txt = str_replace("&ntilde;", "ñ", $txt);
		$txt = str_replace("&ograve;", "ò", $txt);
		$txt = str_replace("&oacute;", "ó", $txt);
		$txt = str_replace("&ocirc;", "ô", $txt);
		$txt = str_replace("&otilde;", "õ", $txt);
		$txt = str_replace("&ouml;", "ö", $txt);
		$txt = str_replace("&divide;", "÷", $txt);
		$txt = str_replace("&oslash;", "ø", $txt);
		$txt = str_replace("&ugrave;", "ù", $txt);
		$txt = str_replace("&uacute;", "ú", $txt);
		$txt = str_replace("&ucirc;", "û", $txt);
		$txt = str_replace("&uuml;", "ü", $txt);
		$txt = str_replace("&yacute;", "ý", $txt);
		$txt = str_replace("&thorn;", "þ", $txt);
		$txt = str_replace("&yuml;", "ÿ", $txt);
		$txt = str_replace("&euro;", "€", $txt);
		
		return $txt;
	}