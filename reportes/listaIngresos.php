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


		//-- buscamos los ingresos con esos proyectos
		$textoBusqueda = "SELECT ing.no, ing.tipo, ing.concepto, ing.operacion, ing.mes, ing.numProy,
														ing.noSolFon, ing.validado, ing.capT1, ing.capT2, ing.capT3, ing.capT4, ing.capT5,
														ing.noAut1, ing.fechaDep1, pro.numeroProyecto, pro.nombreProyecto, pro.nombreResponsable, ing.SFtotal
										FROM ingresos AS ing INNER JOIN	proyectos AS pro ON	ing.numProy = pro.numeroProyecto WHERE ";
		if($userProy[0] == 999999){
 			$textoBusqueda .= "ing.tipo = 'INGRESO'	 AND ing.validado > 0 ";
		} else {

			for($x=0; $x<count($userProy); $x++){
				if($x<(count($userProy) - 1)){
					$textoBusqueda .= "pro.numeroProyecto = '$userProy[$x]' AND ing.tipo = 'INGRESO' AND ing.validado > 0 OR ";
				} else {
					$textoBusqueda .= "pro.numeroProyecto = '$userProy[$x]' AND ing.tipo = 'INGRESO' AND ing.validado > 0 ";
				}
			}
		}
		$textoBusqueda .= "ORDER BY ing.no";
		$busqueda = mysqli_query($con, $textoBusqueda) or die (mysqli_error($con));
		$ingresos = [];
		while($reg = mysqli_fetch_array($busqueda)){
			$ingresos[] = $reg;
		}
		//-----------------------TERMINAN CONSULTAS Y OPERACIONES PHP -----------
?>


<html>
	<head>
		<meta charset="utf-8">
		<title>Reporte de Ingresos Desglosados</title>

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
				<legend>Búsqueda de Ingresos</legend>
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
			var datosIng = <?php print_r( json_encode($ingresos) ); ?>;

			//--			CONDICIONES INICIALES
			//--------------------------------
			cargaListaProyectos();
			cargaListaEncargados();
			pintaIngresos("", "");

			//--			FUNCIONES DEL DOM
			//--------------------------------
			$("#selProy").change(function(){
				$("#selEncar").val("");
				pintaIngresos($("#selProy").val(), "");
			});

			$("#selEncar").change(function(){
				$("#selProy").val("");
				pintaIngresos("", $("#selEncar").val());
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
			function pintaIngresos(pro, enc){
				var suma1000 = 0;	var suma2000 = 0;	var suma3000 = 0;
				var suma4000 = 0;	var suma5000 = 0;	var sumaTotal = 0;
				var sumaValidado = 0; var faltaMinistrar = 0;
				var sumaFaltaMinistrar = 0;
				var thisLista = ''+
				'<legend>Lista de Ingresos desglosados</legend>'+
				'<table class="tablaReport" border="1">'+
					'<tr>'+
						'<td>No</td>'+ '<td>No. SF</td>'+ '<td>Mes</td>'+
						'<td># Proyecto</td>'+ '<td>Nombre del Proyecto</td>'+ '<td>No. de Autorización</td>'+
						'<td>Fecha de Depósito</td>'+ '<td>Importe Solicitado</td>'+ '<td>cap. 1000</td>'+
						'<td>cap. 2000</td>'+ '<td>cap. 3000</td>'+ '<td>cap. 4000</td>'+
						'<td>cap. 5000</td>'+ '<td>Total</td>'+ '<td>Aún por ministrar</td>'+
					'<tr>';

				for(var x=0; x<(datosIng).length; x++){

					switch (true) {
						case (pro == "" && enc == ""):			//sin filtros
							thisLista += '<tr>';
							break;
						case (pro != "" && enc == ""):			//filtro por proyecto
								thisLista += '<tr '+ ((pro == datosIng[x]['numeroProyecto'])? '' : 'style="display:none;"' ) +'>';
							break;
						case (pro == "" && enc != ""):			//filtro por encargado
								thisLista += '<tr '+ ((enc == $.trim(datosIng[x]['nombreResponsable']))? '' : 'style="display:none;"' ) +'>';
							break;
					}	//-- fin del switch

					faltaMinistrar = datosIng[x]['SFtotal'] - datosIng[x]['validado'];
					thisLista += ''+
						'<td>'+datosIng[x]['no']+'</td>'+
						'<td>'+datosIng[x]['noSolFon']+'</td>'+
						'<td>'+datosIng[x]['mes']+'</td>'+
						'<td>'+datosIng[x]['numeroProyecto']+'</td>'+
						'<td>'+datosIng[x]['nombreProyecto']+'</td>'+
						'<td>'+datosIng[x]['noAut1']+'</td>'+
						'<td>'+datosIng[x]['fechaDep1']+'</td>'+
						'<td>$'+addCommas(datosIng[x]['SFtotal'])+'</td>'+
						'<td>$'+addCommas(datosIng[x]['capT1'])+'</td>'+
						'<td>$'+addCommas(datosIng[x]['capT2'])+'</td>'+
						'<td>$'+addCommas(datosIng[x]['capT3'])+'</td>'+
						'<td>$'+addCommas(datosIng[x]['capT4'])+'</td>'+
						'<td>$'+addCommas(datosIng[x]['capT5'])+'</td>'+
						'<td>$'+addCommas(datosIng[x]['validado'])+'</td>'+
						'<td>$'+addCommas(faltaMinistrar)+'</td>'+
					'</tr>';

					switch (true) {
						case (pro == "" && enc == ""):			//sin filtros
								suma1000 += parseFloat(datosIng[x]['capT1']); 	suma2000 += parseFloat(datosIng[x]['capT2']);
								suma3000 += parseFloat(datosIng[x]['capT3']);		suma4000 += parseFloat(datosIng[x]['capT4']);
								suma5000 += parseFloat(datosIng[x]['capT5']);		sumaTotal += parseFloat(datosIng[x]['SFtotal']);
								sumaValidado += parseFloat(datosIng[x]['validado']);
								sumaFaltaMinistrar += faltaMinistrar;
							break;
						case (pro != "" && enc == ""):			//filtro por proyecto
								if(pro == datosIng[x]['numeroProyecto']){
									suma1000 += parseFloat(datosIng[x]['capT1']); 	suma2000 += parseFloat(datosIng[x]['capT2']);
									suma3000 += parseFloat(datosIng[x]['capT3']);		suma4000 += parseFloat(datosIng[x]['capT4']);
									suma5000 += parseFloat(datosIng[x]['capT5']);		sumaTotal += parseFloat(datosIng[x]['SFtotal']);
									sumaValidado += parseFloat(datosIng[x]['validado']);
									sumaFaltaMinistrar += faltaMinistrar;
								}
							break;
						case (pro == "" && enc != ""):			//filtro por encargado
								if( enc == $.trim(datosIng[x]['nombreResponsable']) ){
									suma1000 += parseFloat(datosIng[x]['capT1']); 	suma2000 += parseFloat(datosIng[x]['capT2']);
									suma3000 += parseFloat(datosIng[x]['capT3']);		suma4000 += parseFloat(datosIng[x]['capT4']);
									suma5000 += parseFloat(datosIng[x]['capT5']);		sumaTotal += parseFloat(datosIng[x]['SFtotal']);
									sumaValidado += parseFloat(datosIng[x]['validado']);
									sumaFaltaMinistrar += faltaMinistrar;
								}
							break;
					}	//-- fin del switch
				}

				thisLista += ''+
					'<tr>'+
						'<td colspan="6">&nbsp;</td>'+
						'<td>Total:</td>'+
						'<td>$'+addCommas(sumaTotal)+'</td>'+
						'<td>$'+addCommas(suma1000)+'</td>'+
						'<td>$'+addCommas(suma2000)+'</td>'+
						'<td>$'+addCommas(suma3000)+'</td>'+
						'<td>$'+addCommas(suma4000)+'</td>'+
						'<td>$'+addCommas(suma5000)+'</td>'+
						'<td>$'+addCommas(sumaValidado)+'</td>'+
						'<td>$'+addCommas(sumaFaltaMinistrar)+'</td>'+
					'</tr>'+
				'</table>';

				$("#bloqueTabla").html(thisLista);
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
</html>
