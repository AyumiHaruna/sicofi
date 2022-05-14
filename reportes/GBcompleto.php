<?php 	require_once('menu.php');
require_once('../config.php');
header('Content-Type: text/html; charset=UTF-8');
 ?>
<html>
	<head>
		<title>
			G.B. Reporte de Ingresos contra Egresos
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

			$egreso = mysqli_query($con, "SELECT * FROM egresosgb")
					or die(mysqli_error($con));

			$ingTotal = 0;
			while($reg = mysqli_fetch_array($ingreso))
			{	$ingTotal = $reg['total'] + $ingTotal;	}

			$egrTotal = 0;
			while($reg = mysqli_fetch_array($egreso))
			{	$egrTotal = $reg['total'] + $egrTotal;	}

			$disponible = $ingTotal - $egrTotal;


			echo '<fieldset id="base">
					<fieldset id="formulario">
					<legend>Disponible en Gasto B&aacute;sico</legend>';
			echo '<table class="tablaReport" border="1">
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td>Ingresos</td>
						<td>$'.number_format($ingTotal, 2,'.',',').'</td>
					</tr>
					<tr>
						<td>Egresos</td>
						<td>$'.number_format($egrTotal, 2,'.',',').'</td>
					</tr>
					<tr>
						<td><b>Disponible</b></td>
						<td><b>$'.number_format($disponible, 2,'.',',').'</b></td>
					</tr>';
			mysqli_close($con);
		?>
	</body>
</html>
