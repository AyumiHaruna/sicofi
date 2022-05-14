<?php 		//SE ACTUALIZAN LOS DATOS A LA DB
	require_once('../config.php');
	session_start();
	header('Content-Type: text/html; charset=UTF-8');
	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");

	$noCheque = 				$_POST['noCheque'];
	$fechaElaboracion = 		date('Y-m-d', strtotime($_POST['fechaElaboracion']));
	$nombre = 					$_POST['nombre'];
	$concepto = 				$_POST['concepto'];
	$cap1000 = 					$_POST['cap1000'];
	$observaciones = 			$_POST['observaciones'];


	mysqli_query($con, "UPDATE egresosnm
						SET fechaElaboracion = '$fechaElaboracion',
							nombre = '$nombre',
							concepto = '$concepto',
							observaciones = '$observaciones',
							cap1000 = $cap1000
						WHERE noCheque = $noCheque")
		or die(mysqli_error($con));

	mysqli_close($con);
	header('location: NMeditaEgresos.php');

?>
