<?php 	require_once('../config.php');
require_once('menu.php');
header('Content-Type: text/html; charset=UTF-8');
 ?>
<html>
	<head>
		<title>
			NM Reporte de Egresos Desglosados
		</title>
		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/reportes.css"></link>
		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>		<!-- Efectos del Menú -->

	</head>

	<body>
    	<!-- IIIIIIIIIIIIIIIIIIIIIIIIIIII   Este cuadro contiene el Menú	IIIIIIIIIIIIIIIIIIIII -->
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

			$egresos = mysqli_query($con, "SELECT * FROM egresosnm")
					or die(mysqli_error($con));

			echo '<fieldset id="base">
					<fieldset id="formulario">
						<legend>Reporte detallado de Egresos en N&oacute;mina</legend>';
			echo '<table class="tablaReport">
					<tr><td>No. de Cheque</td>
						<td>Fecha de Elaboraci&oacute;n</td>
						<td>Nombre</td>
						<td>Concepto</td>
						<td>Observaciones</td>
						<td>Cap. 1000</td>
					</tr>';

			//Variables para la suma final
			$s1000 = 0;

			while ($reg = mysqli_fetch_array ($egresos))
			{
				echo '<tr>
						<td>'.$reg['noCheque'].'</td>
						<td>'.$reg['fechaElaboracion'].'</td>
						<td>'.$reg['nombre'].'</td>
						<td>'.$reg['concepto'].'</td>
						<td>'.$reg['observaciones'].'</td>
						<td>$'.number_format($reg['cap1000'], 2,'.',',').'</td>
					</tr>';

				$s1000 = $reg['cap1000'] + $s1000;
			}

				echo '<tr>
						<td colspan="4">&nbsp;</td>
						<td><b>Total:</b></td>
						<td><b>$'.number_format($s1000, 2,'.',',').'</b></td>
					</tr>
				</table>
			</fieldset>
			</fieldset>';

			mysqli_close($con);
		?>
	</body>
</html>
