<!DOCTYPE html>
<?php
	require_once('menu.php');
	session_start();
	header('Content-Type: text/html; charset=UTF-8');
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>Registro de Egresos</title>

		<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
		<script type="text/javascript" src="../js/jquery-ui.js"></script>
		<script type="text/javascript" src="../js/readOnly.js"></script>
		<script type="text/javascript" src="../js/jquery.mask.min.js"></script>
		<link rel="stylesheet" href="../css3/jquery-ui.css">
		<link rel="stylesheet" href="../css3/bootstrap.min.css">
		<link rel="stylesheet" href="../css3/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" href="../css3/altaEgresos.css">
	</head>
	<body>
		<?php //-------------------------	INICIA MENÚ	--------------------------
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

		<!--  /////////////////////////// DIV TRANSPARENCIA ////////////////////////// -->
				<div class="fondoTransparencia"></div>

		<!--  /////////////////////////// DIV AVISO ////////////////////////////////// -->
				<div class="col-md-4 col-md-offset-4 aviso text-center"></div>

	  <!--  /////////////////////////// DIV AVISO ////////////////////////////////// -->
			  <div class="col-md-4 col-md-offset-4 control" >
					<div class="col-xs-6 text-center">
						<button type="button" id="imprimir" class="btn btn-block btn-lg imprimir actionButton"> <i class="fa fa-print" aria-hidden="true"></i> &nbsp; Imprimir</button>
					</div>
					<div class="col-xs-6 text-center">
						<button type="button" id="nuevo" class="btn btn-block btn-lg nuevo actionButton"> <i class="fa fa-file-text-o" aria-hidden="true"></i> &nbsp; Nuevo</button>
					</div>
			  </div>

		<!--  /////////////////////////// DIV CONTROL ////////////////////////////////// -->
				<div class="container mainDiv">
					<div class="col-md-12 bloque">
						<div class="col-md-12 subBloque">
							<legend>DATOS DEL CHEQUE</legend>
							<br>
							<div class="row">
								<div class="col-xs-3 text-right">	No. de Cheque: </div>
								<div class="col-xs-3">
									<input type="text" name="noCheque" id="noCheque" class="noCheque form-control" onkeypress="return justNumbers(event);" required>
							  </div>
								<div class="col-xs-3 text-right">	Último Cheque Registrado: </div>
								<div class="col-xs-3 ultimoCheque">	--- </div>
							</div>
							<div class="row">
								<div class="col-xs-3 text-right">	Folio </div>
								<div class="col-xs-3">
									<input type="text" name="folio" id="folio" class="folio form-control" onkeypress="return justNumbers(event);" required>
								</div>
								<div class="col-xs-3 text-right">	Fecha de Elaboración </div>
								<div class="col-xs-3">
									<input type="date" name="fechaElaboracion" id="fechaElaboracion" class="fechaElaboracion form-control">
								</div>
								<div class="col-xs-3 text-right">	Nombre: </div>
								<div class="col-xs-9">
									<input type="text" name="nombre" id="nombre" class="nombre form-control">
								</div>
								<div class="col-xs-3 text-right">	Concepto: </div>
								<div class="col-xs-9">
									<input type="text" name="concepto" id="concepto" class="concepto form-control">
								</div>
								<div class="col-xs-3 text-right">	Proyecto: </div>
								<div class="col-xs-9">
									<select name="proyecto" class="proyecto form-control" id="proyecto" required>
										<option value=""> --- Seleccione un proyecto de la lista --- </option>
									</select>
								</div>

								<div class="col-md-12 bloqueViaticos">
									<div class="col-xs-4 text-center">
										Cheque de Viaticos?<br><br>
										<input type="checkbox" name="viaticos" id="viaticos" class="viaticos">
									</div>
									<div class="col-xs-4 text-center">
										Inicio de la Vigencia<br><br>
										<input type="date" name="iniVig" id="iniVig" class="iniVig form-control">
									</div>
									<div class="col-xs-4 text-center">
										Fin de la Vigencia<br><br>
										<input type="date" name="finVig" id="finVig" class="finVig form-control">
									</div>
								</div>

								<div class="col-md-12 bloqueObs">
									<div class="col-xs-4 text-right">
										Observaciones:
									</div>
									<div class="col-xs-8">
										<textarea name="observaciones" id="observaciones" class="observaciones form-control"rows="5" cols="80"></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-4 bloque">
						<div class="col-md-12 subBloque">
							<legend>Disponible en el Proyecto</legend>
							<br>
							Capítulo 1000: &nbsp; <span id="dis1" class="spanAzul"></span> 	<br><br>
							Capítulo 2000: &nbsp; <span id="dis2" class="spanAzul"></span>	<br><br>
							Capítulo 3000: &nbsp; <span id="dis3" class="spanAzul"></span>	<br><br>
							Capítulo 4000: &nbsp; <span id="dis4" class="spanAzul"></span>	<br><br>
							Capítulo 5000: &nbsp; <span id="dis5" class="spanAzul"></span>	<br><br>
							Total: &nbsp; <span id="disT" class="spanAzul"></span>	<br>
						</div>
					</div>

					<div class="col-md-8 bloque">
						<div class="col-md-12 subBloque">
							<legend>Montos del Cheque</legend>
							<br>

							<?php
								for($x=1; $x<=5; $x++)
								{
									echo '
										<div class="row">
											<div class="col-xs-4 text-right">
													Capítulo '.$x.'000: &nbsp; $
											</div>
											<div class="col-xs-5">
												<input type="text" name="cap'.$x.'000" class="cap'.$x.'000 form-control" id="cap'.$x.'000" value="0.00">
											</div>
											<div class="col-xs-2">
												&nbsp;
											</div>
										</div>
									';
								}
							?>
							<div class="row">
								<div class="col-xs-4 text-right">
										Total Capturado: &nbsp; $
								</div>
								<div class="col-xs-5">
									<input type="text" name="total" class="total form-control" id="total" value="0.00" readOnly>
								</div>
								<div class="col-xs-2">
									&nbsp;
								</div>
							</div>

							<div class="row">
								<div class="col-xs-6 text-center">
									<button type="button" id="atras" class="atras actionButton"> <i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp; Regresar</button>
								</div>
								<div class="col-xs-6 text-center">
									<button type="button" id="guardar" class="guardar actionButton"> <i class="fa fa-floppy-o" aria-hidden="true"></i> &nbsp; Guardar</button>
								</div>
							</div>

						</div>
					</div>
				</div>
		<script type="text/javascript">
			$( document ).ready(function() {
				//------------------------------------------------------------
	      //				            	VARIABLES GLOBALES
	      //------------------------------------------------------------
				var gCap = new Array();
				var gTotalAutorizado = 0;
				var fechaMayor = 0;

				//------------------------------------------------------------
	      //				            	ACCIONES DEL DOM
	      //------------------------------------------------------------
				$(".aviso").hide();
	      $(".fondoTransparencia").hide();
				$(".control").hide();
				$( "#iniVig, #finVig, #fechaElaboracion" ).datepicker();
				getUltimoCheque();
				getTodayDate();
				getListaProyectos();
				testViaticos();
				for(var x=1; x<=5; x++){
					$("#cap"+x+"000, #total").mask('000,000,000,000,000.00', {reverse: true});
				}


				//------------------------------------------------------------
	      //				            	ACCIONES DEL DOM
	      //------------------------------------------------------------
				$("#noCheque").change(function(){
					testNoCheque();
				});

				$("#nombre, #concepto").change(function(){
					$(this).val(($(this).val()).toUpperCase());
				});

				$("#proyecto").change(function (){
					getMontosProyecto();
				});

				$("#viaticos").change(function(){
					testViaticos();
				});

				$("#iniVig, #finVig").change(function(){
					validaFechas();
				});

				$("#cap1000, #cap2000, #cap3000, #cap4000, #cap5000").keyup(function(){
					if( $(this).val() == "" )
						$(this).val('0.00')
					calculaTotal();
				});

				$("#atras").click(function(){
					history.back();
				});

				$("#guardar").click(function(){
					validaDatos();
				});

				$("#imprimir").click(function(){
					var location = "generaPoliza.php?a="+$("#noCheque").val()+"&b="+$("#noCheque").val();
					window.open(location);
					var location = "generaCheque.php?a="+$("#noCheque").val()+"&b="+$("#noCheque").val();
					window.open(location);
				});

				$("#nuevo").click(function(){
					window.location.href = 'altaEgresos.php';
				});



				//------------------------------------------------------------
	      //          Funciones AJAX
	      //------------------------------------------------------------
				//-----Obitiene el ultimo cheque capturado-----
				//---------------------------------------------
				function getUltimoCheque()
				{
					$.ajax({
	            data: { type:'getUltimoCheque'},
	            type: "POST",
	            url: "altaEgresosSube.php",
	        })
					.done(function(data){
						$(".ultimoCheque").html(data);
						$("#noCheque").val( (parseInt(data))+1 );
					})
					.fail(function(){
            muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
          });
				}

				//prueba si el cheque capturado no es duplicado
				//---------------------------------------------
				function testNoCheque()
				{
					$.ajax({
	            data: { type:'testNoCheque', noCheque:$("#noCheque").val()},
	            type: "POST",
	            url: "altaEgresosSube.php",
	        })
					.done(function(data){
						if(data == '1'){
							muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; El no de cheque capturado ya existe');
						}
					})
					.fail(function(){
            muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
          });
				}

				//--------obtiene la lista de proyectos--------
				//---------------------------------------------
				function getListaProyectos()
				{
					$.ajax({
	            data: { type:'getListaProyectos'},
	            type: "POST",
	            url: "altaEgresosSube.php",
	        })
					.done(function(data){
						data = $.parseJSON(data);
						for(var x=0; x<data.length; x++)
						{
							$("#proyecto").append('<option value="'+data[x]['nombreProyecto']+'">'+data[x]['numeroProyecto']+' - '+data[x]['nombreProyecto']+'</option>');
						}
					})
					.fail(function(){
            muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
          });
				}

				//--------obtiene los montos del proyecto------
				//---------------------------------------------
				function getMontosProyecto()
				{
					$.ajax({
	            data: { type:'getMontosProyecto', nombreProyecto:$("#proyecto").val()},
	            type: "POST",
	            url: "altaEgresosSube.php",
	        })
					.done(function(data){
						console.log(data);
						data = $.parseJSON(data);
						for(var x=1; x<=5; x++)
						{
							var thisVal = (parseFloat(data[x]).toFixed(2))
							gCap[x] = parseFloat(data[x]);
							$("#dis"+x).html(' $ '+ addCommas(thisVal) );
						}
						gTotalAutorizado = parseFloat(data['total']);
						data['total'] = (data['total']).toFixed(2);
						$("#disT").html(' $ '+ addCommas( data['total'] ) );

					})
					.fail(function(){
            muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
          });
				}

				function guardaDatos( datos )
				{
					$.ajax({
	            data: { type:'guardaDatos', noCheque:datos['noCheque'], folio:datos['folio'],
						 					fechaElaboracion:datos['fechaElaboracion'], nombre:datos['nombre'],
										  concepto:datos['concepto'], proyecto:datos['proyecto'], viaticos:datos['viaticos'],
										  iniVig:datos['iniVig'], finVig:datos['finVig'], cap1000:datos['cap1000'],
										  cap2000:datos['cap2000'], cap3000:datos['cap3000'], cap4000:datos['cap4000'],
										  cap5000:datos['cap5000'], total:datos['total'], observaciones:datos['observaciones'] },
	            type: "POST",
	            url: "altaEgresosSube.php"
	        })
					.done(function(data){
						//console.log(data)
						if(data == "ok"){
							muestraAviso('success', '<i class="fa fa-check fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; El cheque se registro exitosamente');
							$( "#guardar" ).prop( "disabled", true );
								$(".fondoTransparencia").show();
								$(".control").show();
						} else {
							muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
						}
					})
					.fail(function(){
            muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
          });
				}

				//------------------------------------------------------------
	      //          Funciones Generales
	      //------------------------------------------------------------
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
				//---
				function testViaticos()
				{
					if($("#viaticos").is(":checked")) {
            readonly('#iniVig, date', false);
						readonly('#finVig, date', false);
        	} else {
						readonly('#iniVig, date', true);
						readonly('#finVig, date', true);
					}
				}
				//---
				function validaFechas()
				{
					var date1 = $("#iniVig").val();
					var date2 = $("#finVig").val();
					if(date1 != "" && date2 != "") {
						if(date1 > date2) {
							muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; La segunda fecha debe ser posterior a la primera');
							fechaMayor = 1;
						} else { fechaMayor = 0; }
					}
				}
				//---
				function calculaTotal()
				{
					var cuentaTotal = 0;
					for(var x=1; x<=5; x++){
						cuentaTotal += parseFloat( $("#cap"+x+"000").val().replace(',', '') );
					}
						cuentaTotal = cuentaTotal.toFixed(2);
						$("#total").val(cuentaTotal);
						if(cuentaTotal > parseInt(gTotalAutorizado)){
							muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; El monto total sobrepasa lo autorizado para el proyecto');
						}
				}
				//---
				function getTodayDate()
				{
					var d = new Date();
					var month = d.getMonth()+1;
					var day = d.getDate();

					var output = d.getFullYear() + '-' +
					    ((''+month).length<2 ? '0' : '') + month + '-' +
					    ((''+day).length<2 ? '0' : '') + day;
					$("#fechaElaboracion").val(output);
				}
				//---
				function validaDatos()
				{
					var datos = [];
					//reune los datos capturados
					if( $("#noCheque").val() != "") {
						datos['noCheque'] = $("#noCheque").val();
						//-
						if( $("#folio").val() != "") {
							datos['folio'] = $("#folio").val();
							//-
							if( $("#fechaElaboracion").val() != "") {
								datos['fechaElaboracion'] = $("#fechaElaboracion").val();
								//-
								if( $("#nombre").val() != "") {
									datos['nombre'] = $("#nombre").val();
									//-
									if( $("#concepto").val() != "") {
										datos['concepto'] = $("#concepto").val();
										//-
										if( $("#proyecto").val() != "") {
											datos['proyecto'] = $("#proyecto").val();
											//-
											//--los demas datos no son obligatorios
											if($("#viaticos").is(":checked")) {
												datos['viaticos'] = 1;
												datos['iniVig'] = $("#iniVig").val();
												datos['finVig'] = $("#finVig").val();
											} else {
												datos['viaticos'] = 0;
												datos['iniVig'] = "";
												datos['finVig'] = "";
											}
											for(var x=1; x<=5; x++){
												( $("#cap"+x+'000').val() != "") ? datos['cap'+x+'000'] = $("#cap"+x+'000').val() : datos['cap'+x+'000'] = 0;
											}
											( $("#total").val() != "") ? datos['total'] = $("#total").val() : datos['total'] = 0;
											datos['observaciones'] = $("#observaciones").val();
											//-----------
											//hacemos test a los últimos candados
											//revisamos coherencia en las fechas
											if($("#viaticos").is(":checked") && fechaMayor == 1 ) {
												muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; La fecha final de vigencia debe ser posterior a la inicial');
											} else {
												//revisamos que el monto total no sea mayor al presupuestado
												if( (parseFloat( $("#total").val().replace(',', '') )) > parseFloat(gTotalAutorizado) ){
													if( confirm( 'El monto total sobrepasa lo autorizado para el proyecto, ¿guardar de todos modos? ') ){
														guardaDatos( datos );
													}
													muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; El monto total sobrepasa lo autorizado para el proyecto');
												} else {
													//console.log(datos);
													guardaDatos( datos );
												}
											}
										} else {
											muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Falta seleccionar proyecto');
											$("#proyecto").focus();
										}
									} else {
										muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Falta concepto');
										$("#concepto").focus();
									}
								} else {
									muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Falta nombre');
									$("#nombre").focus();
								}
							} else {
								muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Falta fecha de elaboración');
								$("#fechaElaboracion").focus();
							}
						} else {
							muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Falta el número de folio');
							$("#folio").focus();
						}
					} else {
						muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Falta el número de cheque');
						$("#noCheque").focus();
					}
				}

				//-------Configura datepicker a español--------
				//---------------------------------------------
				$.datepicker.regional['es'] = {
					closeText: 'Cerrar',
					prevText: '< Ant',
					nextText: 'Sig >',
					currentText: 'Hoy',
					monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
					monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
					dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
					dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
					dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
					weekHeader: 'Sm',
					dateFormat: 'yy-mm-dd',
					firstDay: 1,
					isRTL: false,
					showMonthAfterYear: false,
					yearSuffix: ''
					};
					$.datepicker.setDefaults($.datepicker.regional['es']);
					$(function () {
					$("#fecha").datepicker();
					});
			}); //--Termina JQuery

			//------------------------------------------------------------
			//          Funciones Vanilla Javascript
			//------------------------------------------------------------
			//------permite solo numeros en inputs---------
			//---------------------------------------------
			function justNumbers(e)
			{
				var keynum = window.event ? window.event.keyCode : e.which;
				if ((keynum == 8) || (keynum == 46))
				return true;

				return /\d/.test(String.fromCharCode(keynum));
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
	</body>
</html>
