<?php 	require_once('menu.php');
require_once('../config.php');
header('Content-Type: text/html; charset=UTF-8');   ?>
<html>
	<head>
		<title>
			NM Reporte de Ingresos Desglosados
		</title>
		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/reportes.css"></link>
		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>		<!-- Efectos del Menú -->

	</head>

	<body>
	<!-- IIIIIIIIIIIIIIIIIIIIIIIIIIII   Este cuadro contiene el Menú	IIIIIIIIIIIIIIIIIIIII -->
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

			$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
				or die("Problemas con la conexi&oacute;n a la base de datos");
				$con->query("SET NAMES 'utf8'");

//inicia reporteo

			$ingreso = mysqli_query($con, "SELECT * FROM ingresosnm")
					or die(mysqli_error($con));


			echo '<fieldset id="base">
					<fieldset id="formulario">
					<legend>Reporte detallado de Ingresos en N&oacute;mina</legend>';
			echo '<table class="tablaReport" border="1">
					<tr>
						<td>No</td>
						<td>Tipo</td>
						<td>Concepto</td>
						<td>Mes</td>
						<td>Quincena</td>
						<td>Fecha de Dep&oacute;sito</td>
						<td>Autorizaci&oacute;n</td>
						<td>Cap 1000</td>
						</tr>';

			$s1000 = 0;

			while ($reg = mysqli_fetch_array ($ingreso))
			{

				echo '<tr>
						<td>'.$reg['no'].'</td>
						<td>'.$reg['tipo'].'</td>
						<td>'.$reg['concepto'].'</td>
						<td>'. $reg['mes'].'
						<td>'. $reg['quincena'].'
						</td><td>'.$reg['fechaDeposito'].'
						</td><td>'.$reg['noAutorizacion'].'
						</td><td>$'.number_format($reg['cap1000'], 2,'.',',').'</td>
					</tr>';
				//realizamos la suma de cada cifra numérica
					$s1000 = $reg['cap1000'] + $s1000;
				}

			echo '<tr>
					<td colspan="6">&nbsp;</td>
					<td><b>Total:</b></td>
					<td><b>$'.number_format($s1000, 2,'.',',').'</b></td>
				<tr>';

			echo '</table>';

			mysqli_close($con);
		?>
	</body>
</html>
