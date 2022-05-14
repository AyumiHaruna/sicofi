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

	//Test if SF start is selected, if not, stop and return message
	if( !isset( $_GET['start'] ) )
	{
		echo 'No se seleccionó ninguna solicitud de fondos';
		return null;
	}
	//is exists, asign to a variable
	else
	{
		$sfStart = $_GET['start'];
	}
	$sfEnd = ( isset($_GET['end']) )? $_GET['end'] : null ;

	//---------------------------------------------------
	//				CONSULTAS A LAS BASES DE DATOS
	//---------------------------------------------------
	//there is a range o data (start & end)
	if( $sfEnd != null )
	{
		$query = mysqli_query($con, "SELECT ing.*, pro.nombreProyecto, pro.numeroProyecto FROM ingresos AS ing JOIN proyectos AS pro ON ing.numProy = pro.numeroProyecto WHERE ing.noSolFon BETWEEN $sfStart AND $sfEnd") or die(mysqli_error($query));
	}
	//if there isnt a range of data ( just start )
	else
	{
		$query = mysqli_query($con, "SELECT ing.*, pro.nombreProyecto, pro.numeroProyecto FROM ingresos AS ing JOIN proyectos AS pro ON ing.numProy = pro.numeroProyecto WHERE ing.noSolFon = $sfStart") or die(mysqli_error($query));
	}

	// $ingresos = [];
	//get "ingresos" query resutlss
	while($reg = mysqli_fetch_assoc($query))
	{
		$ingresos[] = $reg;
	}

	//now lets get SF->LISTA D EPARTIDAS for each result
	for ($x=0; $x < count($ingresos) ; $x++) {
		//get details for each "SOLICITUD DE FONDOS"
		$query = mysqli_query($con, "SELECT par.*, zli.nomPartida FROM partidassf AS par JOIN zlistapartidas AS zli ON par.noPartida = zli.noPartida WHERE par.noSolFon = ".$ingresos[$x]['noSolFon']." ORDER BY np") or die(mysqli_error($query));
		$ingresos[$x]['listaPartidas'] = [];
		while($reg = mysqli_fetch_assoc($query))
		{
			$ingresos[$x]['listaPartidas'][] = $reg;
		}
	}

	//get autoridades
	$autoridades  = mysqli_query($con, "SELECT * FROM autoridades") or die(mysqli_error($autoridades));
	if($reg2 = mysqli_fetch_assoc($autoridades))
	{
		$titular = $reg2['titular'];
	}


	// print_r($ingresos[0]);


	//---------------------------------------------------
	//								PRINTING S.F.
	//---------------------------------------------------
	//configuramos la pagina a tamaño carta
	$pdf = new FPDF('P','cm', array(21.59 , 29.00 ));

	for ($x=0; $x < count($ingresos); $x++) {
		$partidasPorHoja = 25;
		$noHojas = 1 + (intval(count($ingresos[$x]['listaPartidas']) / $partidasPorHoja));

		//con este codigo conseguiremos el día de la semana
		$fechaDia = date("w", strtotime($ingresos[$x]['fechaElab']));
			switch($fechaDia){
			case '0':		  $fechaDia = ("Domingo");		  break;
			case '1':		  $fechaDia = ("Lunes");		  break;
			case '2':		  $fechaDia = ("Martes");		  break;
			case '3':		  $fechaDia = ("Miercoles");	  break;
			case '4':		  $fechaDia = ("Jueves");		  break;
			case '5':		  $fechaDia = ("Viernes");		  break;
			case '6':		  $fechaDia = ("Sabado");		  break;
			}

			//con este codigo conseguiremos el mes con letras
			$fechaMes = date("m", strtotime($ingresos[$x]['fechaElab']));
			 switch($fechaMes){
			case '01':	  $fechaMes = ("Enero");		  break;
			case '02':	  $fechaMes = ("Febrero");		  break;
			case '03':	  $fechaMes = ("Marzo");		  break;
			case '04':	  $fechaMes = ("Abril");		  break;
			case '05':	  $fechaMes = ("Mayo");			  break;
			case '06':	  $fechaMes = ("Junio");		  break;
			case '07':	  $fechaMes = ("Julio");		  break;
			case '08':	  $fechaMes = ("Agosto");		  break;
			case '09':	  $fechaMes = ("Septiembre");	  break;
			case '10':	  $fechaMes = ("Octubre");		  break;
			case '11':	  $fechaMes = ("Noviembre");	  break;
			case '12':	  $fechaMes = ("Diciembre");	  break;
			}

		$fechaD = date("d", strtotime($ingresos[$x]['fechaElab']));
		$fechaY = date("Y", strtotime($ingresos[$x]['fechaElab']));

		// genera un formato por cada hoja
		for ($y=1; $y <= $noHojas ; $y++) {
			// print_r( json_encode($ingresos[$x]) );
			// echo '<br><br>';

			$pdf -> AddPage();					//generamos la primer pagina
			//Insertaremos la imagen de fondo para la poliza
			$pdf -> Image('../imagen/formatoSF.jpg','0','0','21.59','27.94','JPG');

			//No de página
			$pdf -> SetXY(18.5, 1.5);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			$pdf -> Cell(0, 1, 'Pag. '.$y.' / '.$noHojas, 0, 1, 'L');

			//solicitud de fondos
			$pdf -> SetXY(2, 3);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			$pdf -> Cell(0, 1, $ingresos[$x]['noSolFon'], 0, 1, 'L');

			//fecha de Elaboracion
			$pdf -> SetXY(4, 3);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			$pdf -> Cell(0, 1, $ingresos[$x]['fechaElab'], 0, 1, 'L');

			//nombre del centro de trabajo
			$pdf -> SetXY(6.2, 3);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			$pdf -> Cell(0, 1, utf8_decode('COORDINACION NACIONAL DE CONSERVACION DEL PATRIMONIO CULTURAL'), 0, 1, 'L');

			//clave del centro de trabajo
			$pdf -> SetXY(18, 3);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			// $pdf -> Cell(0, 1, '093DR20200', 0, 1, 'L');
			$pdf -> Cell(0, 1, '53000', 0, 1, 'L');

			//tipo de operacion
				//select operacion
				if($ingresos[$x]['operacion'] == 'INVERSION')
				{
				$pdf -> SetXY(3.8, 4.5);
				$pdf -> SetFont('Arial', '', 8); //tipo de fuente
				$pdf -> Cell(0, 1, 'X', 0, 1, 'L');
				}

				if($ingresos[$x]['operacion'] == 'PROYECTOS')
				{
				$pdf -> SetXY(3.8, 5.1);
				$pdf -> SetFont('Arial', '', 8); //tipo de fuente
				$pdf -> Cell(0, 1, 'X', 0, 1, 'L');
				}

				if($ingresos[$x]['operacion'] == 'TERCEROS')
				{
				$pdf -> SetXY(3.8, 5.55);
				$pdf -> SetFont('Arial', '', 8); //tipo de fuente
				$pdf -> Cell(0, 1, 'X', 0, 1, 'L');
				}

			//numero de proyecto
			$pdf -> SetXY(15, 3.9);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			$pdf -> Cell(0, 1, $ingresos[$x]['numProy'], 0, 1, 'L');

			//nombre de proyecto
			$pdf -> SetXY(12, 5);
			$pdf -> SetFont('Arial', '', 7);
			$pdf -> MultiCell(7, 0.3, $ingresos[$x]['nombreProyecto'] , 0);

			//a favor de:
			$pdf -> SetXY(3.8, 7);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			$pdf -> Cell(0, 1, $titular, 0, 1, 'L');

			//corresponde al mes de:
			$pdf -> SetXY(17, 7.3);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			$pdf -> Cell(0, 1,  $ingresos[$x]['mes'].' DE '. $fechaY, 0, 1, 'L');

			$rangoStart = ($partidasPorHoja * $y) - ($partidasPorHoja - 1);
			$rangoEnd = $partidasPorHoja * $y;

			// echo $rangoStart.' - '. $rangoEnd;
			// echo '<br><br>';

			$valX = 10.45;		$valY = 9.3;
			for ($z=($rangoStart-1); $z <= ($rangoEnd-1); $z++) {
				// print_r( $ingresos[$x]['listaPartidas'][0] );
				// echo '<br><br>';
				if( isset( $ingresos[$x]['listaPartidas'][$z] ) )
				{
					$pdf -> SetXY($valX, $valY);
					$pdf -> SetFont('Arial', '', 5.5); //tipo de fuente
					$pdf -> Cell(0, 1,  ($z+1).'.- '.$ingresos[$x]['listaPartidas'][$z]['noPartida'].' '. substr($ingresos[$x]['listaPartidas'][$z]['nomPartida'], 0, 50) , 0, 1, 'L');

					$pdf -> SetXY(18, $valY);
					$pdf -> SetFont('Arial', '', 7); //tipo de fuente
					$pdf -> Cell(0, 1,  '$'.number_format($ingresos[$x]['listaPartidas'][$z]['importe'], 2,'.',',') , 0, 1, 'L');

					$valY = $valY + 0.408;
				}
				else
				{
					break;
				}
			}

			if( $y == $noHojas ){
				//Colocamos el importe total al final de la lista
				$pdf -> SetXY(17.8, 21.1);
				$pdf -> SetFont('Arial', '', 8); //tipo de fuente
				$pdf -> Cell(0, 1, '$'.number_format($ingresos[$x]['SFtotal'], 2,'.',','), 0, 1, 'L');

				//Importe total en numero y letra
				$pdf -> SetXY(5, 22);
				$pdf -> SetFont('Arial', '', 8); //tipo de fuente
				$pdf -> Cell(0, 1, '$'.number_format($ingresos[$x]['SFtotal'], 2,'.',','), 0, 1, 'L');

				$pdf -> SetXY(7.1, 22);
				$pdf -> SetFont('Arial', '', 8); //tipo de fuente
				$pdf -> Cell(0, 1,' ('.MontoMonetarioEnLetras($ingresos[$x]['SFtotal']).')', 0, 1, 'L');

			} else {
				//Colocamos el importe total al final de la lista
				$pdf -> SetXY(17.8, 21.1);
				$pdf -> SetFont('Arial', '', 8); //tipo de fuente
				$pdf -> Cell(0, 1, '- - - - -', 0, 1, 'L');

				//Importe total en numero y letra
				$pdf -> SetXY(5, 22);
				$pdf -> SetFont('Arial', '', 8); //tipo de fuente
				$pdf -> Cell(0, 1, '- - - - -', 0, 1, 'L');

				$pdf -> SetXY(7.1, 22);
				$pdf -> SetFont('Arial', '', 8); //tipo de fuente
				$pdf -> Cell(0, 1, '- - - - -', 0, 1, 'L');
			}

			//OBSERVACIONES
			$pdf -> SetXY(5, 23.3);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			$pdf -> Cell(0, 1, 'RECURSOS CORRESPONDIENTES AL MES DE '.$ingresos[$x]['mes'].' DE '. $fechaY, 0, 1, 'L');

			$pdf -> SetXY(4, 25.3);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			$pdf -> Cell(0, 1, ' COORDINADORA NACIONAL ', 0, 1, 'L');

			$pdf -> SetXY(3.8, 25.8);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			$pdf -> Cell(0, 1, $titular, 0, 1, 'L');
		}

		//------------------------------------------------- INICIA SEGUNDO FORMATO -----------------------------------------------------

		for ($y=1; $y <= $noHojas ; $y++) {
			$pdf -> AddPage();

			//Insertaremos la imagen de fondo para la poliza
			$pdf -> Image('../imagen/CompGastos.jpg','0','0','21.59','27.94','JPG');
			//IMAGE (RUTA,X,Y,ANCHO,ALTO,EXTEN)

			//No de página
			$pdf -> SetXY(18.5, 0.9);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			$pdf -> Cell(0, 1, 'Pag. '.$y.' / '.$noHojas, 0, 1, 'L');

			//select operacion
			if($ingresos[$x]['operacion'] == 'INVERSION')
			{
			$pdf -> SetXY(6.85, 3.7);
			$pdf -> SetFont('Arial', '', 10); //tipo de fuente
			$pdf -> Cell(0, 1, 'X', 0, 1, 'L');
			}

			if($ingresos[$x]['operacion'] == 'PROYECTOS')
			{
			$pdf -> SetXY(15.85, 3.7);
			$pdf -> SetFont('Arial', '', 10); //tipo de fuente
			$pdf -> Cell(0, 1, 'X', 0, 1, 'L');
			}

			if($ingresos[$x]['operacion'] == 'TERCEROS')
			{
			$pdf -> SetXY(11.3, 3.7);
			$pdf -> SetFont('Arial', '', 10); //tipo de fuente
			$pdf -> Cell(0, 1, 'X', 0, 1, 'L');
			}

			//nombre de proyecto
			$pdf -> SetXY(13, 4.8);
			$pdf -> SetFont('Arial', '', 5);
			$pdf -> MultiCell(7, 0.3, $ingresos[$x]['nombreProyecto'] , 0);
			//$pdf -> MultiCell(7, 0.3, utf8_decode($nombreProyecto) , 0);

			//a favor de:
			$pdf -> SetXY(2.5, 6);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			$pdf -> Cell(0, 1, $titular, 0, 1, 'L');

			//Clave:
			$pdf -> SetXY(9.4, 6.1);
			$pdf -> SetFont('Arial', '', 7); //tipo de fuente
			$pdf -> Cell(0, 1, '(1003644)', 0, 1, 'L');

			//MES:
			$pdf -> SetXY(9.4, 6.1);
			$pdf -> SetFont('Arial', '', 7); //tipo de fuente
			$pdf -> Cell(0, 1, '(1003644)', 0, 1, 'L');

			//corresponde al mes de:
			$pdf -> SetXY(15, 6);
			$pdf -> SetFont('Arial', '', 10); //tipo de fuente
			$pdf -> Cell(0, 1, $ingresos[$x]['mes'].' DE '. $fechaY, 0, 1, 'L');

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

			//INICIAMOS EL FOR QUE REGISTRARA TODAS LAS PARTIDAS QUE HEMOS
			$rangoStart2 = ($partidasPorHoja * $y) - ($partidasPorHoja - 1);
			$rangoEnd2 = $partidasPorHoja * $y;
			$valX = 10.41;		$valY = 8.3;
			for ($z=($rangoStart2-1); $z <= ($rangoEnd2-1); $z++) {
				if( isset( $ingresos[$x]['listaPartidas'][$z] ) ){
					$pdf -> SetXY(6.4, $valY);
					$pdf -> SetFont('Arial', '', 6); //tipo de fuente
					$pdf -> Cell(0, 1,  $ingresos[$x]['listaPartidas'][$z]['noPartida'], 0, 1, 'L');

					$pdf -> SetXY(7.3, $valY);
					$pdf -> SetFont('Arial', '', 7); //tipo de fuente
					$pdf -> Cell(0, 1, substr($ingresos[$x]['listaPartidas'][$z]['nomPartida'], 0, 44) , 0, 1, 'L');

					$pdf -> SetXY(15.5, $valY);
					$pdf -> SetFont('Arial', '', 7); //tipo de fuente
					$pdf -> Cell(0, 1,  '$'.number_format($ingresos[$x]['listaPartidas'][$z]['importe'], 2,'.',',') , 0, 1, 'L');

					$valY = $valY + 0.504;
				}	else {
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

			if( $y == $noHojas ){
				//Colocamos el importe total al final de la lista
				$pdf -> SetXY(18.3, 21.9);
				$pdf -> SetFont('Arial', '', 8); //tipo de fuente
				$pdf -> Cell(0, 1, '$'.number_format($ingresos[$x]['SFtotal'], 2,'.',','), 0, 1, 'L');

				$pdf -> SetXY(15.5, 22.4);
				$pdf -> SetFont('Arial', '', 8); //tipo de fuente
				$pdf -> Cell(0, 1, '$'.number_format($ingresos[$x]['SFtotal'], 2,'.',','), 0, 1, 'L');

				$pdf -> SetXY(18.3, 22.4);
				$pdf -> SetFont('Arial', '', 8); //tipo de fuente
				$pdf -> Cell(0, 1, '$'.number_format($ingresos[$x]['SFtotal'], 2,'.',','), 0, 1, 'L');
			} else {
				//Colocamos el importe total al final de la lista
				$pdf -> SetXY(18.3, 21.9);
				$pdf -> SetFont('Arial', '', 8); //tipo de fuente
				$pdf -> Cell(0, 1, '- - - - -', 0, 1, 'L');

				$pdf -> SetXY(15.5, 22.4);
				$pdf -> SetFont('Arial', '', 8); //tipo de fuente
				$pdf -> Cell(0, 1, '- - - - -', 0, 1, 'L');

				$pdf -> SetXY(18.3, 22.4);
				$pdf -> SetFont('Arial', '', 8); //tipo de fuente
				$pdf -> Cell(0, 1, '- - - - -', 0, 1, 'L');
			}

			//Firma del titular de la dependencia
			$pdf -> SetXY(8, 24);
			$pdf -> SetFont('Arial', '', 8); //tipo de fuente
			$pdf -> Cell(0, 1, $titular, 0, 1, 'L');
		}
	}

	//Fin y salida del archivo
	$pdf -> Output("reportPoliza.pdf", "I");		//archivo de salida
	mysqli_close($con);

	//---------------------------------------------------
	//							FUNCIONES GENERALES
	//---------------------------------------------------
	//INTERCAMBIA CANTIDADES DE NUMEROS A LETRAS
	function NumerosALetras($importeTotal)
  {
      $maximo = pow(10,9);
          $unidad            = array(1=>"UNO", 2=>"DOS", 3=>"TRES", 4=>"CUATRO", 5=>"CINCO", 6=>"SEIS", 7=>"SIETE", 8=>"OCHO", 9=>"NUEVE");
          $decena            = array(10=>"DIEZ", 11=>"ONCE", 12=>"DOCE", 13=>"TRECE", 14=>"CATORCE", 15=>"QUINCE", 20=>"VEINTE", 30=>"TREINTA", 40=>"CUARENTA", 50=>"CINCUENTA", 60=>"SESENTA", 70=>"SETENTA", 80=>"OCHENTA", 90=>"NOVENTA");
          $prefijo_decena    = array(10=>"DIECI", 20=>"VEINTI", 30=>"TREINTA Y ", 40=>"CUARENTA Y ", 50=>"CINCUENTA Y ", 60=>"SESENTA Y ", 70=>"SETENTA Y ", 80=>"OCHENTA Y ", 90=>"NOVENTA Y ");
          $centena           = array(100=>"CIEN", 200=>"DOSCIENTOS", 300=>"TRESCIENTOS", 400=>"CUATROCIENTOS", 500=>"QUINIENTOS", 600=>"SEISCIENTOS", 700=>"SETECIENTOS", 800=>"OCHOCIENTOS", 900=>"NOVECIENTOS");
          $prefijo_centena   = array(100=>"CIENTO ", 200=>"DOSCIENTOS ", 300=>"TRESCIENTOS ", 400=>"CUATROCIENTOS ", 500=>"QUINIENTOS ", 600=>"SEISCIENTOS ", 700=>"SETECIENTOS ", 800=>"OCHOCIENTOS ", 900=>"NOVECIENTOS ");
          $sufijo_miles      = "MIL";
          $sufijo_millon     = "UN MILLON";
          $sufijo_millones   = "MILLONES";

          //echo var_dump($importeTotal); die;

          $base         = strlen(strval($importeTotal));
          $pren         = intval(floor($importeTotal/pow(10,$base-1)));
          $prencentena  = intval(floor($importeTotal/pow(10,3)));
          $prenmillar   = intval(floor($importeTotal/pow(10,6)));
          $resto        = $importeTotal%pow(10,$base-1);
          $restocentena = $importeTotal%pow(10,3);
          $restomillar  = $importeTotal%pow(10,6);

          if (!$importeTotal) return "";

      if (is_int($importeTotal) && $importeTotal>0 && $importeTotal < abs($maximo))
      {
                  switch ($base) {
                          case 1: return $unidad[$importeTotal];
                          case 2: return array_key_exists($importeTotal, $decena)  ? $decena[$importeTotal]  : $prefijo_decena[$pren*10]   . NumerosALetras($resto);
                          case 3: return array_key_exists($importeTotal, $centena) ? $centena[$importeTotal] : $prefijo_centena[$pren*100] . NumerosALetras($resto);
                          case 4: case 5: case 6: return ($prencentena>1) ? NumerosALetras($prencentena). " ". $sufijo_miles . " " . NumerosALetras($restocentena) : $sufijo_miles. " " . NumerosALetras($restocentena);
                          case 7: case 8: case 9: return ($prenmillar>1)  ? NumerosALetras($prenmillar). " ". $sufijo_millones . " " . NumerosALetras($restomillar)  : $sufijo_millon. " " . NumerosALetras($restomillar);
                  }
      } else {
          echo "ERROR con el numero - $importeTotal<br/> Debe ser un numero entero menor que " . number_format($maximo, 0, ".", ",") . ".";
      }

          //return $texto;

  }

	//AGREGA EL TEXTO DE TIPO DE CAMBIO
	function MontoMonetarioEnLetras($monto)
	{

					$monto = str_replace(',','',$monto); //ELIMINA LA COMA

					$pos = strpos($monto, '.');

					if ($pos == false)      {
									$monto_entero = $monto;
									$monto_decimal = '0';
					}else{
									$monto_entero = substr($monto,0,$pos);
									$monto_decimal = substr($monto,$pos,strlen($monto)-$pos);
									$monto_decimal = $monto_decimal * 100;
					}

					$monto = (int)($monto_entero);

					if($monto_decimal < 10)
			{$texto_con = " PESOS 0$monto_decimal/100 M.N.";}
		else
			{$texto_con = " PESOS $monto_decimal/100 M.N.";}

		return NumerosALetras($monto).$texto_con;
				// echo NumerosALetras($monto).$texto_con;

	}
?>
