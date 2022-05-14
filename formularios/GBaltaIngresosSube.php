<?php
	require_once('../config.php');
	session_start();

	$insertdate = date('Y-m-d', strtotime($_POST['fechaDeposito']));
	$concepto = 			$_POST['concepto'];
	$tipo =				 	$_POST['tipo'];

	$mes = 					$_POST['mes'];
	$noAutorizacion = 		$_POST['noAutorizacion'];

	$cap2000 = 				$_POST['cap2000'];
	$cap3000 = 				$_POST['cap3000'];
	$total = $_POST['cap2000']+  $_POST['cap3000'];

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");
		
	if($tipo == 'reintegro')
	{
		mysqli_query($con, "INSERT INTO ingresosgb(tipo, concepto,
							mes, fechaDeposito,
							cap2000, cap3000,
							noAutorizacion, total, noComprobacion1, comprobacion1,
							comprobado, restaComprobar )

							VALUES('$tipo', '$concepto',
							'$mes', '$insertdate',
							$cap2000, $cap3000, $noAutorizacion,
							$total, 'reintegro1', $total, $total, 0)")
			or die(mysqli_error($con));
	}
	else
	{
		mysqli_query($con, "INSERT INTO ingresosgb(tipo, concepto,
							mes, fechaDeposito,
							cap2000, cap3000,
							noAutorizacion, total,
							comprobado, restaComprobar )

							VALUES('$tipo', '$concepto',
							'$mes', '$insertdate',
							$cap2000, $cap3000, $noAutorizacion,
							$total, 0,  $total)")
			or die(mysqli_error($con));
	}

	mysqli_close($con);

	header('location:../formularios/GBaltaIngresos.php');
?>
