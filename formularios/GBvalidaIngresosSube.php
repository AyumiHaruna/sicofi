<?php
	require_once('../config.php');
	session_start();
	header('Content-Type: text/html; charset=UTF-8');

	$no 				=	$_POST['no'];
	$noComprobacion1 	=	$_POST['noComprobacion1'];
	$noComprobacion2	= 	$_POST['noComprobacion2'];
	$comprobacion1		= 	$_POST['comprobacion1'];
	$comprobacion2		= 	$_POST['comprobacion2'];
	$total				= 	$_POST['total'];
	$comprobado			= 	$_POST['comprobado'];
	$restaComprobar		= 	$_POST['restaComprobar'];

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");

	mysqli_query($con, "UPDATE ingresosgb
						SET noComprobacion1 = '$noComprobacion1',
							comprobacion1 = $comprobacion1,
							noComprobacion2 = '$noComprobacion2',
							comprobacion2 = $comprobacion2,
							comprobado = $comprobado,
							restaComprobar = $restaComprobar
						WHERE no = $no")

			or die(mysqli_error($con));


	header('location:../formularios/GBvalidaIngresos.php');

?>
