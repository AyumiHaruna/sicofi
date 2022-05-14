<!DOCTYPE html>
<?php
	require_once('menu.php');
	session_start();
	header('Content-Type: text/html; charset=UTF-8');
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>Validación de Egresos</title>

		<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
		<link rel="stylesheet" href="../css3/bootstrap.min.css">
		<link rel="stylesheet" href="../css3/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" href="../css3/valEgresos2.css">
	</head>
	<body>
		<?php
			//-------------------------	INICIA MENÚ	--------------------------
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

<!--  /////////////////////////// DIV CONTROL ////////////////////////////////// -->
		<div class="col-md-6 col-md-offset-3 control text-center">
			La factura escaneada ya pertenece al cheque <span class="spanRed avisoCheque"></span>,<br>
			para duplicarla en este cheque se necesita la autorizacion de un administrador:<br><br>
			<div class="col-xs-4">
				Nombre:
			</div>
			<div class="col-xs-8">
				<input type="text" name="admNombre" id="admNombre" class="form-control">
			</div>
			<div class="col-xs-4">
				Contraseña:
			</div>
			<div class="col-xs-8">
				<input type="password" name="admPass" id="admPass" value="" class="form-control">
			</div>
			<div class="col-md-10 col-md-offset-1 text-left">
				Observaciones:<br>
				<textarea name="admObs" id="admObs" rows="5" cols="80" class="form-control" placeholder="Observaciones de la factura"></textarea>
			</div>
			<br><br>
			<div class="col-xs-6 text-center">
				<button type="button" id="admCancelar" class="btn btn-lg submit_button">Cancelar</button>
			</div>
			<div class="col-xs-6 text-center">
				<button type="button" value="admGuardar" id="admGuardar" class="btn btn-lg submit_button"> <i class="fa fa-floppy-o" aria-hidden="true"></i> &nbsp; Guardar</button>
			</div>
		</div>

<!--  /////////////////////////// DIV CONTROL2 ////////////////////////////////// -->
		<div class="col-md-6 col-md-offset-3 control2 text-center">
			Realmente deseas eliminar esta factura? <br><br>
			<div class="col-xs-4">
				Nombre:
			</div>
			<div class="col-xs-8">
				<input type="text" name="admNombre2" id="admNombre2" class="form-control">
			</div>
			<div class="col-xs-4">
				Contraseña:
			</div>
			<div class="col-xs-8">
				<input type="password" name="admPass2" id="admPass2" value="" class="form-control">
			</div>
			<br><br>
			<div class="col-xs-6 text-center">
				<button type="button" id="tryCancel" class="btn btn-lg submit_button">Cancelar</button>
			</div>
			<div class="col-xs-6 text-center">
				<button type="button" id="tryDelete" class="btn btn-lg submit_button"> <i class="fa fa-trash-o" aria-hidden="true"></i> &nbsp; Eliminar</button>
			</div>
		</div>

<!--  /////////////////////////// DIV CONTROL3 ////////////////////////////////// -->
		<div class="col-md-6 col-md-offset-3 control3 text-center">
			<div class="col-xs-4">
				Folio de la factura:
			</div>
			<div class="col-xs-8">
				<input type="text" name="scFolio" id="scFolio" class="form-control">
			</div>
			<div class="col-xs-4">
				Monto: $
			</div>
			<div class="col-xs-8">
				<input type="number" name="scMonto" id="scMonto" value="" class="form-control">
			</div>
			<br><br>
			<div class="col-xs-6 text-center">
				<button type="button" id="scCancelar" class="btn btn-lg submit_button">Cancelar</button>
			</div>
			<div class="col-xs-6 text-center">
				<button type="button" value="scGuardar" id="scGuardar" class="btn btn-lg submit_button"> <i class="fa fa-floppy-o" aria-hidden="true"></i> &nbsp; Guardar</button>
			</div>
		</div>

<!--  /////////////////////////// MAIN DIV ////////////////////////////////// -->
		<div class="container">
				<div class="col-md-12 bloque">
					<form method="post" action="GBvalidaEgresosSube.php" name="form1">
		        <div class="col-md-4 subBloque">
		          <legend>DATOS GENERALES</legend>
							<br>
							<div class="col-xs-6 text-right">
								No. de Cheque:
							</div>
							<div class="col-xs-6">
								<input type="text" name="noCheque" id="noCheque" class="form-control" readonly>
							</div>
							<div class="col-xs-6 text-right">
								Fecha de Elaboración:
							</div>
							<div class="col-xs-6">
								<input type="date" name="fechaElaboracion" id="fechaElaboracion" class="form-control" readonly>
							</div>
							<div class="col-xs-12">
								Nombre:
							</div>
							<div class="col-xs-12">
								<input type="text" name="nombre" id="nombre" class="form-control" readonly>
							</div>
							<div class="col-xs-12">
								Concepto:
							</div>
							<div class="col-xs-12">
								<textarea name="concepto" id="concepto" class="form-control" readonly></textarea>
							</div>
							<div class="col-xs-12">
								Observaciones:
							</div>
							<div class="col-xs-12">
								<textarea name="observaciones" id="observaciones" class="form-control" readonly></textarea>
							</div>
						</div>	<!-- termina bloque de DATOS GENERALES -->
						<div class="col-md-7 subBloque">
		          <legend>DATOS DE VALIDACIONES</legend>
							<br>
							<div class="row">
								<?php
									echo '<div class="col-xs-6"> Nombre de la Comprobación </div>
												<div class="col-xs-6"> Monto de la Comprobación $ </div>';
									for($x=1; $x<=3; $x++)
									{
										echo '<div class="col-xs-6"> <input type="text" name="nomComp'.$x.'" id="nomComp'.$x.'" class="form-control nomComp input"> </div>
										<div class="col-xs-6"> <input type="text" name="monComp'.$x.'" id="monComp'.$x.'" class="form-control monComp input" onkeypress="return justNumbers(event);"> </div>';
									}
								?>
							</div>
							<div class="row montosTotales">
								<div class="col-xs-4"> Total del Cheque </div>
								<div class="col-xs-4"> Comprobado </div>
								<div class="col-xs-4"> Resta Comprobar </div>
								<div class="col-xs-4"> <input type="text" name="importeTotal" id="importeTotal" class="form-control" readOnly> </div>
								<div class="col-xs-4"> <input type="text" name="comprobado" id="comprobado" class="form-control" readOnly> </div>
								<div class="col-xs-4"> <input type="text" name="restaComprobar" id="restaComprobar" class="form-control" readOnly> </div>
							</div>
							<div class="row">
								<div class="col-xs-4">
									Porcentaje Comprobado: <br>
									<span class="porcentaje"> % 0.00</span>
								</div>
								<div class="col-xs-4 text-center">
									<button type="button" id="btnRegresar" class="btn btn-lg submit_button"> <i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp; Regresar</button>
								</div>
								<div class="col-xs-4 text-center">
									<button type="button" value="Guardar" id="btnSubmit" class="btn btn-lg submit_button"> <i class="fa fa-floppy-o" aria-hidden="true"></i> &nbsp; Guardar</button>
								</div>
							</div>
						</div>
					</form>
					<br>

					<div class="col-md-12 subBloque2">
						<legend>FACTURAS DE LA COMPROBACIÓN</legend>
						<div class="row bloqueFacturas">
							<div class="col-xs-9">
								<input type="text" name="qrCode" id="qrCode" class="form-control" placeholder="Código de la Factura">
							</div>
							<div class="col-xs-3 text-right">
								<button type="button" id="qrAddBtn" class="btn btn-lg add_button"> Agregar factura &nbsp;<i class="fa fa-lg fa-plus-circle" aria-hidden="true"></i> </button>
							</div>
						</div>
						<br>
						<div class="col-md-12">
							<table class="table listaFacturas">
								<thead>
									<tr>
										<th>#</th>
										<th>Codigo de la Factura</th>
										<th>Monto total de la Factura</th>
										<th>Eliminar</th>
									</tr>
								</thead>
							</table>
						</div>
						<div class="col-md-12 text-center">
							<button type="button" id="qrAddBtn2" class="btn btn-lg add_button"> Facturas sin Código &nbsp;<i class="fa fa-lg fa-plus-circle" aria-hidden="true"></i> </button>
						</div>
					</div>
				</div>
		</div>


		<script type="text/javascript">
			$( document ).ready(function() {
				//------------------------------------------------------------
	      //                Condiciones Iniciales
	      //------------------------------------------------------------
				var duplicado = 0;
				var noDuplicado = 0;
				var thisNoCheque = <?php echo $_REQUEST['codigo'];  ?>;
				var autoriza='';
				var observaciones='';
				var toDel = 0;
				$(".aviso, .fondoTransparencia, .control, .control2, .control3").hide();
				pintaGenerales( thisNoCheque );
				pintaTabla();

				//------------------------------------------------------------
	      //              					Acciones
	      //------------------------------------------------------------
				//-------captura de datos en algun monto-------
				//---------------------------------------------
				$(".input").change(function(){
					calculaMontos();
					$("#btnSubmit").addClass("focusSubmit");
				});

				//--------guarda los datos capturados----------
				//---------------------------------------------
				$("#btnSubmit").click(function(){
					guardaCambios();
					$("#btnSubmit").removeClass("focusSubmit");
				});

				//--------regresa a la pagina anterior---------
				//---------------------------------------------
	      $("#btnRegresar").click(function(){
	        history.back();
	      });

				//compruba si el codigo escaneado no es duplicado
				//---------------------------------------------
				$("#qrCode").change(function(){
					pruebaQr();
				});

				//--------------Agrega QR a la DB--------------
				//---------------------------------------------
				$("#qrAddBtn").click(function(){
					pruebaQr();
					setTimeout(function(){
						if($("#qrCode").val() != '') {
							if(duplicado == 0)	{
								autoriza=''; observaciones='';
								guardaCodigo();
								$("#qrCode").val('');
							} else if(duplicado == 1){
								$(".fondoTransparencia").show();
								$(".control").show();
								$(".avisoCheque").html(noDuplicado);
							}
						} else {
							muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No hay ningun código que capturar');
						}
					}, 500);
				})

				//--cierra la ventana de validacion del admin--
				//---------------------------------------------
				$("#admCancelar, #tryCancel, #scCancelar, .fondoTransparencia").click(function(){
					$(".fondoTransparencia, .control, .control2, .control3").hide();
					$('#admNombre').val('');
					$('#admPass').val('');
					$('#admObs').val('');
					$('#scFolio').val('');
					$('#scMonto').val('');
				});

				//---duplica la factura y la guarda en la db---
				//---------------------------------------------
				$("#admGuardar").click(function(){
					$.ajax({
							data: { type:'testAdm', usuario:$('#admNombre').val(), password:$('#admPass').val()},
							type: "POST",
							url: "GBvalidaEgresosSube.php",
					})
					.done(function(data){
						if(data != 'ok'){
							if( data == '3'){
								muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; El usuario capturado no existe');
							}
							if( data == '2'){
								muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; La contraseña del usuario es incorrecta');
							}
							if( data == '1'){
								muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; El usuario no tiene permisos para realizar esta operación');
							}
						} else {	//duplica el código
							autoriza = $('#admNombre').val();
							observaciones = $("#admObs").val();
							guardaCodigo();
							setTimeout(function(){
								$(".fondoTransparencia").hide();
								$(".control").hide();
								$('#admNombre').val('');
								$('#admPass').val('');
								$('#admObs').val('');
								$('#qrCode').val('');
								$("#qrCode").val('');
							}, 500);
						}
					})
					.fail(function(){
						muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
					});
				});

				//---duplica la factura y la guarda en la db---
				//---------------------------------------------
				$("#scGuardar").click(function(){
					if( $("#scFolio").val() == '' || $("#scMonto").val() == '' ){
						muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Falta capturar datos');
					} else {
						$.ajax({
								data: { type:'postQr', noCheque:thisNoCheque, cadena:$("#scFolio").val(), tipo:'GB', monto:$("#scMonto").val(), duplicado:0, autoriza:'', observaciones:''},
								type: "POST",
								url: "GBvalidaEgresosSube.php",
						})
						.done(function(data){
							if(data == 'ok') {
								 muestraAviso('success', '<i class="fa fa-check-circle-o fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Datos guardados exitosamente');
								 $("#scFolio, #scMonto").val('');
								 $(".control3, .fondoTransparencia").hide();
								 pintaTabla();
							} else {
								 muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Ocurió un error al guardar los datos, favor de reintentarlo');
							}
						})
						.fail(function(){
							muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
						});
					}
				});

				//--------pregunta si eliminar factura---------
				//---------------------------------------------
				$(".listaFacturas").on('click', '.delFac', function(){
					$(".control2").show();
					$(".fondoTransparencia").show();
					toDel = $(this).attr('id');
				});

				//-------Elimina la factura seleccionada-------
				//---------------------------------------------
				$("#tryDelete").click(function(){
					$.ajax({
							data: { type:'testAdm', usuario:$('#admNombre2').val(), password:$('#admPass2').val()},
							type: "POST",
							url: "GBvalidaEgresosSube.php",
					})
					.done(function(data){
						if(data != 'ok'){
							if( data == '3'){
								muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; El usuario capturado no existe');
							}
							if( data == '2'){
								muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; La contraseña del usuario es incorrecta');
							}
							if( data == '1'){
								muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; El usuario no tiene permisos para realizar esta operación');
							}
						} else {	//duplica el código
							//--------------------
								$.ajax({
										data: { type:'delQr', no:toDel},
										type: "POST",
										url: "GBvalidaEgresosSube.php",
								})
								.done(function(data){
									if(data == 'ok'){
										$(".control2").hide();
										$(".fondoTransparencia").hide();
										toDel = 0;
										pintaTabla();
										muestraAviso('success', '<i class="fa fa-check-circle-o fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; La factura se eliminó exitosamente');
									} else {
										muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Ocurrió un error al eliminar la facutra, favor de reintentarlo');
									}
								})
								.fail(function(){
									muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
								});
							//--------------------
							setTimeout(function(){
								$(".fondoTransparencia").hide();
								$(".control").hide();
								$('#admNombre').val('');
								$('#admPass').val('');
								$('#admObs').val('');
								$('#qrCode').val('');
								$("#qrCode").val('');
							}, 500);
						}
					})
					.fail(function(){
						muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
					});
				});

				//Abre formulario para agregar facturas sin codigo qr
				//---------------------------------------------------
				$("#qrAddBtn2").click(function(){
					$(".fondoTransparencia, .control3").show();
				});


				//------------------------------------------------------------
	      //          Funciones Generales
	      //------------------------------------------------------------
				//------Guarda el codigo QR del formulario-----
				//---------------------------------------------
				function guardaCodigo()
				{
					var thisArr = $("#qrCode").val();
					var cadena = thisArr;
					thisArr = thisArr.split('/tt¿');
					if( thisArr[1] != null)	{
						thisArr = thisArr[1].split('/id¿');
						var monto = parseFloat(thisArr[0]);
						if( monto != NaN ){
							//guardamos en la db
							$.ajax({
			            data: { type:'postQr', noCheque:thisNoCheque, cadena, tipo:'GB', monto, duplicado, autoriza, observaciones},
			            type: "POST",
			            url: "GBvalidaEgresosSube.php",
			        })
							.done(function(data){
								if(data == 'ok') {
									 muestraAviso('success', '<i class="fa fa-check-circle-o fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Datos guardados exitosamente');
									 $("qrCode").val();
									 pintaTabla();
								} else {
									 muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Ocurió un error al guardar los datos, favor de reintentarlo');
								}
							})
							.fail(function(){
		            muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
		          });
						} else {
								muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Código inválido ');
						}
					} else {
						muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Código inválido ');
					}
				}
	//--- //------Guarda los cambios del formulario------
				//---------------------------------------------
				function guardaCambios()
				{
					$.ajax({
	            data: { type:'postCambios', noCheque:thisNoCheque,
											nomComp1 : $("#nomComp1").val(),
											nomComp2 : $("#nomComp2").val(),
											nomComp3 : $("#nomComp3").val(),
											monComp1 : $("#monComp1").val(),
											monComp2 : $("#monComp2").val(),
											monComp3 : $("#monComp3").val(),
											comprobado : $("#comprobado").val(), restaComprobar : $("#restaComprobar").val()},
	            type: "POST",
	            url: "GBvalidaEgresosSube.php",
	        })
					.done(function(data){
						console.log(data);
						if( data == 'ok')	{
							muestraAviso('success', '<i class="fa fa-check-circle-o fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Cambios guardados exitosamente');
						} else {
							muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
						}
					})
					.fail(function(){
            muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
          });
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
				//--------obtiene los datos del cheque---------
				//---------------------------------------------
				function pintaGenerales(noCheque)
				{
	        $.ajax({
	            data: { type:'getGenerales', noCheque },
	            type: "POST",
	            url: "GBvalidaEgresosSube.php",
	        })
	        .done(function(data){
            data = $.parseJSON(data);
						//rellena datos generales con los datos obtenidos
						$("#noCheque").val( data['noCheque'] ); $("#fechaElaboracion").val( data['fechaElaboracion'] );
						$("#folio").val( data['folio'] ); $("#nombre").val( data['nombre'] );
						$("#concepto").val( data['concepto'] ); $("#observaciones").val( data['observaciones'] );
						for(var x=1; x<=3; x++)
						{
							$("#nomComp"+x).val( data['noComprobacion'+x] );
							$("#monComp"+x).val( data['comprobacion'+x] );
						}
						$("#importeTotal").val( data['total'] );
						$("#comprobado").val( data['comprobado'] );
						$("#restaComprobar").val( data['restaComprobar'] );
						calculaMontos();
	        })
	        .fail(function(){
	          muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
	        });
				}
				//-----------realiza cálculos------------------
				//---------------------------------------------
				function calculaMontos()
				{
					var comprobado = 0;
					for(var x=1; x<=3; x++)
					{
						if( $("#monComp"+x).val() != "") {
							comprobado += parseFloat($("#monComp"+x).val());
						}
					}
					if( comprobado > parseFloat($("#importeTotal").val()) ) {
						muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; Tus Comprobaciones son mayores al Improte Total');
					}

					$("#comprobado").val(comprobado);
					$("#restaComprobar").val( (parseFloat($("#importeTotal").val()) - comprobado).toFixed(2) );
					var porcentaje = (comprobado*100)/parseFloat($("#importeTotal").val())
					$(".porcentaje").html('% '+porcentaje.toFixed(2));
				}

				function pruebaQr()
				{
					$.ajax({
	            data: { type:'testQr', codigo:$("#qrCode").val() },
	            type: "POST",
	            url: "GBvalidaEgresosSube.php",
	        })
					.done(function(data){
						console.log(data);
						if(data == '[]'){
							duplicado = 0;
						} else {
							duplicado = 1;
							data = $.parseJSON(data);
							if(data['tipo'] == 'PR'){
								var tip = 'proyectos';
							} else if(data['tipo'] == 'GB') {
								var tip = 'Gasto Básico';
							}
							muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; El código ya ha sido usado para el cheque : '+data['noCheque']+' de '+tip);
							noDuplicado = data['noCheque'];
						}
					})
					.fail(function(){
						duplicado = 1;
            muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
          });
				}
				//-------pinta la tabla de las facturas--------
				//---------------------------------------------
				function pintaTabla()
				{
					$.ajax({
							data: { type:'getFacturas', noCheque:thisNoCheque},
							type: "POST",
							url: "GBvalidaEgresosSube.php",
					})
					.done(function(data){
						data = $.parseJSON(data);
						$(".listaFacturas").html('');
						for(var x=0; x<Object.keys(data).length; x++)
						{
							$(".listaFacturas").append('<tr><td>'+(x+1)+'</td><td>'+data[x]['cadena']+'</td><td>$ '+addCommas(data[x]['monto'])+'</td><td><i class="fa fa-trash-o fa-2x delFac" aria-hidden="true" id="'+data[x]['no']+'"></i></td></tr>');
						}
					})
					.fail(function(){
						muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
					});
				}
			});	//Termina JQUERY

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
