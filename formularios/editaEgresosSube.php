<?php
	require_once('../config.php');
	session_start();

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
	$con->query("SET NAMES 'utf8'");

	//-----------------------------------------------------------//
	//							OBTIENE LA LISTA DE PROYECTOS           		//
	//-----------------------------------------------------------//
	if( $_POST['type'] == 'getListaProyectos')
	{
		$data = array();
		//obtenemos los datos generales de un cheque
		$busqueda = mysqli_query($con, "SELECT numeroProyecto, nombreProyecto FROM proyectos ORDER BY numeroProyecto") or die(mysqli_error($con));
		while($row = mysqli_fetch_assoc($busqueda))
		{
				$data[] = $row;
		}
		$data = utf8_converter($data);
		print_r( json_encode($data) );
	}

	//-----------------------------------------------------------//
	//						OBTIENE LA INFORMACION DEL CHEQUE         		//
	//-----------------------------------------------------------//
	if( $_POST['type'] == 'getInfoCheque')
	{
		$data = array();
		//obtenemos los datos generales de un cheque
		$busqueda = mysqli_query($con, "SELECT * FROM egresos where noCheque = ".$_POST['noCheque']) or die(mysqli_error($con));
		if($row = mysqli_fetch_assoc($busqueda))
		{
				$data = $row;
		}
		$data = utf8_converter($data);
		print_r( json_encode($data) );
	}

	//-----------------------------------------------------------//
	//							OBTIENE LA LISTA DE PROYECTOS           		//
	//-----------------------------------------------------------//
	if( $_POST['type'] == 'getMontosProyecto')
	{
		$data = array();
		//obtenemos los datos generales de un cheque
		$busqueda = mysqli_query($con, "SELECT * FROM proyectos WHERE nombreProyecto = '".$_POST['nombreProyecto']."'") or die(mysqli_error($con));
		if($row = mysqli_fetch_assoc($busqueda))
		{
				$data = $row;
		}
		$data = utf8_converter($data);
		print_r( json_encode($data) );
	}

	//-----------------------------------------------------------//
	//					GUARDA LOS DATOS DEL FORM EN LA DB          		//
	//-----------------------------------------------------------//
	if( $_POST['type'] == 'guardaDatos' )
	{
		$fechaElaboracion = $_POST['fechaElaboracion'];
		if( $_POST['iniVig'] == "" || $_POST['finVig'] == ""){
			$iniVig = NULL; $finVig = NULL;
		} else {
			$iniVig = $_POST['iniVig'];
			$finVig = $_POST['finVig'];
		}

		mysqli_query($con, "UPDATE egresos SET
								fechaElaboracion = '".$fechaElaboracion."',
								nombre = '".$_POST['nombre']."', concepto = '".$_POST['concepto']."',
								nombreProyecto = '".$_POST['proyecto']."', folio = ".$_POST['folio'].", observaciones = '".$_POST['observaciones']."',
								cap1000 = ".floatVal(str_replace(",", "", $_POST['cap1000'])).",	cap2000 = ".floatVal(str_replace(",", "", $_POST['cap2000'])).",
								cap3000 = ".floatVal(str_replace(",", "", $_POST['cap3000'])).", cap4000 = ".floatVal(str_replace(",", "", $_POST['cap4000'])).",
								cap5000 = ".floatVal(str_replace(",", "", $_POST['cap5000'])).", importeTotal = ".floatVal(str_replace(",", "", $_POST['total'])).",
								restaComprobar = ".floatVal(str_replace(",", "", $_POST['total'])).", viaticos = ".$_POST['viaticos'].",
								iniVig = '".$iniVig."', finVig = '".$finVig."' WHERE noCheque = ".$_POST['noCheque']) or die (mysqli_error($con));
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
