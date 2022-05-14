<?php
	require_once('../config.php');
	session_start();
	header('Content-Type: text/html; charset=UTF-8');

	$insertdate = date('Y-m-d', strtotime($_POST['fechaDeposito']));
	$no =					$_POST['no'];
	$concepto = 			$_POST['concepto'];
	$tipo =				 	$_POST['tipo'];
	$mes = 					$_POST['mes'];
	$quincena =				$_POST['quincena'];
	$noAutorizacion = 		$_POST['noAutorizacion'];
	$cap1000 = 				$_POST['cap1000'];

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");

	mysqli_query($con, "UPDATE ingresosnm
						SET fechaDeposito = '$insertdate',
							concepto = '$concepto',
							tipo = '$tipo',
							mes = '$mes',
							quincena = $quincena,
							noAutorizacion = $noAutorizacion,
							cap1000 = $cap1000
						WHERE no = $no")
			or die(mysqli_error($con));

	mysqli_close($con);

	header('location:../formularios/NMeditaIngresos.php');
?>
