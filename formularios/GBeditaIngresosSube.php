<?php
	require_once('../config.php');
	session_start();
	header('Content-Type: text/html; charset=UTF-8');

	$insertdate = date('Y-m-d', strtotime($_POST['fechaDeposito']));
	$no =					$_POST['no'];
	$concepto = 			$_POST['concepto'];
	$tipo =				 	$_POST['tipo'];
	$mes = 					$_POST['mes'];
	$noAutorizacion = 		$_POST['noAutorizacion'];
	$cap2000 = 				$_POST['cap2000'];
	$cap3000 = 				$_POST['cap3000'];
	$total =		 		$_POST['total'];


	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");

	if($tipo == 'reintegro')
	{
		mysqli_query($con, "UPDATE ingresosgb
							SET fechaDeposito = '$insertdate',
								no = '$no',
								concepto = '$concepto',
								tipo = '$tipo',
								mes = '$mes',
								noAutorizacion = '$noAutorizacion',
								cap2000 = '$cap2000',
								cap3000 = '$cap3000',
								total = '$total',
								comprobado = '$total',
								restaComprobar = 0
							WHERE no = $no")

				or die(mysqli_error($con));
	}
	else
	{
		mysqli_query($con, "UPDATE ingresosgb
							SET fechaDeposito = '$insertdate',
								no = '$no',
								concepto = '$concepto',
								tipo = '$tipo',
								mes = '$mes',
								noAutorizacion = '$noAutorizacion',
								cap2000 = '$cap2000',
								cap3000 = '$cap3000',
								total = '$total',
								comprobado = 0,
								restaComprobar = '$total'
							WHERE no = $no")

				or die(mysqli_error($con));
	}
	mysqli_close($con);

	header('location:../formularios/GBeditaIngresos.php');

?>
