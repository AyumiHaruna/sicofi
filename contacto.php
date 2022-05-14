<?php 	require_once('menu.php');
header('Content-Type: text/html; charset=UTF-8');
?>
<html>
	<head>
		<title> Contacto Sicofi </title>

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

					case 5:		echo $menu[5];
						break;
				}
			}

		?>
<!--		-->

		<fieldset>
			CONTACTO
				<form method="post" action="sendMail.php" name="form1">
					<br>
					<table class="tablaForm" border="0">
						  <tr>
							<td>Tu Nombre:</td>
							<td>Comentarios:</td>
						  </tr>
						  <tr>
							<td> <input type="text" name="nombre" required> </td>
							<td rowspan="5"> <textarea class="texto" id="area" name="comentarios" rows="10" cols="40" value=""></textarea></td>
						  </tr>
						  <tr>
							<td>e-mail:</td>
						  </tr>
						  <tr>
							<td> <input type="text" name="email" required> </td>
						  </tr>
						  <tr>
							<td>Asunto:</td>
						  </tr>
						  <tr>
							<td> <input type="text" name="asunto" required> </td>
						  </tr>
						  <tr>
							<td></td>
						  </tr>
						  <tr>
							<td> <input type="submit" value="Enviar"> </td>
						  </tr>
					</table>
				</form>
		</fieldset>

	</body>
</html>
