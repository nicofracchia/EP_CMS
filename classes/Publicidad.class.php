<?php

	class Publicidad {
		function listadoPublicidades(){
			global $conexion;
			
			$SQL = "SELECT * FROM publicidad WHERE eliminada = 0 AND habilitada = 1 ORDER BY -orden DESC";
			$RS = mysqli_query($conexion, $SQL);
			$HTML = "<div class='tituloPublicidad'>Habilitadas</div>";
			while($p = mysqli_fetch_object($RS)){
				$HTML .= "<form action='publicidad.php' method='post' id='frm_".$p->id."'><input type='hidden' name='idPublicidad' value='".$p->id."' /><input type='hidden' name='accion' value='' id='accion_".$p->id."' />";
				$HTML .= "	<div class='lineaPublicidad'>";
				$HTML .= "		<div class='orden'><input type='text' name='orden' value='".$p->orden."' placeholder='Orden' /></div>";
				$HTML .= "		<div class='img'><img src='".$p->imagen."' alt='' /></div>";
				$HTML .= "		<div class='link'><input type='text' name='link' value='".$p->link."' /></div>";
				$HTML .= "		<div class='guardar'><i class='material-icons' title='Guardar' onclick='fnPubli(".$p->id.",1);'>save</i></div>";
				$HTML .= "		<div class='deshabilitar'><i class='material-icons' title='Deshabilitar' onclick='fnPubli(".$p->id.",2);'>close</i></div>";
				$HTML .= "		<div class='eliminar'><i class='material-icons' title='Eliminar' onclick='fnPubli(".$p->id.",3);'>delete</i></div>";
				$HTML .= "	</div>";
				$HTML .= "</form>";
			}
			
			$SQL = "SELECT * FROM publicidad WHERE eliminada = 0 AND habilitada = 0 ORDER BY -orden DESC";
			$RS = mysqli_query($conexion, $SQL);
			$HTML .= "<div class='tituloPublicidad'>Deshabilitadas</div>";
			while($p = mysqli_fetch_object($RS)){
				$HTML .= "<form action='publicidad.php' method='post' id='frm_".$p->id."'><input type='hidden' name='idPublicidad' value='".$p->id."' /><input type='hidden' name='accion' value='' id='accion_".$p->id."' />";
				$HTML .= "	<div class='lineaPublicidad'>";
				$HTML .= "		<div class='orden'><input type='text' name='orden' value='".$p->orden."' placeholder='Orden' /></div>";
				$HTML .= "		<div class='img'><img src='".$p->imagen."' alt='' /></div>";
				$HTML .= "		<div class='link'><input type='text' name='link' value='".$p->link."' /></div>";
				$HTML .= "		<div class='guardar'><i class='material-icons' title='Guardar' onclick='fnPubli(".$p->id.",1);'>save</i></div>";
				$HTML .= "		<div class='habilitar'><i class='material-icons' title='Deshabilitar' onclick='fnPubli(".$p->id.",4);'>done</i></div>";
				$HTML .= "		<div class='eliminar'><i class='material-icons' title='Eliminar' onclick='fnPubli(".$p->id.",3);'>delete</i></div>";
				$HTML .= "	</div>";
				$HTML .= "</form>";
			}
			
			
			echo $HTML;
		}
	}
?>