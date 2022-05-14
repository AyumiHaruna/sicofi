<?php
	require_once('../config.php');
	session_start();
	header('Content-Type: text/html; charset=UTF-8');

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
	$con->query("SET NAMES 'utf8'");

	//-----------------------------------------------------------//
	//	     			OBTIENE DATOS COMPLETOS DE UN CHEQUE           //
	//-----------------------------------------------------------//
	if( $_POST['type'] == 'getGenerales')
	{
		$data = array();
		//obtenemos los datos generales de un cheque
		$busqueda = mysqli_query($con, "SELECT * FROM egresos WHERE noCheque = ".$_POST['noCheque']) or die(mysqli_error($con));
		while($row =mysqli_fetch_assoc($busqueda))
		{
				$data = $row;
		}

		$data['montoLetra'] = MontoMonetarioEnLetras($data['comprobacion6']);

		$data = utf8_converter($data);
		print_r( json_encode($data) );
	}

	//-----------------------------------------------------------//
	//	     			OBTIENE DATOS COMPLETOS DE UN CHEQUE           //
	//-----------------------------------------------------------//
	if( $_POST['type'] == 'postCambios' )
	{
		$noCheque =  $_POST['noCheque'];
		for($x=1; $x<=6; $x++)
		{
			if($_POST['comprobacion'.$x] == NULL){ $comprobacion[$x] = 0;	}
			else {	$comprobacion[$x] = $_POST['comprobacion'.$x]; }

			$validacionComp[$x] = $_POST['validacionComp'.$x];

			if($_POST['fechaComp'.$x] == NULL) {	$fechaComp[$x] = NULL;	}
			else {	$fechaComp[$x] = date('Y-m-d', strtotime($_POST['fechaComp'.$x])); }
		}

		$comprobado = $_POST['comprobado'];
		$restaComprobar = $_POST['restaComprobar'];

		mysqli_query($con, "UPDATE egresos SET
							comprobacion1 = $comprobacion[1], comprobacion2 = $comprobacion[2],
							comprobacion3 = $comprobacion[3],	comprobacion4 = $comprobacion[4],
							comprobacion5 = $comprobacion[5],	comprobacion6 = $comprobacion[6],
							validacionComp1 = $validacionComp[1], validacionComp2 = $validacionComp[2],
							validacionComp3 = $validacionComp[3],	validacionComp4 = $validacionComp[4],
							validacionComp5 = $validacionComp[5],	validacionComp6 = $validacionComp[6],
							fechaComp1 = '$fechaComp[1]',	fechaComp2 = '$fechaComp[2]',
							fechaComp3 = '$fechaComp[3]',	fechaComp4 = '$fechaComp[4]',
							fechaComp5 = '$fechaComp[5]',	fechaComp6 = '$fechaComp[6]',
							comprobado = $comprobado,	restaComprobar = $restaComprobar
							WHERE noCheque = $noCheque") or die(mysqli_error($con));
		echo 'ok';
	}

	//-----------------------------------------------------------//
	//	     			GUARDA UNA FACUTRA EN LA DB					           //
	//-----------------------------------------------------------//
	if( $_POST['type'] == 'postQr' )
	{
		mysqli_query($con, "INSERT INTO listaFacturas(noCheque, cadena, tipo, monto, duplicado, autoriza, observaciones)
		 						 	VALUES( ".$_POST['noCheque'].", '".$_POST['cadena']."', '".$_POST['tipo']."', ".$_POST['monto'].",
									 				".$_POST['duplicado'].", '".$_POST['autoriza']."', '".$_POST['observaciones']."' )") or die(mysqli_error($con));
		echo 'ok';
	}

	//-----------------------------------------------------------//
	//				    REVISA DUPLICIDAD EN LOS CHEQUES          		//
	//-----------------------------------------------------------//
	if( $_POST['type'] == 'testQr' )
	{
		$data = array();
		$codigo = $_POST['codigo'];
		//obtenemos los datos generales de un cheque
		$busqueda = mysqli_query($con, "SELECT * FROM listaFacturas WHERE cadena LIKE '$codigo%'") or die(mysqli_error($con));
		while($row =mysqli_fetch_assoc($busqueda))
		{
				$data = $row;
		}
			$data = utf8_converter($data);
			print_r( json_encode($data) );
	}

	//-----------------------------------------------------------//
	//				   REVISA DATOS DEL ADMIN QUE VALIDARÁ         		//
	//-----------------------------------------------------------//
	if( $_POST['type'] == 'testAdm')
	{
		$data = array();
		//obtenemos los datos generales de un cheque
		$busqueda = mysqli_query($con, "SELECT * FROM usuarios WHERE id = '".$_POST['usuario']."'") or die(mysqli_error($con));
		while($row =mysqli_fetch_assoc($busqueda))
		{
				$data = $row;
		}
		//si no existen elementos regresa error 1 (no existe el usuario)
		if( !isset($data['id']) ){
			echo '3';
		} else {
			//si la contraseña no coincide con el ID
			if( $data['password'] != $_POST['password'] ){
				echo '2';
			} else {
				//si la cuenta no es nivel 1 (administrador)
				if( $data['nivel'] != 1 ){
					echo '1';
				} else {
					echo 'ok';
				}
			}
		}
	}

	//-----------------------------------------------------------//
	//				   REVISA DATOS DEL ADMIN QUE VALIDARÁ         		//
	//-----------------------------------------------------------//
	if( $_POST['type'] == 'getFacturas')
	{
		$data = array();
		//obtenemos los datos generales de un cheque
		$busqueda = mysqli_query($con, "SELECT * FROM listaFacturas WHERE noCheque = ".$_POST['noCheque']." AND tipo = 'PR'") or die(mysqli_error($con));
		while($row =mysqli_fetch_assoc($busqueda))
		{
				$data[] = $row;
		}
		$data = utf8_converter($data);
		print_r( json_encode($data) );
	}

	//-----------------------------------------------------------//
	//			     			ELIMINA UNA FACTURA					           			//
	//-----------------------------------------------------------//
	if( $_POST['type'] == 'delQr' )
	{
		mysqli_query($con, "DELETE FROM listaFacturas WHERE no = ".$_POST['no']) or die(mysqli_error($con));
		echo 'ok';
	}

	//-----------------------------------------------------------//
	//			     		OBTIENE DATOS DE AUTORIDADES            			//
	//-----------------------------------------------------------//
	if( $_POST['type'] == 'getAutoridades' )
	{
		$data = array();
		//obtenemos los datos generales de un cheque
		$busqueda = mysqli_query($con, "SELECT * FROM autoridades") or die(mysqli_error($con));
		while($row =mysqli_fetch_assoc($busqueda))
		{
				$data = $row;
		}
		$data = utf8_converter($data);
		print_r( json_encode($data) );
	}

	//-----------------------------------------------------------//
	//			     		GUARDA EL LUGAR DE COMISIÓN            			//
	//-----------------------------------------------------------//
	if( $_POST['type'] == 'guardaExtra' )
	{
		mysqli_query($con, "UPDATE egresos SET extra = '".$_POST['extra']."' WHERE noCheque = ".$_POST['noCheque']) or die(mysqli_error($con));
		echo 'ok';
	}

	//------------------------------------------------------------------
	//			FNC	ESTA FUNCION DECODIFICA CARACTERES ESPECIALES CON UTF-8
	//-----------------------------------------------------------------
	function utf8_converter($array)
  {
      array_walk_recursive($array, function(&$item, $key){
          if(!mb_detect_encoding($item, 'utf-8', true)){
                  $item = utf8_encode($item);
          }
      });
      return $array;
  }

	//------------------------------------------------------------------
	// 											OBTIENE EL MONTO EN LETRAS
	//-----------------------------------------------------------------
	//------------------------ AREA DE FUNCIONES ---------------------//
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


	//CERRAMOS LA CONEXIÓN A LA BASE DE DATOS
	mysqli_close($con);

?>
