<?php
	//-----------------------------------------------------------//
	//	     			CONFIGURACIONES GENERALES					             //
	//-----------------------------------------------------------//
	require_once("menu.php");
	require_once("../config.php");
	session_start();
	header('Content-Type: text/html; charset=UTF-8');
	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio']) or die("Problema s con la conexi&oacute;n a la base de deatos");
	$con->query("SET NAMES 'utf8'");

	//-----------------------------------------------------------//
	//	     			ASIGNACIÓN DE VARIABLES						             //
	//-----------------------------------------------------------//
	$tipo	 								= 	'INGRESO';
	$concepto 						=		$_POST['concepto'];
	$operacion						=		$_POST['operacion'];
	$noSolFon						= 	$_POST['noSolFon'];
	$mes									=		$_POST['mes'];
	$numProy							= 	$_POST['numProy'];
	$fechaElab			=		$_POST['fechaElaboracion'];
	$obs 									= 	$_POST['obs'];
	$SFtotal	=	$_POST['total'];

	$subMes = strtolower(substr( $mes, 0, 3 ));

	$listaPartidas = json_decode($_POST['myJson']);

	//suma de total para cada capítulo
	$suma1 = 0;		$suma2 = 0;		$suma3 = 0; $suma4 = 0;		$suma5 = 0;
	for ($x=0; $x<count($listaPartidas); $x++) {
		// print_r($listaPartidas[$x]);
		echo '<br><br>';
		$noPartida[$x] = $listaPartidas[$x]->noPartida;
		$monto[$x] 		=	$listaPartidas[$x]->importe;

		switch (true) {
			case substr($listaPartidas[$x]->noPartida, 0, 5) >= 10000 AND substr($listaPartidas[$x]->noPartida, 0, 5) <= 19999 :
					$suma1 += $monto[$x];
					$cap[$x] = 1000;
			break;

			case substr($listaPartidas[$x]->noPartida, 0, 5) >= 20000 AND substr($listaPartidas[$x]->noPartida, 0, 5) <= 29999 :
					$suma2 += $monto[$x];
					$cap[$x] = 2000;
			break;

			case substr($listaPartidas[$x]->noPartida, 0, 5) >= 30000 AND substr($listaPartidas[$x]->noPartida, 0, 5) <= 39999 :
					$suma3 += $monto[$x];
					$cap[$x] = 3000;
			break;

			case substr($listaPartidas[$x]->noPartida, 0, 5) >= 40000 AND substr($listaPartidas[$x]->noPartida, 0, 5) <= 49999 :
					$suma4 += $monto[$x];
					$cap[$x] = 4000;
			break;

			case substr($listaPartidas[$x]->noPartida, 0, 5) >= 50000 AND substr($listaPartidas[$x]->noPartida, 0, 5) <= 59999 :
					$suma5 += $monto[$x];
					$cap[$x] = 5000;
			break;
		}
	}

	//-----------------------------------------------------------//
	//	     		CONSULTAS EN LAS BASES DE DATOS			             //
	//-----------------------------------------------------------//
	// update ingresos data
	mysqli_query($con, "UPDATE ingresos	SET
									tipo = '$tipo',		 concepto = '$concepto',
									operacion = '$operacion',  mes = '$mes',
									numProy = $numProy, fechaElab = '$fechaElab',
									noSolFon = $noSolFon, SFcap1000 = $suma1,
									SFcap2000 = $suma2, SFcap3000 = $suma3,
									SFcap4000 = $suma4, SFcap5000 = $suma5,
									SFtotal = $SFtotal, obs = '$obs'
									WHERE noSolFon = $noSolFon") or die(mysqli_error($con));

		//Delete partidasSF where $noSolFon
		mysqli_query($con, "DELETE FROM partidassf WHERE noSolFon = $noSolFon") or die(mysqli_error($con));

			print_r($noPartida);

		//register each listaPartidas on partidassf
		for($y = 0; $y < count($noPartida); $y++)
		{
				mysqli_query($con, " INSERT INTO partidassf (noSolFon, np, noPartida, cap, importe)
					VALUES( $noSolFon, ($y + 1), '".$noPartida[$y]."', $cap[$y], $monto[$y] ) ") or die(mysqli_error($con));
		}

		//-----------------------------------------------------------//
		//	     			REDIRECT TO PRINT										           //
		//-----------------------------------------------------------//
		header("Location: http://172.26.26.126/sicofi/formularios/impresionSF2.php?start=".$noSolFon);
		mysqli_close($con);
		die();

?>
