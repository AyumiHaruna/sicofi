<?php
	//-----------------------------------------------
	//					CONFIGURACIONES
	//-----------------------------------------------
	require_once('../config.php');
	session_start();
	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio']) or die("Problemas con la conexi&oacute;n a la base de datos");
	$con->query("SET NAMES 'utf8'");

	$noSolFon = $_POST['noSolFon'];
	$trans = $_POST['noAut'];
	$fechaElab = $_POST['fechaElab'];
	$fechaAct = $_POST['fecha2'];
	$comprobado = $_POST['total'];

	//-----------------------------------------------
	//	Obtengo los datos del ingreso al que hace referencia
	//-----------------------------------------------
	$ingresosSearch = mysqli_query( $con, "SELECT * FROM ingresos WHERE noSolFon = $noSolFon") or die (mysqli_error($con));
	while($reg = mysqli_fetch_assoc($ingresosSearch)){
		$ingresos = $reg;
		$validado = $reg['validado'];
	}

	//-----------------------------------------------
	//	Obtendre las caratulas previamente capturadas y el nó de la nueva comprobacion
	//-----------------------------------------------
	$comprobacion = [];
	$compT = 0;
	$caratula = 0;
	$comprobacionSearch = mysqli_query( $con, "SELECT * FROM comprobacion WHERE noSolFon = $noSolFon ORDER BY caratula" ) or die (mysqli_error($con));
	while($reg = mysqli_fetch_array($comprobacionSearch)){
		$comprobacion[] = $reg;
		$compT = $compT + $reg['comprobado'];
		if( str_replace("C - ", "", $reg['caratula']) > $caratula){
			$caratula = str_replace("C - ", "", $reg['caratula']);
		}
	}

 	$caratula = ((int)$caratula) + 1;
	$caratula = 'C - '.$caratula;

	$compT = $compT + $comprobado;
	if($comprobado >= $validado - 1)
	{
		$tipoComp = 'TOTAL';
	}
	else
	{
		$tipoComp = 'PARCIAL';
	}

	$partida; $noNotas; $monto;
	$listaPartidas = json_decode($_POST['myJson']);
	for ($x=0; $x < count($listaPartidas); $x++) {
			$partida[$x] = $listaPartidas[$x]->noPartida;
			$noNotas[$x] = $listaPartidas[$x]->noNotas;
			$monto[$x] = $listaPartidas[$x]->monto;
	}

	// //----------------------------------------
	//		imprimir prueba variables
	//----------------------------------------

	echo 'noSolFon: '.$noSolFon.'<br>';
	echo 'trans: '.$trans.'<br>';
	echo 'fechaElab: '.$fechaElab.'<br>';
	echo 'comprobado: '.$comprobado.'<br>';
	echo 'caratula: '.$caratula.'<br>';
	echo 'tipoComp: '.$tipoComp.'<br>';
	echo 'fechaAct: '.$fechaAct.'<br><br>';

	for($x = 0; $x < count($partida); $x++)
	{
		echo 'partida'.$x.': '.$partida[$x].'<br>';
		echo 'noNotas'.$x.': '.$noNotas[$x].'<br>';
		echo 'monto'.$x.': '.$monto[$x].'<br><br>';
	}


	//--------------------------------------------------------
	//
	//		Registramos lod datos en sus DB correspondientes
	//--------------------------------------------------------

	//INGRESOS
	if($_SESSION['anio'] < 2019){
		mysqli_query($con, "UPDATE ingresos SET
						nomComp".$numCar." = '$caratula',	monto".$numCar." = $comprobado,
						comprobado = $compT	WHERE noSolFon = $noSolFon") or die(mysqli_error($con));
	} else {		//si es >= 2019 no necesitamos almacenar la caratula en ingresos
		mysqli_query($con, "UPDATE ingresos SET	comprobado = $compT	WHERE noSolFon = $noSolFon") or die(mysqli_error($con));
	}

	//	COMPROBACION
	mysqli_query($con, "INSERT INTO comprobacion(noSolFon, trans, fechaElab, comprobado, caratula, tipoComp, fechaAct) VALUES
	($noSolFon, '$trans', '$fechaElab', $comprobado, '$caratula', '$tipoComp', '$fechaAct')")
		or die(mysqli_error($con));

	//LISCOMP
	for($x = 0; $x < count($partida); $x++)
	{
		mysqli_query($con, "INSERT INTO liscomp(noSolFon, caratula, partida, noNotas, monto) VALUES($noSolFon, '$caratula', '$partida[$x]', $noNotas[$x], $monto[$x])")
			or die(mysqli_error($con));
	}

	mysqli_close($con);

	echo"<script type='text/javascript'>
				alert('Solicitud Enviada');
				window.open('compIngresos3.php?codigo=".$noSolFon."','','');
				window.open('compIngresosImp.php?codigo=".$caratula."&codigo2=".$noSolFon."','','status=yes, width=600, height=400, menubar=yes');
				window.close();
			</script>";
?>
