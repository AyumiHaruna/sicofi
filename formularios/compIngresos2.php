<?php 	require_once('menu.php');
require_once('../config.php');
header('Content-Type: text/html; charset=UTF-8');
 ?>
<html>
	<head>
		<title>
			Comprobación de Ingresos
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

			$nombre = $_REQUEST['codigo'];

			$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
				or die("Problemas con la conexi&oacute;n a la base de datos");
				$con->query("SET NAMES 'utf8'");

			$ingresos = mysqli_query($con, "SELECT * FROM ingresos
													WHERE numProy = '$_REQUEST[codigo]'
													ORDER BY no")
						or die(mysqli_error($con));

			$proyectos = mysqli_query($con, "SELECT * FROM proyectos
														WHERE numeroProyecto = '$_REQUEST[codigo]' ")
						or die(mysqli_query($con));

				while ($reg = mysqli_fetch_array ($proyectos))
				{
					$Bproy =  $reg['nombreProyecto'];
					$BnumProy =  $reg['numeroProyecto'];
				}


			echo '<fieldset id="base">
					<fieldset id="formulario">
						<legend>'.$BnumProy.'  -  '. $Bproy .'</legend>';
			echo '
				<a href="compIngresos1.php"><img src="../imagen/atras.png" width="50" height="50"></img></a>
				<br>
				<table class="tablaReport" border="1">
					<tr>
						<td>Editar</td>
						<td>No</td>
						<td>Tipo</td>
						<td>No. S.F.</td>
						<td>Concepto</td>
						<td>Importe Total</td>
						<td>Comprobado</td>
						<td>Resta Comprobar</td>
						</tr>';
			$tVal = 0;		$tComp = 0;

			while ($reg = mysqli_fetch_array ($ingresos))
			{
				echo '<tr>';
					if($reg['tipo'] == 'INGRESO')
					{
						echo'<td><a href="compIngresos3.php?codigo='.$reg['noSolFon'].'"> <img src="../Imagen/writeNegro.png" width="25" height="25"></img></a></td>';
					}
					else
					{
						echo'<td><img src="../Imagen/writeGray.png" width="25" height="25"></img></td>';
					}
				$restaComprobar = $reg['validado'] - $reg['comprobado'];
				echo   '<td>'.$reg['no'].'</td>
						<td>'.$reg['tipo'].'</td>
						<td>'.$reg['noSolFon'].'</td>
						<td>'. $reg['concepto'].'
						</td><td>$'.number_format($reg['validado'], 2,'.',',').'
						</td><td>$'.number_format($reg['comprobado'], 2,'.',',').'
						</td><td>$'.number_format($restaComprobar, 2,'.',',').'
						</td>
					</tr>';

				//variables almacenadas para la sumatoria Total
				$tVal = $tVal + $reg['validado'];
				$tComp = $tComp + $reg['comprobado'];
				//---------------------------------------------
			}

			$tResta = $tVal - $tComp;
			echo '	<tr>
						<td colspan="4"> &nbsp; </td>
						<td> Totales: </td>
						<td>$'.number_format($tVal, 2,'.',',').'</td>
						<td>$'.number_format($tComp, 2,'.',',').'</td>
						<td>$'.number_format($tResta, 2,'.',',').'</td>
					</tr>
				</table>';

			mysqli_close($con);
		?>
	</body>
</html>
