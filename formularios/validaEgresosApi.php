<?php
  require_once('../config.php');
	session_start();
  header('Content-Type: text/html; charset=UTF-8');

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
	$con->query("SET NAMES 'utf8'");

  //$_POST['type'] = 'getLisEgresos';
  //-----------------------------------------------------------//
  //	     			OBTIENE LA LISTA DE EGRESOS                    //
	//-----------------------------------------------------------//
  if($_POST['type'] == 'getLisEgresos')
  {
    $busqueda = mysqli_query($con, "SELECT egr.noCheque, egr.fechaElaboracion, egr.nombre,
                                      egr.concepto, egr.nombreProyecto, egr.importeTotal,
                                      egr.restaComprobar, ". (($_SESSION['anio'] >= 2017)? "egr.status, ": "") ."pro.numeroProyecto
                                    FROM egresos AS egr JOIN proyectos AS pro ON egr.nombreProyecto = pro.nombreProyecto") or die(mysqli_error($con));
    $data = [];
    while($row = mysqli_fetch_assoc($busqueda)) {
      $data[] = $row;
    }
    if(count($data) == 0){
      echo '0';
    } else {
      $data = utf8_converter($data);
      echo json_encode($data);
    }
  }

  //-----------------------------------------------------------//
  //	     			MODIFICA EL STATUS DEL CHEQUE                  //
  //-----------------------------------------------------------//
  if($_POST['type'] == 'updateStatus')
  {
    mysqli_query($con, "UPDATE egresos SET status = ".$_POST['status']." WHERE noCheque = ".$_POST['noCheque']) or die(mysqli_error($con));
    echo 'ok';
  }


  //-----------------------------------------------------------//
  //    			MODIFICA EL STATUS DEL GRUPO DE CHEQUES           //
  //-----------------------------------------------------------//
  if($_POST['type'] == 'updateStatusGrupo'){
    $grupo = json_decode($_POST['grupo']);
    $grupo = array_values(array_filter($grupo));
    //CADENA DEL query
    $query = "UPDATE egresos SET status = ".$_POST['status']." WHERE ";
    for($x=0; $x<count($grupo); $x++){
      $query .= "noCheque = ". $grupo[$x] . (($x == (count($grupo)-1)) ? "" : " OR ");
    }
    mysqli_query($con, $query) or die(mysqli_error($con));

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

  //cierra la sesión de mysql
	mysqli_close($con);
?>
