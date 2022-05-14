<?php
  require_once('../config.php');
  session_start();

  $con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
    or die("problema con la conexi&oacute;n a la base de datos");
  $con->query("SET NAMES 'utf8'");

  /*$_POST['type'] = 'getReporteMil';
  $_POST['data'] = [1,1,1];*/

  //-----------------------------------------------------------//
	//	     			OBTIENE REPORTE 1mil 2mil 3mil
  if( $_POST['type'] == 'getReporteMil'){
    //-- obtenemos los PROYECTOS visibles para el usurio logueado
    $busqueda = mysqli_query($con, "SELECT * FROM usuarios WHERE id = '".$_SESSION['id']."'")
      or die(mysqli_error($con));
    if($reg = mysqli_fetch_assoc($busqueda)){
      for($x=0; $x<45; $x++){
        $proy[$x] = $reg['proy'.($x+1)];
      }
    }
    //-- ordenamos el código para cada query
    $queryProyectos = "SELECT nombreProyecto, numeroProyecto, totalAutorizado FROM proyectos ";
    $queryIngresos = "SELECT ing.no, ing.tipo, ing.concepto, ing.operacion, ing.mes, ing.numProy,	ing.noSolFon, ing.validado, ";
    $queryEgresos = "SELECT egr.nombreProyecto, egr.importeTotal, ";

    if( $_POST['data'][0] == 1) {
      $queryIngresos .= "ing.capT1, ";
      $queryEgresos .= "egr.cap1000, ";
    }
    if( $_POST['data'][1] == 1) {
      $queryIngresos .= "ing.capT2, ";
      $queryEgresos .= "egr.cap2000, ";
    }
    if( $_POST['data'][2] == 1) {
      $queryIngresos .= "ing.capT3, ";
      $queryEgresos .= "egr.cap3000, ";
    }
    if( $_POST['data'][3] == 1) {
      $queryIngresos .= "ing.capT4, ";
      $queryEgresos .= "egr.cap4000, ";
    }
    if( $_POST['data'][4] == 1) {
      $queryIngresos .= "ing.capT5, ";
      $queryEgresos .= "egr.cap5000, ";
    }

    $queryIngresos .= "pro.numeroProyecto, pro.nombreProyecto, ing.SFtotal FROM ingresos AS ing JOIN proyectos AS pro ON ing.numProy = pro.numeroProyecto ";
    $queryEgresos .= "pro.numeroProyecto	FROM egresos AS egr	JOIN proyectos AS pro	ON egr.nombreProyecto = pro.nombreProyecto ";

    if($proy[0] != 999999){
      $queryProyectos .= "WHERE ";
      $queryIngresos .= "WHERE ";
      $queryEgresos .= "WHERE ";
      for($x=0; $x<45; $x++){
        if( $proy[$x] != 0 ){
          $queryProyectos .= "numeroProyecto = ".$proy[$x]." ";
          $queryIngresos .= "pro.numeroProyecto = ".$proy[$x]." ";
          $queryEgresos .= "pro.numeroProyecto = ".$proy[$x]." ";
          if($proy[($x+1)] != 0){
            $queryProyectos .= "OR ";
            $queryIngresos .= "OR ";
            $queryEgresos .= "OR ";
          }
        }
      }
    }

    $queryProyectos .= "ORDER BY numeroProyecto";
    $queryIngresos .= "ORDER BY pro.numeroProyecto";
    $queryEgresos .= "ORDER BY numeroProyecto";

    //-- hacemos la busquedas
    $buscaProyectos = mysqli_query($con, $queryProyectos) or die (mysqli_error($con));
    $buscaIngresos = mysqli_query($con, $queryIngresos) or die (mysqli_error($con));
    $buscaEgresos = mysqli_query($con, $queryEgresos) or die (mysqli_error($con));
    //-- ordenamos datos
      //--proyectos
    while($row = mysqli_fetch_assoc($buscaProyectos)){
      $data[ $row['numeroProyecto'] ]['numProy'] = $row['numeroProyecto'];
      $data[ $row['numeroProyecto'] ]['nomProy'] = $row['nombreProyecto'];
      $data[ $row['numeroProyecto'] ]['totAut'] = $row['totalAutorizado'];
      $data[ $row['numeroProyecto'] ]['min'] = 0;
      $data[ $row['numeroProyecto'] ]['rei'] = 0;
      $data[ $row['numeroProyecto'] ]['eje'] = 0;
    }
      //--ingresos
    while($row = mysqli_fetch_assoc($buscaIngresos)){
      if( $row['tipo'] == 'INGRESO' ){ $flag = 'min'; }
      else if( $row['tipo'] == 'REINTEGRO' ){ $flag = 'rei'; }

      if( $_POST['data'][0] == 1) { $data[ $row['numeroProyecto'] ][$flag] += $row['capT1']; }
      if( $_POST['data'][1] == 1) { $data[ $row['numeroProyecto'] ][$flag] += $row['capT2']; }
      if( $_POST['data'][2] == 1) { $data[ $row['numeroProyecto'] ][$flag] += $row['capT3']; }
      if( $_POST['data'][3] == 1) { $data[ $row['numeroProyecto'] ][$flag] += $row['capT4']; }
      if( $_POST['data'][4] == 1) { $data[ $row['numeroProyecto'] ][$flag] += $row['capT5']; }
    }
      //--Egresos
    while($row = mysqli_fetch_assoc($buscaEgresos)){
      if( $_POST['data'][0] == 1) { $data[ $row['numeroProyecto'] ]['eje'] += $row['cap1000']; }
      if( $_POST['data'][1] == 1) { $data[ $row['numeroProyecto'] ]['eje'] += $row['cap2000']; }
      if( $_POST['data'][2] == 1) { $data[ $row['numeroProyecto'] ]['eje'] += $row['cap3000']; }
      if( $_POST['data'][3] == 1) { $data[ $row['numeroProyecto'] ]['eje'] += $row['cap4000']; }
      if( $_POST['data'][4] == 1) { $data[ $row['numeroProyecto'] ]['eje'] += $row['cap5000']; }
    }
    //-- realizamos cuentas
    for($x=0; $x<count($data); $x++){
      $data[ array_keys($data)[$x] ]['dif'] = $data[ array_keys($data)[$x] ]['min'] + $data[ array_keys($data)[$x] ]['rei'] - $data[ array_keys($data)[$x] ]['eje'];
    }

    $data = utf8_converter($data);
		print_r( json_encode($data) );
  }

  //------------------------------------------------------------------
  //			FNC	ESTA FUNCION DECODIFICA CARACTERES ESPECIALES CON UTF-8
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
