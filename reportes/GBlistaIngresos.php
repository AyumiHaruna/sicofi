<?php 	require_once('menu.php');
require_once('../config.php');
header('Content-Type: text/html; charset=UTF-8');
?>
<html>
	<head>
		<title>
			G.B. Reporte de Ingresos Desglosados
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

			$ingreso = mysqli_query($con, "SELECT * FROM ingresosgb")
					or die(mysqli_error($con));


			echo '<fieldset id="base">
					<fieldset id="formulario">
					<legend>Reporte detallado de Ingresos en Gasto B&aacute;sico</legend>';
			echo '<table class="tablaReport" border="1">
					<tr>
						<td>No</td>
						<td>Tipo</td>
						<td>Concepto</td>
						<td>Mes</td>
						<td>Fecha de Dep&oacute;sito</td>
						<td>Autorizaci&oacute;n</td>
						<td>Cap 2000</td>
						<td>Cap 3000</td>
						<td>Importe total</td>
						</tr>';

			$s2000 = 0;
			$s3000 = 0;
			$sTotal = 0;

			while ($reg = mysqli_fetch_array ($ingreso))
			{

				echo '<tr>
						<td>'.$reg['no'].'</td>
						<td>'.$reg['tipo'].'</td>
						<td>'.$reg['concepto'].'</td>
						<td>'. $reg['mes'].'
						</td><td>'.$reg['fechaDeposito'].'
						</td><td>'.$reg['noAutorizacion'].'
						</td><td>$'.number_format($reg['cap2000'], 2,'.',',').'</td>
						</td><td>$'.number_format($reg['cap3000'], 2,'.',',').'
						</td><td>$'.number_format($reg['total'], 2,'.',',').'</td>
					</tr>';
				//realizamos la suma de cada cifra numérica
					$s2000 = $reg['cap2000'] + $s2000;
					$s3000 = $reg['cap3000'] + $s3000;
					$sTotal = $reg['total'] + $sTotal;
				}

			echo '<tr>
					<td colspan="5">&nbsp;</td>
					<td><b>Total:</b></td>
					<td><b>$'.number_format($s2000, 2,'.',',').'</b></td>
					<td><b>$'.number_format($s3000, 2,'.',',').'</b></td>
					<td><b>$'.number_format($sTotal, 2,'.',',').'</b></td>
				<tr>';

			echo '</table>';

			mysqli_close($con);
		?>
	</body>
</html>
