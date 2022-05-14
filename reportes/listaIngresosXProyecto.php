<?php 	require_once('menu.php');
require_once('../config.php');
header('Content-Type: text/html; charset=UTF-8');
?>
<html>
	<head>
		<title>
			Reporte de Ingresos Por Proyecto
		</title>
		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/reportes.css"></link>
		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script src="../js/jquery-3.1.1.js" type="text/javascript"></script>
		<script type="text/javascript" src="../js/script.js"></script>		<!-- Efectos del Menú -->

	</head>

	<body>
	<!-- IIIIIIIIIIIIIIIIIIIIIIIIIIII   Este cuadro contiene el Menú	IIIIIIIIIIIIIIIIIIIII -->
				<?php
			session_start();
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

			$miUsuario = $_SESSION['id'];

			#HACEMOS UN PAR DE BUSQUEDAS PARA IDENTIFICAR EL PRIMER Y EL ULTIMO VALOR 'numeroProyecto' DE LA TABLA
			#	CONEXION
			$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
				or die("Problemas con la conexi&oacute;n a la base de datos");
				$con->query("SET NAMES 'utf8'");

//----------------- FIN DE BUSQUEDAS PARA LOS FILTROS-----------------

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
	//----------------- FIN DE BUSQUEDAS PARA LOS FILTROS-----------------




			$proyecto = mysqli_query($con, "SELECT * FROM usuarios WHERE id = '$miUsuario'")
				or die(mysqli_error($con));

			while($reg = mysqli_fetch_array($proyecto))
			{
				$proy[0] = $reg['proy1'];
				$proy[1] = $reg['proy2'];
				$proy[2] = $reg['proy3'];
				$proy[3] = $reg['proy4'];
				$proy[4] = $reg['proy5'];
				$proy[5] = $reg['proy6'];
				$proy[6] = $reg['proy7'];
				$proy[7] = $reg['proy8'];
				$proy[8] = $reg['proy9'];
				$proy[9] = $reg['proy10'];
				$proy[10] = $reg['proy11'];
				$proy[11] = $reg['proy12'];
				$proy[12] = $reg['proy13'];
				$proy[13] = $reg['proy14'];
				$proy[14] = $reg['proy15'];
				$proy[15] = $reg['proy16'];
				$proy[16] = $reg['proy17'];
				$proy[17] = $reg['proy18'];
				$proy[18] = $reg['proy19'];
				$proy[19] = $reg['proy20'];
				$proy[20] = $reg['proy21'];
				$proy[21] = $reg['proy22'];
				$proy[22] = $reg['proy23'];
				$proy[23] = $reg['proy24'];
				$proy[24] = $reg['proy25'];
				$proy[25] = $reg['proy26'];
				$proy[26] = $reg['proy27'];
				$proy[27] = $reg['proy28'];
				$proy[28] = $reg['proy29'];
				$proy[29] = $reg['proy30'];
				$proy[30] = $reg['proy31'];
				$proy[31] = $reg['proy32'];
				$proy[32] = $reg['proy33'];
				$proy[33] = $reg['proy34'];
				$proy[34] = $reg['proy35'];
				$proy[35] = $reg['proy36'];
				$proy[36] = $reg['proy37'];
				$proy[37] = $reg['proy38'];
				$proy[38] = $reg['proy39'];
				$proy[39] = $reg['proy40'];
				$proy[40] = $reg['proy41'];
				$proy[41] = $reg['proy42'];
				$proy[42] = $reg['proy43'];
				$proy[43] = $reg['proy44'];
				$proy[44] = $reg['proy45'];
			}

			if($proy[0] == 999999)
			{
				#	busca el numero menor
				$menor = mysqli_query($con, "SELECT ing.numProy, pro.numeroProyecto, pro.nombreProyecto, pro.nombreResponsable
												FROM ingresos AS ing
												JOIN proyectos AS pro
													ON ing.numProy = pro.numeroProyecto
												ORDER BY ing.numProy LIMIT 1")
					or die(mysqli_error($con));
				#	busca el numero mayor
				$mayor = mysqli_query($con, "SELECT ing.numProy, pro.numeroProyecto, pro.nombreProyecto, pro.nombreResponsable
												FROM ingresos AS ing
												JOIN proyectos AS pro
													ON ing.numProy = pro.numeroProyecto
												ORDER BY ing.numProy DESC LIMIT 1")
					or die(mysqli_error($con));
				#aqui mismo agregaré la busqueda que genera los valores de la tabla

				$ingresos = mysqli_query($con, "SELECT  ing.no, ing.tipo, ing.concepto, ing.operacion, ing.mes, ing.numProy,
																	ing.noSolFon, ing.validado, ing.capT1, ing.capT2, ing.capT3, ing.capT4, ing.capT5,
																	pro.numeroProyecto, pro.nombreProyecto, pro.nombreResponsable, ing.SFtotal
													FROM ingresos AS ing
													JOIN proyectos AS pro
														ON	ing.numProy = pro.numeroProyecto
													WHERE ing.validado > 0 AND ing.tipo = 'INGRESO'
													ORDER BY ing.numProy")
					or die(mysqli_error($con));
			}
			else
			{
				#	busca el numero menor
				$menor = mysqli_query($con, "SELECT ing.numProy, pro.numeroProyecto, pro.nombreProyecto, pro.nombreResponsable
												FROM ingresos AS ing
												JOIN proyectos AS pro
													ON ing.numProy = pro.numeroProyecto
												WHERE pro.numeroProyecto = '$proy[0]' OR
																	pro.numeroProyecto = '$proy[1]' OR pro.numeroProyecto = '$proy[2]' OR
																	pro.numeroProyecto = '$proy[3]' OR pro.numeroProyecto = '$proy[4]' OR
																	pro.numeroProyecto = '$proy[5]' OR pro.numeroProyecto = '$proy[6]' OR
																	pro.numeroProyecto = '$proy[7]' OR pro.numeroProyecto = '$proy[8]' OR
																	pro.numeroProyecto = '$proy[9]' OR pro.numeroProyecto = '$proy[10]' OR
																	pro.numeroProyecto = '$proy[11]' OR pro.numeroProyecto = '$proy[12]' OR
																	pro.numeroProyecto = '$proy[13]' OR pro.numeroProyecto = '$proy[14]' OR
																	pro.numeroProyecto = '$proy[15]' OR pro.numeroProyecto = '$proy[16]' OR
																	pro.numeroProyecto = '$proy[17]' OR pro.numeroProyecto = '$proy[18]' OR
																	pro.numeroProyecto = '$proy[19]' OR pro.numeroProyecto = '$proy[20]' OR
																	pro.numeroProyecto = '$proy[21]' OR pro.numeroProyecto = '$proy[22]' OR
																	pro.numeroProyecto = '$proy[23]' OR pro.numeroProyecto = '$proy[24]' OR
																	pro.numeroProyecto = '$proy[25]' OR pro.numeroProyecto = '$proy[26]' OR
																	pro.numeroProyecto = '$proy[27]' OR pro.numeroProyecto = '$proy[28]' OR
																	pro.numeroProyecto = '$proy[29]' OR pro.numeroProyecto = '$proy[30]' OR
																	pro.numeroProyecto = '$proy[31]' OR pro.numeroProyecto = '$proy[32]' OR
																	pro.numeroProyecto = '$proy[33]' OR pro.numeroProyecto = '$proy[34]' OR
																	pro.numeroProyecto = '$proy[35]' OR pro.numeroProyecto = '$proy[36]' OR
																	pro.numeroProyecto = '$proy[37]' OR pro.numeroProyecto = '$proy[38]' OR
																	pro.numeroProyecto = '$proy[39]' OR pro.numeroProyecto = '$proy[40]' OR
																	pro.numeroProyecto = '$proy[41]' OR pro.numeroProyecto = '$proy[42]' OR
																	pro.numeroProyecto = '$proy[43]' OR pro.numeroProyecto = '$proy[44]'
												ORDER BY ing.numProy LIMIT 1")
					or die(mysqli_error($con));
				#	busca el numero mayor
				$mayor = mysqli_query($con, "SELECT ing.numProy, pro.numeroProyecto, pro.nombreProyecto, pro.nombreResponsable
												FROM ingresos AS ing
												JOIN proyectos AS pro
													ON ing.numProy = pro.numeroProyecto
												WHERE pro.numeroProyecto = '$proy[0]' OR
																	pro.numeroProyecto = '$proy[1]' OR pro.numeroProyecto = '$proy[2]' OR
																	pro.numeroProyecto = '$proy[3]' OR pro.numeroProyecto = '$proy[4]' OR
																	pro.numeroProyecto = '$proy[5]' OR pro.numeroProyecto = '$proy[6]' OR
																	pro.numeroProyecto = '$proy[7]' OR pro.numeroProyecto = '$proy[8]' OR
																	pro.numeroProyecto = '$proy[9]' OR pro.numeroProyecto = '$proy[10]' OR
																	pro.numeroProyecto = '$proy[11]' OR pro.numeroProyecto = '$proy[12]' OR
																	pro.numeroProyecto = '$proy[13]' OR pro.numeroProyecto = '$proy[14]' OR
																	pro.numeroProyecto = '$proy[15]' OR pro.numeroProyecto = '$proy[16]' OR
																	pro.numeroProyecto = '$proy[17]' OR pro.numeroProyecto = '$proy[18]' OR
																	pro.numeroProyecto = '$proy[19]' OR pro.numeroProyecto = '$proy[20]' OR
																	pro.numeroProyecto = '$proy[21]' OR pro.numeroProyecto = '$proy[22]' OR
																	pro.numeroProyecto = '$proy[23]' OR pro.numeroProyecto = '$proy[24]' OR
																	pro.numeroProyecto = '$proy[25]' OR pro.numeroProyecto = '$proy[26]' OR
																	pro.numeroProyecto = '$proy[27]' OR pro.numeroProyecto = '$proy[28]' OR
																	pro.numeroProyecto = '$proy[29]' OR pro.numeroProyecto = '$proy[30]' OR
																	pro.numeroProyecto = '$proy[31]' OR pro.numeroProyecto = '$proy[32]' OR
																	pro.numeroProyecto = '$proy[33]' OR pro.numeroProyecto = '$proy[34]' OR
																	pro.numeroProyecto = '$proy[35]' OR pro.numeroProyecto = '$proy[36]' OR
																	pro.numeroProyecto = '$proy[37]' OR pro.numeroProyecto = '$proy[38]' OR
																	pro.numeroProyecto = '$proy[39]' OR pro.numeroProyecto = '$proy[40]' OR
																	pro.numeroProyecto = '$proy[41]' OR pro.numeroProyecto = '$proy[42]' OR
																	pro.numeroProyecto = '$proy[43]' OR pro.numeroProyecto = '$proy[44]'
												ORDER BY ing.numProy DESC LIMIT 1")
					or die(mysqli_error($con));
				#aqui mismo agregaré la busqueda que genera los valores de la tabla

				$ingresos = mysqli_query($con, "SELECT  ing.no, ing.tipo, ing.concepto, ing.operacion, ing.mes, ing.numProy,
																	ing.noSolFon, ing.validado, ing.capT1, ing.capT2, ing.capT3, ing.capT4, ing.capT5,
																	pro.numeroProyecto, pro.nombreProyecto, pro.nombreResponsable, ing.SFtotal
													FROM ingresos AS ing
													JOIN proyectos AS pro
														ON ing.numProy = pro.numeroProyecto
													WHERE pro.numeroProyecto = '$proy[0]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[1]' AND ing.validado > 0 AND ing.tipo = 'INGRESO' 	OR
																	pro.numeroProyecto = '$proy[2]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[3]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[4]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[5]' AND ing.validado > 0 AND ing.tipo = 'INGRESO' 	OR
																	pro.numeroProyecto = '$proy[6]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[7]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[8]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[9]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[10]' AND ing.validado > 0 AND ing.tipo = 'INGRESO' 	OR
																	pro.numeroProyecto = '$proy[11]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[12]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[13]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[14]' AND ing.validado > 0 AND ing.tipo = 'INGRESO' 	OR
																	pro.numeroProyecto = '$proy[15]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[16]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[17]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[18]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[19]' AND ing.validado > 0 AND ing.tipo = 'INGRESO' 	OR
																	pro.numeroProyecto = '$proy[20]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[21]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[22]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[23]' AND ing.validado > 0 AND ing.tipo = 'INGRESO' 	OR
																	pro.numeroProyecto = '$proy[24]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[25]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[26]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[27]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[28]' AND ing.validado > 0 AND ing.tipo = 'INGRESO' 	OR
																	pro.numeroProyecto = '$proy[29]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[30]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[31]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[32]' AND ing.validado > 0 AND ing.tipo = 'INGRESO' 	OR
																	pro.numeroProyecto = '$proy[33]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[34]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[35]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[36]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[37]' AND ing.validado > 0 AND ing.tipo = 'INGRESO' 	OR
																	pro.numeroProyecto = '$proy[38]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[39]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[40]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[41]' AND ing.validado > 0 AND ing.tipo = 'INGRESO' 	OR
																	pro.numeroProyecto = '$proy[42]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[43]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'	OR
																	pro.numeroProyecto = '$proy[44]' AND ing.validado > 0 AND ing.tipo = 'INGRESO'
													ORDER BY ing.numProy")
					or die(mysqli_error($con));
			}
			#TERMINAN LAS BUSQUEDAS, Y COMIENZAN LOS PROCESOS PARA ALMACENAR VARIABLES Y VALORES DE LA TABLA
			#	ALMACENA VARIABLE CON EL VALOR MAS BAJO (SOBRE LA BUSQUEDA DEL VALOR MAS BAJO)
			while($reg = mysqli_fetch_array($menor))
				{
					$primero = $reg['numProy'];
					$nombreProy = $reg['nombreProyecto'];
					$nombreResponsable = $reg['nombreResponsable'];
				}
			#	ALMACENA VARIABLE CON EL VALOR MAS ALTO (SOBRE LA BUSQUEDA DEL VALRO MAS ALTO)

			while($reg1 = mysqli_fetch_array($mayor))
				{
					$ultimo = $reg1['numProy'];
				}

			#DECLARAMOS ALGUNAS VARIABLES

			$numProy = $primero;
			$suma1000 = 0;
			$suma2000 = 0;
			$suma3000 = 0;
			$suma4000 = 0;
			$suma5000 = 0;
			$suma = 0;
			$sumaUltimo = 0;
			$sumaUltimo1000 = 0;
			$sumaUltimo2000 = 0;
			$sumaUltimo3000 = 0;
			$sumaUltimo4000 = 0;
			$sumaUltimo5000 = 0;
			$sumaMinistrados = 0;
			$uSumaMinistrados = 0;
			$porMinistrar = 0;

			//Sumas totales
			$sSuma1000 = 0;
			$sSuma2000 = 0;
			$sSuma3000 = 0;
			$sSuma4000 = 0;
			$sSuma5000 = 0;
			$sSumaTotal = 0;
			$sSumaMinistrados = 0;
			$sPorMinistrar = 0;
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
<?php
			#INICIA LA CREACION DE LA TABLA

			echo '<fieldset id="base">';
			echo '<fieldset id="formulario">
					<legend>Reporte de Ingresos por Proyecto</legend>';
			echo '<table class="tablaReport" border = "1" >';
			echo '<tr>
					<td>No. de Proyecto </td>
					<td>Nombre del Proyecto </td>
					<td>Total Solicitado</td>
					<td>Ministrado Cap 1000</td>
					<td>Ministrado Cap 2000</td>
					<td>Ministrado Cap 3000</td>
					<td>Ministrado Cap 4000</td>
					<td>Ministrado Cap 5000</td>
					<td>Total Ministrado </td>
					<td>Por Ministrar</td>
				</tr>';
			#CICLO MIENTRAS SE RECORRA EL ARREGLO DE INGRESOS
			while ($reg = mysqli_fetch_array ($ingresos))
			{
				if($numProy == $reg['numProy'])  #Cuando es igual solo guarda variables y suma el total
				{
					$nombreProy = $reg['nombreProyecto'];
					$nombreResponsable = $reg['nombreResponsable'];
					$suma = $reg['SFtotal'] + $suma;
					$suma1000 = $reg['capT1'] + $suma1000;
					$suma2000 = $reg['capT2'] + $suma2000;
					$suma3000 = $reg['capT3'] + $suma3000;
					$suma4000 = $reg['capT4'] + $suma4000;
					$suma5000 = $reg['capT5'] + $suma5000;
					$sumaMinistrados = $reg['validado'] + $sumaMinistrados;

					$sSuma1000 = $reg['capT1'] + $sSuma1000;
					$sSuma2000 = $reg['capT2'] + $sSuma2000;
					$sSuma3000 = $reg['capT3'] + $sSuma3000;
					$sSuma4000 = $reg['capT4'] + $sSuma4000;
					$sSuma5000 = $reg['capT5'] + $sSuma5000;
					$sSumaTotal = $reg['SFtotal'] + $sSumaTotal;
					$sSumaMinistrados = $reg['validado'] + $sSumaMinistrados;
				}
				else		#Cuando es diferente escribe las variables guardadas/ suma =0 / registra nuevas variables
				{
					$porMinistrar = $suma - $sumaMinistrados;
					echo '<tr class="lineaTabla" dataProyecto="'.$numProy.'" dataEncargado="'.trim($nombreResponsable, " ").'"> <td>	'.$numProy.'</td>
							<td>'.$nombreProy.'</td>
							<td>$'.number_format($suma, 2,'.',',').'</td>
							<td>$'.number_format($suma1000, 2,'.',',').'</td>
							<td>$'.number_format($suma2000, 2,'.',',').'</td>
							<td>$'.number_format($suma3000, 2,'.',',').'</td>
							<td>$'.number_format($suma4000, 2,'.',',').'</td>
							<td>$'.number_format($suma5000, 2,'.',',').'</td>
							<td>$'.number_format($sumaMinistrados, 2,'.',',').'</td>
							<td>$'.number_format($porMinistrar, 2,'.',',').'</td></tr>';

					$suma = 0;
					$suma1000 = 0;
					$suma2000 = 0;
					$suma3000 = 0;
					$suma4000 = 0;
					$suma5000 = 0;
					$sumaMinistrados = 0;
					$porMinistrar = 0;
					$numProy = $reg['numProy'];
					$nombreProy = $reg['nombreProyecto'];
					$nombreResponsable = $reg['nombreResponsable'];
					$suma = $reg['SFtotal'] + $suma;
					$suma1000 = $reg['capT1'] + $suma1000;
					$suma2000 = $reg['capT2'] + $suma2000;
					$suma3000 = $reg['capT3'] + $suma3000;
					$suma4000 = $reg['capT4'] + $suma4000;
					$suma5000 = $reg['capT5'] + $suma5000;
					$sumaMinistrados = $reg['validado'] + $sumaMinistrados;

					$sSuma1000 = $reg['capT1'] + $sSuma1000;
					$sSuma2000 = $reg['capT2'] + $sSuma2000;
					$sSuma3000 = $reg['capT3'] + $sSuma3000;
					$sSuma4000 = $reg['capT4'] + $sSuma4000;
					$sSuma5000 = $reg['capT5'] + $sSuma5000;
					$sSumaTotal = $reg['SFtotal'] + $sSumaTotal;
					$sSumaMinistrados = $reg['validado'] + $sSumaMinistrados;
				}

						#Esta rutina aplicara solamente si el proyecto del ingreso es igual a nuestra variables último
				if($ultimo == $reg['numProy'])
				{
					$sumaUltimo = $reg['SFtotal'] + $sumaUltimo;
					$sumaUltimo1000 = $reg['capT1'] + $sumaUltimo1000;
					$sumaUltimo2000 = $reg['capT2'] + $sumaUltimo2000;
					$sumaUltimo3000 = $reg['capT3'] + $sumaUltimo3000;
					$sumaUltimo4000 = $reg['capT4'] + $sumaUltimo4000;
					$sumaUltimo5000 = $reg['capT5'] + $sumaUltimo5000;
					$uSumaMinistrados = $reg['validado'] + $uSumaMinistrados;

					/*$sSuma1000 = $reg['cap1000'] + $sSuma1000;
					$sSuma2000 = $reg['cap2000'] + $sSuma2000;
					$sSuma3000 = $reg['cap3000'] + $sSuma3000;
					$sSumaTotal = $reg['importeTotal'] + $sSumaTotal;
					$sSumaMinistrados = $reg['validado'] + $sSumaMinistrados;*/
				}
			}
					$porMinistrar = $suma - $sumaMinistrados;
			echo '<tr class="lineaTabla" dataProyecto="'.$numProy.'" dataEncargado="'.trim($nombreResponsable, " ").'"> <td>'.$numProy.'</td>
							<td>'.$nombreProy.'</td>
							<td>$'.number_format($sumaUltimo, 2,'.',',').'</td>
							<td>$'.number_format($sumaUltimo1000, 2,'.',',').'</td>
							<td>$'.number_format($sumaUltimo2000, 2,'.',',').'</td>
							<td>$'.number_format($sumaUltimo3000, 2,'.',',').'</td>
							<td>$'.number_format($sumaUltimo4000, 2,'.',',').'</td>
							<td>$'.number_format($sumaUltimo5000, 2,'.',',').'</td>
							<td>$'.number_format($uSumaMinistrados, 2,'.',',').'</td>
							<td>$'.number_format($porMinistrar, 2,'.',',').'</td> </tr>';

			$sPorMinistrar = $sSumaTotal - $sSumaMinistrados;
			echo '<tr class="lineaTotales">
					<td> &nbsp; </td>
					<td colspan>Total:</td>
					<td>$'.number_format($sSumaTotal, 2,'.',',').'</td>
					<td>$'.number_format($sSuma1000, 2,'.',',').'</td>
					<td>$'.number_format($sSuma2000, 2,'.',',').'</td>
					<td>$'.number_format($sSuma3000, 2,'.',',').'</td>
					<td>$'.number_format($sSuma4000, 2,'.',',').'</td>
					<td>$'.number_format($sSuma5000, 2,'.',',').'</td>
					<td>$'.number_format($sSumaMinistrados, 2,'.',',').'</td>
					<td>$'.number_format($sPorMinistrar, 2,'.',',').'</td>
				<tr>';



			echo '</table>';
			mysqli_close($con);
		?>
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
			filtraProyectos("", "");

			//--			FUNCIONES DEL DOM
			//--------------------------------
			$("#selProy").change(function(){
				$("#selEncar").val("");
				filtraProyectos($("#selProy").val(), "");
			});

			$("#selEncar").change(function(){
				$("#selProy").val("");
				filtraProyectos("", $("#selEncar").val());
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

			//-- oculta proyectos que no son seleccionados
			function filtraProyectos(proy, enca){
				//console.log(proy+' - '+enca);
				$(".lineaTabla, .lineaTotales").hide();
				switch (true) {
					case (proy == "" && enca == ""):
						$(".lineaTabla, .lineaTotales").show();
						break;
					case (proy != "" && enca == ""):
						$(".lineaTabla[dataProyecto = '"+proy+"']").show();
						break;
					case (proy == "" && enca != ""):
						$(".lineaTabla[dataEncargado = '"+enca+"']").show();
						break;
				}
			}
		});
	</script>
</html>
