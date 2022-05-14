<?php
	require_once('../config.php');
	session_start();

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
	$con->query("SET NAMES 'utf8'");

	//-----------------------------------------------------------//
	//	     			OBTIENE EL ULTIMO CHEQUE REGISTRADO            //
	//-----------------------------------------------------------//
	if( $_POST['type'] == 'getUltimoCheque')
	{
		//obtenemos los datos generales de un cheque
		$busqueda = mysqli_query($con, "SELECT noCheque FROM egresos ORDER BY noCheque DESC LIMIT 1") or die(mysqli_error($con));
		if($row =mysqli_fetch_assoc($busqueda)) {
				$data = $row['noCheque'];
		} else {
			$data = 0;
		}
		print_r($data);
	}

	//-----------------------------------------------------------//
	//		REVISA SI EL CHEQUE CAPTURADO NO ES DUPLICADO          //
	//-----------------------------------------------------------//
	if( $_POST['type'] == 'testNoCheque')
	{
		//obtenemos los datos generales de un cheque
		$busqueda = mysqli_query($con, "SELECT noCheque FROM egresos WHERE noCheque = ".$_POST['noCheque']) or die(mysqli_error($con));
		if($row =mysqli_fetch_assoc($busqueda)) {
				$data = 1;
		} else {
			$data = 0;
		}
		print_r($data);
	}

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
	//							OBTIENE LA LISTA DE PROYECTOS           		//
	//-----------------------------------------------------------//
	if( $_POST['type'] == 'getMontosProyecto')
	{
		for($x=1; $x<=5; $x++){
			$data['ing'][$x] = 0;
			$data['egr'][$x] = 0;
		}
		$data['res']['total'] = 0;
		$nombreProyecto = $_POST['nombreProyecto'];
		//obtenemos el numero de proyecto
		$busqueda = mysqli_query($con, "SELECT numeroProyecto FROM proyectos WHERE nombreProyecto = '".$nombreProyecto."'") or die(mysqli_error($con));
		if($row = mysqli_fetch_assoc($busqueda)){
			$numeroProyecto = $row['numeroProyecto'];
		}
		//obtenemos los ingresos del proyecto
		$busqueda = mysqli_query($con, "SELECT * FROM ingresos WHERE numProy = ".$numeroProyecto) or die (mysqli_error($con));
		while($row =mysqli_fetch_array($busqueda)){
			for($x=1; $x<=5; $x++){
				$data['ing'][$x] += $row['capT'.$x];
			}
		}
		//obtenemos los egresos del proyecto
		$busqueda = mysqli_query($con, "SELECT * FROM egresos WHERE nombreProyecto = '".$nombreProyecto."'") or die (mysqli_error($con));
		while($row = mysqli_fetch_array($busqueda)){
			for($x=1; $x<=5; $x++){
				$data['egr'][$x] += $row['cap'.$x.'000'];
			}
		}

		for($x=1; $x<=5; $x++){
			$data['res'][$x] = $data['ing'][$x] - $data['egr'][$x];
			$data['res']['total'] += $data['res'][$x];
		}

		$data = utf8_converter($data);
		print_r( json_encode($data['res']) );
		//print_r( json_encode($data) );
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
		mysqli_query($con, "INSERT INTO egresos (noCheque, fechaElaboracion, nombre, concepto,
																						nombreProyecto, folio, observaciones, cap1000,
																						cap2000, cap3000, cap4000, cap5000, importeTotal,	restaComprobar,
																					  viaticos, iniVig, finVig)
												VALUES (".$_POST['noCheque'].", '".$fechaElaboracion."', '".$_POST['nombre']."', '".$_POST['concepto']."',
																'".$_POST['proyecto']."', ".$_POST['folio'].", '".$_POST['observaciones']."', ".floatVal(str_replace(",", "", $_POST['cap1000'])).",
																".floatVal(str_replace(",", "", $_POST['cap2000'])).", ".floatVal(str_replace(",", "", $_POST['cap3000'])).", ".floatVal(str_replace(",", "", $_POST['cap4000'])).",
																".floatVal(str_replace(",", "", $_POST['cap5000'])).", ".floatVal(str_replace(",", "", $_POST['total'])).", ".floatVal(str_replace(",", "", $_POST['total'])).",
																".$_POST['viaticos'].", '".$iniVig."', '".$finVig."')")
																or die(mysqli_error($con));
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
