<?php
	//---------------------------------------------------
	//										CONFIGURACIONES
	//---------------------------------------------------
	require_once('../config.php');
	require_once("../fpdf/fpdf.php");	//Llamamos la librería
	session_start();
	header('Content-Type: text/html; charset=UTF-8');
	set_time_limit(0);
	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("Problemas con la conexi&oacute;n a la base de datos");

	//---------------------------------------------------
	//							VARIABLES GLOBALES
	//---------------------------------------------------
	$caratula = $_GET['codigo'];
	$noSolFon = $_GET['codigo2'];
	//AREA DE CONSULTA Y CONEXION A DB
	$caratula = $_GET['codigo'];

	//---------------------------------------------------
	//				CONSULTAS A LAS BASES DE DATOS
	//---------------------------------------------------
	//Datos de INGRESOS
	$ingresos = mysqli_query($con, "SELECT * FROM ingresos WHERE noSolFon = $noSolFon")
		or die(mysqli_error($con));

	while($reg = mysqli_fetch_array($ingresos))
	{
		$tipo = $reg['tipo'];
		$operacion = $reg['operacion'];
		$numProy = $reg['numProy'];
		$validado = $reg['validado'];
		$mes = $reg['mes'];
	}

	//Datos de COMPROBACION
	$comprobacion = mysqli_query($con, "SELECT * FROM comprobacion WHERE noSolFon = $noSolFon AND caratula = '$caratula'")
		or die(mysqli_error($con));

	while($reg = mysqli_fetch_array($comprobacion))
	{
		$trans = $reg['trans'];
		$fechaElab = $reg['fechaElab'];
		$comprobado = $reg['comprobado'];
		$tipoComp = $reg['tipoComp'];
		$fechaAct  =$reg['fechaAct'];
	}

	$anio = $_SESSION['anio'];

//Datos de LISCOMP
	if( $_SESSION['anio'] >= 2020 ) {
		$query = "SELECT * FROM liscomp WHERE noSolFon = $noSolFon AND caratula = '$caratula' AND active = 1";
	} else {
		$query = "SELECT * FROM liscomp WHERE noSolFon = $noSolFon AND caratula = '$caratula'";
	}
	$liscomp = mysqli_query($con, $query) or die(mysqli_error($con));
	
	$x = 1;
	$importeTotal = 0;
	while ($reg = mysqli_fetch_array($liscomp))
	{
		$partida[$x] = $reg['partida'];

		//buscaremos el nombre de la partida en la DB LISTAPARTIDAS

		$zlistapartidas = mysqli_query($con, "SELECT nomPartida FROM zlistapartidas WHERE noPartida = '$partida[$x]'")
			or die(mysqli_error($con));
		while ($reg1 = mysqli_fetch_array($zlistapartidas))
		{
			$nombrePartida[$x] = $reg1['nomPartida'];
		}

		$noNotas[$x] = $reg['noNotas'];
		$monto[$x] = $reg['monto'];
		$importeTotal = $monto[$x] + $importeTotal;
		$x++;
	}

//Datos de PROYECTOS
	$proyectos = mysqli_query($con, "SELECT nombreProyecto FROM proyectos WHERE numeroProyecto =  $numProy")
		or die(mysqli_error($con));

	while($reg = mysqli_fetch_array($proyectos))
	{
		$nombreProyecto = $reg['nombreProyecto']		;
	}

//Datos de AUTORIDADES
	$autoridades = mysqli_query($con, "SELECT * FROM  autoridades")
		or die(mysqli_error($con));
	while ($reg = mysqli_fetch_array($autoridades))
	{
		$titular = $reg['titular'];
	}


	//---------------------------------------------------
	//								PRINTING S.F.
	//---------------------------------------------------
	//Creamos la pagina

	$pdf = new FPDF('P','cm', array(21.59 , 29.00 ));	//creamos la pagina de tamaño carta 27.94
	$partidasPorHoja = 25;
	$noHojas = 1 + (intval(count($partida) / $partidasPorHoja));

	for ($y=1; $y <= $noHojas ; $y++) {

			$pdf -> AddPage();					//generamos la primer pagina
			//Insertaremos la imagen de fondo para la poliza
			$pdf -> Image('../imagen/CompGastos.jpg','0','0','21.59','27.94','JPG');
			//IMAGE (RUTA,X,Y,ANCHO,ALTO,EXTEN)

			//No de página
			$pdf -> SetXY(13, 0.5);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			$pdf -> Cell(0, 1, 'Pag. '.$y.' / '.$noHojas, 0, 1, 'L');

			$pdf -> SetXY(18, 0.5);
			$pdf -> SetFont('Arial', '', 10); //tipo de fuente
			$pdf -> Cell(0, 1, $caratula, 0, 1, 'L');
			//select operacion
			if($operacion == 'INVERSION')
			{
			$pdf -> SetXY(6.85, 3.7);
			$pdf -> SetFont('Arial', '', 10); //tipo de fuente
			$pdf -> Cell(0, 1, 'X', 0, 1, 'L');
			}

			if($operacion == 'PROYECTOS')
			{
			$pdf -> SetXY(15.85, 3.7);
			$pdf -> SetFont('Arial', '', 10); //tipo de fuente
			$pdf -> Cell(0, 1, 'X', 0, 1, 'L');
			}

			if($operacion == 'TERCEROS')
			{
			$pdf -> SetXY(11.3, 3.7);
			$pdf -> SetFont('Arial', '', 10); //tipo de fuente
			$pdf -> Cell(0, 1, 'X', 0, 1, 'L');
			}

			//select tipoComp|
			if($tipoComp == 'TOTAL')
			{
			$pdf -> SetXY(6.85, 4.6);
			$pdf -> SetFont('Arial', '', 10); //tipo de fuente
			$pdf -> Cell(0, 1, 'X', 0, 1, 'L');
			}

			if($tipoComp == 'PARCIAL')
			{
			$pdf -> SetXY(1.3, 4.6);
			$pdf -> SetFont('Arial', '', 10); //tipo de fuente
			$pdf -> Cell(0, 1, 'X', 0, 1, 'L');
			}

			//nombre de proyecto
			$pdf -> SetXY(13, 4.65);
			$pdf -> SetFont('Arial', '', 5);
			$pdf -> MultiCell(7, 0.3, $numProy.' - '.$nombreProyecto , 0);
			//$pdf -> MultiCell(7, 0.3, utf8_decode($nombreProyecto) , 0);

			//fecha de ministración
			$pdf -> SetXY(8.2, 5.75);
			$pdf -> SetFont('Arial', '', 6);
			$pdf -> MultiCell(7, 0.3, $fechaElab, 0);

			//Numero de Transferencia:
			$pdf -> SetXY(12, 5.75);
			$pdf -> SetFont('Arial', '', 6);
			$pdf -> MultiCell(7, 0.3, $trans, 0);

			//importe de la transferencia_
			$pdf -> SetXY(17, 5.75);
			$pdf -> SetFont('Arial', '', 6);
			$pdf -> MultiCell(7, 0.3, '$ '.number_format($validado, 2,'.',','), 0);

			//a favor de:
			$pdf -> SetXY(2.5, 6);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			$pdf -> Cell(0, 1, ''.$titular, 0, 1, 'L');

			//Clave:
			$pdf -> SetXY(9.8, 6.1);
			$pdf -> SetFont('Arial', '', 7); //tipo de fuente
			$pdf -> Cell(0, 1, '(9492)', 0, 1, 'L');

			//corresponde al mes de:
			$pdf -> SetXY(15, 6);
			$pdf -> SetFont('Arial', '', 10); //tipo de fuente
			$pdf -> Cell(0, 1, $mes.' DE '. $anio, 0, 1, 'L');

			//Cuenta:
			$pdf -> SetXY(1.5, 7.82);
			$pdf -> SetFont('Arial', '', 6); //tipo de fuente
			$pdf -> Cell(0, 1, '431000', 0, 1, 'L');

			//SubCuenta:
			$pdf -> SetXY(3.3, 7.82);
			$pdf -> SetFont('Arial', '', 6); //tipo de fuente
			$pdf -> Cell(0, 1, '530000', 0, 1, 'L');

			//SubCuenta:
			$pdf -> SetXY(5, 7.82);
			$pdf -> SetFont('Arial', '', 6); //tipo de fuente
			$pdf -> Cell(0, 1, '000001', 0, 1, 'L');


			$rangoStart = ($partidasPorHoja * $y) - ($partidasPorHoja - 1);
			$rangoEnd = $partidasPorHoja * $y;

			$valY = 7.82;
			for ($z=$rangoStart; $z <= ($rangoEnd-1); $z++) {
				if( isset( $partida[$z] ) )
				{
					// echo $partida[$z];
					// echo '<br><br>';
					$pdf -> SetXY(6.4, $valY);
					$pdf -> SetFont('Arial', '', 6); //tipo de fuente
					$pdf -> Cell(0, 1,  $partida[$z], 0, 1, 'L');

					$pdf -> SetXY(7.3, $valY);
					$pdf -> SetFont('Arial', '', 7); //tipo de fuente
					$pdf -> Cell(0, 1,  substr($nombrePartida[$z], 0, 44) , 0, 1, 'L');

					$pdf -> SetXY(14.5, $valY);
					$pdf -> SetFont('Arial', '', 6); //tipo de fuente
					$pdf -> Cell(0, 1,  $noNotas[$z], 0, 1, 'L');

					$pdf -> SetXY(15.5, $valY);
					$pdf -> SetFont('Arial', '', 7); //tipo de fuente
					$pdf -> Cell(0, 1,  '$'.number_format($monto[$z], 2,'.',',') , 0, 1, 'L');

					$valY = $valY + 0.504;
				}
				else
				{
					break;
				}
			}

			//Cuenta:
		$pdf -> SetXY(1.5, 21.9);
		$pdf -> SetFont('Arial', '', 6); //tipo de fuente
		$pdf -> Cell(0, 1, '114500', 0, 1, 'L');

		//SubCuenta:
		$pdf -> SetXY(3.3, 21.9);
		$pdf -> SetFont('Arial', '', 6); //tipo de fuente
		$pdf -> Cell(0, 1, '530000', 0, 1, 'L');

		//SubCuenta:
		$pdf -> SetXY(5, 21.9);
		$pdf -> SetFont('Arial', '', 6); //tipo de fuente
		$pdf -> Cell(0, 1, '006692', 0, 1, 'L');

		//P.E. TRANSF.
		$pdf -> SetXY(10.5, 21.9);
		$pdf -> SetFont('Arial', '', 8); //tipo de fuente
		$pdf -> Cell(0, 1, $trans, 0, 1, 'L');

		if( $y != $noHojas){
			//Colocamos el importe total al final de la lista
			$pdf -> SetXY(18.3, 21.9);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			$pdf -> Cell(0, 1, '- - - -', 0, 1, 'L');

			$pdf -> SetXY(15.5, 22.4);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			$pdf -> Cell(0, 1, '- - - -', 0, 1, 'L');

			$pdf -> SetXY(18.3, 22.4);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			$pdf -> Cell(0, 1, '- - - -', 0, 1, 'L');
		} else {
			//Colocamos el importe total al final de la lista
			$pdf -> SetXY(18.3, 21.9);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			$pdf -> Cell(0, 1, '$'.number_format($importeTotal, 2,'.',','), 0, 1, 'L');

			$pdf -> SetXY(15.5, 22.4);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			$pdf -> Cell(0, 1, '$'.number_format( array_sum($monto) , 2,'.',','), 0, 1, 'L');

			$pdf -> SetXY(18.3, 22.4);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			$pdf -> Cell(0, 1, '$'.number_format($importeTotal, 2,'.',','), 0, 1, 'L');
		}

		//fecha de elaboración
		$pdf -> SetXY(2.7, 24);
		$pdf -> SetFont('Arial', '', 10); //tipo de fuente
		$pdf -> Cell(0, 1, $fechaAct, 0, 1, 'L');

		//Firma del titular de la dependencia
		$pdf -> SetXY(8, 24);
		$pdf -> SetFont('Arial', '', 8); //tipo de fuente
		$pdf -> Cell(0, 1, ' '.$titular, 0, 1, 'L');
	}
	
	//Fin y salida del archivo
	$pdf -> Output("reportPoliza.pdf", "I");		//archivo de salida



//---------------------------------------------------
	mysqli_close($con);

?>
