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

    //get las S.F. register
    $ultimo = mysqli_query($con, "SELECT noSolFon FROM ingresos ORDER BY noSolFon DESC LIMIT 1") or die(mysqli_error($con));
    while($ult = mysqli_fetch_array($ultimo))
    {
      $lastSF = $ult['noSolFon'];
    }

    //get proyect list
    $proyectos = mysqli_query($con, "SELECT nombreProyecto, numeroProyecto FROM proyectos ORDER BY numeroProyecto") or die(mysqli_error($con));

    //get lista de partidasSF
    $partidas = mysqli_query($con, "SELECT noPartida, nomPartida FROM zlistapartidas ORDER BY noPartida")
    or die(mysqli_error($con));

    mysqli_close($con);
 ?>


  <!DOCTYPE html>
  <html lang="en" dir="ltr">
    <head>
      <meta charset="utf-8">
      <title> Alta Solicitudes de Fondo </title>
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
        <form  method="post" action="SFsube.php" name="form1" id="mainForm">
          <div class="row">
              <div class="col-sm-8">
                <div class="row">
                  <div class="col-sm-12 block_header" data-toggle="collapse" data-target="#datos_generales">
                    Datos Generales
                  </div>
                  <div class="col-sm-12 block_square collapse in" id="datos_generales">
                    <div class="row">
                      <div class="col-sm-12 text-right">
                        Última S.F. registrada: <b><?php echo (isset($lastSF)? $lastSF : 'N/A' ) ?></b>
                      </div>
                      <div class="col-sm-4">
                        <label for="fechaElaboracion">Fecha de Elaboración:</label>
                        <input type="date" name="fechaElaboracion" class="fecha form-control" value="<?php echo date('Y-m-d');?>" required>
                      </div>
                      <div class="col-sm-4">
                        <label for="operacion">Tipo de operación:</label>
                        <select name="operacion" class="form-control">
                          <option value="INVERSIÓN"> Inversi&oacute;n </option>>
                          <option value="PROYECTOS" selected> Proyectos </option>
                          <option value="TERCEROS"> Terceros </option>
                        </select>
                      </div>
                      <div class="col-sm-2 col-sm-offset-2">
                        <label for="noSolFon">No. de Solicitud de Fondos:</label>
                        <input type="text"  id="noSolFon"  name="noSolFon" class="form-control" onkeypress="return justNumbers(event);" size="16" value="<?php echo (isset($lastSF)? $lastSF+1 : 1 ) ?>" required>
                      </div>
                      <div class="col-sm-9">
                        <label for="numProy">Proyecto</label>
                        <select name="numProy" id="numProy" class="form-control">
                          <?php
                            while($reg = mysqli_fetch_array($proyectos))
                            {
                              echo '<option value="'.$reg['numeroProyecto'].'">'.$reg['numeroProyecto'].' - '.$reg['nombreProyecto'].'</option>';
                            }
                          ?>
                        </select>
                      </div>
                      <div class="col-sm-3">
                        <label for="mes">Mes:</label>
                        <select name="mes" id="mes" class="form-control">
      										<option value="ENERO"> Enero </option> 	<option value="FEBRERO"> Febrero </option>
      										<option value="MARZO"> Marzo </option> 	<option value="ABRIL"> Abril </option>
      										<option value="MAYO"> Mayo </option> 	<option value="JUNIO"> Junio </option>
      										<option value="JULIO"> Julio </option> 	<option value="AGOSTO"> Agosto </option>
      										<option value="SEPTIEMBRE"> Septiembre </option> 	<option value="OCTUBRE"> Octubre </option>
      										<option value="NOVIEMBRE"> Noviembre </option> 	<option value="DICIEMBRE"> Diciembre </option>
      									</select>
                      </div>
                      <div class="col-sm-12">
                        <label for="concepto">Concepto:</label>
                        <input type="text" name="concepto" class="form-control" onChange="conMayusculas(this)" size="81" required>
                      </div>
                      <div class="col-sm-12 text-right">
                        <button type="button" name="button" class="actionBtn" id="autofill">Autorrellenar &nbsp; <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> </button>
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
                        <textarea name="obs" rows="4" cols="80" width="100px" class="form-control"></textarea>
                      </div>
                      <div class="col-sm-12">
                        <label for="total">Total: $</label>
                        <input type="text" name ="total" id="total" class="form-control" value="0" readonly>
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
      var listaPartidas = new Array();
      var minMes = "ene";
      var selected = null;

      //------------------------------------------------------------
      //				        FUNCIONES DEL DOM
      //------------------------------------------------------------
      //fill table with detalleProyecto
      $("#autofill").click(function(){
        getPartidasMes();
      });

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
          [minMes]: $("#addInput").val(),
          "nomPartida": $("#addSelect option:selected").attr("metaSelect")
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
          for( var x=0; x<listaPartidas.length; x++){
            console.log(listaPartidas[x]);
            toPrint += '<tr>'+
                          '<td>'+(x+1)+'</td>'+
                          '<td>'+listaPartidas[x]['noPartida']+' - '+listaPartidas[x]['nomPartida']+'</td>'+
                          '<td class="text-right">$'+listaPartidas[x][ minMes ]+'</td>'+
                          '<td>'+
                              '<button type="button" class="demi_btn" id="modBtn" meta="'+x+'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>&nbsp;&nbsp;'+
                              '<button type="button" class="demi_btn" id="delBtn" meta="'+x+'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>'+
                          '</td>'+
                       '</tr>';
             //get total count
             totalCount += parseFloat(listaPartidas[x][ minMes ]);
             $("#total").val( (totalCount).toFixed(2) );
          }
        }
        $("#myJson").val( JSON.stringify(listaPartidas) );
        $("#dynamicTable").html(toPrint);
      }

      //------------------------------------------------------------
      //				        FUNCIONES CON AJAX
      //------------------------------------------------------------
      function getPartidasMes()
      {
        var thisMes = $("#mes").val();
        var thisNumProy = $("#numProy").val();
        console.log(thisNumProy);
        minMes = thisMes.substring(0, 3);
        minMes = minMes.toLowerCase();

        $.ajax({
            data: { type:'getPartidasMes', mes:thisMes, numProy:thisNumProy},
            type: "POST",
            url: "SFapi.php",
        })
        .done(function(data){
          if(data != '0')
          {
            listaPartidas = $.parseJSON(data);
          }
          else
          {
            listaPartidas = new Array();
          }
          pintaPartidas( minMes );
        })
        .fail(function(){
          muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
        });
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
