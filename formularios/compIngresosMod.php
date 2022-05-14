<?php
    //--------------------------------------------------------------
    //                    STARTING PHP FUNCTION
    //--------------------------------------------------------------

    //-- REQUIRE CONFIG

    require_once('../config.php');
    require_once('menu.php');
    header('Content-Type: text/html; charset=UTF-8');
    session_start();

    //--------------------------------------------------------------
    //                    CONSULTAS A LA BASE DE DATOS
    //--------------------------------------------------------------
    //connection instance
    $con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio']) or die("Problemas con la conexi&oacute;n a la base de datos");
    $con->query("SET NAMES 'utf8'");

		$caratula = $_GET['codigo'];			$noSolFon = $_GET['codigo2'];

    //--INGRESOS
    // get all ingresos info where noSolFon =
		$ingresos = mysqli_query($con, "SELECT * FROM ingresos WHERE noSolFon = $noSolFon") or die(mysqli_error($con));
		while($reg = mysqli_fetch_array($ingresos))
		{
			$mes = $reg['mes'];
			$concepto = utf8_encode($reg['concepto']);
			$noAut1 = $reg['noAut1'];
			$noAut2 = $reg['noAut2'];
			if($noAut2 == 0)
			{	$noAut = $noAut1;		}
			else
			{	$noAut = $noAut1.' / '.$noAut2;		}
			$numProy = $reg['numProy'];
			$fechaDep = $reg['fechaDep1'];
			$operacion = $reg['operacion'];
			$validado = $reg['validado'];
		}

    //--PROYECTOS
    // get the name of the project
		$proyectos = mysqli_query($con, "SELECT nombreProyecto FROM proyectos WHERE numeroProyecto = $numProy") or die(mysqli_error($con));
		while($reg = mysqli_fetch_array($proyectos))
		{
			$nombreProyecto = $reg['nombreProyecto'];
		}

		//--COMPROBACION
		$comprobacion = mysqli_query($con, "SELECT * FROM comprobacion WHERE noSolFon = $noSolFon") or die(mysqli_error($con));
		$compT = 0;
		while($reg = mysqli_fetch_array($comprobacion))
		{
			if($reg['comprobado'] != NULL)
			{
				$compT =  $compT + $reg['comprobado'];
			}
		}

		$comprobacion2 = mysqli_query($con, "SELECT * FROM comprobacion WHERE noSolFon = $noSolFon AND caratula = '$caratula'") or die(mysqli_error($con));
		if($reg = mysqli_fetch_array($comprobacion2))
		{
			$carComp = $reg['comprobado'];
			$fechaComp = $reg['fechaElab'];
		}

		// echo $compT;
		// echo '<br>';
		// echo $carComp;
		$compT2 = $compT - $carComp;

    //--LISCOMP
    if($_SESSION['anio'] >= 2020){
      $query = "SELECT lis.*, zli.nomPartida FROM liscomp AS lis JOIN zlistapartidas AS zli ON zli.noPartida = lis.partida WHERE lis.noSolFon = $noSolFon AND lis.caratula = '$caratula' AND lis.active = 1";
    } else  {
      $query = "SELECT lis.*, zli.nomPartida FROM liscomp AS lis JOIN zlistapartidas AS zli ON zli.noPartida = lis.partida WHERE lis.noSolFon = $noSolFon AND lis.caratula = '$caratula'";
    }
    
		$liscompSearch = mysqli_query($con, $query) or die(mysqli_error($con));
		while($reg = mysqli_fetch_assoc($liscompSearch))
		{
			$lisComp[] = $reg;
		}

		$partidasSearch = mysqli_query($con, "SELECT noPartida, nomPartida FROM zlistapartidas ORDER BY noPartida") or die(mysqli_error($con));
    while ($reg = mysqli_fetch_array($partidasSearch)) {
      $partidas[] = $reg;
    }

    mysqli_close($con);
 ?>


  <!DOCTYPE html>
  <html lang="en" dir="ltr">
    <head>
      <meta charset="utf-8">
      <title> Caratula de Comprobaci&oacute;n </title>
      <script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
      <link rel="stylesheet" href="../css3/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
      <link rel="stylesheet" href="../css3/sfStyle.css">
      <script src="../js/bootstrap.min.js"></script>
    </head>
    <body>

      <?php
          //--------------------------------------------------------------
          //                     INVOKE THE MENU
          //--------------------------------------------------------------
          //revisa si el usuario esta logueado
          //sino esta logueado lo redirecciona al index
          if($_SESSION == NULL)
          {
            // echo '<script languaje="javascript">
            //     alert("Area restringida, redireccionando...");
            //     location.href="../index.php";
            //     </script>';
          }
          //si esta logueado muestra el menu correspondiente al nivel
          else
          {
            $nivel = $_SESSION['nivel'];

            switch($nivel)
            {
              case 1:		echo $menu[1];
                break;

              case 2:		echo $menu[2];
                break;

              case 3:		echo $menu[3];
                break;

              case 4:		echo $menu[4];
                break;

              case 5:		echo $menu[5];
                break;
            }
          }

          //                        MENU END
          //--------------------------------------------------------------
      ?>


      <div class="container-fluid">
        <form  method="post" action="compIngresosMod2.php" name="form1" id="mainForm">
          <div class="row">

            <div class="col-sm-8">
              <div class="row">
                <div class="col-sm-12 block_header" data-toggle="collapse" data-target="#datos_generales">
                  Datos del Ingresos (Precargados)
                </div>
                <div class="col-sm-12 block_square collapse in" id="datos_generales">
                  <div class="row">
                    <div class="col-sm-4">
                      <label for="noSolFon">No. de Solicitud de Fondos:</label>
                      <input type="text" name="noSolFon" class="form-control" size="16" value="<?php echo $noSolFon ?>" readOnly>
                    </div>
    								<div class="col-sm-4">
                      <label for="mes">Mes:</label>
                      <input type="text" name="mes" class="form-control" size="16" value="<?php echo $mes ?>" readOnly>
                    </div>
                    <div class="col-sm-4">
                      <label for="noAut">Numero de Transferencia:</label>
                      <input type="text" name="noAut" class="form-control" size="16" value="<?php echo $noAut ?>" readOnly>
                    </div>
    								<div class="col-sm-12">
                      <label for="concepto">Concepto:</label>
                      <input type="text" name="concepto" class="form-control" onChange="conMayusculas(this)" size="81" value="<?php echo utf8_decode($concepto) ?>" required>
                    </div>
                    <div class="col-sm-12">
                      <label for="proyecto">Proyecto</label>
                      <input type="text" name="proyecto" class="form-control" value="<?php echo $numProy.' - '.$nombreProyecto ?>" readOnly>
                    </div>
    								<div class="col-sm-4">
                      <label for="noSolFon">Tipo de Opearación:</label>
                      <input type="text" name="operacion" class="form-control" value="<?php echo $operacion ?>" readOnly>
                    </div>
    								<div class="col-sm-4">
                      <label for="noSolFon">Fecha de Ministración:</label>
                      <input type="date" name="fechaElab" class="form-control" value="<?php echo $fechaDep ?>" readOnly>
                    </div>
    								<div class="col-sm-4">
                      <label for="noSolFon">Fecha de Elaboración:</label>
                      <input type="date" name="fecha2" class="form-control" value="<?php echo (ISSET($fechaComp))? $fechaComp : date('Y-m-d');?>" required>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-12 block_header" data-toggle="collapse" data-target="#captura_partidas">
                  Captura de partidas
                </div>
                <div class="col-sm-12 block_square collapse in" id="captura_partidas">
                  <div class="row">
                    <div class="col-sm-10">
                      <div class="row">
                        <div class="col-sm-12">
                          <span id="addSpan">Nueva partida</span>
                        </div>
                        <div class="col-sm-6">
                          <label for="addSelect">Selecciona la partida:</label>
                          <select class="form-control" name="addSelect" id="addSelect">
                            <?php
                              foreach ($partidas as $key => $par) {
                                echo '<option value="'.$par['noPartida'].'" metaSelect="'.$par['nomPartida'].'">'.$par['noPartida'].' - '.$par['nomPartida'].'</option>';
                              }
                            ?>
                          </select>
                        </div>
                        <div class="col-sm-3">
                          <label for="addInput"># de Notas:</label>
                          <input type="text" class="form-control" name="addInput" id="addNotas" value="0" onkeypress="return justNumbers(event);">
                        </div>
                        <div class="col-sm-3">
                          <label for="addInput">Captura el monto:</label>
                          <input type="text" class="form-control" name="addInput" id="addInput" value="0.00" onkeypress="return justNumbers(event);">
                        </div>
                        <div class="col-sm-12 text-right">
                          <button type="button" class="demi_btn btn-lg" id="addOk" name="button"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                        </div>
                        <div class="windowBlock"></div>
                      </div>
                    </div>
                    <div class="col-sm-2 text-right">
                      <button type="button" name="button" class="actionBtn" id="btnNuevaPartida">Nueva partida &nbsp; <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> </button>
                    </div>
                    <div class="col-sm-12 table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Partida</th>
														<th>No. Notas</th>
                            <th>Monto</th>
                            <th>Controles</th>
                          </tr>
                        </thead>
                        <tbody id="dynamicTable">

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

            </div>

            <div class="col-sm-4 fixedtotales">
              <div class="row">
                <div class="col-sm-10 col-sm-offset-1 block_header" data-toggle="collapse" data-target="#block_totales">
                  Totales
                </div>

                <div class="col-sm-10 col-sm-offset-1 block_square collapse in" id="block_totales">
                  <div class="row">
                    <div class="col-sm-12">
                      <label for="total"> Importe de la Transferencia: ($)</label>
                      <input type="text" name ="validado" class="form-control" value="<?php echo number_format($validado, 2,'.',',') ?>" readonly>
                    </div>

                    <div class="col-sm-12">
                      <label for="total">Previamente Capturado: ($)</label>
                      <input type="text" name ="previo" class="form-control" value="<?php echo number_format($compT2, 2,'.',',') ?>" readonly>
                    </div>

                    <div class="col-sm-12">
                      <label for="total">Total de la Captura: ($)</label>
                      <input type="text" name ="total" id="total" class="form-control" value="0" readonly>
                    </div>

                    <div class="col-sm-12">
                      <label for="total">Resta por Comprobar: ($)</label>
                      <input type="text" name ="resta" id="resta" class="form-control" value="0" readonly>
                    </div>

                    <div class="col-sm-12">
                      <input type="button" class="actionBtn btn-block submit" value="Enviar" style="margin-top: 1vw;">
                    </div>

                    <div class="col-sm-12">
                      <label for="total">Observaciones: ($)</label>
                      <textarea name ="observaciones" id="observaciones" class="form-control" textarea rows="4" cols="50" placeholder="Motivos de la modificación"></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <textarea name="myJson" id="myJson" rows="8" cols="80" style="display:none;"></textarea>
					<input type="hidden" name="caratula" id="caratula" value="<?php echo $caratula ?>">
					<input type="hidden" name="carComp" id="caratula" value="<?php echo $carComp ?>">
        </form>
      </div>

    </body>
  </html>


  <script type="text/javascript">
    $( document ).ready(function() {
      //------------------------------------------------------------
      //				        VARIABLES GLOBALES
      //------------------------------------------------------------
      var listaPartidas = <?php print_r(json_encode($lisComp)) ?>;
      var selected = null;
      var totalTransferencia = <?php echo $validado; ?>;
      var totalPrevio = <?php echo $compT2; ?>;
      var totalResta;
      var thisSend;

      pintaPartidas();

      //------------------------------------------------------------
      //				        FUNCIONES DEL DOM
      //------------------------------------------------------------
      //select partida to update
      $("#dynamicTable").on("click", "#modBtn", function(){
        selected = $(this).attr('meta');
        $("#addSpan").html("Modificar Partida");
        $(".windowBlock").hide();
      });

      //select partida to delete
      $("#dynamicTable").on("click", "#delBtn", function(){
        if (confirm('Realmente deseas quitar esta partida?')) {
          listaPartidas.splice( $(this).attr('meta'), 1 );
        }
        pintaPartidas();
      });

      //hide windowBlock
      $("#btnNuevaPartida").click(function(){
        $(".windowBlock").hide();
        selected = null;
        $("#addSpan").html("Nueva Partida");
      });

      //add or update partida
      $("#addOk").click(function(){
        //generate the object
        var myObject = {
          "partida": $("#addSelect").val(),
          "monto": $("#addInput").val(),
          "noNotas": $("#addNotas").val(),
          "nomPartida": $("#addSelect option:selected").attr("metaSelect")
        }

        if (selected == null) {     //its a new Partida
          listaPartidas.push( myObject );
        } else {   //its an update
          listaPartidas[selected] = myObject;
        }
        //to finish, pintaPartidas, return window block and errase addInput
        pintaPartidas();
        $(".windowBlock").show();
        $("#addInput").val("0.00");
      });

      //al Enviar comprobar si la comprobacion es correcta
      $(".submit").click(function(){

        // preguntar si enviar update sin motivo 
        if( $("#observaciones").val() == "" ){
          if( !confirm( "Se modificara la comprobación sin especificar algún motivo, ¿estas seguro@?" ) ){
            //cancela el envío
            return;
          }
        }

        //envia formulario
        $("#mainForm").submit();
      });

      //------------------------------------------------------------
      //				        FUNCIONES GENERALES
      //------------------------------------------------------------
      function pintaPartidas()
      {
        var toPrint = '';
        var totalCount = 0;
        if (listaPartidas.length == 0) {
            toPrint += '<tr><td colspan="4"><b>Aún no se registran partidas</b><td></tr>'
            $("#total").val(0);
        } else {      //if there is results
          // console.log(listaPartidas);
          for( var x=0; x<listaPartidas.length; x++){
            toPrint += '<tr>'+
                          '<td>'+(x+1)+'</td>'+
                          '<td>'+listaPartidas[x]['partida']+' - '+listaPartidas[x]['nomPartida']+'</td>'+
                          '<td>'+listaPartidas[x]['noNotas']+'</td>'+
                          '<td>'+listaPartidas[x]['monto']+'</td>'+
                          '<td>'+
                              '<button type="button" class="demi_btn" id="modBtn" meta="'+x+'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>&nbsp;&nbsp;'+
                              '<button type="button" class="demi_btn" id="delBtn" meta="'+x+'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>'+
                          '</td>'+
                       '</tr>';
             //get total count
             totalCount += parseFloat(listaPartidas[x]['monto']);
          }
        }
        $("#myJson").val( JSON.stringify(listaPartidas) );
        $("#dynamicTable").html(toPrint);

        //calculamos montos
        $("#total").val( (totalCount).toFixed(2) );
        // console.log("calculating");
        // console.log("totalTransferencia: " + totalTransferencia);
        // console.log("totalPrevio: " + totalPrevio);
        // console.log("totalCount: " + totalCount);

        totalResta = totalTransferencia - totalPrevio - totalCount;
        $("#resta").val( (totalResta).toFixed(2) );

        //comprobamos si la comprobacion no es mayor a la transferencia
        if (totalResta < 0) {
          // thisSend = 1;
          thisDif = Math.abs(totalResta);
					alert('tus comprobaciones son mayores al monto de la transferencia por: $'+(thisDif).toFixed(2));
        } else {
          // thisSend = 0;
        }
      }

    });

    //allow just numbers
    function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46))
        return true;

        return /\d/.test(String.fromCharCode(keynum));
    }

    //change all letters to mayusc
    function conMayusculas(field)
    {					// -- Cambia a Mayusculas
      field.value = field.value.toUpperCase()
    }

  </script>
