<?php 		//SE ACTUALIZAN LOS DATOS A LA DB
	require_once('../config.php');
	session_start();
	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");

	$noCheque = 				$_POST['noCheque'];
	$fechaElaboracion = 		date('Y-m-d', strtotime($_POST['fechaElaboracion']));
	$nombre = 					$_POST['nombre'];
	$concepto = 				$_POST['concepto'];
	$cap2000 = 					$_POST['cap2000'];
	$cap3000 = 					$_POST['cap3000'];
	$total = 			$cap2000 + $cap3000;
	$observaciones = 			$_POST['observaciones'];


	mysqli_query($con, "UPDATE egresosgb
						SET fechaElaboracion = '$fechaElaboracion',
							nombre = '$nombre',
							concepto = '$concepto',
							observaciones = '$observaciones',
							cap2000 = $cap2000,
							cap3000 = $cap3000,
							total = $total,
							restaComprobar = $total
						WHERE noCheque = $noCheque")
		or die(mysqli_error($con));

	mysqli_close($con);
	header('location: GBeditaEgresos.php');

?>
