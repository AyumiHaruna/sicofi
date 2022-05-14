<?php
require_once('menu.php');
session_start();
header('Content-Type: text/html; charset=UTF-8'); ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Validación de Egresos</title>

    <script type="text/javascript" src="../js/jquery-3.1.1.js"></script>

		<script src="../js/jquery-ui.min.js"></script>
		<link rel="stylesheet" type="text/css" href="../css3/jquery-ui.min.css"> </link>

		<link rel="stylesheet" href="../css3/bootstrap.min.css">

		<link rel="stylesheet" href="../css3/font-awesome.min.css">

		<link rel="stylesheet" type="text/css" href="../css2/menu.css">

		<link rel="stylesheet" href="../css3/validaEgresos.css">

		<script src="../js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="../css3/dataTables.bootstrap.min.css">
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

<!--  /////////////////////////// DIV CARGA ////////////////////////////////// -->
    <div class="col-md-12 text-center" id="carga"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>

<!--  /////////////////////////// DIV AVISO ////////////////////////////////// -->
    <div class="col-md-4 col-md-offset-4 aviso text-center"></div>

<!--  ////////////////////////// DIV AVISO ////////////////////////////////// -->
    <?php
      if($_SESSION['anio'] >= 2017){
        echo
          '<div class="col-md-2 text-center statGroup">
            <i class="fa fa-object-group" aria-hidden="true"></i> &nbsp; Cambiar status por grupo
          </div>';
      }
    ?>

<!--  ////////////////////////// DIV FORMULARIO  ////////////////////////////////// -->
    <div class="col-md-6 col-md-offset-3 subForm">
      <div class="col-md-12 bloque">
        <div class="col-md-12 subBloque">
          <legend>CAMBIAR STATUS EN GRUPO</legend>
          <b>Escribe los números de cheque que deseas modificar:</b> <br>
          <span id="indicaciones">(puedes utilizar "," para separar números y "-" para indicar intervalos, ordenandolos de menor al mayor)</span><br><br>
          <div class="col-md-2 text-right">
            <label for="intervalos">Números:</label>
          </div>
          <div class="col-md-10">
            <input type="text" class="form-control" id="intervalos" placeholder="Ej. 1, 2, 5, 7-12, 16"><br>
          </div>
          <div class="col-md-2 text-right">
            <label for="selStatus">Status:</label>
          </div>
          <div class="col-md-10">
            <select class="form-control" id="selStatus">
              <option value="1">En firma</option>
              <option value="2">Para entrega</option>
              <option value="3">Entregado</option>
            </select>
          </div>
          <div class="col-md-6">
            <button type="button" class="btn btn-block btnAction" id="btnCancel"><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp; Cancelar</button>
          </div>
          <div class="col-md-6">
            <button type="button" class="btn btn-block btnAction" id="btnCambiar"><i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i>&nbsp;Cambiar</button>
          </div>
        </div>
      </div>
    </div>

<!--  /////////////////////////// MAIN DIV ////////////////////////////////// -->
    <div class="col-md-12 mainDiv">
      <div class="col-md-12 bloque">
        <div class="col-md-12 subBloque">
          <legend>VALIDACIÓN DE EGRESOS</legend>
          <div class="col-md-12 lista">

          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
      $( document ).ready(function() {
        //--			CONDICIONES INICIALES
				//---------------------------------------
				$(".aviso, .subForm").hide();
				$('[data-toggle="tooltip"]').tooltip();
				getLisEgresos();

				//--			FUNCIONES GENERALES
				//---------------------------------------
				//-- Agrega comas a las cantidades
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
				//--

        //------------controlador de avisos------------
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
        //--

				//-- click en algun radio button
				$(".lista").on('click', '.stepSelector', function(){
					updateStatus( $(this).attr('name'), $(this).filter(':checked').val() );
				});

        //--cambia el ícono
        function cambiaIcono(no, stat){
          console.log('cambiandoIcono');
          $("#stat"+no).removeClass();
          $("#stat"+no).addClass( 'step'+stat );
          $("#stat"+no).addClass( 'text-center' );
          switch (stat) {
            case '1':
              $("#stat"+no).html('<i class="fa fa-battery-empty" aria-hidden="true"></i><br>En Firma');
            break;
            case '2':
              $("#stat"+no).html('<i class="fa fa-battery-half" aria-hidden="true"></i><br>Para entrega');
            break;
            case '3':
              $("#stat"+no).html('<i class="fa fa-battery-full" aria-hidden="true"></i><br>Entregado');
            break;
          }
        }

        //--reintentar modificación
        $(".lista").on('click', '.stepError', function(){
          updateStatus( $(this).attr('name'), $(this).filter(':checked').val() );
        });

        //-- esconde boton de status grupal
        $(window).scroll(function() {
          if ($(this).scrollTop()>0) {
              $('.statGroup').fadeOut();
           } else {
            $('.statGroup').fadeIn();
           }
        });

        //-- muestra el formulario para cambiar en grupo
        $(".statGroup").click(function(){
          $(".fondoTransparencia, .subForm").show();
        });

        //-- oculta el formulario para cambiar en grupo
        $("#btnCancel").click(function(){
          $(".fondoTransparencia, .subForm").hide();
          $("#intervalos").val('');
        });

        //-- Cambia el Status de un grupo de cheques
        $("#btnCambiar").click(function(){
          if($.trim($("#intervalos").val()) != ''){  //validamos que se hayan capturado los cheques a cambiar
            var thisString = $.trim($("#intervalos").val());
            var data = new Array();
            var okFlag = true;
            thisString = thisString.split(",");
            for(var x=0; x<thisString.length; x++){ //for -x-;
              if( isNaN(thisString[x]) ){
                if( (thisString[x]).includes("-") ){ // si es un intervalo
                  var thisSubString = (thisString[x]).split("-");
                  if(isNaN($.trim(thisSubString[0])) || isNaN($.trim(thisSubString[1])) || $.trim(thisSubString[0]) == '' || $.trim(thisSubString[1]) == ''){
                    muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Cadena de caracteres inválida');
                    okFlag = false;
                  } else {
                    thisString.splice(x, 0);
                    for(var y=0; y<=(($.trim(thisSubString[1])) -  ($.trim(thisSubString[0]))); y++){
                        data.push((parseFloat($.trim(thisSubString[0])))+y);
                    }
                  }
                } else if( ($.trim(thisString[x])).includes(" ") ) {
                  var thisSubString = (thisString[x]).split(" ");
                  for(var y=0; y<thisSubString.length; y++){  // for -y-
                     thisString.splice(x, 0);
                     data.push($.trim(thisSubString[y]));
                  } // end for -y-
                }
              } else {
                if( $.trim(thisString[x]) > 0){
                  data.push($.trim(thisString[x]));
                } else {
                  muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Cadena de caracteres inválida');
                  okFlag = false;
                }
              }
            } // end for -x-
          } else {
            muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; favor de capturar los numeros de cheque que desea cambiar');
            okFlag = false;
          }

          //-- si okFlag true entonces podemos guardar
          if(okFlag == true){
            updateStatusGrupo( JSON.stringify(data) )
          }
        });

				//--			FUNCIONES AJAX
				//---------------------------------------
				//-- obtiene la lista de egresos e imprime la tabla
				function getLisEgresos() {
          $.ajax({
              data: { type:'getLisEgresos' },
              type: "POST",
              url: "validaEgresosApi.php",
          })
          .done(function(data){
              console.log("getLisEgresos(OK)");
              console.log(<?php echo $_SESSION['anio'] ?>);
							var thisTable = ''+
							'<table class="table table-hover" id="tablaEgresos">'+
								'<thead>'+
									'<tr>'+
										'<th># Cheque</th> <th>Fecha</th> <th>Nombre</th> <th>Proyecto</th> <th>Concepto</th> <th>Total</th> <th>Por comprobar</th> <th>Validar</th>'+
                    ((parseInt(<?php echo $_SESSION['anio'] ?>) >= '2017')? '<th>Status</th> <th></th>' : '' )+
									'</tr>'+
								'</thead>'+
								'<tfoot>'+
									'<tr>'+
										'<th># Cheque</th> <th>Fecha</th> <th>Nombre</th> <th>Proyecto</th> <th>Concepto</th> <th>Total</th> <th>Por comprobar</th> <th>Validar</th>'+
                    ((parseInt(<?php echo $_SESSION['anio'] ?>) >= '2017')? '<th>Status</th> <th></th>' : '' )+
									'</tr>'+
								'</tfoot>'
								'<tbody>';

							if(data == '0'){
								thisTable += '<td colspan="8">Lista Vacía</td>'
							} else {
								data = $.parseJSON(data);
								for(var x=0; x<data.length; x++){
									thisTable += ''+
									'<tr>'+
										'<td>'+data[x]['noCheque']+'</td>'+
										'<td>'+data[x]['fechaElaboracion']+'</td>'+
										//'<td class="littleText" '+ (( data[x]['nombre'].length > 55 ) ? 'data-toggle="tooltip" title="'+data[x]['nombre']+'">'+(data[x]['nombre']).substring(0,55)+'...' : '>'+data[x]['nombre'] ) +'</td>'+
                    '<td class="littleText">'+data[x]['nombre']+'</td>'+
										'<td data-toggle="tooltip" title="'+data[x]['nombreProyecto']+'">'+data[x]['numeroProyecto']+'</td>'+
										//'<td class="littleText" '+ (( data[x]['concepto'].length > 55 ) ? 'data-toggle="tooltip" title="'+data[x]['concepto']+'">'+(data[x]['concepto']).substring(0,55)+'...' : '>'+data[x]['concepto'] ) +'</td>'+
                    '<td class="littleText">'+data[x]['concepto']+'</td>'+
										'<td>$'+addCommas(data[x]['importeTotal'])+'</td>'+
										'<td>$'+addCommas(data[x]['restaComprobar'])+'</td>'+
										'<td class="text-center"><a href="validaEgresos2.php?codigo='+data[x]['noCheque']+'"><i class="fa fa-pencil fa-lg" aria-hidden="true"></i></a></td>';

                    if(parseInt(<?php echo $_SESSION['anio'] ?>) >= 2017){
                      thisTable += ''+
                      '<td>';
                        for(var y=1; y<=3; y++){
                          thisTable += '<input type="radio" class="stepSelector" name="'+data[x]['noCheque']+'" value="'+y+'" '+((data[x]['status'] == y)? 'checked' : '')+'>';
                        }
                      thisTable += ''+
                      '</td>';

                        switch (data[x]['status']) {
                          case '1':
                            thisTable+=''+
                              '<td id="stat'+data[x]['noCheque']+'" class="step1 text-center">'+
                                '<i class="fa fa-battery-empty" aria-hidden="true"></i><br>En Firma'+
                              '</td>';
                          break;
                          case '2':
                            thisTable+=''+
                              '<td id="stat'+data[x]['noCheque']+'" class="step2 text-center">'+
                                '<i class="fa fa-battery-half" aria-hidden="true"></i><br>Para Entrega'+
                              '</td>';
                          break;
                          case '3':
                            thisTable+=''+
                              '<td id="stat'+data[x]['noCheque']+'" class="step3 text-center">'+
                                '<i class="fa fa-battery-full" aria-hidden="true"></i><br>Entregado'+
                              '</td>';
                          break;
                        }
                    }



										thisTable += ''+
									'</tr>';
								}
							}

							thisTable += ''+
								'</tbody>'+
							'</table>';

							$(".lista").html(thisTable);
              if(data != '0'){
                $('#tablaEgresos').DataTable({
                  "order": [[ 0, "desc" ]],
                  "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                  },
                  "lengthMenu": [[10, 50, 100, -1], [10, 50, 100, "Todos"]]
                });
              }

              $(".fondoTransparencia, #carga").hide()
          })
          .fail(function(){
            console.log("getLisEgresos(fail)");
            muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
          });
        }

				//-- actualiza el status del cheque seleccionado
				function updateStatus(noCheque, status){
          $("#stat"+noCheque).removeClass();
          $("#stat"+noCheque).addClass( 'stepLoading' );
          $("#stat"+noCheque).html('<i class="fa fa-spinner fa-spin fa-lg fa-fw"></i><span class="sr-only">Loading...</span>');
          $.ajax({
              data: { type:'updateStatus', noCheque, status},
              type: "POST",
              url: "validaEgresosApi.php"
          })
          .done(function(data){
              if(data == 'ok'){
                console.log("updateStatus(OK)");
                muestraAviso('success', '<i class="fa fa-check fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Status actualizado exitosamente');
                cambiaIcono(noCheque, status);
              } else {
                muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
                console.log("updateStatus(ok-fail)");
                console.log(data);
                $("#stat"+noCheque).removeClass();
                $("#stat"+noCheque).addClass( 'stepError' );
                $("#stat"+noCheque).addClass( 'text-center' );
                $("#stat"+noCheque).attr( 'meta1', noCheque );
                $("#stat"+noCheque).attr( 'meta2', status );
                $("#stat"+noCheque).html('<i class="fa fa-refresh fa-lg" meta1="'+noCheque+'" meta2="'+status+'"></i><br>Reintentar');
              }
          })
          .fail(function(){
            console.log("updateStatus(fail)");
            muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
            $("#stat"+noCheque).removeClass();
            $("#stat"+noCheque).addClass( 'stepError' );
            $("#stat"+noCheque).html('<i class="fa fa-refresh fa-lg"></i><br>Reintentar');
          });
        }

        //-- cambia el status de un grupo de cheques
        function updateStatusGrupo( grupo ){
          $.ajax({
              data: { type:'updateStatusGrupo', grupo, status: $("#selStatus").val() },
              type: "POST",
              url: "validaEgresosApi.php"
          })
          .done(function(data){
              if(data == 'ok'){
                console.log("updateStatusGrupo(OK)");
                muestraAviso('success', '<i class="fa fa-check fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Status actualizado exitosamente');
                $(".fondoTransparencia, #carga").show();
                $(".subForm").hide();
                $("#intervalos").val('');
                getLisEgresos()
              } else {
                muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
                console.log("updateStatusGrupo(ok-fail)");
                console.log(data);
              }
          })
          .fail(function(){
            console.log("updateStatusGrupo(fail)");
            muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
          });
        }


      });
    </script>
  </body>
</html>
