<?php 		//SE ACTUALIZAN LOS DATOS A LA DB
	require_once('menu.php');
	require_once('../config.php');
	session_start();
	header('Content-Type: text/html; charset=UTF-8');
	
	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");

	$noCheque = 				$_POST['nocheque'];
	$fechaElaboracion = 		date('Y-m-d', strtotime($_POST['fechaElaboracion']));
	$nombre = 					$_POST['nombre'];
	$concepto = 				$_POST['concepto'];
	$cap2000 = 					$_POST['cap2000'];
	$cap3000 = 					$_POST['cap3000'];
	$total = 					$_POST['total'];
	$observaciones = 			$_POST['observaciones'];



	mysqli_query($con, "INSERT INTO egresosgb (noCheque, fechaElaboracion,
								nombre, concepto, observaciones,
								cap2000, cap3000, total, comprobado,
								restaComprobar)

						VALUES($noCheque, '$fechaElaboracion', '$nombre',
							'$concepto', '$observaciones', $cap2000,
							$cap3000, $total, 0, $total)")
		or die(mysqli_error($con));

	mysqli_close($con);

//	header('location:../reportes/listaProyectos.php');

?>

<html>
	<head>
		<title> Egreso GB Realizado </title>

		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/reportes.css"></link>

		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>		<!-- Efectos del Menú -->

	</head>

	<body>
	<!-- IIIIIIIIIIIIIIIIIIIIIIIIIIII   Este cuadro contiene el Menú	IIIIIIIIIIIIIIIIIIIII -->
		<?php
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

	echo	'<fieldset id="base">
				<fieldset id="formulario">
					<legend><b> La captura se realiz&oacute; Correctamente </b></legend>
					<br>
					Datos:<br>
					<table class="tablaReport">
						<tr>
						</tr>
						<tr>
							<td>No. de Cheque:</td>
							<td>'.$noCheque.'</td>
							<td>Fecha de Elaboraci&oacute;n: </td>
							<td>'.$fechaElaboracion.'</td>
						</tr>
						<tr>
							<td>Nombre:</td>
							<td colspan="5">'.$nombre.'</td>
						</tr>
						<tr>
							<td>Concepto:</td>
							<td colspan="5">'.$concepto.'</td>
						</tr>
						<tr>
							<td colspan="2">Cap 2000</td>
							<td colspan="2">Cap 3000</td>
						</tr>
						<tr>
							<td colspan="2">$'.number_format($cap2000,2,'.',',').'</td>
							<td colspan="2">$'.number_format($cap3000,2,'.',',').'</td>
						</tr>
						<tr>
							<td>Importe Total:</td>
							<td>$'.number_format($total,2,'.',',').'</td>
						</tr>
						<tr>
							<td>Observaciones:</td>
							<td colspan="5">'.$observaciones.'</td>
						</tr>
					</table>
				</fieldset>
				<br>
				<fieldset id="formulario">
					<b>Desea imprimir el Cheque y su P&oacute;liza de Gasto B&aacute;sico?</b>
					<br>
					<br>
					<table class="tablaReport">
						<td><a href="GBimprimeEgresosCheque.php?a='.$fechaElaboracion.'
																&b='.$nombre.'
																&c='.$total.'"target=_blank"><input type="button" value="Imprimir Cheque"></a></td>
						<td><a href="GBimprimeEgresosPoliza.php?a='.$fechaElaboracion.'
																&b='.$nombre.'
																&c='.$total.'
																&d='.$noCheque.'
																&e='.$concepto.'"target=_blank"><input type="button" value="Imprimir P&oacute;liza"></a></td>
						<td><a href="GBaltaEgresos.php"><input type="button" value="Regresar"></a></td>
					</table>
				</fieldset>
			</fieldset>';
	?>
	</body>
</html>
