<?php
	require_once('../config.php');
	session_start();

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");

	mysqli_query($con, "UPDATE egresosnm SET
						cap1000 = 0,
						observaciones = 'CANCELADO'
						WHERE noCheque = $_REQUEST[codigo]")
		or die(mysqli_error($con));

	mysqli_close($con);
	header('location: ../formularios/NMaltaEgresos.php');

?>
