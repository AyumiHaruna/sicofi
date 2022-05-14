<?php
require_once('menu.php');
	require_once('config.php');
	header('Content-Type: text/html; charset=UTF-8'); ?>
<html>
	<head>
		<title> SICOFI </title>
		<meta http-equiv="content-type" content="text/html; UTF-8" />


		<link rel="stylesheet" type="text/css" href="css2/menu.css"></link>		<!-- Hoja de Estilos -->
		<link rel="stylesheet" type="text/css" href="css2/index.css"></link>
		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>		<!-- Efectos del Menú -->
		<script language="javascript">
			var ul;
			var li_items;
			var li_number;
			var image_number = 0;
			var slider_width = 0;
			var image_width;
			var current = 0;
			function init(){
				ul = document.getElementById('image_slider');
				li_items = ul.children;
				li_number = li_items.length;
				for (i = 0; i < li_number; i++){
				// nodeType == 1 means the node is an element.
				// in this way it's a cross-browser way.
				//if (li_items[i].nodeType == 1){
					//clietWidth and width???
					image_width = li_items[i].childNodes[0].clientWidth;
					slider_width += image_width;
					image_number++;
		}

		ul.style.width = parseInt(slider_width) + 'px';
		slider(ul);
		}

		function slider(){
			animate({
				delay:17,
				duration: 4000,
				delta:function(p){return Math.max(0, -1 + 2 * p)},
				step:function(delta){
						ul.style.left = '-' + parseInt(current * image_width + delta * image_width) + 'px';
					},
				callback:function(){
					current++;
					if(current < li_number-1){
						slider();
					}
					else{
						var left = (li_number - 1) * image_width;
						setTimeout(function(){goBack(left)},2000);
						setTimeout(slider, 4000);
					}
				}
			});
		}
		function goBack(left_limits){
			current = 0;
			setInterval(function(){
				if(left_limits >= 0){
					ul.style.left = '-' + parseInt(left_limits) + 'px';
					left_limits -= image_width / 10;
				}
			}, 17);
		}
		function animate(opts){
			var start = new Date;
			var id = setInterval(function(){
				var timePassed = new Date - start;
				var progress = timePassed / opts.duration
				if(progress > 1){
					progress = 1;
				}
				var delta = opts.delta(progress);
				opts.step(delta);
				if (progress == 1){
					clearInterval(id);
					opts.callback();
				}
			}, opts.dalay || 17);
		}
		window.onload = init;
		</script>

	</head>
	<body>
		<!-- IIIIIIIIIIIIIIIIIIIIIIIIIIII   Este cuadro contiene el Menú	IIIIIIIIIIIIIIIIIIIII -->
		<?php
			session_start();

			if($_SESSION == NULL)
			{
				echo $menu[0];
				$anio = date("Y")-1;
			}
			else
			{
				$nivel = $_SESSION['nivel'];

				switch($nivel)
				{
					case 1:		echo $menu[1];
						break;

					case 2:		echo $menu[2];
						break;

					case 3:		echo $menu[3];
						break;

					case 4:		echo $menu[4];
						break;

					case 5:		echo $menu[5];
						break;
				}

				$anio = $_SESSION['anio'];
			}



	// IF NO EXISTE SESION DEL USUARIO ELSE
		if(!isset($_SESSION['id']))
		 {
			echo '
			<fieldset id="login"">
				<B>LOG-IN</B>
				<form action="validar_usuario.php" method="post">
					<table class="tablaForm"">
						<tr>
							<td>Usuario:</td>
							<td><input type="text" name="id" required="required"></td>
						</tr>
						<tr>
							<td>Password:</td>
							<td><input type="password" name="password" required="required"></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2"> Con qu&eacute; a&ntilde;o deseas trabajar?
						</tr>
						<tr>
							<td colspan="2">
								<select name="anio">
									<option value="2015">A&ntilde;o 2015 </option>
									<option value="2016">A&ntilde;o 2016 </option>
									<option value="2017">A&ntilde;o 2017 </option>
									<option value="2018">A&ntilde;o 2018 </option>
									<option value="2019">A&ntilde;o 2019 </option>
									<option value="2020">A&ntilde;o 2020 </option>
									<option value="2021" selected>A&ntilde;o 2021 </option>
				   			</select>
							</td>
						</tr>
						<tr>
							<td colspan="2"><center><input type="submit" value="Iniciar Sesi&oacute;n" name="iniciar"></center></td>
						</tr>
						<tr>
							<td colspan="2"> &nbsp; </td>
						</tr>
						<tr>
							<td colspan="2"><center> Eres nuevo? Registrate Aqu&iacute: </center></td>
						</tr>
						<tr>
							<td colspan="2"><center> <a href="registro.php"><input type="button" value="Registro"></a> </center></td>
						<tr>
					</table>
				 </form>

			</fieldset>

		';
		}
		else
		{
			$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
				or die("Problemas con la conexi&oacute;n a la base de datos");
				$con->query("SET NAMES 'utf8'");

			$update = mysqli_query($con, "SELECT fecha FROM bitacora
										WHERE nombre = 'Dieter Pedro Ramirez Rubi'
										ORDER BY fecha  DESC LIMIT 1")
				or die(mysqli_error($con));

			while($reg = mysqli_fetch_array($update))
			{
				$date = $reg['fecha'];
			}


			echo '
					<fieldset id="loged">
						<p class="logout" align="right"><a href="logout.php">Cerrar Sesi&oacute;n </a></p>

							<div class="container">
								<div class="image-slider-wrapper">
									<ul id="image_slider">
										<li><img src="imagen/coordi01.jpg" width=500></li>
										<li><img src="imagen/coordi02.jpg" width=500></li>
										<li><img src="imagen/coordi03.jpg" width=500></li>
										<li><img src="imagen/coordi04.jpg" width=500></li>
										<li><img src="imagen/coordi05.jpg" width=500></li>
										<li><img src="imagen/coordi06.jpg" width=500></li>
										<li><img src="imagen/coordi07.jpg" width=500></li>
										<li><img src="imagen/coordi08.jpg" width=500></li>
									</ul>
									<div class="pager">
									</div>
								</div>
							</div>
						<h2> Bienvenid@ </h2>
						'.$_SESSION['nombre'].'<br>
						<br> Estas trabajando con SICOFI del '.$_SESSION['anio'].'<br>
											&Uacute;ltima Actualizaci&oacute;n: '.((ISSET($date))? $date : '' ).'
						<hr>
					</fieldset>
				';
		}
		?>


	</body>
</html>
