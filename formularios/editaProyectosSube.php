<?php
	require_once('../config.php');
	session_start();

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");

	//-----------------------------------------------------------
	//				OBTIENE LOS DATOS GENERALES DEL PROYECTO
	//-----------------------------------------------------------
	if($_POST['tipo'] == 'getGenProyecto')
	{
		$numProy = $_POST['numeroProyecto'];
		$montos = mysqli_query($con, "SELECT * FROM proyectos
										WHERE numeroProyecto = $numProy")			//REVISAR POSIBLE FALLA EN LA ESTRUCTURA
			or die(mysqli_error($con));
		$data = array();
		while($r = mysqli_fetch_assoc($montos)) {
			$data[] = $r;
		}
		$data = utf8_converter($data[0]);
		echo json_encode($data);
	}

	//-----------------------------------------------------------
	//				ACTUALIZA LOS DATOS GENERALES DEL PROYECTO
	//-----------------------------------------------------------
	if($_POST['tipo'] == 'updProy')
	{
		//print_r($_POST);
    $numeroProy = $_POST['numeroProyecto'];
		$nombreProyecto = $_POST['nombreProyecto'];
		$nombreResponsable = $_POST['nombreResponsable'];
		$cuenta = $_POST['cuenta'];
		if($_POST['titulo'] == '-'){
			$titulo = "";
		} else {
			$titulo = $_POST['titulo'];
		}

    mysqli_query($con, "UPDATE proyectos
  						SET	nombreProyecto = '$nombreProyecto', nombreResponsable = '$nombreResponsable',
  							  cuenta = '$cuenta', titulo = '$titulo'
  						WHERE numeroProyecto = $numeroProy")
  			or die(mysqli_error($con));
    echo 'ok';
	}    //--

	//-----------------------------------------------------------
	//		OBTIENE LOS MONTOS MENSUALES APROBADOS DEL PROYECTO
	//-----------------------------------------------------------
	if($_POST['tipo'] == 'getMontos')
	{
		$numProy = $_POST['numeroProyecto'];
		//echo 'test';
		$montos = mysqli_query($con, "SELECT * FROM proyectos
										WHERE numeroProyecto = '$numProy'")			//REVISAR POSIBLE FALLA EN LA ESTRUCTURA
			or die(mysqli_error($con));
		$data = array();
		while($r = mysqli_fetch_assoc($montos)) {
		 	$data[] = $r;
		}
		echo json_encode($data);
	}

	//-----------------------------------------------------------
	//		OBTIENE LA LISTA DE PARTIDAS AUTORIZADAS DEL CAPITULO
	//-----------------------------------------------------------
	if($_POST['tipo'] == 'getPartidas')
	{
		$capitulo = ($_POST['capitulo'] * 10);
		$capituloFin = $capitulo + 10000;

		$partidas = mysqli_query($con, "SELECT * FROM zlistapartidas
										WHERE noPartida >= $capitulo AND noPartida <= $capituloFin")			//REVISAR POSIBLE FALLA EN LA ESTRUCTURA
			or die(mysqli_error($con));

			$data = array();
	    while($row =mysqli_fetch_assoc($partidas))
	    {
	        $data[] = $row;
	    }
		$data = utf8_converter($data);
		print_r(json_encode($data));
	}

	//-----------------------------------------------------------
	//					INSERTA PARTIDAS Y ACTUALIZA PROYECTO
	//-----------------------------------------------------------
	if($_POST['tipo'] == 'createPartidas')
	{
		$numeroProyecto = $_POST['numeroProyecto'];		$capitulo = $_POST['capitulo'];
		$numeroPartida = $_POST['noPartida']; 	$ene = $_POST['ene'];		$feb = $_POST['feb'];
		$mar = $_POST['mar'];		$abr = $_POST['abr'];		$may = $_POST['may'];		$jun = $_POST['jun'];
		$jul = $_POST['jul'];		$ago = $_POST['ago'];		$sep = $_POST['sep'];		$oct = $_POST['oct'];
		$nov = $_POST['nov'];		$dic = $_POST['dic']; 	$total = $_POST['total'];
		//agregamos los datos a detalle del proyecto
		mysqli_query($con, "INSERT INTO detalleProyecto(numeroProyecto, capitulo, numeroPartida, ene, feb, mar, abr, may, jun, jul, ago, sep, oct, nov, dic, total)
												VALUES($numeroProyecto, $capitulo, '$numeroPartida', $ene, $feb, $mar, $abr, $may, $jun, $jul, $ago, $sep, $oct, $nov, $dic, $total)")			//REVISAR POSIBLE FALLA EN LA ESTRUCTURA
			or die(mysqli_error($con));
		//obtenemos los datos actuales del proyecto
		$proyecto = mysqli_query($con, "SELECT * FROM proyectos WHERE numeroProyecto = $numeroProyecto")
			or die(mysqli_error($con));

		//creamos un array con los datos del proyecto
		$data = array();
		while($r = mysqli_fetch_assoc($proyecto)) {
		 	$data[] = $r;
		}
		$data = $data[0];

		//la variable flag sera igual al primer digito del capitulo y servira para actualizar los montos
		$flag = $capitulo[0];
		//creare variables con los nuevos valores resultantes de la suma del actual con los de la partida
		$Nene = number_format( ($ene + $data['ene'.$flag]), 2, '.', '');		$Nfeb = number_format( ($feb + $data['feb'.$flag]), 2, '.', '');
		$Nmar = number_format( ($mar + $data['mar'.$flag]), 2, '.', '');		$Nabr = number_format( ($abr + $data['abr'.$flag]), 2, '.', '');
		$Nmay = number_format( ($may + $data['may'.$flag]), 2, '.', '');		$Njun = number_format( ($jun + $data['jun'.$flag]), 2, '.', '');
		$Njul = number_format( ($jul + $data['jul'.$flag]), 2, '.', '');		$Nago = number_format( ($ago + $data['ago'.$flag]), 2, '.', '');
		$Nsep = number_format( ($sep + $data['sep'.$flag]), 2, '.', '');		$Noct = number_format( ($oct + $data['oct'.$flag]), 2, '.', '');
		$Nnov = number_format( ($nov + $data['nov'.$flag]), 2, '.', '');		$Ndic = number_format( ($dic + $data['dic'.$flag]), 2, '.', '');
		$Ncap = number_format( ($total + $data['cap'.$flag.'000']), 2, '.', '');
		$Ntotal = number_format( ($total + $data['totalAutorizado']), 2, '.', '');
		//dependiendo del capitulo afectado se hara una consulta de update distinta
		mysqli_query($con, "UPDATE proyectos
							SET	ene".$flag." = $Nene,	feb".$flag." = $Nfeb,	mar".$flag." = $Nmar,	abr".$flag." = $Nabr,	may".$flag." = $Nmay,	jun".$flag." = $Njun,
									jul".$flag." = $Njul,	ago".$flag." = $Nago,	sep".$flag." = $Nsep,	oct".$flag." = $Noct,	nov".$flag." = $Nnov,	dic".$flag." = $Ndic,
									cap".$flag."000 = $Ncap, totalAutorizado = $Ntotal
							WHERE numeroProyecto = $numeroProyecto")or die(mysqli_error($con));

		echo 'ok';
	}

	//-----------------------------------------------------------
	//  	OBTIENE LISTA DE PARTIDAS CAPTURADAS EN EL PROYECTO
	//-----------------------------------------------------------
	if($_POST['tipo'] == 'getPartidasProyecto')
	{
		$numProy = $_POST['numeroProyecto'];
		//echo 'test';
		$partidas = mysqli_query($con, "SELECT det.no, det.numeroProyecto, det.capitulo, det.numeroPartida,
										det.ene, det.feb, det.mar, det.abr, det.may, det.jun, det.jul, det.ago, det.sep, det.oct, det.nov, det.dic,
										det.total, lis.nomPartida
										FROM detalleProyecto AS det
										JOIN zlistapartidas AS lis
											ON det.numeroPartida = lis.noPartida
										WHERE det.numeroProyecto = '$numProy' ORDER BY det.numeroPartida")			//REVISAR POSIBLE FALLA EN LA ESTRUCTURA
			or die(mysqli_error($con));
		$data = array();
		while($r = mysqli_fetch_assoc($partidas)) {
			$data[] = $r;
		}
		$data = utf8_converter($data);
		echo json_encode($data);
	}

	//-----------------------------------------------------------
	//  		ELIMINA LA PARTIDA Y RESTA SUS MONTOS AL PROYECTO
	//-----------------------------------------------------------
	if($_POST['tipo'] == 'delPartidas')
	{
		//VARIABLES------
		$numeroProyecto = $_POST['numeroProyecto'];
		$id = $_POST['id'];
		//---------------

		//obtenemos los datos de la partida seleccionada
		$datosPartida = mysqli_query($con, "SELECT * FROM detalleProyecto WHERE no = $id")
			or die(mysqli_error($con));
		//creamos un array con los datos de la partida
		$datos = array();
		while($r = mysqli_fetch_assoc($datosPartida)) {
		 	$datos[] = $r;
		}
		$datosPartida = $datos[0];

		//obtenemos los datos del proyecto afectado
		$datosProyecto = mysqli_query($con, "SELECT * FROM proyectos WHERE numeroProyecto = $numeroProyecto")
			or die(mysqli_error($con));
		//creamos un array con los datos de la partida
		$datos = array();
		while($r = mysqli_fetch_assoc($datosProyecto)) {
		 	$datos[] = $r;
		}
		$datosProyecto = $datos[0];
		$datosNproyecto = $datosProyecto;

		//obtenemos variables para
		$flag = $datosPartida['capitulo'][0];

		//restamos del proyecto las cantidades de la partida
		$datosNproyecto['ene'.$flag] = $datosProyecto['ene'.$flag] - $datosPartida['ene'];
		$datosNproyecto['feb'.$flag] = $datosProyecto['feb'.$flag] - $datosPartida['feb'];
		$datosNproyecto['mar'.$flag] = $datosProyecto['mar'.$flag] - $datosPartida['mar'];
		$datosNproyecto['abr'.$flag] = $datosProyecto['abr'.$flag] - $datosPartida['abr'];
		$datosNproyecto['may'.$flag] = $datosProyecto['may'.$flag] - $datosPartida['may'];
		$datosNproyecto['jun'.$flag] = $datosProyecto['jun'.$flag] - $datosPartida['jun'];
		$datosNproyecto['jul'.$flag] = $datosProyecto['jul'.$flag] - $datosPartida['jul'];
		$datosNproyecto['ago'.$flag] = $datosProyecto['ago'.$flag] - $datosPartida['ago'];
		$datosNproyecto['sep'.$flag] = $datosProyecto['sep'.$flag] - $datosPartida['sep'];
		$datosNproyecto['oct'.$flag] = $datosProyecto['oct'.$flag] - $datosPartida['oct'];
		$datosNproyecto['nov'.$flag] = $datosProyecto['nov'.$flag] - $datosPartida['nov'];
		$datosNproyecto['dic'.$flag] = $datosProyecto['dic'.$flag] - $datosPartida['dic'];
		$datosNproyecto['cap'.$flag.'000'] = $datosProyecto['cap'.$flag.'000'] - $datosPartida['total'];
		$datosNproyecto['totalAutorizado'] = $datosProyecto['totalAutorizado'] - $datosPartida['total'];

		//actualizamos el proyecto en la DB
		mysqli_query($con, "UPDATE proyectos SET
							totalAutorizado = ".$datosNproyecto['totalAutorizado'].", cap".$flag."000 = ".$datosNproyecto['cap'.$flag.'000'].",
							ene".$flag." = ".$datosNproyecto['ene'.$flag].",	feb".$flag." = ".$datosNproyecto['feb'.$flag].", mar".$flag." = ".$datosNproyecto['mar'.$flag].",
							abr".$flag." = ".$datosNproyecto['abr'.$flag].", may".$flag." = ".$datosNproyecto['may'.$flag].",	jun".$flag." = ".$datosNproyecto['jun'.$flag].",
							jul".$flag." = ".$datosNproyecto['jul'.$flag].",	ago".$flag." = ".$datosNproyecto['ago'.$flag].", sep".$flag." = ".$datosNproyecto['sep'.$flag].",
							oct".$flag." = ".$datosNproyecto['oct'.$flag].", nov".$flag." = ".$datosNproyecto['nov'.$flag].",	dic".$flag." = ".$datosNproyecto['dic'.$flag]."
							WHERE numeroProyecto = $numeroProyecto")
				or die(mysqli_error($con));

		//eliminamos la partida de la DB
		mysqli_query($con, "DELETE FROM detalleProyecto WHERE no=$id") or die(mysqli_error($con));

		echo 'ok';
	}

	//-----------------------------------------------------------
	//  		 		OBTIENE LOS DATOS DE UNA SOLA PARTIDA
	//-----------------------------------------------------------
	if($_POST['tipo'] == 'getThisPartida')
	{
		$partidas = mysqli_query($con, "SELECT det.no, det.numeroProyecto, det.capitulo, det.numeroPartida,
										det.ene, det.feb, det.mar, det.abr, det.may, det.jun, det.jul, det.ago, det.sep, det.oct, det.nov, det.dic,
										det.total, lis.nomPartida
										FROM detalleProyecto AS det
										JOIN zlistapartidas AS lis
										ON det.numeroPartida = lis.noPartida
										WHERE det.no = ".$_POST['id'])
		or die(mysqli_error($con));

		$data = array();
	  while($row =mysqli_fetch_assoc($partidas))
	  {
	     $data[] = $row;
	  }
		$partidas = utf8_converter($data);
		print_r(json_encode($partidas[0]));
	}

	//-----------------------------------------------------------
	//  			MODIFICA UNA PARTIDA, Y ACTUALIZA MONTOS
	//-----------------------------------------------------------
	if($_POST['tipo'] == 'editaPartida')
	{
		//VARIABLES------
		$numeroProyecto = $_POST['numeroProyecto'];
		$id = $_POST['id'];
		//---------------

		//obtenemos los datos de la partida seleccionada--------------------------------------------
		$datosPartida = mysqli_query($con, "SELECT * FROM detalleProyecto WHERE no = $id")
			or die(mysqli_error($con));
		//creamos un array con los datos de la partida
		$datos = array();
		while($r = mysqli_fetch_assoc($datosPartida)) {
		 	$datos[] = $r;
		}
		$datosPartida = $datos[0];

		//obtenemos los datos del proyecto afectado-------------------------------------------------
		$datosProyecto = mysqli_query($con, "SELECT * FROM proyectos WHERE numeroProyecto = $numeroProyecto")
			or die(mysqli_error($con));
		//creamos un array con los datos del proyecto
		$datos = array();
		while($r = mysqli_fetch_assoc($datosProyecto)) {
		 	$datos[] = $r;
		}
		$datosProyecto = $datos[0];

		//obtenemos el capitulo afectado
		$flag = $datosPartida['capitulo'][0];

		//restamos del proyecto las cantidades de la partida seleccionada
		$datosProyecto['ene'.$flag] = $datosProyecto['ene'.$flag] - $datosPartida['ene'];
		$datosProyecto['feb'.$flag] = $datosProyecto['feb'.$flag] - $datosPartida['feb'];
		$datosProyecto['mar'.$flag] = $datosProyecto['mar'.$flag] - $datosPartida['mar'];
		$datosProyecto['abr'.$flag] = $datosProyecto['abr'.$flag] - $datosPartida['abr'];
		$datosProyecto['may'.$flag] = $datosProyecto['may'.$flag] - $datosPartida['may'];
		$datosProyecto['jun'.$flag] = $datosProyecto['jun'.$flag] - $datosPartida['jun'];
		$datosProyecto['jul'.$flag] = $datosProyecto['jul'.$flag] - $datosPartida['jul'];
		$datosProyecto['ago'.$flag] = $datosProyecto['ago'.$flag] - $datosPartida['ago'];
		$datosProyecto['sep'.$flag] = $datosProyecto['sep'.$flag] - $datosPartida['sep'];
		$datosProyecto['oct'.$flag] = $datosProyecto['oct'.$flag] - $datosPartida['oct'];
		$datosProyecto['nov'.$flag] = $datosProyecto['nov'.$flag] - $datosPartida['nov'];
		$datosProyecto['dic'.$flag] = $datosProyecto['dic'.$flag] - $datosPartida['dic'];
		$datosProyecto['cap'.$flag.'000'] = $datosProyecto['cap'.$flag.'000'] - $datosPartida['total'];
		$datosProyecto['totalAutorizado'] = $datosProyecto['totalAutorizado'] - $datosPartida['total'];

		//sumamos las nuevas cantidades de la partida al proyecto
		$datosProyecto['ene'.$flag] = $datosProyecto['ene'.$flag] + $_POST['ene'];
		$datosProyecto['feb'.$flag] = $datosProyecto['feb'.$flag] + $_POST['feb'];
		$datosProyecto['mar'.$flag] = $datosProyecto['mar'.$flag] + $_POST['mar'];
		$datosProyecto['abr'.$flag] = $datosProyecto['abr'.$flag] + $_POST['abr'];
		$datosProyecto['may'.$flag] = $datosProyecto['may'.$flag] + $_POST['may'];
		$datosProyecto['jun'.$flag] = $datosProyecto['jun'.$flag] + $_POST['jun'];
		$datosProyecto['jul'.$flag] = $datosProyecto['jul'.$flag] + $_POST['jul'];
		$datosProyecto['ago'.$flag] = $datosProyecto['ago'.$flag] + $_POST['ago'];
		$datosProyecto['sep'.$flag] = $datosProyecto['sep'.$flag] + $_POST['sep'];
		$datosProyecto['oct'.$flag] = $datosProyecto['oct'.$flag] + $_POST['oct'];
		$datosProyecto['nov'.$flag] = $datosProyecto['nov'.$flag] + $_POST['nov'];
		$datosProyecto['dic'.$flag] = $datosProyecto['dic'.$flag] + $_POST['dic'];
		$datosProyecto['cap'.$flag.'000'] = $datosProyecto['cap'.$flag.'000'] + $_POST['total'];
		$datosProyecto['totalAutorizado'] = $datosProyecto['totalAutorizado'] + $_POST['total'];

		//actualizamos el proyecto en la DB
		mysqli_query($con, "UPDATE proyectos SET
							totalAutorizado = ".$datosProyecto['totalAutorizado'].", cap".$flag."000 = ".$datosProyecto['cap'.$flag.'000'].",
							ene".$flag." = ".$datosProyecto['ene'.$flag].",	feb".$flag." = ".$datosProyecto['feb'.$flag].", mar".$flag." = ".$datosProyecto['mar'.$flag].",
							abr".$flag." = ".$datosProyecto['abr'.$flag].", may".$flag." = ".$datosProyecto['may'.$flag].",	jun".$flag." = ".$datosProyecto['jun'.$flag].",
							jul".$flag." = ".$datosProyecto['jul'.$flag].",	ago".$flag." = ".$datosProyecto['ago'.$flag].", sep".$flag." = ".$datosProyecto['sep'.$flag].",
							oct".$flag." = ".$datosProyecto['oct'.$flag].", nov".$flag." = ".$datosProyecto['nov'.$flag].",	dic".$flag." = ".$datosProyecto['dic'.$flag]."
							WHERE numeroProyecto = $numeroProyecto")
				or die(mysqli_error($con));

		//actualizamos la partida
		mysqli_query($con, "UPDATE detalleProyecto SET
							ene = ".$_POST['ene'].", 	feb = ".$_POST['feb'].",  mar = ".$_POST['mar'].",
							abr = ".$_POST['abr'].", 	may = ".$_POST['may'].", 	jun = ".$_POST['jun'].",
							jul = ".$_POST['jul'].", 	ago = ".$_POST['ago'].",	sep = ".$_POST['sep'].",
							oct = ".$_POST['oct'].", 	nov = ".$_POST['nov'].",	dic = ".$_POST['dic'].",
							total = ".$_POST['total']."
							WHERE no=$id") or die(mysqli_error($con));
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
