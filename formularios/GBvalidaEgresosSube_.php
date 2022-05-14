<?php
	require_once('../config.php');
	session_start();
	header('Content-Type: text/html; charset=UTF-8');

#	ASIGNACION DE VARIABLES
	$noCheque =  $_POST['noCheque'];

	if($_POST['comprobacion1'] == NULL){ $comprobacion1 = 0;	}
	else {	$comprobacion1 = $_POST['comprobacion1']; }

	if($_POST['comprobacion2'] == NULL){ $comprobacion2 = 0;	}
	else {	$comprobacion2 = $_POST['comprobacion2']; }

	if($_POST['comprobacion3'] == NULL){ $comprobacion3 = 0;	}
	else {	$comprobacion3 = $_POST['comprobacion3']; }

	$noComprobacion1 =  $_POST['noComprobacion1'];
	$noComprobacion2 =  $_POST['noComprobacion2'];
	$noComprobacion3 =  $_POST['noComprobacion3'];


	$comprobado = $_POST['comprobado'];
	$restaComprobar = $_POST['restaComprobar'];

#INSERSIÓN DE DATOS EN MYSQL
	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");

	mysqli_query($con, "UPDATE egresosgb
						SET
						comprobacion1 = $comprobacion1,
						comprobacion2 = $comprobacion2,
						comprobacion3 = $comprobacion3,
						noComprobacion1 = '$noComprobacion1',
						noComprobacion2 = '$noComprobacion2',
						noComprobacion3 = '$noComprobacion3',
						comprobado = $comprobado,
						restaComprobar = $restaComprobar

						WHERE noCheque = $noCheque")
		or die(mysqli_error($con));

	mysqli_close($con);

	header('location:../formularios/GBvalidaEgresos.php');
?>
