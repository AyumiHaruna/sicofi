<?php 	require_once('menu.php');
		require_once('../config.php');
		header('Content-Type: text/html; charset=UTF-8');
		?>

<html>
	<head>
		<title> Editor de Ingresos </title>

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

		$busqueda = mysqli_query($con, "SELECT *  FROM ingresosgb")
			or die(mysqli_error($con));

		echo '
		<fieldset id="base">
			<fieldset id="formulario">
				<legend>Editor de Ingresos</legend>
				<form method="post" action="GBeditaIngresos.php" name="form1">
					<table class="tablaForm">
						<tr>
							<td> Ordenar por: </td>
							<td>
								<select name="val1">
									<option value="no">No. de Ingreso</option>
									<option value="tipo">Tipo de Ingreso</option>
									<option value="concepto">Concepto del Ingreso</option>
									<option value="fechaDeposito">Fecha del Dep&oacute;sito</option>
									<option value="total">Monto Total</option>
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
			$ingreso = mysqli_query($con, "SELECT *	FROM ingresosgb
											ORDER BY no")
				or die(mysqli_error($con));
		}

		else
		{
			$val1 = $_POST['val1'];
			$ingreso = mysqli_query($con, "SELECT *  FROM ingresosgb
												ORDER BY $val1")
				or die(mysqli_error($con));
		}


		echo '
					<fieldset id="formulario">
						<table class="tablaReport" border="0">
							<tr>
								<td>Editar</td>
								<td>No. </td>
								<td>Tipo</td>
								<td>Concepto</td>
								<td>mes</td>
								<td>Fecha de Dep&oacute;sito</td>
								<td>cap2000</td>
								<td>cap3000</td>
								<td>Importe Total</td>
							<tr>';

		while($reg = mysqli_fetch_array ($ingreso))
		{
			echo '

							<tr>
								<td><a href="GBeditaIngresos2.php?codigo='.$reg['no'].'"><img src="../Imagen/writeNegro.png" width="25" height="25"></img></a></td>
								<td>'.$reg['no'].'</td>
								<td>'.$reg['tipo'].'</td>
								<td>'.$reg['concepto'].'</td>
								<td>'.$reg['mes'].'</td>
								<td>'.$reg['fechaDeposito'].'</td>
								<td>$'.number_format($reg['cap2000'], 2,'.',',').'</td>
								<td>$'.number_format($reg['cap3000'], 2,'.',',').'</td>
								<td>$'.number_format($reg['total'], 2,'.',',').'</td>
							</tr>

			';
		}

			echo '		</table>
				</fieldset>';
	mysqli_close($con);
	?>

	</body>
</html>
