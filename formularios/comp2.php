<?php 	require_once('menu.php');
require_once('../config.php');
header('Content-Type: text/html; charset=UTF-8');
?>
<html>
	<head>
		<title>
			Comprobacion de ingresos
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

	//INICIA PROGRAMA
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
			echo '<table class="tablaReport" border="1">
					<tr>
						<td>No</td>
						<td>No. S.F.</td>
						<td>Concepto</td>
						<td>Fecha de Dep&oacute;sito</td>
						<td>Importe Total</td>
						<td>Of. de Modificaci&oacute;n</td>
						<td>Comp. Realizada</td>
						</tr>

			<form method="post" action="comp3.php" name="form1">
			<input type="submit" value="Actualizar">
			<input type="hidden" name="proy" value="'.$BnumProy.'"> ';

		$x = 1;
			while ($reg = mysqli_fetch_array ($ingresos))
			{
				echo '<tr>';

				echo   '<td><input type="hidden" name="no'.$x.'" value="'.$reg['no'].'"> '.$reg['no'].' </td>
						<td>'.$reg['noSolFon'].'</td>
						<td>'. $reg['concepto'].'</td>
						<td> '.$reg['fechaDep1'].'</td>
						<td>$'.number_format($reg['validado'], 2,'.',',').'</td>';

						if( $reg['ofMod'] == 0 )
						{ echo '<td> <input type="checkbox" name="ofMod'.$x.'" value="1"> </td>'; }
						else { echo '<td> <input type="checkbox" name="ofMod'.$x.'" value="1" checked> </td>'; }

						if( $reg['subComp'] == 0)
						{ echo '<td> <input type="checkbox" name="subComp'.$x.'" value="1"></td>'; }
						else { echo '<td> <input type="checkbox" name="subComp'.$x.'" value="1" checked></td>'; }


				echo '</tr>';
				$x++;
			}
			echo '</table> </form>';

			mysqli_close($con);
		?>
	</body>
</html>
