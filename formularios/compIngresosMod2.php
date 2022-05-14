<?php
// print_r($_POST);
	require_once('../config.php');
	session_start();

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio']) or die("Problemas con la conexi&oacute;n a la base de datos");
	$con->query("SET NAMES 'utf8'");

	$noSolFon = $_POST['noSolFon'];
	$trans = $_POST['noAut'];
	$fechaElab = $_POST['fechaElab'];
	$fechaAct = $_POST['fecha2'];
	$comprobado = $_POST['total'];
	$carComp = $_POST['carComp'];
	 
	if( $_SESSION['anio'] >= 2020 ){	
		$comentario = $_POST['observaciones'];		
		if($comentario == "") {
			$comentario = "Se modificó la carátula sin definir algún motivo";
		}
		$comentario = $comentario . " / por: " . $_SESSION['id'];
	} 
	

	//-----------------------------------------------
	//	Obtendre las caratulas
	//-----------------------------------------------

	$caratula = $_POST['caratula'];

	//------------------------------------------------
	//		Obtendre lo comprobado hasta la fecha
	//------------------------------------------------
	$ingresos = mysqli_query($con, "SELECT * FROM ingresos WHERE noSolFon = $noSolFon") or die(mysqli_error($con));
	while($reg = mysqli_fetch_array($ingresos))
	{
		$validado = $reg['validado'];
	}

	$compT = 0;
	$comprobacionSearch = mysqli_query( $con, "SELECT * FROM comprobacion WHERE noSolFon = $noSolFon 
																		AND caratula != '$caratula'ORDER BY caratula" ) or die (mysqli_error($con));
	while($reg = mysqli_fetch_array($comprobacionSearch)){
		$compT = $compT + $reg['comprobado'];
	}

	//nuevo comprobado total
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
			$partida[$x] = $listaPartidas[$x]->partida;
			$noNotas[$x] = $listaPartidas[$x]->noNotas;
			$monto[$x] = $listaPartidas[$x]->monto;
	}

//----------------------------------------
//		imprimir prueba variables
//----------------------------------------

	echo '<br><br>';	
	echo 'noSolFon: '.$noSolFon.'<br>';
	echo 'trans: '.$trans.'<br>';
	echo 'fechaElab: '.$fechaElab.'<br>';
	echo 'comprobado: '.$comprobado.'<br>';
	echo 'caratula: '.$caratula.'<br>';
	echo 'tipoComp: '.$tipoComp.'<br>';
	echo 'fechaAct: '.$fechaAct.'<br>';
	if( $_SESSION['anio'] >= 2020 ){	
		echo 'Comentario: '.$comentario.'<br><br>';	
	}

	for($x = 0; $x < count($partida); $x++)
	{
		echo 'partida - '.$x.': '.$partida[$x].'<br>';
		echo 'noNotas - '.$x.': '.$noNotas[$x].'<br>';
		echo 'monto - '.$x.': '.$monto[$x].'<br><br>';
	}

	echo 'validado: ' .$validado .'<br>';
	echo 'comprobadoTotal: ' . $compT .'<br>';;


	//--------------------------------------------------------
	//
	//		Modificamos las partidas de lisComp para inactivando las viejas
	//--------------------------------------------------------

	if( $_SESSION['anio'] < 2020 ){		//if is less than 2020 -> delete all lisComp
		$query = "DELETE FROM lisComp WHERE noSolFon = $noSolFon AND caratula = '$caratula'";
	} else { 	// if is from 2020 or above	-> update all active lisComp
		$query = "UPDATE lisComp SET  comentario = '$comentario', 
									fecha_mod = '$fechaAct',
									active = 0
								WHERE noSolFon = $noSolFon AND 
									caratula = '$caratula' AND 
									active = 1";
	}
	mysqli_query($con, $query) or die(mysqli_error($con));
	
	
	// agrega la lista de nuevas partidas para esta carátula 
	for($x = 0; $x < count($partida); $x++)
	{
		mysqli_query($con, "INSERT INTO liscomp(noSolFon, caratula, partida, noNotas, monto) VALUES($noSolFon, '$caratula', '$partida[$x]', $noNotas[$x], $monto[$x])")
			or die(mysqli_error($con));
	}

	
	//--------------------------------------------------------
	//
	//		Actualizamos los datos de la tabla Comprobacion
	//--------------------------------------------------------

	mysqli_query($con, "UPDATE comprobacion SET comprobado = $comprobado WHERE noSolFon = $noSolFon AND caratula = '$caratula'") or die(mysqli_error($con));

	//--------------------------------------------------------
	//
	//		Actualizamos los datos en ingresos
	//--------------------------------------------------------

	//INGRESOS

	$numCar = explode("C - ", $caratula);
	$numCar = $numCar[1];

	if($_SESSION['anio'] < 2019){
		mysqli_query($con, "UPDATE ingresos SET nomComp".$numCar." = '$caratula',
								monto".$numCar." = $comprobado,	comprobado = $compT
								WHERE noSolFon = $noSolFon") or die(mysqli_error($con));
	} else {
		mysqli_query($con, "UPDATE ingresos SET	comprobado = $compT
								WHERE noSolFon = $noSolFon") or die(mysqli_error($con));
	}

	
	//cierra la conexión a la base de datos 
 	mysqli_close($con);
	
	echo"<script type='text/javascript'>
				alert('Solicitud Enviada');
				window.open('compIngresos3.php?codigo=".$noSolFon."','','');
				window.open('compIngresosImp.php?codigo=".$caratula."&codigo2=".$noSolFon."','','status=yes, width=600, height=400, menubar=yes');
				window.close();
			</script>";
?>
