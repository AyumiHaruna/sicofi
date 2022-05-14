<?php
require_once('../config.php');
session_start();

	$tipo =				 		$_POST['tipo'];
	$concepto = 			$_POST['concepto'];
	$operacion = 			$_POST['operacion'];
	$mes = 						$_POST['mes'];
	$numProy = 				$_POST['numProy'];
	//fechaElaboracion =  fechaDeposito
	$fechaDep1 =	$_POST['fechaDep1'];
	$noAut1 = 					$_POST['noAut1'];
	//fechaDeopsito anteriormente declarada
	$cap1000 = 				$_POST['cap1000'];
	$cap2000 = 				$_POST['cap2000'];
	$cap3000 = 				$_POST['cap3000'];
	$cap4000 = 				$_POST['cap4000'];
	$cap5000 = 				$_POST['cap5000'];
	$importeTotal = 		$_POST['cap1000'] + $_POST['cap2000'] +  $_POST['cap3000'] +  $_POST['cap4000'] +  $_POST['cap5000'];
	//validado 	= 	importeTotal

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");

		// mysqli_query($con, "INSERT INTO ingresos(tipo, concepto, operacion, mes,
		// 							numProy, fechaElab, fechaDep1, noAut1, cap1_1, cap2_1,
		// 							cap3_1, cap4_1, cap5_1,	capT1, capT2, capT3, capT4, capT5,
		// 							validado, nomComp1, monto1, comprobado, ofMod, subComp)
		//
		// 							VALUES('$tipo', '$concepto', '$operacion', '$mes',
		// 							$numProy, '$fechaDep1', '$fechaDep1', $noAut1, $cap1000, $cap2000,
		// 							$cap3000, $cap4000, $cap5000,  $cap1000, $cap2000, $cap3000, $cap4000, $cap5000,
		// 							$importeTotal, 'REINTEGRO', $importeTotal, $importeTotal, 1, 1) ")
		// or die(mysqli_error($con));

		IF( $_SESSION['anio'] < 2019 ){
      mysqli_query($con, "INSERT INTO ingresos(tipo, concepto, operacion, mes,
                    numProy, fechaElab, SFcap1000, SFcap2000, SFcap3000, SFcap4000,
										SFcap5000, SFtotal, fechaDep1, noAut1, cap1_1, cap2_1,
                    cap3_1, cap4_1, cap5_1,	capT1, capT2, capT3, capT4, capT5,
                    validado, nomComp1, monto1, comprobado, ofMod, subComp)

                    VALUES('$tipo', '$concepto', '$operacion', '$mes',
                    $numProy, '$fechaDep1', $cap1000, $cap2000, $cap3000, $cap4000,
										$cap5000, $importeTotal, '$fechaDep1', $noAut1, $cap1000, $cap2000,
                    $cap3000, $cap4000, $cap5000,  $cap1000, $cap2000, $cap3000, $cap4000, $cap5000,
                    $importeTotal, 'REINTEGRO', $importeTotal, $importeTotal, 1, 1) ")
      or die(mysqli_error($con));
    } else {    // >= 2019
      mysqli_query($con, "INSERT INTO ingresos(tipo, concepto, operacion, mes,
										numProy, fechaElab, SFcap1000, SFcap2000, SFcap3000, SFcap4000,
										SFcap5000, SFtotal, fechaDep1, noAut1, cap1_1, cap2_1,
										cap3_1, cap4_1, cap5_1,	capT1, capT2, capT3, capT4, capT5,
  									validado, comprobado, ofMod, subComp)

  									VALUES('$tipo', '$concepto', '$operacion', '$mes',
											$numProy, '$fechaDep1', $cap1000, $cap2000, $cap3000, $cap4000,
											$cap5000, $importeTotal, '$fechaDep1', $noAut1, $cap1000, $cap2000,
	                    $cap3000, $cap4000, $cap5000,  $cap1000, $cap2000, $cap3000, $cap4000, $cap5000,
  									$importeTotal, $importeTotal, 1, 1) ")
  		or die(mysqli_error($con));
    }


	mysqli_close($con);

header('location:../formularios/altaReintegros.php');
?>
.
