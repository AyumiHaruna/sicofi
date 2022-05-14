<?php require_once('menu.php');
session_start();
header('Content-Type: text/html; charset=UTF-8'); ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Comparativo de Partidas Ejercidas</title>

    <script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="../css3/bootstrap.min.css">
    <link rel="stylesheet" href="../css3/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../css2/menu.css">
    <link rel="stylesheet" href="../css3/compPart.css">
    <link rel="stylesheet" href="../css3/dataTables.bootstrap.min.css">
    <script type="text/javascript" src="../js/readOnly.js"></script>
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

<!--  /////////////////////////// MAIN DIV ////////////////////////////////// -->
    <div class="col-md-12 mainDiv">
      <div class="col-md-12 bloque">
        <div class="col-md-12 subBloque">
          <legend>TABLERO DE OPCIONES</legend>
          <div class="col-md-10 col-md-offset-1">
            <div class="col-xs-4">
              Proyecto: <br>
              <select class="form-control filtro" id="selProyecto">
              </select>
            </div>

            <div class="col-xs-4">
              Partida: <br>
              <select class="form-control filtro" id="selPartida">
              </select>
            </div>

            <div class="col-xs-4">
              Mes: <br>
              <select class="form-control filtro" id="selMes">
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12 bloque">
        <div class="col-md-12 subBloque">
          <legend>COMPARATIVO DE PARTIDAS EJERCIDAS</legend>
          <div class="comparativo">

          </div>

          <div class="resultados">

          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
      $( document ).ready(function() {
        //------- CONDICIONES INICIALES--------------------
        //-------------------------------------------------
        var minMes = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
        var comMes = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        var datosTabla = "";
        var objData = "";

        var totalPres = 0;
        var totalEjer = 0;
        var totalDife = 0;

        $(".fondoTransparencia").hide();
        $(".aviso").hide();
        obtenProyectos();
        obtenPartidas();
        obtenMes();
        $("#selPartida, #selMes").val("");
        readonly('#selPartida, #selMes', true);

        //-------- OBTIENE LOS DATOS COMPLETOS ------------
        //-------------------------------------------------
        $.ajax({
            data: { type:'getParTodas' },
            type: "POST",
            url: "compPartApi.php",
        })
        .done(function(data){
            console.log("datosCompletos(ok)");
            objData = $.parseJSON(data);
            pintaTabla('', '', '');
        })
        .fail(function(){
          console.log("datosCompletos(fail)")
          muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
        });

        //-------- OBTIENE LA LISTA DE PROYECTOS ------------
        //-------------------------------------------------
        function obtenProyectos()
        {
          $.ajax({
              data: { type:'getLisProy' },
              type: "POST",
              url: "compPartApi.php",
          })
          .done(function(data){
              console.log("obtenProyectos(OK)");
              data = $.parseJSON(data);
              //console.log(data);
              var lista = '';
              lista += '<option value=""> Sin Filtro </option>';
              for(var x=0; x<(data).length; x++){
                lista += '<option value="'+data[x]['numeroProyecto']+'">'+data[x]['numeroProyecto']+' - '+data[x]['nombreProyecto']+'</option>';
              }
              $("#selProyecto").html(lista);
          })
          .fail(function(){
            console.log("obtenProyectos(fail)");
            muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
          });
        }

        //-------- OBTIENE LA LISTA DE PARTIDAS ------------
        //-------------------------------------------------
        function obtenPartidas()
        {
          //busca datos del segundo filtro
          $.ajax({
              data: { type:'getLisPart' },
              type: "POST",
              url: "compPartApi.php",
          })
          .done(function(data){
              console.log("obtenPartidas(OK)");
              data = $.parseJSON(data);
              //console.log(data);
              var lista = '';
              lista += '<option value=""> Sin Filtro </option>';
              for(var x=0; x<(data).length; x++){
                lista += '<option value="'+data[x]['noPartida']+'">'+data[x]['noPartida']+' - '+data[x]['nomPartida']+'</option>';
              }
              $("#selPartida").html(lista);
          })
          .fail(function(){
            console.log("obtenProyectos(fail)");
            muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
          });
        }

        //-------- OBTIENE LA LISTA DE PARTIDAS ------------
        //-------------------------------------------------
        function obtenMes()
        {
          var lista = '<option value="">Sin Filtro</option>';
          for(var x=0; x<(comMes).length; x++){
            lista += '<option value="'+minMes[x]+'">'+comMes[x]+'</option>';
          }
          console.log("obtenMes(OK)");
          $("#selMes").html(lista);
        }

        //             FUNCION DE FILTROS
        //------------------------------------------------------------
        $(".filtro").change(function(){
          if( $("#selProyecto").val() == "" ) {   //si hay un proyecto seleccionado
              $("#selPartida, #selMes").val("");
              readonly('#selPartida, #selMes', true);
          } else {
            if(  $("#selPartida").val() == "" ){
              $("#selMes").val("");
              readonly('#selPartida', false);
              readonly('#selMes', true);
            } else {
              readonly('#selMes', false);
            }
          }
          //console.log('pintaTabla: '+$("#selProyecto").val()+', '+$("#selPartida").val()+','+$("#selMes").val())
          pintaTabla( $("#selProyecto").val(), $("#selPartida").val(), $("#selMes").val() );
        });

        //            pinta la tabla con los filtros
        //------------------------------------------------------------
        function pintaTabla( pro, par, mes )
        {
          //console.log(objData);
          datosTabla = "";
         thisProy = 0;
         thisPart = 0;
         thisMes = 0;

         totalPres = 0;
         totalEjer = 0;
         totalDife = 0;

          //creamos la tabla
          addTable('<table class="table table-striped table-bordered dt-responsive" cellspacing="0" id="tabComp">');
            addTable('<thead class="thead-inverse">');
              addTable('<tr>');
                addTable('<th># Proy</th>'+
                          '<th>Proyecto</th>'+
                          '<th># Part</th>'+
                          '<th>Partida</th>' +
                          '<th>Mes</th>' +
                          '<th>Presupuestado</th>'+
                          '<th>Ejercido</th>'+
                          '<th>Diferencia</th>');
              addTable('</tr>');
            addTable('</thead>');
            addTable('<tbody>');

            for(var x=0; x<Object.keys(objData).length; x++)  //nivel proyectos
            {
              if( pro == '' ) //se eligió un proyecto ? -- NO --
              {
                thisProy = Object.keys(objData)[x];
              }
              else
              {
                thisProy = pro;
                x = Object.keys(objData).length;
              }

              for(var y=0; y<Object.keys(objData[thisProy]['partidas']).length; y++)  //nivel partias
              {
                if( par == '') //se eligió un proyecto ? -- NO --
                {
                  thisPartida = Object.keys(objData[thisProy]['partidas'])[y];
                }
                else
                {
                  thisPartida = par;
                  y = Object.keys(objData[thisProy]['partidas']).length
                }

                if( objData[thisProy]['partidas'][thisPartida] != null)
                {
                  for(var z=0; z<12; z++) //nivel meses
                  {
                    if( mes == '')
                    {
                      thisMes = minMes[z]
                      var flag = z;
                    }
                    else
                    {
                      thisMes = mes;
                      var flag = minMes.indexOf(thisMes);
                      z = 12;
                    }

                    if( objData[thisProy]['partidas'][thisPartida][ thisMes ]['presupuestado'] == "0.00" && objData[thisProy]['partidas'][thisPartida][ thisMes ]['ejercidos'] == "0.00" ) {
                      // si ambas cifras son 0, no imprimas nada.
                    } else {
                      addTable('<tr>');
                        addTable('<td>'+thisProy+'</td>');
                        addTable('<td>'+objData[thisProy]['nombreProyecto']+'</td>');
                        addTable('<td>'+thisPartida+'</td>');
                        addTable('<td>'+objData[thisProy]['partidas'][thisPartida]['nombrePartida']+'</td>');
                        addTable('<td>'+ comMes[flag] +'</td>');
                        addTable('<td>$'+addCommas(objData[thisProy]['partidas'][thisPartida][ thisMes ]['presupuestado'])+'</td>');
                        addTable('<td>$'+addCommas(objData[thisProy]['partidas'][thisPartida][ thisMes ]['ejercidos'])+'</td>');
                        if( parseFloat(objData[thisProy]['partidas'][thisPartida][ thisMes ]['presupuestado']) - parseFloat(objData[thisProy]['partidas'][thisPartida][ thisMes ]['ejercidos']) < 0 ) {
                          addTable('<td class="textRed">$'+addCommas( parseFloat(objData[thisProy]['partidas'][thisPartida][ thisMes ]['presupuestado']) - parseFloat(objData[thisProy]['partidas'][thisPartida][ thisMes ]['ejercidos']) )+'</td>');
                        } else {
                          addTable('<td>$'+addCommas( parseFloat(objData[thisProy]['partidas'][thisPartida][ thisMes ]['presupuestado']) - parseFloat(objData[thisProy]['partidas'][thisPartida][ thisMes ]['ejercidos']) )+'</td>');
                        }
                        totalPres += parseFloat(objData[thisProy]['partidas'][thisPartida][ thisMes ]['presupuestado']);
                        totalEjer += parseFloat(objData[thisProy]['partidas'][thisPartida][ thisMes ]['ejercidos']);
                        totalDife += parseFloat(objData[thisProy]['partidas'][thisPartida][ thisMes ]['presupuestado']) - parseFloat(objData[thisProy]['partidas'][thisPartida][ thisMes ]['ejercidos']);
                      addTable('</tr>');
                    }
                  }
                } else { console.log('noExiste') }

              }
            }

            addTable('</tbody>');
          addTable('</table>');

          //pintamos la tabla y ejecutamos dataTables (filtros)
          $(".comparativo").html(datosTabla);
          $('#tabComp').DataTable({
            "language": {
              "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            },
            "lengthMenu": [[10, 50, 100, -1], [10, 50, 100, "Todos"]]
          });

          var pintandoRes = "";
          pintandoRes += '<table class="table table-striped table-bordered dt-responsive text-center" cellspacing="0"><tr><td>Presupuestado:<br>$'+addCommas(totalPres)+'</td><td>Ejercido:<br>$'+addCommas(totalEjer)+'</td>';
          if( totalDife >= 0 ){
            pintandoRes +='<td>Total:<br>$'+addCommas(totalDife)+'</td> </tr></table>'
          } else {
            pintandoRes +='<td class="textRed">Total:<br>$'+addCommas(totalDife)+'</td> </tr></table>'
          }

          //pintamos resultados
          $(".resultados").html(pintandoRes);
        }

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

        //             Agrega datos a var datosTabla
        //------------------------------------------------------------
        function addTable( datos )
        {
          datosTabla = datosTabla + datos;
        }

        //          Agrega comas a las cantidades
        //------------------------------------------------------------
        function addCommas(nStr)
        {
            nStr = parseFloat(nStr);
            nStr = nStr.toFixed(2);
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
      });
    </script>
  </body>
</html>
