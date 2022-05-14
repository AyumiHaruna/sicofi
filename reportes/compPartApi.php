<?php
  require_once('../config.php');
	session_start();
  header('Content-Type: text/html; charset=UTF-8');

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
	$con->query("SET NAMES 'utf8'");


  //-----------------------------------------------------------//
  //                  variables generales                      //
  //-----------------------------------------------------------//
  // $_POST['type'] = 'getParTodas';
  //Lista de meses
  $minMes = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
  $comMes = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

  //--Obtenemos la lista de proyectos permitidos para el usuario
	$busqueda = mysqli_query($con, "SELECT * FROM usuarios WHERE id = '".$_SESSION['id']."'")
		or die(mysqli_error($con));
	if($_SESSION['anio'] >= 2017){ $canProy = 45; } else { $canProy = 10; }
	while($reg = mysqli_fetch_array($busqueda))
	{
		for($x=1; $x<=$canProy; $x++){
			$proy[] = $reg['proy'.$x];
		}
	}
  //--eliminamos bloques vacios
	$newUserProy = [];
 	for($x=0; $x<count($proy); $x++){
		if($proy[$x] != ""){
			$newUserProy[] = $proy[$x];
		}
	}
	$proy = $newUserProy;


  //-----------------------------------------------------------//
  //	     			OBTIENE REPORTE DE PARTIDAS DEL A�O            //
	//-----------------------------------------------------------//
  if($_POST['type'] == 'getParTodas')
  {
      $datos = array();
      //obtenemos la lista de proyectos para cada usuario
      $textoBusqueda = "SELECT * FROM proyectos ";
      if($proy[0] != 999999){
          $textoBusqueda .= "WHERE ";
          for($x=0; $x<count($proy); $x++){
            if($x != (count($proy)-1)){
              $textoBusqueda .= "numeroProyecto = ".$proy[$x]." OR ";
            } else {
              $textoBusqueda .= "numeroProyecto = ".$proy[$x]." ";
            }
          }
      }
      $textoBusqueda .= "ORDER BY numeroProyecto";

      $busqueda = mysqli_query($con, $textoBusqueda) or die(mysqli_error($con));
	    while($row =mysqli_fetch_assoc($busqueda))
	    {
	        $datos[ $row['numeroProyecto'] ]['numeroProyecto'] = $row['numeroProyecto'];
          $datos[ $row['numeroProyecto'] ]['nombreProyecto'] = $row['nombreProyecto'];
          $datos[ $row['numeroProyecto'] ]['totalAutorizado'] = $row['totalAutorizado'];
          $datos[ $row['numeroProyecto'] ]['partidas'] = [];
	    }

      //recorreos la lista de proyectos
      for($x=0; $x<count($datos); $x++)
      {
        $thisProyecto = array_keys($datos)[$x];
        //agregamos las partidas que aparecen en presupuestadas para cada proyecto
        $busqueda = mysqli_query($con, "SELECT det.numeroProyecto, det.numeroPartida, det.ene, det.feb, det.mar, det.abr, det.may, det.jun, det.jul,
                                              det.ago, det.sep, det.oct, det.nov, det.dic, det.total, lis.nomPartida FROM detalleProyecto AS det
                                              JOIN zlistapartidas AS lis ON det.numeroPartida = lis.noPartida WHERE det.numeroProyecto = $thisProyecto ORDER BY det.numeroPartida") or die(mysqli_error($con));
        while($row =mysqli_fetch_assoc($busqueda))
  	    {
          $datos[$thisProyecto]['partidas'][ $row['numeroPartida'] ]['numeroPartida'] = $row['numeroPartida'];
          $datos[$thisProyecto]['partidas'][ $row['numeroPartida'] ]['nombrePartida'] = $row['nomPartida'];
          for($y=0; $y<count($minMes); $y++)
          {
            $datos[$thisProyecto]['partidas'][ $row['numeroPartida'] ][ $minMes[$y] ]['presupuestado'] = $row[ $minMes[$y] ];
            $datos[$thisProyecto]['partidas'][ $row['numeroPartida'] ][ $minMes[$y] ]['ejercidos'] = '0.00';
          }
        }

        //agregamos las partidas que aparecen en comprobadas para cada proyecto
        if( $_SESSION['anio'] >= 2020) {
          $query = "SELECT lis.noSolFon, lis.partida, lis.monto, lis.active, ing.numProy, ing.mes, ing.numProy, zli.nomPartida FROM lisComp AS lis
                    JOIN ingresos AS ing ON lis.noSolFon = ing.noSolFon
                    JOIN zlistapartidas AS zli ON lis.partida = zli.noPartida
                    WHERE ing.numProy = ".$thisProyecto." 
                    AND lis.active = 1
                    ORDER BY lis.partida";
        } else {
          $query = "SELECT lis.noSolFon, lis.partida, lis.monto, ing.numProy, ing.mes, ing.numProy, zli.nomPartida FROM lisComp AS lis
                    JOIN ingresos AS ing ON lis.noSolFon = ing.noSolFon
                    JOIN zlistapartidas AS zli ON lis.partida = zli.noPartida
                    WHERE ing.numProy = ".$thisProyecto." ORDER BY lis.partida";
        }
        $busqueda = mysqli_query($con, $query) or die(mysqli_error($con));
        while($row =mysqli_fetch_assoc($busqueda))
        {
          //si no existe ya la partida creada anteriormente en el proyecto, esta se crea
          if( !isset( $datos[$thisProyecto]['partidas'][ $row['partida'] ] ) )
          {
            $datos[$thisProyecto]['partidas'][ $row['partida'] ]['numeroPartida'] =  $row['partida'];
            $datos[$thisProyecto]['partidas'][ $row['partida'] ]['nombrePartida'] =  $row['nomPartida'];
            for($y=0; $y<count($minMes); $y++)
            {
              if( $minMes[$y] == strtolower(substr($row['mes'],0,3)) )
              {
                $datos[$thisProyecto]['partidas'][ $row['partida'] ][ $minMes[$y] ]['presupuestado'] = '0.00';
                $datos[$thisProyecto]['partidas'][ $row['partida'] ][ $minMes[$y] ]['ejercidos'] = $row['monto'];
              } else {
                $datos[$thisProyecto]['partidas'][ $row['partida'] ][ $minMes[$y] ]['presupuestado'] = '0.00';
                $datos[$thisProyecto]['partidas'][ $row['partida'] ][ $minMes[$y] ]['ejercidos'] = '0.00';
              }
            }
          } else {    //Si ya existe la partida creada, almacena pero suma si existe
            $datos[$thisProyecto]['partidas'][ $row['partida'] ][ strtolower(substr($row['mes'],0,3)) ]['ejercidos'] += $row['monto'];
          }
        }
      } //-- fin de FOR que recorre

	  $datos = utf8_converter($datos);
      print_r( json_encode($datos) );
  }

  //-----------------------------------------------------------//
  //	     			OBTIENE LA LISTA DE PROYECTOS                  //
	//-----------------------------------------------------------//
  if($_POST['type'] == 'getLisProy')
  {
    $data = array();

    $textoBusqueda = "SELECT numeroProyecto, nombreProyecto FROM proyectos ";
    if($proy[0] != 999999){
        $textoBusqueda .= "WHERE ";
        for($x=0; $x<count($proy); $x++){
          if($x != (count($proy)-1)){
            $textoBusqueda .= "numeroProyecto = ".$proy[$x]." OR ";
          } else {
            $textoBusqueda .= "numeroProyecto = ".$proy[$x]." ";
          }
        }
    }
    $textoBusqueda .= "ORDER BY numeroProyecto";

    $busqueda = mysqli_query($con, $textoBusqueda) or die(mysqli_error($con));
    while($row = mysqli_fetch_assoc($busqueda)) {
		 	$data[] = $row;
		}
		$data = utf8_converter($data);
		echo json_encode($data);
  }

  //-----------------------------------------------------------//
  //	     			OBTIENE LA LISTA DE PARTIDAS                   //
	//-----------------------------------------------------------//
  if($_POST['type'] == 'getLisPart')
  {
    $data = array();
    $busqueda = mysqli_query($con, "SELECT noPartida, nomPartida FROM zlistapartidas ORDER BY noPartida") or die(mysqli_error($con));
    while($row = mysqli_fetch_assoc($busqueda)) {
		 	$data[] = $row;
		}
		$data = utf8_converter($data);
		echo json_encode($data);
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

  //cierra la sesi�n de mysql
	mysqli_close($con);
?>
