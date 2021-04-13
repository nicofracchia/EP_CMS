<?php

	function invierteFecha($fecha){
		$fecha2 = explode('-',$fecha);
		if(count($fecha2) == 3){
			$fecha2 = $fecha2[2].'-'.$fecha2[1].'-'.$fecha2[0];
			return $fecha2;
		}else{
			return $fecha;
		}
	}
	function listadoLegislaturasOption ($seleccionada){
		global $conexion;
		$SQL = "SELECT * FROM legislaturas ORDER BY id ASC";
		$RS = mysqli_query($conexion, $SQL);
		$HTML = "";
		while($l = mysqli_fetch_object($RS)){
			if($seleccionada == $l->id){$sel = " selected";}else{$sel = "";}
			$HTML .= "<option value='".$l->id."' ".$sel.">".$l->legislatura."</option>";
		}
		return $HTML;
	}