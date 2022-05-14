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

		$no = $_REQUEST['codigo'];
		$ingresoSelected = mysqli_query($con, "SELECT * FROM ingresos WHERE no = '$no' ") or die(mysqli_error($con));
		while($reg = mysqli_fetch_assoc($ingresoSelected))
		{
			$ingresos = $reg;
		}
		// print_r($ingresos);

    //get proyect list
    $proyectos = mysqli_query($con, "SELECT nombreProyecto, numeroProyecto FROM proyectos ORDER BY numeroProyecto") or die(mysqli_error($con));

    //get lista de partidasSF
    $partidasSelected = mysqli_query($con, 'SELECT sf.*, zli.nomPartida FROM partidassf AS sf JOIN zlistapartidas AS zli ON sf.noPartida = zli.noPartida WHERE sf.noSolFon = '.$ingresos['noSolFon'].' ORDER BY sf.np') or die(mysqli_error($con));
    while($reg = mysqli_fetch_assoc($partidasSelected))
		{
			$sfPartidas[] = $reg;
		}

    //get lista de partidasSF
    $partidas = mysqli_query($con, "SELECT noPartida, nomPartida FROM zlistapartidas ORDER BY noPartida")
    or die(mysqli_error($con));

    // print_r(json_encode($partidas));

    mysqli_close($con);
 ?>


  <!DOCTYPE html>
  <html lang="en" dir="ltr">
    <head>
      <meta charset="utf-8">
      <title> Modifica Solicitudes de Fondo </title>
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
        <form  method="post" action="editaIngresosSube.php" name="form1" id="mainForm">
          <div class="row">
            <div class="col-sm-8">
              <div class="row">
                <div class="col-sm-12 block_header" data-toggle="collapse" data-target="#datos_generales">
                  Datos Generales
                </div>
                <div class="col-sm-12 block_square collapse in" id="datos_generales">
                  <div class="row">
                    <div class="col-sm-4">
                      <label for="fechaElaboracion">Fecha de Elaboración:</label>
                      <input type="date" name="fechaElaboracion" class="fecha form-control" value="<?php echo $ingresos['fechaElab'];  ?>" required>
                    </div>
                    <div class="col-sm-4">
                      <label for="operacion">Tipo de operación:</label>
                      <select name="operacion" class="form-control">
                        <option value="INVERSIÓN" <?php echo ($ingresos['operacion'] == "INVERSION")? 'SELECTED' : '' ; ?> > Inversi&oacute;n </option>>
                        <option value="PROYECTOS" <?php echo ($ingresos['operacion'] == "PROYECTOS")? 'SELECTED' : '' ; ?> > Proyectos </option>
                        <option value="TERCEROS" <?php echo ($ingresos['operacion'] == "TERCEROS")? 'SELECTED' : '' ; ?> > Terceros </option>
                      </select>
                    </div>
                    <div class="col-sm-2 col-sm-offset-2">
                      <label for="noSolFon">No. de Solicitud de Fondos:</label>
                      <input type="text"  id="noSolFon"  name="noSolFon" class="form-control" onkeypress="return justNumbers(event);" size="16" value="<?php echo $ingresos['noSolFon'] ?>" ReadOnly>
                    </div>
                    <div class="col-sm-9">
                      <label for="numProy">Proyecto</label>
                      <select name="numProy" id="numProy" class="form-control">
                        <?php
                          while($reg = mysqli_fetch_array($proyectos))
                          {
                            echo '<option value="'.$reg['numeroProyecto'].'" '.(($reg['numeroProyecto'] == $ingresos['numProy'])? "SELECTED" : "" ).'>'.$reg['numeroProyecto'].' - '.$reg['nombreProyecto'].'</option>';
                          }
                        ?>
                      </select>
                    </div>
                    <div class="col-sm-3">
                      <label for="mes">Mes:</label>
                      <select name="mes" id="mes" class="form-control">
    										<option value="ENERO" <?php echo ( $ingresos['mes'] == "ENERO" )? "SELECTED" : "" ; ?>> Enero </option>
    										<option value="FEBRERO" <?php echo ( $ingresos['mes'] == "FEBRERO" )? "SELECTED" : "" ; ?> > Febrero </option>
    										<option value="MARZO" <?php echo ( $ingresos['mes'] == "MARZO" )? "SELECTED" : "" ; ?> > Marzo </option>
    										<option value="ABRIL" <?php echo ( $ingresos['mes'] == "ABRIL" )? "SELECTED" : "" ; ?> > Abril </option>
    										<option value="MAYO" <?php echo ( $ingresos['mes'] == "MAYO" )? "SELECTED" : "" ; ?> > Mayo </option>
    										<option value="JUNIO" <?php echo ( $ingresos['mes'] == "JUNIO" )? "SELECTED" : "" ; ?> > Junio </option>
    										<option value="JULIO" <?php echo ( $ingresos['mes'] == "JULIO" )? "SELECTED" : "" ; ?> > Julio </option>
    										<option value="AGOSTO" <?php echo ( $ingresos['mes'] == "AGOSTO" )? "SELECTED" : "" ; ?> > Agosto </option>
    										<option value="SEPTIEMBRE" <?php echo ( $ingresos['mes'] == "SEPTIEMBRE" )? "SELECTED" : "" ; ?> > Septiembre </option>
    										<option value="OCTUBRE" <?php echo ( $ingresos['mes'] == "OCTUBRE" )? "SELECTED" : "" ; ?> > Octubre </option>
    										<option value="NOVIEMBRE" <?php echo ( $ingresos['mes'] == "NOVIEMBRE" )? "SELECTED" : "" ; ?> > Noviembre </option>
    										<option value="DICIEMBRE" <?php echo ( $ingresos['mes'] == "DICIEMBRE" )? "SELECTED" : "" ; ?> > Diciembre </option>
    									</select>
                    </div>
                    <div class="col-sm-12">
                      <label for="concepto">Concepto:</label>
                      <input type="text" name="concepto" class="form-control" onChange="conMayusculas(this)" size="81" value="<?php echo $ingresos['concepto'] ?>" required>
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
                        <div class="col-sm-8">
                          <label for="addSelect">Selecciona la partida:</label>
                          <select class="form-control" name="addSelect" id="addSelect">
                            <?php
                              while($reg[$x] = mysqli_fetch_array($partidas))
                              {
                                echo '<option value="'.$reg[$x]['noPartida'].'" metaSelect="'.$reg[$x]['nomPartida'].'">'.$reg[$x]['noPartida'].' - '.$reg[$x]['nomPartida'].'</option>';
                              }
                            ?>
                          </select>
                        </div>
                        <div class="col-sm-4">
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
                      <label for="obs">Observaciones:</label>
                      <textarea name="obs" rows="4" cols="80" width="100px" class="form-control"><?php echo $ingresos['obs']; ?></textarea>
                    </div>
                    <div class="col-sm-12">
                      <label for="total">Total: $</label>
                      <input type="text" name ="total" id="total" class="form-control" value="<?php echo $ingresos['SFtotal']; ?>" readonly>
                    </div>
                    <div class="col-sm-12">
                      <input type="submit" class="actionBtn btn-block" value="Enviar">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <textarea name="myJson" id="myJson" rows="8" cols="80" style="display:none;"></textarea>
        </form>
      </div>

    </body>
  </html>


  <script type="text/javascript">
    $( document ).ready(function() {
      //------------------------------------------------------------
      //				        VARIABLES GLOBALES
      //------------------------------------------------------------
      var listaPartidas = <?php print_r(json_encode($sfPartidas)); ?>;
      var minMes = "<?php echo strtolower( substr($ingresos['mes'], 0, 3) )?>";
      var selected = null;
      pintaPartidas( minMes );

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
        pintaPartidas( minMes );
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
          "noPartida": $("#addSelect").val(),
          "importe": $("#addInput").val(),
          "nomPartida": $("#addSelect option:selected").attr("metaSelect"),
          "noSolFon": <?php echo $ingresos['noSolFon']; ?>,
          "np": listaPartidas.length
        }

        if (selected == null) {     //its a new Partida
          listaPartidas.push( myObject );
        } else {   //its an update
          listaPartidas[selected] = myObject;
        }
        //to finish, pintaPartidas, return window block and errase addInput
        pintaPartidas( minMes );
        $(".windowBlock").show();
        $("#addInput").val("0.00");
      });

      //------------------------------------------------------------
      //				        FUNCIONES GENERALES
      //------------------------------------------------------------
      function pintaPartidas( minMes )
      {
        var toPrint = '';
        var totalCount = 0;
        if (listaPartidas.length == 0) {
            toPrint += '<tr><td colspan="4"><b>No se encontraron partidas presupuestadas para éste mes</b><td></tr>'
            $("#total").val(0);
        } else {      //if there is results
          console.log(listaPartidas);
          for( var x=0; x<listaPartidas.length; x++){
            toPrint += '<tr>'+
                          '<td>'+(x+1)+'</td>'+
                          '<td>'+listaPartidas[x]['noPartida']+' - '+listaPartidas[x]['nomPartida']+'</td>'+
                          '<td class="text-right">$'+listaPartidas[x]['importe']+'</td>'+
                          '<td>'+
                              '<button type="button" class="demi_btn" id="modBtn" meta="'+x+'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>&nbsp;&nbsp;'+
                              '<button type="button" class="demi_btn" id="delBtn" meta="'+x+'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>'+
                          '</td>'+
                       '</tr>';
             //get total count
             totalCount += parseFloat(listaPartidas[x]['importe']);
             $("#total").val( (totalCount).toFixed(2) );
          }
        }
        $("#myJson").val( JSON.stringify(listaPartidas) );
        $("#dynamicTable").html(toPrint);
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
