<?php 	require_once('menu.php');
		require_once('../config.php');
		header('Content-Type: text/html; charset=UTF-8');
		?>

<html>
	<head>
		<title> Editor de Egresos  N&oacute;mina</title>

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

//inicia busqueda de proyectos

		$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
			or die("problemas con la conexi&oacute;n a la base de datos");
			$con->query("SET NAMES 'utf8'");

		echo '
		<fieldset id="base">
			<fieldset id="formulario">
			<legend>Busqueda de Egresos N&oacute;mina</legend>
				<form method="post" action="NMeditaEgresos.php" name="form1">
					<table class="tablaForm">
						<tr>
							<td> Ordenar por: </td>
							<td>
								<select name="val1">
									<option value="noCheque">N&uacute;mero de Cheque </option>
									<option value="concepto">Concepto</option>
									<option value="fechaElaboracion">Fecha de Elaboraci&oacute;n</option>
									<option value="cap1000">Total</option>
									</select>
							</td>
						</tr>

						<tr>
							<td colspan="1"><input type="submit" value="Ordenar" required></td>
						</tr>
					</table>
				</form>
			</fieldset>
		';

		if(!isset($_POST['val1']))
		{
			$egresos = mysqli_query($con, "SELECT noCheque, fechaElaboracion, nombre,
											concepto, cap1000
											FROM egresosnm
											ORDER BY noCheque")
				or die(mysqli_error($con));
		}

		else
		{
			$val1 = $_POST['val1'];
			$egresos = mysqli_query($con, "SELECT noCheque, fechaElaboracion, nombre,
											concepto, cap1000
											FROM egresosnm
											ORDER BY $val1 DESC")
				or die(mysqli_error($con));
		}


		echo '<fieldset id="formulario">
						<legend>Lista de Egresos en N&oacute;mina Desglosados</legend>';
			echo '<table class="tablaReport" border="1">
					<tr>
						<td>Editar</td>
						<td>No. de Cheque</td>
						<td>Fecha de Elaboraci&oacute;n</td>
						<td>Nombre</td>
						<td>Concepto</td>
						<td>ImporteTotal</td>
						</tr>';

			while ($reg = mysqli_fetch_array ($egresos))
			{
				echo '<tr>
						<td><a href="NMeditaEgresos2.php?codigo='.$reg['noCheque'].'"><img src="../Imagen/writeNegro.png" width="25" height="25"></img></a></td>
						<td>'.$reg['noCheque'].'</td>
						<td>'.$reg['fechaElaboracion'].'</td>
						<td>'.$reg['nombre'].'</td>
						<td>'.$reg['concepto'].'</td>
						<td>$'.number_format($reg['cap1000'], 2,'.',',').'</td>
					</tr>';
			}

			echo '</table>
				</fieldset>';
	mysqli_close($con);
	?>
	</fieldset>
	</body>
</html>
