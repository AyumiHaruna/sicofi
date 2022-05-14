<?php
	require_once('../config.php');
	session_start();
	header('Content-Type: text/html; charset=UTF-8');

	$insertdate = date('Y-m-d', strtotime($_POST['fechaDeposito']));
	$concepto = 			$_POST['concepto'];
	$tipo =				 	$_POST['tipo'];

	$mes = 					$_POST['mes'];
	$noAutorizacion = 		$_POST['noAutorizacion'];
	$quincena = 		$_POST['quincena'];

	$cap1000= 		$_POST['cap1000'];

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");

	mysqli_query($con, "INSERT INTO ingresosnm (tipo, noAutorizacion,
										fechaDeposito, mes,  quincena, concepto, cap1000)
								VALUES('$tipo', $noAutorizacion, '$insertdate', '$mes',
												'$quincena', '$concepto', $cap1000)")
		or die(mysqli_error($con));

	mysqli_close($con);

	header('location:../formularios/NMaltaIngresos.php');
?>
