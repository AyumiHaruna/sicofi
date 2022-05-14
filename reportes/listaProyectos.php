<!DOCTYPE html>

<?php require_once('menu.php');
require_once('../config.php');
header('Content-Type: text/html; charset=UTF-8');
session_start();

$miUsuario = $_SESSION['id'];
$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
	or die("Problemas con la conexi&oacute;n a la base de datos");
	$con->query("SET NAMES 'utf8'");
?>

<html>
	<head>
		<meta charset="utf-8">
		<title> Lista de Proyectos </title>

		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/reportes.css"></link>
		<script src="../js/jquery-3.1.1.js" type="text/javascript"></script>
		<script type="text/javascript" src="../js/script.js"></script>		<!-- Efectos del Menú -->
		<style media="screen">
			.tablaReport{
				margin-bottom: 30px;
			}
		</style>
	</head>
	<body>
		<?php 			//FUNCIONES DE SESION
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

		<?php 	//II Cuadro de Búsqueda  II//
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
				$busqueda = mysqli_query($con, "SELECT * FROM proyectos ORDER BY numeroProyecto")
					or die(mysqli_error($con));
			}	else {
				$textoBusqueda = 'SELECT * FROM proyectos WHERE ';
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
			?>





			<fieldset id="base">
				<fieldset id="formulario">
					<legend>Búsqueda de proyectos</legend>
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
			//--				VARIABLES GLOBALES
			//------------------------------
			var datosProy =  <?php  print_r( json_encode($datosProyectos) ); ?>;


			//-- 				CONDICIONES INICIALES
			//-------------------------------
			cargaListaProyectos();
			cargaListaEncargados();
			pintaProyectos();

			//-- 				FUNCIONES DEL DOM
			//-------------------------------
			$("#selProy").change(function(){
				$("#selEncar").val("");
				filtraProyectos($("#selProy").val(), "");
			});

			$("#selEncar").change(function(){
				$("#selProy").val("");
				filtraProyectos("", $("#selEncar").val());
			});

			//--				FUNCIONES GENERALES
			//-------------------------------
			//-- rellena el select de proyectos
			function cargaListaProyectos(){
				console.log('cargaListaProyectos(ok)');
				var thisLista = '<option value=""> -- Seleccione un proyecto</option>';
					for(var x=0; x<(datosProy).length; x++){
						thisLista += '<option value="'+datosProy[x]['numeroProyecto']+'">'+datosProy[x]['numeroProyecto']+' - '+datosProy[x]['nombreProyecto']+'</td>';
					}
				$("#selProy").html(thisLista);
			}

			//-- rellena el select de encargados
			function cargaListaEncargados(){
				console.log('cargaListaEncargados(ok)');
				var thisLista = '<option value=""> -- Seleccione a un encargado</option>';
				var listaEncargados =[];
				for(var x=0; x<(datosProy).length; x++){		//obtenemos la lista de nombres
					if( ($.inArray( $.trim(datosProy[x]['nombreResponsable']) , listaEncargados) == -1) ){
						listaEncargados.push( $.trim(datosProy[x]['nombreResponsable']) );
					}
				}
				listaEncargados.sort()
				for(var x=0; x<(listaEncargados).length; x++){
					thisLista += '<option value="'+listaEncargados[x]+'">'+listaEncargados[x]+'</option>';
				}
				$("#selEncar").html(thisLista);
			}


			//-- pinta la lista de proyectos con sus datos
			function pintaProyectos(){
				console.log('pintaProyectos(ok)');
				var tablaProyectos = '<legend>Reporte de Proyectos</legend>';
				for(var x=0; x<(datosProy).length; x++){		//obtenemos la lista de nombres
					tablaProyectos += ''+
					'<table class="tablaReport" border="0" dataProyecto="'+datosProy[x]['numeroProyecto']+'" dataEncargado="'+$.trim(datosProy[x]['nombreResponsable'])+'">'+
						'<tr>'+
							'<td colspan="4">Proyecto:</td>'+
							'<td colspan="8">'+datosProy[x]['nombreProyecto']+'</td>'+
						'</tr>'+
						'<tr>'+
							'<td colspan="3" align="right">N&uacute;mero de Proyecto:</td>'+
							'<td>'+datosProy[x]['numeroProyecto']+'</td>'+
							'<td colspan="3" align="right">Nombre del Responsable</td>'+
							'<td colspan="5" rowspan="3">'+datosProy[x]['nombreResponsable']+'</td>'+
						'</tr>'+
						'<tr>'+
							'<td colspan="7">&nbsp;</td>'+
						'</tr>'+
						'<tr>'+
							'<td colspan="5" align="right"><b>Cap&iacute;tulo 1000:</b></td>'+
							'<td colspan="2"><b>$'+addCommas(datosProy[x]['cap1000'])+'</b></td>'+
						'</tr>'+
						'<tr>'+
							'<td>Enero:</td>'+
							'<td>$'+addCommas(datosProy[x]['ene1'])+'</td>'+
							'<td>Febrero:</td>'+
							'<td>$'+addCommas(datosProy[x]['feb1'])+'</td>'+
							'<td>Marzo:</td>'+
							'<td>$'+addCommas(datosProy[x]['mar1'])+'</td>'+
							'<td>Abril:</td>'+
							'<td>$'+addCommas(datosProy[x]['abr1'])+'</td>'+
							'<td>Mayo:</td>'+
							'<td>$'+addCommas(datosProy[x]['may1'])+'</td>'+
							'<td>Junio:</td>'+
							'<td>$'+addCommas(datosProy[x]['jun1'])+'</td>'+
						'</tr>'+
						'<tr>'+
							'<td>Julio:</td>'+
							'<td>$'+addCommas(datosProy[x]['jul1'])+'</td>'+
							'<td>Agosto:</td>'+
							'<td>$'+addCommas(datosProy[x]['ago1'])+'</td>'+
							'<td>Septiembre:</td>'+
							'<td>$'+addCommas(datosProy[x]['sep1'])+'</td>'+
							'<td>Octubre:</td>'+
							'<td>$'+addCommas(datosProy[x]['oct1'])+'</td>'+
							'<td>Noviembre</td>'+
							'<td>$'+addCommas(datosProy[x]['nov1'])+'</td>'+
							'<td>Diciembre</td>'+
							'<td>$'+addCommas(datosProy[x]['dic1'])+'</td>'+
						'</tr>'+
						'<tr>'+
							'<td colspan="5" align="right"><b>Cap&iacute;tulo 2000:</b></td>'+
							'<td colspan="2"><b>$'+addCommas(datosProy[x]['cap2000'])+'</b></td>'+
						'</tr>'+
						'<tr>'+
							'<td>Enero:</td>'+
							'<td>$'+addCommas(datosProy[x]['ene2'])+'</td>'+
							'<td>Febrero:</td>'+
							'<td>$'+addCommas(datosProy[x]['feb2'])+'</td>'+
							'<td>Marzo:</td>'+
							'<td>$'+addCommas(datosProy[x]['mar2'])+'</td>'+
							'<td>Abril:</td>'+
							'<td>$'+addCommas(datosProy[x]['abr2'])+'</td>'+
							'<td>Mayo:</td>'+
							'<td>$'+addCommas(datosProy[x]['may2'])+'</td>'+
							'<td>Junio:</td>'+
							'<td>$'+addCommas(datosProy[x]['jun2'])+'</td>'+
						'</tr>'+
						'<tr>'+
							'<td>Julio:</td>'+
							'<td>$'+addCommas(datosProy[x]['jul2'])+'</td>'+
							'<td>Agosto:</td>'+
							'<td>$'+addCommas(datosProy[x]['ago2'])+'</td>'+
							'<td>Septiembre:</td>'+
							'<td>$'+addCommas(datosProy[x]['sep2'])+'</td>'+
							'<td>Octubre:</td>'+
							'<td>$'+addCommas(datosProy[x]['oct2'])+'</td>'+
							'<td>Noviembre</td>'+
							'<td>$'+addCommas(datosProy[x]['nov2'])+'</td>'+
							'<td>Diciembre</td>'+
							'<td>$'+addCommas(datosProy[x]['dic2'])+'</td>'+
						'</tr>'+
						'<tr>'+
							'<td colspan="5" align="right"><b>Cap&iacute;tulo 3000;</b></td>'+
							'<td colspan="2"><b>$'+addCommas(datosProy[x]['cap3000'])+'</b></td>'+
						'</tr>'+
						'<tr>'+
							'<td>Enero:</td>'+
							'<td>$'+addCommas(datosProy[x]['ene3'])+'</td>'+
							'<td>Febrero:</td>'+
							'<td>$'+addCommas(datosProy[x]['feb3'])+'</td>'+
							'<td>Marzo:</td>'+
							'<td>$'+addCommas(datosProy[x]['mar3'])+'</td>'+
							'<td>Abril:</td>'+
							'<td>$'+addCommas(datosProy[x]['abr3'])+'</td>'+
							'<td>Mayo:</td>'+
							'<td>$'+addCommas(datosProy[x]['may3'])+'</td>'+
							'<td>Junio:</td>'+
							'<td>$'+addCommas(datosProy[x]['jun3'])+'</td>'+
						'</tr>'+
						'<tr>'+
							'<td>Julio:</td>'+
							'<td>$'+addCommas(datosProy[x]['jul3'])+'</td>'+
							'<td>Agosto:</td>'+
							'<td>$'+addCommas(datosProy[x]['ago3'])+'</td>'+
							'<td>Septiembre:</td>'+
							'<td>$'+addCommas(datosProy[x]['sep3'])+'</td>'+
							'<td>Octubre:</td>'+
							'<td>$'+addCommas(datosProy[x]['oct3'])+'</td>'+
							'<td>Noviembre</td>'+
							'<td>$'+addCommas(datosProy[x]['nov3'])+'</td>'+
							'<td>Diciembre</td>'+
							'<td>$'+addCommas(+datosProy[x]['dic3'])+'</td>'+
						'</tr>'+
						'<tr>'+
							'<td colspan = "2" align="right"><font color="blue"><b>Total Autorizado:</b></font></td>'+
							'<td colspan = "3"><font color="blue"><b>$'+addCommas(datosProy[x]['totalAutorizado'])+'</b></font></td>'+
						'</tr>'+
					 '</table>';
				}
				$("#bloqueTabla").html(tablaProyectos);
			}

			function filtraProyectos(proy, enca){
				console.log('filtraProyectos(ok)');
				//console.log(proy+' - '+enca);
				$(".tablaReport").hide();
				switch (true) {
					case (proy == "" && enca == ""):
						$(".tablaReport").show();
						break;
					case (proy != "" && enca == ""):
						$(".tablaReport[dataProyecto = '"+proy+"']").show();
						break;
					case (proy == "" && enca != ""):
						$(".tablaReport[dataEncargado = '"+enca+"']").show();
						break;
				}
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
