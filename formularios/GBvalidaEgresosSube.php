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
		$busqueda = mysqli_query($con, "SELECT * FROM egresosgb WHERE noCheque = ".$_POST['noCheque']) or die(mysqli_error($con));
		while($row =mysqli_fetch_assoc($busqueda))
		{
				$data = $row;
		}
		$data = utf8_converter($data);
		print_r( json_encode($data) );
	}

	//-----------------------------------------------------------//
	//	     			OBTIENE DATOS COMPLETOS DE UN CHEQUE           //
	//-----------------------------------------------------------//
	if( $_POST['type'] == 'postCambios' )
	{
		$noCheque =  $_POST['noCheque'];
		for($x=1; $x<=3; $x++)
		{
			if($_POST['monComp'.$x] == NULL){ $monComp[$x] = 0;	}
			else {	$monComp[$x] = $_POST['monComp'.$x]; }

			$nomComp[$x] = $_POST['nomComp'.$x];
		}

		$comprobado = $_POST['comprobado'];
		$restaComprobar = $_POST['restaComprobar'];

		mysqli_query($con, "UPDATE egresosgb SET
							comprobacion1 = $monComp[1],
							comprobacion2 = $monComp[2],
							comprobacion3 = $monComp[3],
							noComprobacion1 = '$nomComp[1]',
							noComprobacion2 = '$nomComp[2]',
							noComprobacion3 = '$nomComp[3]',
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
		$busqueda = mysqli_query($con, "SELECT * FROM listaFacturas WHERE noCheque = ".$_POST['noCheque']." AND tipo = 'GB'") or die(mysqli_error($con));
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

	//CERRAMOS LA CONEXIÓN A LA BASE DE DATOS
	mysqli_close($con);

?>
