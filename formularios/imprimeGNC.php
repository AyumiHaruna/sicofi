
<?php
	//require_once('../dompdf/dompdf_config.inc.php');	//incluimos la libreria de dompdf
	require_once('../config.php');	//requerido para la conexión a la base de datos
	session_start();
	header('Content-Type: text/html; charset=UTF-8');
  //con este dato generamos la busqueda en la DB
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
      <title>Recibo de Gastos no Comprobables</title>

      <script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
  		<link rel="stylesheet" href="../css3/bootstrap.min.css">
  		<link rel="stylesheet" href="../css3/font-awesome.min.css">
      <link rel="stylesheet" href="../css3/imprimeGNC.css">

  </head>
  <body>
    <div class="container">
			<div class="row">
				<!--  /////////////////////////// DIV TRANSPARENCIA ////////////////////////// -->
						<div class="fondoTransparencia"></div>

				<!--  /////////////////////////// DIV AVISO ////////////////////////////////// -->
						<div class="col-md-5 col-md-offset-2 aviso text-center"></div>

				<!--  /////////////////////////// DIV TRANSPARENCIA ////////////////////////// -->
						<div class="col-md-5 col-md-offset-2 text-center control">
							Modificar éste texto por: <br>
							<input type="text" name="" class="form-control" id="changeInput"><br>
							<button type="button" id="can">Cancelar</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="mod">Modificar</button>
						</div>


			</div>

      <div class="row logos">
        <div class="col-xs-6 text-center">
          <img src="../imagen/logoCultura.png" id="logoCultura">
        </div>
        <div class="col-xs-6 text-center">
          <img src="../imagen/logoInah.png" id="logoInah">
        </div>
      </div>

      <div class="row bloque1">
        <div class="col-md-12 text-right">
          <span id="span1-1">INSTITUTO NACIONAL DE ANTROPOLOGÍA E HISTORIA</span><br>
          <span id="span1-2">SECRETARIA ADMINISTRATIVA</span><br>
          <span id="span1-3"> </span><br>
          <span id="span1-4"> </span>
        </div>
      </div>

      <div class="row bloque2" align="justify">
        Recibí del Instituto Nacional de Antropología e Historia de la Coordinación Nacional de
        Conservación del Patrimonio Cultural, la cantidad de $<span id="span2-1"> </span>
        <span id="span2-2"></span>, por concepto de gastos no
        comprobables, conforme lo establece el Oficio - Circular N°401B (17)33.2014/11, con base
        en las normas publicadas en el Diario Oficial de la Federación el 28 de diciembre del 2007,
        VIÁTICOS, originados de la comisión realizada a <button type="button" class="updButton hidden-print" id="1"><i class="fa fa-pencil" aria-hidden="true"></i></button><span id="span2-3">Lugar de la Comisión</span>,
        <div class="divDate">
        	del <span id="span2-4"> </span> al <span id="span2-5"> </span>.
        </div>
      </div>

      <div class="row bloque3">
        <div class="col-xs-4 text-center cMargen">
          ATENTAMENTE <br><br><br><br><br>
          <button type="button" class="updButton hidden-print" id="2"><i class="fa fa-pencil" aria-hidden="true"></i></button><span id="span3-1"></span>
        </div>
        <div class="col-xs-4 text-center cMargen">
          VISTO BUENO <br><br><br><br><br>
          <span id="span3-2"> </span>
        </div>
        <div class="col-xs-4 text-center cMargen">
          AUTORIZADO <br><br><br><br><br>
          <span id="span3-3"> </span>
        </div>
        <div class="col-xs-4 text-center cMargen2">
          COMISIONADO
        </div>
        <div class="col-xs-4 text-center cMargen2">
          SUBDIRECTORA ADMINISTRATIVA
        </div>
        <div class="col-xs-4 text-center cMargen2">
          COORDINADOR NACIONAL
        </div>
      </div>

			<br>
			<div class="row text-center">
							<button type="button" class="print hidden-print" id="2"><i class="fa fa-print fa-3x" aria-hidden="true"></i></button>
			</div>

    </div>
  </body>

  <script type="text/javascript">
    $( document ).ready(function() {
      //------------------------------------------------
      //            CONDICIONES INICIALES
      //------------------------------------------------
			//---------- VARIABLES GLOBALES ------------------
			var gNoCheque = <?php echo $_GET['codigo']; ?>;
      var gMeses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
			var gDatosCheque;
			var gbloqueMod = 0;

			//---------- FUNCIONES INICIALES -----------------
			$(".fondoTransparencia").hide();
			$(".aviso").hide();
			$(".control").hide();
			getGenerales();
			getAutoridades();
      pintaFechaActual();
			setTimeout(function(){
			  pintaDatos();
			}, 500);


      //------------------------------------------------
      //            FUNCIONES DEL DOM
      //------------------------------------------------
			//----- OCULTA TRANSPARENCIA Y CONTROL------------
			$(".fondoTransparencia, #can").click(function(){
				$(".fondoTransparencia, .control").hide();
				gbloqueMod = 0;
			});

			$(".updButton").click(function(){
				gbloqueMod = $(this).attr("id");
				if(gbloqueMod == "1") {
					$("#changeInput").val( $("#span2-3").html() );
				} else if( gbloqueMod == "2") {
					$("#changeInput").val( $("#span3-1").html() );
				}
				$(".fondoTransparencia").show();
				$(".control").show();
			});

			$("#mod").click(function(){
				if(gbloqueMod == "1") {
					$("#span2-3").html( $("#changeInput").val() );
					guardaExtra();
				} else if(gbloqueMod == "2") {
					$("#span3-1").html( $("#changeInput").val() );
				}
				$(".fondoTransparencia").hide();
				$(".control").hide();
			});

			$(".print").click(function(){
				window.print();
			});
      //------------------------------------------------
      //              FUNCIONES AJAX
      //------------------------------------------------
			function getGenerales()
			{
				$.ajax({
						data: { type:'getGenerales', noCheque:gNoCheque },
						type: "POST",
						url: "validaEgresosSube.php",
				})
				.done(function(data){
					data = $.parseJSON(data);
					gDatosCheque = data;
				})
				.fail(function(){
					muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
				});
			}

			function getAutoridades()
			{
				$.ajax({
						data: { type:'getAutoridades' },
						type: "POST",
						url: "validaEgresosSube.php",
				})
				.done(function(data){
					data = $.parseJSON(data);
					$("#span3-2").html( data['admin'] );
					$("#span3-3").html( data['titular'] );
				})
				.fail(function(){
					muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
				});
			}

			function guardaExtra()
			{
				$.ajax({
						data: { type:'guardaExtra', extra:$("#changeInput").val(), noCheque:gNoCheque },
						type: "POST",
						url: "validaEgresosSube.php",
				})
				.done(function(data){
					console.log(data);
					if(data == 'ok') {
						muestraAviso('success', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Lugar de comisión guardado');
					} else {
						muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Ocurrió un error al intentar guardar, favor de reintentarlo');
					}
				})
				.fail(function(){
					muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
				});
			}

      //------------------------------------------------
      //            FUNCIONES GENERALES
      //------------------------------------------------
			//-----------PINTA DATOS AL RECIBO----------------
			//------------------------------------------------
			function pintaDatos()
			{
				console.log(gDatosCheque);
				$("#span1-4").html('BUENO POR $'+gDatosCheque['comprobacion6']);
				$("#span2-1").html(gDatosCheque['comprobacion6']);
				$("#span2-2").html('('+gDatosCheque['montoLetra']+')');
				$("#span3-1").html(gDatosCheque['nombre']);

				if( gDatosCheque['extra'] != null) {
					$("#span2-3").html(gDatosCheque['extra'])
				}

				var textDate = '';
				if( gDatosCheque['iniVig'] != gDatosCheque['finVig']){
					var f=new Date(gDatosCheque['iniVig']);
					f.setSeconds(f.getSeconds() + 43200);
					textDate += "del " + f.getDate() + " de " + gMeses[f.getMonth()] + " de " + f.getFullYear();
	        /*var f = (f.getDate() + " de " + gMeses[f.getMonth()] + " de " + f.getFullYear());
					$("#span2-4").html(f);*/

					var f=new Date(gDatosCheque['finVig']);
					f.setSeconds(f.getSeconds() + 43200);
					textDate += " al " + f.getDate() + " de " + gMeses[f.getMonth()] + " de " + f.getFullYear();
	        /*var f = (f.getDate() + " de " + gMeses[f.getMonth()] + " de " + f.getFullYear());
					$("#span2-5").html(f);*/
					//NumeroALetras(gDatosCheque['comprobacion6']);
				} else {
					var f=new Date(gDatosCheque['iniVig']);
					f.setSeconds(f.getSeconds() + 43200);
					textDate += "el " + f.getDate() + " de " + gMeses[f.getMonth()] + " de " + f.getFullYear();
				}
				//console.log(textDate);
				$(".divDate").html(textDate);
				//NumeroALetras(gDatosCheque['comprobacion6']);
			}

      //----------OBTIENE LA FECHA ACTUAL---------------
      //------------------------------------------------
      function pintaFechaActual()
      {
        var f=new Date();
        var f = (f.getDate() + " de " + gMeses[f.getMonth()] + " de " + f.getFullYear());
				$("#span1-3").html('Ciudad de México, a '+f);
      }

			//------------controlador de avisos------------
			//---------------------------------------------
			function muestraAviso(tipo, mensaje)
			{
				if(tipo == 'success'){ $(".aviso").addClass('success') }
				$(".aviso").html(mensaje);
				$(".aviso").show();
				setTimeout( function(){
					$(".aviso").hide();
					$(".aviso").removeClass('success');
					//$(".fondoTransparencia").hide();
				} ,3500);
			}
    });


  </script>
</html>
