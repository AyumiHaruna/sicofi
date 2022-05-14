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

	if($_POST['comprobacion4'] == NULL){ $comprobacion4 = 0;	}
	else {	$comprobacion4 = $_POST['comprobacion4']; }

	if($_POST['comprobacion5'] == NULL){ $comprobacion5 = 0;	}
	else {	$comprobacion5 = $_POST['comprobacion5']; }

	if($_POST['comprobacion6'] == NULL){ $comprobacion6 = 0;	}
	else {	$comprobacion6 = $_POST['comprobacion6']; }

	$validacionComp1 =  $_POST['validacionComp1'];
	$validacionComp2 =  $_POST['validacionComp2'];
	$validacionComp3 =  $_POST['validacionComp3'];
	$validacionComp4 =  $_POST['validacionComp4'];
	$validacionComp5 =  $_POST['validacionComp5'];
	$validacionComp6 =  $_POST['validacionComp6'];

	if($_POST['fechaComp1'] == NULL) {	$fechaComp1 = NULL;	}
	else {	$fechaComp1 = date('Y-m-d', strtotime($_POST['fechaComp1'])); }

	if($_POST['fechaComp2'] == NULL) {	$fechaComp2 = NULL;	}
	else {	$fechaComp2 = date('Y-m-d', strtotime($_POST['fechaComp2'])); }

	if($_POST['fechaComp3'] == NULL) {	$fechaComp3 = NULL;	}
	else {	$fechaComp3 = date('Y-m-d', strtotime($_POST['fechaComp3'])); }

	if($_POST['fechaComp4'] == NULL) {	$fechaComp4 = NULL;	}
	else {	$fechaComp4 = date('Y-m-d', strtotime($_POST['fechaComp4'])); }

	if($_POST['fechaComp5'] == NULL) {	$fechaComp5 = NULL;	}
	else {	$fechaComp5 = date('Y-m-d', strtotime($_POST['fechaComp5'])); }

	if($_POST['fechaComp6'] == NULL) {	$fechaComp6 = NULL;	}
	else {	$fechaComp6 = date('Y-m-d', strtotime($_POST['fechaComp6'])); }

	$comprobado = $_POST['comprobado'];
	$restaComprobar = $_POST['restaComprobar'];

#INSERSIÓN DE DATOS EN MYSQL
	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");

	mysqli_query($con, "UPDATE egresos
						SET
						comprobacion1 = $comprobacion1,
						comprobacion2 = $comprobacion2,
						comprobacion3 = $comprobacion3,
						comprobacion4 = $comprobacion4,
						comprobacion5 = $comprobacion5,
						comprobacion6 = $comprobacion6,
						validacionComp1 = $validacionComp1,
						validacionComp2 = $validacionComp2,
						validacionComp3 = $validacionComp3,
						validacionComp4 = $validacionComp4,
						validacionComp5 = $validacionComp5,
						validacionComp6 = $validacionComp6,
						fechaComp1 = '$fechaComp1',
						fechaComp2 = '$fechaComp2',
						fechaComp3 = '$fechaComp3',
						fechaComp4 = '$fechaComp4',
						fechaComp5 = '$fechaComp5',
						fechaComp6 = '$fechaComp6',
						comprobado = $comprobado,
						restaComprobar = $restaComprobar

						WHERE noCheque = $noCheque")
		or die(mysqli_error($con));

	mysqli_close($con);

	header('location:../formularios/validaEgresos.php');
?>
