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
	$fechaElaboracion			=		$_POST['fechaElaboracion'];
	$obs 									= 	$_POST['obs'];

	$subMes = strtolower(substr( $mes, 0, 3 ));

	$listaPartidas = json_decode($_POST['myJson']);

	//suma de total para cada capítulo
	$suma1 = 0;		$suma2 = 0;		$suma3 = 0; $suma4 = 0;		$suma5 = 0;
	for ($x=0; $x<count($listaPartidas); $x++) {

		$noPartida[$x] = $listaPartidas[$x]->noPartida;
		$monto[$x] 		=	$listaPartidas[$x]->$subMes;
		$nombrePartida[$x] 		=	$listaPartidas[$x]->nomPartida;

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

	$importeTotal	=	$_POST['total'];

	//-----------------------------------------------------------//
	//	     			GUARDAMOS DATOS EN LA DB						           //
	//-----------------------------------------------------------//
	//Registramos los datos para ingresos
	mysqli_query($con, " INSERT INTO ingresos(tipo, concepto, operacion,  mes, numProy, fechaElab, noSolFon,
																			SFcap1000, SFcap2000, SFcap3000, SFcap4000, SFcap5000, SFTotal, obs)
											VALUES ('$tipo', '$concepto', '$operacion',  '$mes', $numProy, '$fechaElaboracion', $noSolFon,
																					$suma1, $suma2, $suma3, $suma4, $suma5, $importeTotal, '$obs')")
		or die(mysqli_error($con));

	//Registramos los datos para partidasSF
	for($x = 0; $x < count($listaPartidas); $x++)
	{
		mysqli_query($con, " INSERT INTO partidassf (noSolFon, np, noPartida, cap, importe)
													VALUES( $noSolFon, $x, '$noPartida[$x]', $cap[$x], $monto[$x] ) ")
			or die(mysqli_error($con));
	}

	//-----------------------------------------------------------//
	//	     			REDIRECT TO PRINT										           //
	//-----------------------------------------------------------//



	//header("Location: http://localhost/sicofi/formularios/impresionSF2.php?start=".$noSolFon);
	// header("Location: http://172.26.26.126/sicofi/formularios/impresionSF2.php?start=".$noSolFon);
 echo '
		<script type="text/javascript">
			 window.open("http://172.26.26.126/sicofi/formularios/impresionSF2.php?start='.$noSolFon.'");
			 window.location.href = "http://172.26.26.126/sicofi/formularios/impresionSF.php"; 
		</script>
 ';

	mysqli_close($con);
	die();

?>
