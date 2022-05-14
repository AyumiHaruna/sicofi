<?php
  require_once('../config.php');
  session_start();

  $con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
    or die("problema con la conexi&oacute;n a la base de datos");
  $con->query("SET NAMES 'utf8'");

  //-----------------------------------------------------------//
	//	     			OBTIENE LOS DATOS DE LAS PARTIDAS             //
	//-----------------------------------------------------------//
	if( $_POST['type'] == 'getPartidasMes')
	{
    // print_r($_POST);
    $thisMes = strtolower( substr( $_POST['mes'], 0, 3 ) );

    $busqueda = mysqli_query( $con,
        "SELECT det.numeroPartida, det.".$thisMes.", zli.noPartida, zli.nomPartida FROM detalleProyecto AS det JOIN zlistapartidas AS zli ON det.numeroPartida = zli.noPartida WHERE det.numeroProyecto = ".$_POST['numProy']
      ) or die(mysqli_error($con));
		while($row =mysqli_fetch_assoc($busqueda))
    {
        if( isset($row[ $thisMes ]) ){
          if( $row[ $thisMes ] > 0 ){
            $data[] = $row;
          }
        }
		}

    if(isset($data)){
      $data = utf8_converter($data);
      print_r( json_encode($data) );
    } else {
      echo '0';
    }
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
