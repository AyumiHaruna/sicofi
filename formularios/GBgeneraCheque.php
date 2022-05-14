<?php
	require_once('../config.php');
	require_once("../fpdf/fpdf.php");	//Llamamos la librería
	session_start();
	header('Content-Type: text/html; charset=UTF-8');

	$valor1	= $_GET['a'];
	$valor2 = $_GET['b'];

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
                    $monto_decimal = '00';
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

//CONSULTA A LA BASE DE DATOS
	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("Problemas con la conexi&oacute;n a la base de datos");
		//$con->query("SET NAMES 'utf8'");

	$egresos = mysqli_query($con, "SELECT noCheque, fechaElaboracion, nombre, total
										FROM egresosgb
										WHERE noCheque BETWEEN $valor1 AND $valor2
										ORDER BY noCheque")
			or die(mysqli_error($egresos));

	$pdf = new FPDF('L', 'cm', array(16.5, 7)); 	//creamos la instancia con dimenciones modificadas

	while ($reg = mysqli_fetch_array ($egresos))
		{

			//Generacion del cheque

			$fechaD = date("d", strtotime($reg['fechaElaboracion']));
			$fechaY = date("Y", strtotime($reg['fechaElaboracion']));



			//con este codigo conseguiremos el día de la semana
			$fechaDia = date("w", strtotime($reg['fechaElaboracion']));
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
			  $fechaMes = date("m", strtotime($reg['fechaElaboracion']));
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

			//generacion del PDF
			$pdf -> AddPage();					//creamos la primera pagina

			//Fecha del Cheque
			$pdf -> Ln(1);
			$pdf -> SetY(1.15);
			$pdf -> SetRightMargin(0);			//margen derecho para la fecha
			$pdf -> SetFont('Arial', '', 6); //tipo de fuente
			$pdf -> Cell(0, 1, utf8_decode("Ciudad de México a $fechaDia, $fechaD de $fechaMes del $fechaY"), 0, 1, 'R');

			//Nombre del destinatario
			$pdf -> Ln(0.3);
			$pdf -> SetXY(6.0,2.18);
			$pdf -> SetFont('Arial', '', 6);
			$pdf -> Cell(0, 1, $reg['nombre'], 0, 1, 'L');

			//Monto del cheque
			$pdf -> Ln(-1);
			$pdf -> SetY(2.08);
			$pdf -> SetRightMargin(0);
			$pdf -> SetFont('Arial', '', 8);
			//$pdf -> Cell(0, 1, $reg['total'], 0, 1, 'R');
			$pdf -> Cell(0,1, number_format($reg['total'], 2,'.',','), 0,1, 'R');
						//por mientras
						$monto = $reg['total'];
			//Monto del cheque en letra
			$pdf -> Ln(-0.6);
			$pdf -> SetrightMargin(3.5);
			$pdf -> SetXY(6.0, 2.7);
			$pdf -> SetFont('Arial', '', 6);
			$pdf -> Cell(0, 1, MontoMonetarioEnLetras($reg['total']), 0, 1, 'L');

		}
	//Archivo de Salida
	$pdf -> Output("reportCheque.pdf", "I");		//Archivo de Salida

	mysqli_close($con);
?>
