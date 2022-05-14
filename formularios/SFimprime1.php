<?php
	require_once('../config.php');
	require_once("../fpdf/fpdf.php");	//Llamamos la librería
	session_start();
	header('Content-Type: text/html; charset=UTF-8');

//Almacenamos la variable que pasó del anterior formulario
	$noSolFon = $_GET['noSolFon'];

//AREA DE CONSULTA Y CONEXION A DB

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("Problemas con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");

	$ingresos = mysqli_query($con, "SELECT * FROM ingresos
										WHERE noSolFon = $noSolFon")
			or die(mysqli_error($ingresos));

	$partidas  = mysqli_query($con, "SELECT * FROM partidassf
										WHERE noSolFon = $noSolFon
										ORDER BY np")
			or die(mysqli_error($partidas));

	$autoridades  = mysqli_query($con, "SELECT * FROM autoridades")
			or die(mysqli_error($autoridades));

//REGISTRAMOS TODAS LAS VARIABLES DE LA BUSQUEDA
//Variables de ingresos
	while($reg = mysqli_fetch_array($ingresos))
	{
		$fechaElaboracion = $reg['fechaElab'];			$operacion = $reg['operacion'];
		$numProy = $reg['numProy'];				$mes = $reg['mes'];
		$importeTotal = $reg['SFtotal'];

		$proyecto  = mysqli_query($con, "SELECT * FROM proyectos
										WHERE numeroProyecto = $numProy")
				or die(mysqli_error($proyecto));

		while($regp = mysqli_fetch_array($proyecto))
		{
			$nombreProyecto = $regp['nombreProyecto'];
		}

	}
//Variables de partidassf
	$x=1;
	while($reg1 = mysqli_fetch_array($partidas))
	{
		$noPartida[$x] =   $reg1['noPartida'];
		$importe[$x] = $reg1['importe'];

		$nomPart 	=	mysqli_query($con, "SELECT * FROM zlistapartidas
										WHERE noPartida = '$noPartida[$x]'")
					or die(mysqli_error($nomPart));
		while($regPart = mysqli_fetch_array($nomPart))
		{
			$nombrePart[$x]=  $regPart['nomPartida'];
		}
		$x ++;
	}

	if($reg2 = mysqli_fetch_array($autoridades))
	{
		$titular = $reg2['titular'];
	}
//AREA DE FUNCIONES

//CONVERTIR NUMEROS A LETRAS PARA EL importeTotal
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

	//FUNCION PARA AGREGAR EL TEXTO DE MONEDA
 //FUNCION PARA AGREGAR EL TEXTO DE MONEDA
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

	//con este codigo conseguiremos el día de la semana
	$fechaDia = date("w", strtotime($fechaElaboracion));
	  switch($fechaDia){
		case 0:		  $fechaDia = ("Domingo");		  break;
		case 1:		  $fechaDia = ("Lunes");		  break;
		case 2:		  $fechaDia = ("Martes");		  break;
		case 3:		  $fechaDia = ("Miercoles");	  break;
		case 4:		  $fechaDia = ("Jueves");		  break;
		case 5:		  $fechaDia = ("Viernes");		  break;
		case 6:		  $fechaDia = ("Sabado");		  break;
	  }

	  //con este codigo conseguiremos el mes con letras
	  $fechaMes = date("m", strtotime($fechaElaboracion));
	   switch($fechaMes){
		case 01:	  $fechaMes = ("Enero");		  break;
		case 02:	  $fechaMes = ("Febrero");		  break;
		case 03:	  $fechaMes = ("Marzo");		  break;
		case 04:	  $fechaMes = ("Abril");		  break;
		case 05:	  $fechaMes = ("Mayo");			  break;
		case 06:	  $fechaMes = ("Junio");		  break;
		case 07:	  $fechaMes = ("Julio");		  break;
		case 08:	  $fechaMes = ("Agosto");		  break;
		case 09:	  $fechaMes = ("Septiembre");	  break;
		case 10:	  $fechaMes = ("Octubre");		  break;
		case 11:	  $fechaMes = ("Noviembre");	  break;
		case 12:	  $fechaMes = ("Diciembre");	  break;
	  }

	$fechaD = date("d", strtotime($fechaElaboracion));
	$fechaY = date("Y", strtotime($fechaElaboracion));

		//inicia la elaboración de la poliza con fpdf
	require_once("../fpdf/fpdf.php");	//Llamamos la librería

//-----------------------------------------------------------

//-----------------------------------------------------------

	//Creamos la pagina

	$pdf = new FPDF('P','cm', array(21.59 , 29.00 ));	//creamos la pagina de tamaño carta 27.94
	//$pdf = new FPDF('L', 'cm', array(21, 8)); 	//creamos la instancia con dimenciones modificadas
	$pdf -> AddPage();					//generamos la primer pagina

	//Insertaremos la imagen de fondo para la poliza

	$pdf -> Image('../imagen/formatoSF.jpg','0','0','21.59','27.94','JPG');
			//IMAGE (RUTA,X,Y,ANCHO,ALTO,EXTEN)

	//Primer línea de la pagina comienza por el No. de Solicitud de Fondos
	$pdf -> SetXY(2, 3);
	$pdf -> SetFont('Arial', '', 8); //tipo de fuente
	$pdf -> Cell(0, 1, $noSolFon, 0, 1, 'L');

	//fecha de Elaboracion
	$pdf -> SetXY(4, 3);
	$pdf -> SetFont('Arial', '', 8); //tipo de fuente
	$pdf -> Cell(0, 1, $fechaElaboracion, 0, 1, 'L');

	//nombre del centro de trabajo
	$pdf -> SetXY(6.2, 3);
	$pdf -> SetFont('Arial', '', 8); //tipo de fuente
	$pdf -> Cell(0, 1, utf8_decode('COORDINACIÓN NACIONAL DE CONSERVACIÓN DEL PATRIMONIO CULTURAL'), 0, 1, 'L');

	//clave del centro de trabajo
	$pdf -> SetXY(18, 3);
	$pdf -> SetFont('Arial', '', 8); //tipo de fuente
	$pdf -> Cell(0, 1, '093DR20200', 0, 1, 'L');

	//select operacion
	if($operacion == 'INVERSION')
	{
	$pdf -> SetXY(3.8, 4.5);
	$pdf -> SetFont('Arial', '', 8); //tipo de fuente
	$pdf -> Cell(0, 1, 'X', 0, 1, 'L');
	}

	if($operacion == 'PROYECTOS')
	{
	$pdf -> SetXY(3.8, 5.1);
	$pdf -> SetFont('Arial', '', 8); //tipo de fuente
	$pdf -> Cell(0, 1, 'X', 0, 1, 'L');
	}

	if($operacion == 'TERCEROS')
	{
	$pdf -> SetXY(3.8, 5.55);
	$pdf -> SetFont('Arial', '', 8); //tipo de fuente
	$pdf -> Cell(0, 1, 'X', 0, 1, 'L');
	}

	//numero de proyecto
	$pdf -> SetXY(15, 3.9);
	$pdf -> SetFont('Arial', '', 8); //tipo de fuente
	$pdf -> Cell(0, 1, $numProy, 0, 1, 'L');

	//nombre de proyecto
	$pdf -> SetXY(12, 5);
	$pdf -> SetFont('Arial', '', 7);
	$pdf -> MultiCell(7, 0.3, $nombreProyecto , 0);
	//$pdf -> MultiCell(7, 0.3, utf8_decode($nombreProyecto) , 0);

	//a favor de:
	$pdf -> SetXY(3.8, 7);
	$pdf -> SetFont('Arial', '', 8); //tipo de fuente
	$pdf -> Cell(0, 1, $titular, 0, 1, 'L');

	//corresponde al mes de:
	$pdf -> SetXY(17.2, 7.3);
	$pdf -> SetFont('Arial', '', 8); //tipo de fuente
	$pdf -> Cell(0, 1, $mes.' DE '. $fechaY, 0, 1, 'L');

//INICIAMOS EL FOR QUE REGISTRARA TODAS LAS PARTIDAS QUE HEMOS INGRESADO

	$valX = 10.8;		$valY = 9.3;
	for($x = 1; $x <= count($noPartida); $x++)
	{
		$pdf -> SetXY($valX, $valY);
		$pdf -> SetFont('Arial', '', 5.5); //tipo de fuente
		$pdf -> Cell(0, 1,  $noPartida[$x].' - '. substr($nombrePart[$x], 0, 50) , 0, 1, 'L');

		$pdf -> SetXY(18, $valY);
		$pdf -> SetFont('Arial', '', 7); //tipo de fuente
		$pdf -> Cell(0, 1,  '$'.number_format($importe[$x], 2,'.',',') , 0, 1, 'L');

		$valY = $valY + 0.408;
	}

//Colocamos el importe total al final de la lista

	$pdf -> SetXY(17.8, 21.1);
	$pdf -> SetFont('Arial', '', 8); //tipo de fuente
	$pdf -> Cell(0, 1, '$'.number_format($importeTotal, 2,'.',','), 0, 1, 'L');

//Importe total en numero y letra

	$pdf -> SetXY(5, 22);
	$pdf -> SetFont('Arial', '', 8); //tipo de fuente
	$pdf -> Cell(0, 1, '$'.number_format($importeTotal, 2,'.',','), 0, 1, 'L');

	$pdf -> SetXY(7.1, 22);
	$pdf -> SetFont('Arial', '', 8); //tipo de fuente
	$pdf -> Cell(0, 1,' ('.MontoMonetarioEnLetras($importeTotal).')', 0, 1, 'L');

//OBSERVACIONES

	$pdf -> SetXY(5, 23.3);
	$pdf -> SetFont('Arial', '', 8); //tipo de fuente
	$pdf -> Cell(0, 1, 'RECURSOS CORRESPONDIENTES AL MES DE '.$mes.' DE '. $fechaY, 0, 1, 'L');

//NOMBRE Y FIRMA DEL TITULAR DEL AREA

	$pdf -> SetXY(4, 25.3);
	$pdf -> SetFont('Arial', '', 8); //tipo de fuente
	$pdf -> Cell(0, 1, ' COORDINADORA NACIONAL ', 0, 1, 'L');

	$pdf -> SetXY(3.8, 25.8);
	$pdf -> SetFont('Arial', '', 8); //tipo de fuente
	$pdf -> Cell(0, 1, $titular, 0, 1, 'L');

//------------------------------------------------- INICIA SEGUNA PAGINA -----------------------------------------------------

	$pdf -> AddPage();

		//Insertaremos la imagen de fondo para la poliza

	$pdf -> Image('../imagen/CompGastos.jpg','0','0','21.59','27.94','JPG');
			//IMAGE (RUTA,X,Y,ANCHO,ALTO,EXTEN)

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

	//nombre de proyecto
	$pdf -> SetXY(13, 4.8);
	$pdf -> SetFont('Arial', '', 5);
	$pdf -> MultiCell(7, 0.3, $nombreProyecto , 0);
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
	$pdf -> Cell(0, 1, $mes.' DE '. $fechaY, 0, 1, 'L');

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

	//INICIAMOS EL FOR QUE REGISTRARA TODAS LAS PARTIDAS QUE HEMOS INGRESADO

	$valY = 7.82;
	for($x = 1; $x <= count($noPartida); $x++)
	{
		$pdf -> SetXY(6.4, $valY);
		$pdf -> SetFont('Arial', '', 6); //tipo de fuente
		$pdf -> Cell(0, 1,  $noPartida[$x], 0, 1, 'L');

		$pdf -> SetXY(7.3, $valY);
		$pdf -> SetFont('Arial', '', 7); //tipo de fuente
		$pdf -> Cell(0, 1,  substr($nombrePart[$x], 0, 44) , 0, 1, 'L');

		$pdf -> SetXY(15.5, $valY);
		$pdf -> SetFont('Arial', '', 7); //tipo de fuente
		$pdf -> Cell(0, 1,  '$'.number_format($importe[$x], 2,'.',',') , 0, 1, 'L');

		$valY = $valY + 0.504;
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

	//Colocamos el importe total al final de la lista
	$pdf -> SetXY(18.3, 21.9);
	$pdf -> SetFont('Arial', '', 8); //tipo de fuente
	$pdf -> Cell(0, 1, '$'.number_format($importeTotal, 2,'.',','), 0, 1, 'L');

	$pdf -> SetXY(15.5, 22.4);
	$pdf -> SetFont('Arial', '', 8); //tipo de fuente
	$pdf -> Cell(0, 1, '$'.number_format($importeTotal, 2,'.',','), 0, 1, 'L');

	$pdf -> SetXY(18.3, 22.4);
	$pdf -> SetFont('Arial', '', 8); //tipo de fuente
	$pdf -> Cell(0, 1, '$'.number_format($importeTotal, 2,'.',','), 0, 1, 'L');

	//Firma del titular de la dependencia
	$pdf -> SetXY(8, 24);
	$pdf -> SetFont('Arial', '', 8); //tipo de fuente
	$pdf -> Cell(0, 1, $titular, 0, 1, 'L');

	//Fin y salida del archivo
	$pdf -> Output("reportPoliza.pdf", "I");		//archivo de salida

	mysqli_close($con);
?>
