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
	$cap1000 = 					$_POST['cap1000'];
	$observaciones = 			$_POST['observaciones'];



	mysqli_query($con, "INSERT INTO egresosnm (noCheque, fechaElaboracion,
								nombre, concepto, cap1000, observaciones)

						VALUES($noCheque, '$fechaElaboracion', '$nombre',
							'$concepto', $cap1000, '$observaciones')")
		or die(mysqli_error($con));

	mysqli_close($con);

//	header('location:../reportes/listaProyectos.php');

?>

<html>
	<head>
		<title> Egreso NM Realizado </title>

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
							<td colspan="2">Importe Total (Cap1000): </td>
						</tr>
						<tr>
							<td colspan="2">$'.number_format($cap1000,2,'.',',').'</td>
						</tr>
						<tr>
							<td>Observaciones:</td>
							<td colspan="5">'.$observaciones.'</td>
						</tr>
					</table>
				</fieldset>
				<fieldset id="formulario">
					<b>Desea imprimir el Cheque y su P&oacute;liza de Gasto B&aacute;sico?</b>
					<br>
					<br>
					<table class="tablaReport">
						<td><a href="NMimprimeEgresosCheque.php?a='.$fechaElaboracion.'
																&b='.$nombre.'
																&c='.$cap1000.'"target=_blank"><input type="button" value="Imprimir Cheque"></a></td>
						<td><a href="NMimprimeEgresosPoliza.php?a='.$fechaElaboracion.'
																&b='.$nombre.'
																&c='.$cap1000.'
																&d='.$noCheque.'
																&e='.$concepto.'"target=_blank"><input type="button" value="Imprimir P&oacute;liza"></a></td>
						<td><a href="NMaltaEgresos.php"><input type="button" value="Regresar"></a></td>
					</table>
				</fieldset>
			</fieldset>';
	?>
	</body>
</html>
