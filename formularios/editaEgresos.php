<?php 	require_once('menu.php');
		require_once('../config.php');
		header('Content-Type: text/html; charset=UTF-8');
		?>

<html>
	<head>
		<title> Editor de Egresos </title>

		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/reportes.css"></link>

		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>		<!-- Efectos del Men� -->

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
			<legend>Busqueda de Egresos</legend>
				<form method="post" action="editaEgresos.php" name="form1">
					<table class="tablaForm">
						<tr>
							<td> Ordenar por: </td>
							<td>
								<select name="val1">
									<option value="noCheque">N&uacute;mero de Cheque </option>
									<option value="numeroProyecto">N&uacute;mero de Proyecto</option>
									<option value="nombreProyecto">Nombre del Proyecto</option>
									<option value="concepto">Concepto</option>
									<option value="fechaElaboracion">Fecha de Elaboraci&oacute;n</option>
									<option value="importeTotal">Importe Total</option>
									</select>
							</td>
						</tr>

						<tr>
							<td colspan="1"><input type="submit" value="Ordenar" required></td>
						</tr>
					</table>
				</form>
			</fieldset>
		<br>
		';

		if(!isset($_POST['val1']))
		{
			$egresos = mysqli_query($con, "SELECT egr.noCheque, egr.fechaElaboracion, egr.nombre,
											egr.concepto, egr.nombreProyecto, pro.numeroProyecto,
											egr.importeTotal, egr.comprobado,
											egr.restaComprobar
											FROM egresos AS egr
											INNER JOIN	proyectos AS pro
												ON	egr.nombreProyecto = pro.nombreProyecto
											ORDER BY egr.noCheque")
				or die(mysqli_error($con));
		}

		else
		{
			$val1 = $_POST['val1'];
			$egresos = mysqli_query($con, "SELECT egr.noCheque, egr.fechaElaboracion, egr.nombre,
											egr.concepto, egr.nombreProyecto, pro.numeroProyecto,
											egr.importeTotal, egr.comprobado,
											egr.restaComprobar
											FROM egresos AS egr
											INNER JOIN	proyectos AS pro
												ON	egr.nombreProyecto = pro.nombreProyecto
											ORDER BY $val1 DESC")
				or die(mysqli_error($con));
		}


		echo '<fieldset id="formulario">
				<table class="tablaReport">
					<tr>
						<td>Editar</td>
						<td>No. de Cheque</td>
						<td>Fecha de Elaboraci&oacute;n</td>
						<td>Nombre</td>
						<td>Concepto</td>
						<td>Nombre del Proyecto</td>
						<td>No. de Proyecto</td>
						<td>ImporteTotal</td>
						<td>Comprobado</td>
						<td>Resta por Comprobar</td>
					</tr>';

			while ($reg = mysqli_fetch_array ($egresos))
			{
				echo '<tr>
						<td><a href="editaEgresos2.php?codigo='.$reg['noCheque'].'"><img src="../Imagen/writeNegro.png" width="25" height="25"></img></a></td>
						<td>'.$reg['noCheque'].'</td>
						<td>'.$reg['fechaElaboracion'].'</td>
						<td>'.$reg['nombre'].'</td>
						<td>'.$reg['concepto'].'</td>
						<td>'.$reg['nombreProyecto'].'</td>
						<td>'.$reg['numeroProyecto'].'</td>
						<td>$'.number_format($reg['importeTotal'], 2,'.',',').'</td>
						<td>$'.number_format($reg['comprobado'], 2,'.',',').'</td>
						<td>$'.number_format($reg['restaComprobar'], 2,'.',',').'</td>
					</tr>';
			}

			echo '</table>
				</fieldset>';
	mysqli_close($con);
	?>

	</body>
</html>
