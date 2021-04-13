<div id='contenedorMenu' onmouseleave="oGen.fnMuestraSubMenu_cierreGral();">
	<div id='contenedorMenuScroll'>
		<div class='itemMenu' onclick='oGen.fnAbrePestania(this,2);' data-idPestania='pestania_noticias' data-archivoPestania='modulos/noticias.php'>
			<i class="material-icons">description</i> <span class='tituloItemSeccion'>Noticias</span>
		</div>
		<?php if($_SESSION['tipoUsuario'] == 1){ ?>
		<div class='itemMenu' onclick='oGen.fnAbrePestania(this,2);' data-idPestania='pestania_usuarios' data-archivoPestania='modulos/usuarios.php'>
			<i class="material-icons">group</i> <span class='tituloItemSeccion'>Usuarios</span>
		</div>
		<div class='itemMenu' onclick='oGen.fnAbrePestania(this,2);' data-idPestania='pestania_clientes' data-archivoPestania='modulos/clientes.php'>
			<i class="material-icons">account_circle</i> <span class='tituloItemSeccion'>Clientes</span>
		</div>
		<div class='itemMenu' onclick='oGen.fnAbrePestania(this,2);' data-idPestania='pestania_publicidad' data-archivoPestania='modulos/publicidad.php'>
			<i class="material-icons">announcement</i> <span class='tituloItemSeccion'>Publicidad</span>
		</div>
		<!--
		<div class='itemMenu' onclick='oGen.fnAbrePestania(this,2);' data-idPestania='pestania_push' data-archivoPestania='#'>
			<i class="material-icons">notifications_active</i> <span class='tituloItemSeccion'>Push Notification</span>
		</div>
		-->
		<div class='itemMenu itemMenuEsferaPublicaWeb' onclick='oGen.fnMuestraSubMenu(this,215);'>
			<i class="material-icons">web</i> <span class='tituloItemSeccion'>Esfera Pública</span>
			<span class='subItemMenu' data-idPestania='pestania_EPhome' data-archivoPestania='modulos/ep_home.php'>Home</span>
			<span class='subItemMenu' data-idPestania='pestania_EPservicios' data-archivoPestania='modulos/ep_servicios.php'>Servicios</span>
			<span class='subItemMenu' data-idPestania='pestania_EPnosotros' data-archivoPestania='modulos/ep_nosotros.php'>Nosotros</span>
		</div>
		<?php } ?>
	</div>
</div>