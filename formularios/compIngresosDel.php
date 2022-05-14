<?php
	require_once('../config.php');
	require_once('menu.php');
	session_start();
	header('Content-Type: text/html; charset=UTF-8');

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("Problemas con la conexi&oacute;n a la base de datos");
	$con->query("SET NAMES 'utf8'");

	$caratula = $_GET['codigo'];
	$noSolFon = $_GET['codigo2'];

if(!isset($_GET['val']))
{
	echo'
		<html>
			<head>
				<title> Eliminar Comprobaci&oacute;n </title>

				<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
				<link rel="stylesheet" type="text/css" href="../css2/registro.css"></link>

				<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
				<script type="text/javascript" src="js/script.js"></script>		<!-- Efectos del Menú -->
			</head>

			<body>';

				if($_SESSION == NULL)
				{
					echo $menu[0];
				}
				else
				{
					$nivel = $_SESSION['nivel'];

					switch($nivel)
					{
						case 1:		echo $menu[1];
							break;

						case 2:		echo $menu[2];
							break;

						case 3:		echo $menu[3];
							break;

						case 4:		echo $menu[4];
							break;

						case 5:		echo $menu[5];
							break;
					}
				}
	echo'
				<fieldset id="base">
					<fieldset id="formulario">
						Deseas eliminar la comprobacion ?<br><br>
						<a href="compIngresosDel.php?codigo='.$caratula.'&codigo2='.$noSolFon.'&val=1"><input type="button" value="SI"></a>
						<a href="compIngresos3.php?codigo='.$noSolFon.'"><input type="button" value="NO"></a>
					</fielset>
				</fieldset>
			</body>
		</html>
	';
}

else if($_GET['val'] == 1)
{
	$numCar = explode("C - ", $caratula);
	$numCar = $numCar[1];

	//AREA DE SENTENCIAS A LA DB
	$ingresos = mysqli_query($con, "SELECT * FROM ingresos WHERE noSolFon = $noSolFon") or die(mysqli_error($con));
	if($reg = mysqli_fetch_array($ingresos)) {
		$currentComprobado = $reg['comprobado'];
	}

	$datosCaratula = mysqli_query($con, "SELECT * FROM comprobacion WHERE noSolFon = $noSolFon AND caratula LIKE '%$caratula%'") or die(mysqli_error($con));
	if($reg = mysqli_fetch_array($datosCaratula)) {
		$currentMontoCaratula = $reg['comprobado'];
	}

	$newComprobado = $currentComprobado - $currentMontoCaratula;

	
	if( $_SESSION['anio'] < 2019 ){
		mysqli_query($con, "UPDATE ingresos SET
					 nomComp".$numCar." = '',					 monto".$numCar." = 0,
					 comprobado = $newComprobado					 WHERE noSolFon = $noSolFon")	or die(mysqli_error($con));
	} else {
		mysqli_query($con, "UPDATE ingresos SET
					 comprobado = $newComprobado WHERE noSolFon = $noSolFon")	or die(mysqli_error($con));
	}
	

	
	mysqli_query($con, "DELETE FROM comprobacion WHERE noSolFon = $noSolFon	AND	caratula = '$caratula'") or die ("Problemas con la conexi&oacute;n a comprobacion");
	

	
	if ( $_SESSION['anio'] >= 2020) {
		$query = "UPDATE liscomp SET comentario = 'carátula eliminada / por: ". $_SESSION['id'] ." ',
									fecha_mod = '". date('Y-m-d') ."',
									active = 0 
								WHERE noSolFon = $noSolFon	
								AND caratula = '$caratula'";
	} else {
		$query = "DELETE FROM liscomp WHERE noSolFon = $noSolFon	AND caratula = '$caratula'";
	}
	
	mysqli_query($con, $query) or die ("Problemas con la conexi&oacute;n a lisComp");
	

	
	mysqli_close($con);
	
	header('location:compIngresos3.php?codigo='.$noSolFon);
	
}
?>
