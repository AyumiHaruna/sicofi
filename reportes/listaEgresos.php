<!DOCTYPE html>
<?php
	require_once('menu.php');
	require_once('../config.php');
	header('Content-Type: text/html; charset=UTF-8');
	session_start();

	$miUsuario = $_SESSION['id'];

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("Problemas con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");

	//--Obtenemos la lista de proyectos permitidos para el usuario
	$busqueda = mysqli_query($con, "SELECT * FROM usuarios WHERE id = '$miUsuario'")
		or die(mysqli_error($con));
	if($_SESSION['anio'] >= 2017){ $canProy = 45; } else { $canProy = 10; }
	while($reg = mysqli_fetch_array($busqueda))
	{
		for($x=1; $x<=$canProy; $x++){
			$userProy[] = $reg['proy'.$x];
		}
	}

	//--eliminamos bloques vacios
	$newUserProy = [];
 	for($x=0; $x<count($userProy); $x++){
		if($userProy[$x] != ""){
			$newUserProy[] = $userProy[$x];
		}
	}
	$userProy = $newUserProy;

	//-- buscamos los datos de esos proyectos
		if($userProy[0] == 999999) {
			$busqueda = mysqli_query($con, "SELECT numeroProyecto, nombreProyecto, nombreResponsable FROM proyectos ORDER BY numeroProyecto")
				or die(mysqli_error($con));
		}	else {
			$textoBusqueda = 'SELECT numeroProyecto, nombreProyecto, nombreResponsable FROM proyectos WHERE ';
			for($x=0; $x<count($userProy); $x++){
				if($x < (count($userProy)-1) ){
					$textoBusqueda .= 'numeroProyecto = '.$userProy[$x].' OR ';
				} else {
					$textoBusqueda .= 'numeroProyecto = '.$userProy[$x].' ORDER BY numeroProyecto';
				}
			}
			$busqueda = mysqli_query($con, $textoBusqueda) or die(mysqli_error($con));
		}
		while($reg = mysqli_fetch_array($busqueda))
		{
			$datosProyectos[] = $reg;
		}

		//-- búsqueda de cheques
		$textoBusqueda = "SELECT egr.noCheque, egr.fechaElaboracion, egr.nombre,
															egr.concepto, egr.nombreProyecto, pro.numeroProyecto,
															egr.observaciones, egr.importeTotal, egr.comprobado,
															egr.restaComprobar,";
    $textoBusqueda .= (($_SESSION['anio'] >= 2017)? " egr.status," : "");
    $textoBusqueda .= " pro.numeroProyecto, pro.nombreProyecto, pro.nombreResponsable FROM egresos AS egr JOIN proyectos AS pro ON	egr.nombreProyecto = pro.nombreProyecto";
		if($userProy[0] != 999999){

			$textoBusqueda .= " WHERE";
			for($x=0; $x<(count($datosProyectos)); $x++){
				if($x<(count($datosProyectos) - 1)){
					$textoBusqueda .= " pro.numeroProyecto = " .$datosProyectos[$x]['numeroProyecto']. " OR";
				} else if( (count($datosProyectos)) ) {
					$textoBusqueda .= " pro.numeroProyecto = " .$datosProyectos[$x]['numeroProyecto'];
				}
			}

		}
		$textoBusqueda .= " ORDER BY egr.noCheque DESC";
		$busqueda = mysqli_query($con, $textoBusqueda) or die (mysqli_error($con));
		$egresos = [];
		while($reg = mysqli_fetch_array($busqueda)){
			$egresos[] = $reg;
		}
		//-----------------------TERMINAN CONSULTAS Y OPERACIONES PHP -----------
?>


<html>
	<head>
		<meta charset="utf-8">
		<title>Reporte de Egresos Desglosados</title>

		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/reportes.css"></link>
		<script src="../js/jquery-3.1.1.js" type="text/javascript"></script>
		<script type="text/javascript" src="../js/script.js"></script>		<!-- Efectos del Menú -->
	</head>
	<body>
		<!-- IIIIIIIIIIIIIIIIIIIIIIIIIIII   Este cuadro contiene el Menú	IIIIIIIIIIIIIIIIIIIII -->
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


		<fieldset id="base">
			<fieldset id="formulario">
				<legend>Búsqueda de Egresos</legend>
				<form name="form1">
					<table class="tablaForm">
						<tr>
							<td>Buscar por Proyecto: </td>
							<td><select name="selProy" id="selProy"></select></td>
						</tr>
						<tr>
							<td>Buscar por Encargado:</td>
							<td><select name="selEncar" id="selEncar"></select></td>
						</tr>
					</table>
				</form>
			</fieldset>
		</fieldset>

		<fieldset id="base">
			<fieldset id="bloqueTabla">

			</fieldset>
		</fieldset>
	</body>

	<script type="text/javascript">
		$(document).ready(function(){
			//--		 	VARIABLES GLOBALES
			//--------------------------------
			var datosPro = <?php print_r( json_encode($datosProyectos) ); ?>;
			var datosEgr = <?php print_r( json_encode($egresos) ); ?>;

			//--			CONDICIONES INICIALES
			//--------------------------------
			cargaListaProyectos();
			cargaListaEncargados();
			pintaEgresos("", "");

			//--			FUNCIONES DEL DOM
			//--------------------------------
			$("#selProy").change(function(){
				$("#selEncar").val("");
				pintaEgresos($("#selProy").val(), "");
			});

			$("#selEncar").change(function(){
				$("#selProy").val("");
				pintaEgresos("", $("#selEncar").val());
			});

			//--			FUNCIONES GENERALES
			//--------------------------------
			//-- rellena el select de proyectos
			function cargaListaProyectos(){
				var thisLista = '<option value=""> -- Seleccione un proyecto</option>';
					for(var x=0; x<(datosPro).length; x++){
						thisLista += '<option value="'+datosPro[x]['numeroProyecto']+'">'+datosPro[x]['numeroProyecto']+' - '+datosPro[x]['nombreProyecto']+'</td>';
					}
				$("#selProy").html(thisLista);
			}

			//-- rellena el select de encargados
			function cargaListaEncargados(){
				var thisLista = '<option value=""> -- Seleccione a un encargado</option>';
				var listaEncargados =[];
				for(var x=0; x<(datosPro).length; x++){		//obtenemos la lista de nombres
					if( ($.inArray( $.trim(datosPro[x]['nombreResponsable']) , listaEncargados) == -1) ){
						listaEncargados.push( $.trim(datosPro[x]['nombreResponsable']) );
					}
				}
				listaEncargados.sort()
				for(var x=0; x<(listaEncargados).length; x++){
					thisLista += '<option value="'+listaEncargados[x]+'">'+listaEncargados[x]+'</option>';
				}
				$("#selEncar").html(thisLista);
			}

			//-- pinta la lista de ingresos con sus datos
			function pintaEgresos(pro, enc){
				var sumaImporteTotal = 0;	var sumaComprobado = 0;	var sumaRestaComprobar = 0;

				var thisLista = ''+
				'<legend>Lista de Egresos desglosados</legend>'+
				'<table class="tablaReport" border="1">'+
					'<tr>'+
						'<td>No. de Cheque</td>'+ '<td>Fecha de Elaboración</td>'+ '<td>Nombre</td>'+
						'<td>Concepto</td>'+'<td># Proy.</td>'+'<td>Nombre Proyecto</td>'+ '<td>Observaciones</td>'+
						'<td>Importe Total</td>'+ '<td>Comprobado</td>'+ '<td>Resta Comprobar</td>'+
						'<td>Status</td>'+
					'<tr>';

				for(var x=0; x<(datosEgr).length; x++){

					switch (true) {
						case (pro == "" && enc == ""):			//sin filtros
							thisLista += '<tr>';
							break;
						case (pro != "" && enc == ""):			//filtro por proyecto
								thisLista += '<tr '+ ((pro == datosEgr[x]['numeroProyecto'])? '' : 'style="display:none;"' ) +'>';
							break;
						case (pro == "" && enc != ""):			//filtro por encargado
								thisLista += '<tr '+ ((enc == $.trim(datosEgr[x]['nombreResponsable']))? '' : 'style="display:none;"' ) +'>';
							break;
					}	//-- fin del switch

					thisLista += ''+
						'<td>'+datosEgr[x]['noCheque']+'</td>'+
						'<td>'+datosEgr[x]['fechaElaboracion']+'</td>'+
						'<td>'+datosEgr[x]['nombre']+'</td>'+
						//'<td '+ (( datosEgr[x]['concepto'].length > 100 ) ? 'data-toggle="tooltip" title="'+datosEgr[x]['concepto']+'">'+(datosEgr[x]['concepto']).substring(0,100)+'...' : '>'+datosEgr[x]['concepto'] ) +'</td>'+
						'<td>'+datosEgr[x]['concepto']+'</td>'+
						'<td>'+datosEgr[x]['numeroProyecto']+'</td>'+
						//'<td '+ (( datosEgr[x]['nombreProyecto'].length > 100 ) ? 'data-toggle="tooltip" title="'+datosEgr[x]['nombreProyecto']+'">'+(datosEgr[x]['nombreProyecto']).substring(0,100)+'...' : '>'+ datosEgr[x]['nombreProyecto'] ) +'</td>'+
						'<td>'+ datosEgr[x]['nombreProyecto']+'</td>'+
						'<td>'+datosEgr[x]['observaciones']+'</td>'+
						'<td>$'+addCommas(datosEgr[x]['importeTotal'])+'</td>'+
						//'<td>$'+datosEgr[x]['comprobado']+'</td>'+
						'<td>$'+addCommas(datosEgr[x]['comprobado'])+'</td>'+
						'<td>$'+addCommas(datosEgr[x]['restaComprobar'])+'</td>';
						//'<td>'+datosEgr[x]['status']+'</td>'+

						if( parseInt(<?php echo $_SESSION['anio'] ?>) >= 2017){
							switch (datosEgr[x]['status']) {
								case '1':
									thisLista += '<td style="color: #880E4F">En Firma</td>';
								break;
								case '2':
									thisLista += '<td style="color: #E65100">Para Entrega</td>';
								break;
								case '3':
									thisLista += '<td style="color: #1B5E20">Entregado</td>';
								break;
							}
						} else {
							thisLista += '<td>&nbsp;</td>';
						}

					thisLista += ''+
					'</tr>';

					switch (true) {
						case (pro == "" && enc == ""):			//sin filtros
								sumaImporteTotal += parseFloat(datosEgr[x]['importeTotal']);
								if( datosEgr[x]['comprobado'] != null ){ sumaComprobado += parseFloat(datosEgr[x]['comprobado']); }
								sumaRestaComprobar += parseFloat(datosEgr[x]['restaComprobar']);
							break;
						case (pro != "" && enc == ""):			//filtro por proyecto
								if(pro == datosEgr[x]['numeroProyecto']){
									sumaImporteTotal += parseFloat(datosEgr[x]['importeTotal']);
									if( datosEgr[x]['comprobado'] != null ){ sumaComprobado += parseFloat(datosEgr[x]['comprobado']); }
									sumaRestaComprobar += parseFloat(datosEgr[x]['restaComprobar']);
								}
							break;
						case (pro == "" && enc != ""):			//filtro por encargado
								if( enc == $.trim(datosEgr[x]['nombreResponsable']) ){
									sumaImporteTotal += parseFloat(datosEgr[x]['importeTotal']);
									if( datosEgr[x]['comprobado'] != null ){ sumaComprobado += parseFloat(datosEgr[x]['comprobado']); }
									sumaRestaComprobar += parseFloat(datosEgr[x]['restaComprobar']);
								}
							break;
					}	//-- fin del switch
				}

				thisLista += ''+
					'<tr>'+
						'<td colspan="6">&nbsp;</td>'+
						'<td>Total:</td>'+
						'<td>$'+addCommas(sumaImporteTotal)+'</td>'+
						'<td>$'+addCommas(sumaComprobado)+'</td>'+
						'<td>$'+addCommas(sumaRestaComprobar)+'</td>'+
						'<td>&nbsp;</td>'+
					'</tr>'+
				'</table>';

				$("#bloqueTabla").html(thisLista);
			}

			//          Agrega comas a las cantidades
			//------------------------------------------------------------
			function addCommas(nStr)
			{
					if(nStr == null){	nStr = 0; }
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
</html>
