<?php 	require_once('menu.php');
header('Content-Type: text/html; charset=UTF-8');
?>
<html>
	<head>
		<title> Impresion de Cheques y P&oacute;lizas N&oacute;mina</title>

		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/reportes.css"></link>

		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>		<!-- Efectos del Menú -->

	</head>
	<body>
		<?php
			session_start();
//revisa si el usuario esta logueado
//sino esta logueado lo redirecciona al index
		if($_SESSION == NULL)
		{
			echo '<script languaje="javascript">
					alert("Area restringida, redireccionando...");
					location.href="../index.php";
					</script>';
		}
//si esta logueado muestra el menu correspondiente al nivel
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
		}
//-------------------------------------------------------------------------------
			//registramos variables para hacer busquedas

			$valor1 = $_POST['cheque1'];
			$valor2 = $_POST['cheque2'];


		echo '<fieldset id="base">
			<fieldset id="formulario">
				<legend> Imprimir: </legend>
				<table class="tablaForm">
					<tr>
						<td><a href="NMgeneraCheque.php?a='.$valor1.'
								&b='.$valor2.'"target=_blank"><input type="button" value="Cheques"></a></td>
						<td><a href="NMgeneraPoliza.php?a='.$valor1.'
								&b='.$valor2.'"target=_blank"><input type="button" value="P&oacute;lizas"></a></td>
						<td><a href="NMimpresionChePol.php"><input type="button" value="Regresar"></a></td>
					</tr>

				</table>
			</fieldset>
		</fieldset>';
	?>
	</body>
</html>
