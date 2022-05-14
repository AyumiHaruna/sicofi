<!DOCTYPE html>
<?php require_once('menu.php');
session_start(); ?>
<?php header('Content-Type: text/html; charset=UTF-8'); ?>
<html>
  <head>
    <meta charset="utf-8">
    <title>Alta de Proyectos</title>

    <script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
    <script type="text/javascript" src="../js/readOnly.js"></script>
    <link rel="stylesheet" href="../css3/bootstrap.min.css">
    <link rel="stylesheet" href="../css3/font-awesome.min.css">
    <link rel="stylesheet" href="../css3/detalleProyecto.css">
    <link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>

    <style media="screen">
      .notDisplay{
        display: none;
      }
    </style>
  </head>
  <body>
    <?php
    //revisa si el usuario esta logueado
    //sino esta logueado lo redirecciona al index
        if($_SESSION == NULL)
        {
          echo '<script languaje="javascript">
              alert("Area restringida, redireccionando...");
              location.href="../index.php";
              </script>';
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
    ?>

<!--  /////////////////////////// DIV TRANSPARENCIA ////////////////////////////////// -->
    <div class="fondoTransparencia"></div>

<!--  /////////////////////////// DIV AVISO ////////////////////////////////// -->
    <div class="col-md-4 col-md-offset-4 aviso text-center"></div>

<!--  /////////////////////////// DIV FORMULARIO DE PARTIDAS ////////////////////////////////// -->
    <div class="col-md-8 col-md-offset-2 divForPart">
      <form name="formAddPart" id="formAddPart">
        <span class="indicaciones">Deseas agregar una partida al proyecto?</span>
        <hr>
        <div class="row">
          <div class="col-md-12 form">
            <div class="col-md-2 text-right">
                Capítulo:
            </div>
            <div class="col-md-4">
              <select class="form-control divForParCapi">
                <option value="">-Seleccione un Capítulo</option>
                <option value="1000">Capítulo 1000</option>
                <option value="2000">Capítulo 2000</option>
                <option value="3000">Capítulo 3000</option>
                <!--<option value="4000">Capítulo 4000</option>-->
                <option value="5000">Capítulo 5000</option>
              </select>
            </div>
          </div>

          <div class="col-md-12 form">
            <div class="col-md-2 text-right">
              Partida:
            </div>
            <div class="col-md-10 text-right">
              <select class="form-control divForParPart">
                <option value""> ---- Selecciona un capitulo para ver las opciones</option>
              </select>
            </div>
          </div>

          <div class="col-md-12 text-left form divForParMontos">
            <div class="row">
              <span class="divForPartSpan">Montos por mes:</span>
            </div>
            <div class="row">
              <?php
                $listaMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                for($x=0; $x<12; $x++)
                {
                  echo '<div class="col-sm-2">'.$listaMeses[$x].'<br>
                          <input type="text" class="form-control cantPart partMes'.($x+1).'" value="0.00" onkeypress="return justNumbers(event);">
                        </div>';
                }
              ?>
            </div>
            <br>
            <div class="col-md-6">
              <div class="col-md-6 text-right">
                Total:
              </div>
              <div class="col-md-6">
                  <input type="text" class="form-control partTotal" value="0.00" readOnly>
              </div>
            </div>
            <div class="col-md-6 text-right">
              <button type="button" class="btn btn-default divForPartAddPartida">Agregar</button>
              <button type="button" class="btn btn-default divForPartEditPartida">Modificar</button>
            </div>
          </div>
        </div>
      </form>
    </div>

<!--  /////////////////////////// DATOS GENERALES ////////////////////////////////// -->
    <div class="col-md-6 col-md-offset-3 pantGenerales">
      <span class="indicaciones">¿Deseas crear un nuevo proyecto?</span>
      <hr>
      <div class="col-md-12">
        <div class="col-sm-6 form">
          Número de Proyecto:
          <br>
          <input type="text"  class="form-control panGenNumProy" onkeypress="return justNumbers(event);">
        </div>
        <div class="col-sm-6 form">
          Número de Cuenta:
          <br>
          <input type="text" value="8436835-563" class="form-control panGenCuenta">
        </div>
      </div>

      <div class="col-md-12">
        <div class="col-md-2 form text-right">
          Nombre del proyecto:
        </div>
        <div class="col-md-10 form">
          <textarea rows="8" cols="80" class="form-control panGenNomProy"></textarea>
        </div>
      </div>

      <div class="col-md-12">
        <div class="col-md-3 form text-right">
          Nombre del Responsable:
        </div>
        <div class="col-md-9 form">
          <input type="text" class="form-control panGenNomRes">
        </div>
      </div>

      <div class="col-md-12">
        <div class="col-md-3 form text-right">
          Título del Responsable:
        </div>
        <div class="col-md-3 form">
          <select class="form-control titulo">
            <option value="-">Ninguno</option>
            <option value="LIC">LIC</option>
            <option value="MTRO">MTRO</option>
            <option value="MTRA">MTRA</option>
            <option value="DR">DR</option>
            <option value="DRA">DRA</option>
            <option value="L.A">L.A</option>
          </select>
        </div>

        <div class="col-md-3 form <?php echo (( $_SESSION['anio'] > 2018 )? "" : "notDisplay" ) ?>"> Tipo de Proyecto: </div>
        <div class="col-md-3 form <?php echo (( $_SESSION['anio'] > 2018 )? "" : "notDisplay" ) ?>">
            <select class="form-control tipo">
            <option value="0">Proyecto</option>
            <option value="1">Gasto Básico</option>
            <option value="2">Nómina</option>
          </select>
        </div>

      </div>

      <div class="col-md-12 botones">
        <div class="col-sm-6 text-center">
          <button type="button" class="btn btn-default btn-lg panGenCancelar">Cancelar</button>
        </div>
        <div class="col-sm-6 text-center">
          <button type="button" class="btn btn-default btn-lg panGenCrear">Crear</button>
        </div>
      </div>
    </div>

<!--  /////////////////////////// DATOS DEL PROYECTO ////////////////////////////////// -->
    <div class="container pantDatosProyecto">
      <div class="row bloque">
        <div class="col-md-12">
          <span class="largeSpan">Datos del proyecto</span>
        </div>
        <!-- Datos Generales -->
        <div class="col-md-12 bloqueForm">
          <legend>DATOS GENERALES</legend>
          <br>
          <div class="col-md-4">
            <div class="row">
              <div class="col-md-6 text-right">
                Número de Proyecto:
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control panDatNumProy" readOnly>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 text-right">
                Número de Cuenta:
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control panDatCuen">
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 text-right">
                Tipo de Proyecto:
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control panDatTipo" readOnly>
              </div>
            </div>
          </div>

          <div class="col-md-8">
            <div class="row">
              <div class="col-md-3 text-right">
                Nombre del Proyecto:
              </div>
              <div class="col-md-9">
                <textarea class="form-control panDatNomProy"> </textarea>
              </div>
            </div>

            <div class="row">
              <div class="col-md-3 text-right">
                Nombre del Responsable:
              </div>
              <div class="col-md-9">
                <input type="text" class="form-control panDatNomResp">
              </div>
            </div>

            <div class="row">
              <div class="col-md-3 text-right">
                Título del Responsable:
              </div>
              <div class="col-md-3">
                <select class="form-control panDatTitu">
                  <option value="-">Ninguno</option>
                  <option value="LIC">LIC</option>
                  <option value="MTRO">MTRO</option>
                  <option value="MTRA">MTRA</option>
                  <option value="DR">DR</option>
                  <option value="DRA">DRA</option>
                  <option value="L.A">L.A</option>
                </select>
              </div>
              <div class="col-md-6 text-right">
                <button type="button" class="btn btn-default panDatUpda"> <i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i> </button>
              </div>
            </div>
          </div>
        </div>
        <!-- Lista de Patidas -->
        <div class="col-md-12 bloqueForm">
          <legend>LISTA DE PARTIDAS</legend>
          <div class="col-md-12 text-right">
            <button type="button" class="btn btn-default divParAdd"><i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>&nbsp; Agregar Partida</button>
          </div>
          <div class="col-md-12">
            <table class="table table-bordered detallePartidas" border="1px">

            </table>
          </div>
        </div>
        <!-- Presupuesto por Capitulo -->
        <div class="col-md-12 bloqueForm">
          <legend>PRESUPUESTO POR CAPÍTULOS</legend>

          <span class="subSpan">Capítulo 1000</span>
          <div class="row text-center" style="display: inline;">
            <?php
              $listaMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
              for($x=0; $x<12; $x++)
              {
                echo '<div class="col-md-1">'.$listaMeses[$x].'</div>';
              }
              for($x=0; $x<12; $x++)
              {
                echo '<div class="col-md-1 montos montoUno'.($x+1).'"><u>$999999999.00</u></div>';
              }
            ?>
          </div>

          <span class="subSpan">Capítulo 2000</span>
          <div class="row text-center" style="display: inline;">
            <?php
              $listaMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
              for($x=0; $x<12; $x++)
              {
                echo '<div class="col-md-1">'.$listaMeses[$x].'</div>';
              }
              for($x=0; $x<12; $x++)
              {
                echo '<div class="col-md-1 montos montoDos'.($x+1).'"><u>$999999999.00</u></div>';
              }
            ?>
          </div>

          <span class="subSpan">Capítulo 3000</span>
          <div class="row text-center" style="display: inline;">
            <?php
              $listaMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
              for($x=0; $x<12; $x++)
              {
                echo '<div class="col-md-1">'.$listaMeses[$x].'</div>';
              }
              for($x=0; $x<12; $x++)
              {
                echo '<div class="col-md-1 montos montoTres'.($x+1).'"><u>$999999999.00</u></div>';
              }
            ?>
          </div>

          <!--<span class="subSpan">Capítulo 4000</span>
          <div class="row text-center" style="display: inline;">
            <?php
              $listaMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
              for($x=0; $x<12; $x++)
              {
                echo '<div class="col-md-1">'.$listaMeses[$x].'</div>';
              }
              for($x=0; $x<12; $x++)
              {
                echo '<div class="col-md-1 montos montoCuatro'.($x+1).'"><u>$999999999.00</u></div>';
              }
            ?>
          </div>-->

          <span class="subSpan">Capítulo 5000</span>
          <div class="row text-center" style="display: inline;">
            <?php
              $listaMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
              for($x=0; $x<12; $x++)
              {
                echo '<div class="col-md-1">'.$listaMeses[$x].'</div>';
              }
              for($x=0; $x<12; $x++)
              {
                echo '<div class="col-md-1 montos montoCinco'.($x+1).'"><u>$999999999.00</u></div>';
              }
            ?>
          </div>

          <span class="subSpan">Montos Totales</span>
          <div class="row text-center" style="display: inline;">
            <div class="col-sm-3 text-center">
              Capitulo 1000  <br>
              ---------------------<br>
              <span class="montoCapitulo monCap1">$999999999.00</span>
            </div>
            <div class="col-sm-3 text-center">
              Capitulo 2000  <br>
              ---------------------<br>
              <span class="montoCapitulo monCap2">$999999999.00</span>
            </div>
            <div class="col-sm-3 text-center">
              Capitulo 3000  <br>
              ---------------------<br>
              <span class="montoCapitulo monCap3">$999999999.00</span>
            </div>
            <!--<div class="col-sm-3 text-center">
              Capitulo 4000  <br>
              ---------------------<br>
              <span class="montoCapitulo monCap4">$999999999.00</span>
            </div>-->
            <div class="col-sm-3 text-center">
              Capitulo 5000  <br>
              ---------------------<br>
              <span class="montoCapitulo monCap5">$999999999.00</span>
            </div>


            <div class="col-md-12 text-center montoTotal">
              Monto Total:  <br>
              ---------------------<br>
              <span class="montoCapitulo monCapT">$999999999.00</span>
            </div>
          </div>
        </div>

      </div>
    </div>

  </body>

  <script type="text/javascript">
    $( document ).ready(function() {
      //------------------------------------------------------------
      //                Condiciones Iniciales
      //------------------------------------------------------------
      /*$(".pantGenerales").hide()
      var numeroProyecto = '24041988';
      $(".panDatNumProy").val(numeroProyecto);
      pintaMontos(numeroProyecto);
      pintaPartidas(numeroProyecto);*/

      var numeroProyecto = 0;
      $(".aviso").hide();
      $(".fondoTransparencia").hide();
      $(".divForPart").hide();
      $(".pantDatosProyecto").hide();
      $(".divForPartAddPartida").hide();
      $(".divForPartEditPartida").hide();
      var idPartida = 0;

      //------------------------------------------------------------
      //          oculta formualrios
      //------------------------------------------------------------
      $(".fondoTransparencia").click(function(){
        resetMontos();
        $(".divForPart").hide();
        $(".fondoTransparencia").hide();
        $(".divForPartAddPartida").hide();
        $(".divForPartEditPartida").hide();
        readonly('.divForParCapi, select', false);
        readonly('.divForParPart, select', false);
      });

      //------------------------------------------------------------
      //          boton regresa pagina anterior
      //------------------------------------------------------------
      $(".panGenCancelar").click(function(){
        history.back()
      });

      //------------------------------------------------------------
      //     Crea el proyecto por medio de una llamada AJAX
      //------------------------------------------------------------
      $(".panGenCrear").click(function(){
        //obtiene las variables del formulario
        numeroProyecto = $(".panGenNumProy").val();
        var cuenta = $(".panGenCuenta").val();
        var nombreProyecto = $(".panGenNomProy").val();
        var nombreResponsable = $(".panGenNomRes").val();
        var titulo = $(".titulo").val();
        var tipoCuenta = $(".tipo").val();
        //si alguna variable esta vacia, entonces arroja aviso
        if( numeroProyecto == "" || cuenta == "" || nombreProyecto == "" || nombreResponsable == "" || tipoCuenta =="" ) {
          muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Falta capturar algun campo');
        //si ningun campo esta vacio entonces hace llama ajax
        } else {
          $.ajax({
              data: { tipo:'creaProy' ,numeroProyecto, cuenta, nombreProyecto, nombreResponsable, titulo, tipoCuenta },
              type: "POST",
              url: "altaProyectosSube.php",
          })
            .done(function(msg){
              if(msg == 'ok') {
                  console.log(msg);
                  muestraAviso('success', '<i class="fa fa-check fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; El proyecto fue creado con éxito');
                  $(".pantGenerales").hide();
                  //carga y muestra los datos en pantalla datos proyecto
                  $(".panDatNumProy").val(numeroProyecto);
                  $(".panDatCuen").val(cuenta);
                  $(".panDatNomProy").val(nombreProyecto);
                  $(".panDatNomResp").val(nombreResponsable);
                  $(".panDatTitu").val(titulo);
                  $(".pantDatosProyecto").show();

                  if( tipoCuenta == 0 )
                  {
                      $(".panDatTipo").val('Proyecto')
                  }
                  else if( tipoCuenta == 1 )
                  {
                      $(".panDatTipo").val('Gasto Básico')
                  }
                  else if( tipoCuenta == 2 )
                  {
                      $(".panDatTipo").val('Nomina')
                  }


                  pintaMontos(numeroProyecto);
                  pintaPartidas(numeroProyecto);
              } else {
                  console.log(msg);
                  muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Ocurrió un error al intentar crear el proyecto, favor de reintentarlo');
              }
            })
            .fail(function(){
              muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Ocurrió un error al intentar crear el proyecto, favor de reintentarlo');
            });
        }

      });

      //------------------------------------------------------------
      //     Actualiza datos del proyecto por medio de AJAX
      //------------------------------------------------------------
      $(".panDatUpda").click(function(){
        //obtiene las variables del formulario
        numeroProyecto = $(".panDatNumProy").val();
        var cuenta = $(".panDatCuen").val();
        var nombreProyecto = $(".panDatNomProy").val();
        var nombreResponsable = $(".panDatNomResp").val();
        var titulo = $(".panDatTitu").val();
        $.ajax({
            data: { tipo:'updProy' ,numeroProyecto, cuenta, nombreProyecto, nombreResponsable, titulo },
            type: "POST",
            url: "altaProyectosSube.php",
        })
          .done(function(msg){
            if(msg == 'ok') {
                console.log(msg);
                muestraAviso('success', '<i class="fa fa-check fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; El proyecto fue actualizado con éxito');
            } else {
                console.log(msg);
                muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Ocurrió un error al intentar actualizar el proyecto, favor de reintentalo');
            }
          })
          .fail(function(){
            muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Ocurrió un error al intentar actualizar el proyecto, favor de reintentarlo');
          });
      });

      //------------------------------------------------------------
      //         Abre Formulario para capturar Partidas
      //------------------------------------------------------------
      $(".divParAdd").click(function(){
        $(".fondoTransparencia").show();
        $(".divForPart").show();
        $(".divForPartAddPartida").show();
        $(".divForParCapi").prop('readonly', false);
        $(".divForParPart").prop('readonly', false);
      });

      //------------------------------------------------------------
      //    busca partidas autorizadas del capitulo seleccionado
      //------------------------------------------------------------
      $(".divForParCapi").change(function(){
        //consulta la lista de partidas del cap y rellena el select de opciones
        if( $(".divForParCapi").val() != "" ){
          //obtiene las variables del formulario
          var capitulo = $(".divForParCapi").val();
          $.ajax({
              data: { tipo:'getPartidas', capitulo },
              type: "POST",
              url: "altaProyectosSube.php",
          })
            .done(function(msg){
              msg = $.parseJSON(msg);
              $(".divForParCapi").find("option[value='']").remove();
              $(".divForParPart").html("");
              for(var x=0; x<Object.keys(msg).length; x++)
              {
                $(".divForParPart").append('<option value="'+msg[x]['noPartida']+'">'+msg[x]['noPartida']+' - '+msg[x]['nomPartida']+' </option>');
              }
            })
            .fail(function(){
              muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos');
            });
        }
      });

      //------------------------------------------------------------
      //   Redondea las cantidades capturadas a dos decimales
      //------------------------------------------------------------
      $(".cantPart").keyup(function(){
        if( $(this).val() == "" ){
          $(this).val('0');
        } else {
          // var valorCapturado = parseFloat($(this).val());
          // valorCapturado = valorCapturado.toFixed(2);
          // $(this).val( valorCapturado );
        }
        var suma = 0;
        suma = parseFloat($(".partMes1").val()) + parseFloat($(".partMes2").val()) + parseFloat($(".partMes3").val()) +
                parseFloat($(".partMes4").val()) + parseFloat($(".partMes5").val()) + parseFloat($(".partMes6").val()) +
                parseFloat($(".partMes7").val()) + parseFloat($(".partMes8").val()) + parseFloat($(".partMes9").val()) +
                parseFloat($(".partMes10").val()) + parseFloat($(".partMes11").val()) + parseFloat($(".partMes12").val());
        $(".partTotal").val( suma.toFixed(2) );
      });

      //------------------------------------------------------------
      //       Agrega partida a la base de datos
      //------------------------------------------------------------
      $(".divForPartAddPartida").click(function(){
        //revisa si algun campo es = "vacio"
        if( $(".divForParCapi").val() == "" || $(".divForParPart").val() == "" ||
            $(".partMes1").val() == "" || $(".partMes2").val() == "" ||
            $(".partMes3").val() == "" || $(".partMes4").val() == "" ||
            $(".partMes5").val() == "" || $(".partMes6").val() == "" ||
            $(".partMes7").val() == "" || $(".partMes8").val() == "" ||
            $(".partMes9").val() == "" || $(".partMes10").val() == "" ||
            $(".partMes11").val() == "" || $(".partMes12").val() == "" ||
            $(".partTotal").val() == "")
        {
          console.log('vacio');
        } else {
          var capitulo = $(".divForParCapi").val();   var noPartida =  $(".divForParPart").val();
          var ene = $(".partMes1").val();             var feb = $(".partMes2").val();
          var mar = $(".partMes3").val();             var abr = $(".partMes4").val();
          var may = $(".partMes5").val();             var jun = $(".partMes6").val();
          var jul = $(".partMes7").val();             var ago = $(".partMes8").val();
          var sep = $(".partMes9").val();             var oct = $(".partMes10").val();
          var nov = $(".partMes11").val();            var dic = $(".partMes12").val();
          var total = $(".partTotal").val();
          //realizamos post por AJAX

          $.ajax({
              data: { tipo:'createPartidas', numeroProyecto, capitulo, noPartida, ene, feb, mar, abr,
                      may, jun, jul, ago, sep, oct, nov, dic, total },
              type: "POST",
              url: "altaProyectosSube.php",
          })
            .done(function(msg){
              console.log(msg);
              if(msg == 'ok') {
                muestraAviso('success', '<i class="fa fa-check fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Se agregó la partida con éxito');
                $(".divForPart").hide();
                $(".divForPartAddPartida").hide();
                $(".divForPartEditPartida").hide();
                resetMontos();
                $(".fondoTransparencia").hide();
                pintaMontos(numeroProyecto);
                pintaPartidas(numeroProyecto);
              } else {
                muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Ocurrió un error al intentar agregar la partida, favor de reintentalo');
              }
            })
            .fail(function(){
              muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Ocurrió un error al intentar agregar la partida, favor de reintentalo');
            });
        }
      });

      //------------------------------------------------------------
      //       Elimina una partida y resta su valor en la db
      //------------------------------------------------------------
      $(".detallePartidas").on('click', '.elimina', function(){
        if (confirm("¿Realmente desea eliminar esta partida?"))
        { //obtenemos el id
          var id = $(this).attr('id');
          $.ajax({
              data: { tipo:'delPartidas', numeroProyecto, id },
              type: "POST",
              url: "altaProyectosSube.php",
          })
            .done(function(msg){
              console.log(msg);
              muestraAviso('success', '<i class="fa fa-check fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Se eliminó la partida con éxito');
              pintaMontos(numeroProyecto);
              pintaPartidas(numeroProyecto);
            })
            .fail(function(){
              muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos');
            });
        }
      });

      //------------------------------------------------------------
      //      Modifica una partida y actualiza los montos
      //------------------------------------------------------------
      $(".detallePartidas").on('click', '.edita', function(){
        $(".fondoTransparencia").show();
        $(".divForPart").show();
        $(".divForPartEditPartida").show();
        readonly('.divForParCapi, select', true);
        readonly('.divForParPart, select', true);
          idPartida = $(this).attr('id');
          $.ajax({
              data: { tipo:'getThisPartida', id:idPartida },
              type: "POST",
              url: "altaProyectosSube.php",
          })
            .done(function(msg){
              msg = $.parseJSON(msg);
              var mes = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
              $(".divForParCapi").val(msg['capitulo']);
              $(".divForParPart").html('<option value="'+msg['numeroPartida']+'">'+msg['numeroPartida']+' - '+msg['nomPartida']+'</option>');
              for(var x=0; x<12; x++)
              {
                $(".partMes"+(x+1)).val(msg[ mes[x] ]);
              }
              $(".partTotal").val(msg['total']);
            })
            .fail(function(){
              muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos');
            });
      });

      //------------------------------------------------------------
      //     Almacena los datos modificados en la base de datos
      //------------------------------------------------------------
      $(".divForPartEditPartida").click(function(){
        //revisa si algun campo es = "vacio"
        if( $(".divForParCapi").val() == "" || $(".divForParPart").val() == "" ||
            $(".partMes1").val() == "" || $(".partMes2").val() == "" ||
            $(".partMes3").val() == "" || $(".partMes4").val() == "" ||
            $(".partMes5").val() == "" || $(".partMes6").val() == "" ||
            $(".partMes7").val() == "" || $(".partMes8").val() == "" ||
            $(".partMes9").val() == "" || $(".partMes10").val() == "" ||
            $(".partMes11").val() == "" || $(".partMes12").val() == "" ||
            $(".partTotal").val() == "")
        {
          console.log('vacio');
        } else {
          var capitulo = $(".divForParCapi").val();   var noPartida =  $(".divForParPart").val();
          var ene = $(".partMes1").val();             var feb = $(".partMes2").val();
          var mar = $(".partMes3").val();             var abr = $(".partMes4").val();
          var may = $(".partMes5").val();             var jun = $(".partMes6").val();
          var jul = $(".partMes7").val();             var ago = $(".partMes8").val();
          var sep = $(".partMes9").val();             var oct = $(".partMes10").val();
          var nov = $(".partMes11").val();            var dic = $(".partMes12").val();
          var total = $(".partTotal").val();
          //realizamos post por AJAX
          $.ajax({
              data: { tipo:'editaPartida', numeroProyecto, capitulo, noPartida, ene, feb, mar, abr,
                      may, jun, jul, ago, sep, oct, nov, dic, total, id:idPartida },
              type: "POST",
              url: "altaProyectosSube.php",
          })
            .done(function(msg){
              console.log(msg);
              if(msg == 'ok') {
                muestraAviso('success', '<i class="fa fa-check fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Se agregó la partida con éxito');
                $(".divForPart").hide();
                $(".divForPartAddPartida").hide();
                $(".divForPartEditPartida").hide();
                resetMontos();
                $(".fondoTransparencia").hide();
                readonly('.divForParCapi, select', false);
                readonly('.divForParPart, select', false);
                pintaMontos(numeroProyecto);
                pintaPartidas(numeroProyecto);
              } else {
                muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Ocurrió un error al intentar modificar la partida, favor de reintentalo');
              }
            })
            .fail(function(){
              muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Ocurrió un error al intentar modificar la partida, favor de reintentalo');
            });
        }
      });
    });//---FIN DE JQUERY

    //------------------------------------------------------------
    //                muestra mensaje de aviso
    //------------------------------------------------------------
    function muestraAviso(tipo, mensaje)
    {
      if(tipo == 'success'){ $(".aviso").addClass('success') }
      $(".aviso").html(mensaje);
      $(".aviso").show();
      setTimeout( function(){
        $(".aviso").hide();
        $(".aviso").removeClass('success');
        //$(".fondoTransparencia").hide();
      } ,2500);
    }

    //------------------------------------------------------------
    //                 permite solo usar numeros
    //------------------------------------------------------------
    function justNumbers(e)
    {
      var keynum = window.event ? window.event.keyCode : e.which;
      if ((keynum == 8) || (keynum == 46))
      return true;

      return /\d/.test(String.fromCharCode(keynum));
    }

    //------------------------------------------------------------
    //          pinta los montos po mes de cada capítulo
    //------------------------------------------------------------
    function pintaMontos(numProy)
    {
      $.ajax({
          data: { tipo:'getMontos', numeroProyecto:numProy },
          type: "POST",
          url: "altaProyectosSube.php",
      }).done(function(msg){
          msg = $.parseJSON(msg);
          //console.log(msg);
          //imprimimos valores
          var minMes = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
          var tag = ['Uno', 'Dos', 'Tres', 'Cuatro', 'Cinco'];
          for(var x=0; x<5; x++)
          {
            for(var y=0; y<12; y++)
            {
              $(".monto"+tag[x]+(y+1)).html(addCommas("$"+msg[ minMes[y]+(x+1) ]));
            }
            $(".monCap"+(x+1)).html(addCommas('$'+msg['cap'+(x+1)+'000']));
          }
           $(".monCapT").html(addCommas('$'+msg['totalAutorizado']));
      }).fail(function(){
          muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Error de conexión con la Base de Datos');
      });
    }

    //------------------------------------------------------------
    //          pinta la lista de partidas
    //------------------------------------------------------------
    function pintaPartidas(numProy)
    {
      $.ajax({
          data: { tipo:'getPartidasProyecto', numeroProyecto:numProy },
          type: "POST",
          url: "altaProyectosSube.php",
      }).done(function(msg){
          msg = $.parseJSON(msg);
          //msg = msg[0];
          console.log(msg);
          var datosPartidas = ''+
          '<thead>'+
            '<tr>' +
              '<td>No. de Partida</td>'+
              '<td>Partida</td>'+
              '<td>Enero</td><td>Febrero</td>'+
              '<td>Marzo</td><td>Abril</td><td>Mayo</td>'+
              '<td>Junio</td><td>Julio</td><td>Agosto</td>'+
              '<td>Septiembre</td><td>Octubre</td><td>Noviembre</td><td>Diciembre</td>'+
              '<td>Total</td><td>Acciones</td>'
            '</tr>' +
          '</thead>'+
          '<tbody>';
          for(var x=0; x<Object.keys(msg).length; x++){
            datosPartidas = datosPartidas +
            '<tr>'+
              '<td>'+msg[x]['numeroPartida']+'</td>' + '<td>'+msg[x]['nomPartida']+'</td>' +
              '<td>$'+addCommas(msg[x]['ene'])+'</td>' +
              '<td>$'+addCommas(msg[x]['feb'])+'</td>' + '<td>$'+addCommas(msg[x]['mar'])+'</td>' +
              '<td>$'+addCommas(msg[x]['abr'])+'</td>' + '<td>$'+addCommas(msg[x]['may'])+'</td>' +
              '<td>$'+addCommas(msg[x]['jun'])+'</td>' + '<td>$'+addCommas(msg[x]['jul'])+'</td>' +
              '<td>$'+addCommas(msg[x]['ago'])+'</td>' + '<td>$'+addCommas(msg[x]['sep'])+'</td>' +
              '<td>$'+addCommas(msg[x]['oct'])+'</td>' + '<td>$'+addCommas(msg[x]['nov'])+'</td>' +
              '<td>$'+addCommas(msg[x]['dic'])+'</td>' + '<td>$'+addCommas(msg[x]['total'])+'</td>'+
              '<td> <i class="fa fa-pencil-square-o fa-lg edita" id="'+msg[x]['no']+'" aria-hidden="true"></i> &nbsp; <i class="fa fa-trash fa-lg elimina" id="'+msg[x]['no']+'" aria-hidden="true"></i> </td>'
            '</tr>';
            }
            datosPartidas = datosPartidas +
            '</tbody>';

            $(".detallePartidas").html(datosPartidas);
      }).fail(function(){
          muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Error de conexión con la Base de Datos');
      });
    }

    //------------------------------------------------------------
    //          Reset a montos de capturar
    //------------------------------------------------------------
    function resetMontos()
    {
      for(var x=1; x<=12; x++)
      {
        $(".partMes"+x).val("0.00");
      }
      $(".partTotal").val("0.00");
    }

    //------------------------------------------------------------
    //          Agrega comas a las cantidades
    //------------------------------------------------------------
    function addCommas(nStr)
    {
        nStr += '';
        var x = nStr.split('.');
        var x1 = x[0];
        var x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }
  </script>
</html>
