<?php
	require_once('../config.php');
	session_start();
	header('Content-Type: text/html; charset=UTF-8');

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("Problemas con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");

	//Declaramos y almacenamos Variables
	$fechaElaboracion 	= $_GET['a'];	$nombre 			= $_GET['b'];
	$cap1000 		= $_GET['c'];	$noCheque 			= $_GET['d'];
	$concepto			= $_GET['e'];	$proyecto 			='Nómina';
/*
	echo 'Fecha de Elaboraci&oacute;n:'.$fechaElaboracion.'<br>';
	echo 'Nombre del beneficiario:'.$nombre.'<br>';
	echo 'Importe Total:'.$cap1000.'<br>';
	echo 'No. de Cheque:'.$noCheque.'<br>';
	echo 'Concepto:'.$concepto.'<br>';
	echo 'Numero de Proyecto:'.$numeroProyecto.'<br>';
	echo 'Nombre del Proyecto:'.$nombreProyecto.'<br>';
*/

	$fechaD = date("d", strtotime($fechaElaboracion));
	$fechaY = date("Y", strtotime($fechaElaboracion));

	//CONVERTIR NUMEROS A LETRAS PARA EL MONTO
	function NumerosALetras($monto)
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

            //echo var_dump($monto); die;

            $base         = strlen(strval($monto));
            $pren         = intval(floor($monto/pow(10,$base-1)));
            $prencentena  = intval(floor($monto/pow(10,3)));
            $prenmillar   = intval(floor($monto/pow(10,6)));
            $resto        = $monto%pow(10,$base-1);
            $restocentena = $monto%pow(10,3);
            $restomillar  = $monto%pow(10,6);

            if (!$monto) return "";

        if (is_int($monto) && $monto>0 && $monto < abs($maximo))
        {
                    switch ($base) {
                            case 1: return $unidad[$monto];
                            case 2: return array_key_exists($monto, $decena)  ? $decena[$monto]  : $prefijo_decena[$pren*10]   . NumerosALetras($resto);
                            case 3: return array_key_exists($monto, $centena) ? $centena[$monto] : $prefijo_centena[$pren*100] . NumerosALetras($resto);
                            case 4: case 5: case 6: return ($prencentena>1) ? NumerosALetras($prencentena). " ". $sufijo_miles . " " . NumerosALetras($restocentena) : $sufijo_miles. " " . NumerosALetras($restocentena);
                            case 7: case 8: case 9: return ($prenmillar>1)  ? NumerosALetras($prenmillar). " ". $sufijo_millones . " " . NumerosALetras($restomillar)  : $sufijo_millon. " " . NumerosALetras($restomillar);
                    }
        } else {
            echo "ERROR con el numero - $monto<br/> Debe ser un numero entero menor que " . number_format($maximo, 0, ".", ",") . ".";
        }

            //return $texto;

    }
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
				case '01':	  $fechaMes = ("Enero");		  break;
				case '02':	  $fechaMes = ("Febrero");		  break;
				case '03':	  $fechaMes = ("Marzo");		  break;
				case '04':	  $fechaMes = ("Abril");		  break;
				case '05':	  $fechaMes = ("Mayo");			  break;
				case '06':	  $fechaMes = ("Junio");		  break;
				case '07':	  $fechaMes = ("Julio");			  break;
				case '08':	  $fechaMes = ("Agosto");		  break;
				case '09':	  $fechaMes = ("Septiembre");	  break;
				case '10':	  $fechaMes = ("Octubre");		  break;
				case '11':	  $fechaMes = ("Noviembre");	  break;
				case '12':	  $fechaMes = ("Diciembre");	  break;
			  }
	//inicia la elaboración de la poliza con fpdf
	require_once("../fpdf/fpdf.php");	//Llamamos la librería

	$autoridades = mysqli_query($con, "SELECT * FROM autoridades")
			or die(mysqli_error($con));
	if($reg = mysqli_fetch_array($autoridades));
	{
	$titular = $reg['titular'];
	}

//-----------------------------------------------------------

//-----------------------------------------------------------

	//Creamos la pagina

	$pdf = new FPDF('P','cm', array(21.59 , 29.00 ));	//creamos la pagina de tamaño carta 27.94
	//$pdf = new FPDF('L', 'cm', array(21, 8)); 	//creamos la instancia con dimenciones modificadas
	$pdf -> AddPage();					//generamos la primer pagina

	//Insertaremos la imagen de fondo para la poliza

	$pdf -> Image('../imagen/poliza.jpg','0','0','21.59','27.94','JPG');
			//IMAGE (RUTA,X,Y,ANCHO,ALTO,EXTEN)

	//fecha del Cheque
	$pdf -> Ln(3);					//donde comienza el texto
	$pdf -> SetRightMargin(3);			//margen derecho para la fecha
	$pdf -> SetFont('Arial', '', 12); //tipo de fuente
	$pdf -> Cell(0, 1, utf8_decode("México, Ciudad de México a $fechaDia, $fechaD de $fechaMes del $fechaY"), 0, 1, 'R');

	//Nombre del Destinatario
	$pdf -> Ln(0);
	$pdf -> SetLeftMargin(3);
	$pdf -> SetFont('Arial', '', 10);
	$pdf -> Cell(0, 1, $nombre, 0, 1, 'L');
	$pdf -> SetLeftMargin(0);

	//Monto del Cheque
	$pdf -> Ln(-1);
	$pdf -> SetX(15);
	$pdf -> SetFont('Arial', '', 12);
	//$pdf -> Cell(0, 1, "$ $cap1000", 0, 1, 'L');
	$pdf -> Cell(0,1, '$'.number_format($cap1000, 2,'.',','), 0,1, 'R');

	//Monto en letra
	$pdf -> Ln(0);
	$pdf -> SetLeftMargin(5);
	$pdf -> SetFont('Arial', '', 8);
	$pdf -> Cell(0, 1,' ('.MontoMonetarioEnLetras($cap1000).')', 0, 1, 'L');

	//Numero de cuenta
	$pdf -> Ln(0.5);
	$pdf -> SetX(1.8);
	$pdf -> SetFont('Arial', '', 11);
	$pdf -> Cell(0, 1, 'CUENTA: 70023268-025', 0, 1, 'l');

	//Numero de Cheque
	$pdf -> Ln(-1);
	$pdf -> SetX(18.2);
	$pdf -> SetFont('Arial', '', 10);
	$pdf -> Cell(0, 1, $noCheque, 0, 1, 'l');

	//Concepto del cheque
	$pdf -> Ln(0.1);
	$pdf -> SetX(1.7);
	$pdf -> SetFont('Arial', '', 7);
	$pdf -> Cell(8.9, 0.3, $concepto.'.' , '0');

	//Numro de Proyecto
	$pdf -> Ln(1);
	$pdf -> SetX(4.8);
	$pdf -> SetFont('Arial', '', 8);
	//$pdf -> Cell(0, 1, $numeroProyecto , 0, 1, 'l');

	//tipo de poliza
			$pdf -> Ln(-1.5);
			$pdf -> SetXY(1.7, 10.8);
			$pdf -> SetFont('Arial', '', 14);
			$pdf -> MultiCell(4.8, 0.3, utf8_decode('NÓMINA') , 0);

	//Datos del cuadro Nombre
	$pdf -> Ln(4);
	$pdf -> SetXY(6.5, 15.5);
	$pdf -> SetFont('Arial', '', 10);
	$pdf -> Cell(0, 1, 'GASTO' , 0, 1, 'l');

	//Datos del cuadro Nombre 2
	$pdf -> Ln(0);
	$pdf -> SetX(6.5);
	$pdf -> SetFont('Arial', '', 10);
	$pdf -> Cell(0, 1, 'BANCO CTA. 70023268-025' , 0, 1, 'l');

	//Monto en DEBE
	$pdf -> Ln(-2);
	$pdf -> SetRightMargin(4.8);
	$pdf -> SetX(15);
	$pdf -> SetFont('Arial', '', 10);
	$pdf -> Cell(0,1, '$'.number_format($cap1000, 2,'.',','), 0,1, 'R');

	//Monto en HABER
	$pdf -> Ln(0);
	$pdf -> SetRightMargin(2);
	$pdf -> SetX(17.6);
	$pdf -> SetFont('Arial', '', 10);
	$pdf -> Cell(0,1, '$'.number_format($cap1000, 2,'.',','), 0,1, 'R');

	//Montos Totales
	$pdf -> Ln(6);
	$pdf -> SetX(15);
	$pdf -> SetFont('Arial', '', 10);
	$pdf -> Cell(0,1, '$'.number_format($cap1000, 2,'.',','), 0,1, 'R');

	//Montos Totales2
	$pdf -> Ln(-1);
	$pdf -> SetX(17.6);
	$pdf -> SetFont('Arial', '', 10);
	$pdf -> Cell(0,1, '$'.number_format($cap1000, 2,'.',','), 0,1, 'R');

	//hecho revisado
			$pdf -> Ln(1.3);
			$pdf -> SetX(2.3);
			$pdf -> SetFont('Arial', '', 10);
			$pdf -> Cell(0, 1, 'JCGE' , 0, 1, 'l');

			$pdf -> Ln(-1);
			$pdf -> SetX(4.5);
			$pdf -> SetFont('Arial', '', 10);
			$pdf -> Cell(0, 1, 'MRMG' , 0, 1, 'l');

			//Datos de Autorizado
			$pdf -> Ln(-1);
			$pdf -> SetX(6.4);
			$pdf -> SetFont('Arial', '', 8);
			$pdf -> Cell(0, 1, $titular , 0, 1, 'l');

			//noPOLIZA
			$pdf -> Ln(-1);
			$pdf -> SetX(17.6);
			$pdf -> SetFont('Arial', '', 8);
			$pdf -> Cell(0, 1, $noCheque , 0, 1, 'l');

	//Fin y salida del archivo
	$pdf -> Output("reportPoliza.pdf", "I")		//archivo de salida
	mysqli_close($con);
?>
