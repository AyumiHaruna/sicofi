<?php 	require_once('menu.php');
require_once('../config.php');
header('Content-Type: text/html; charset=UTF-8');
?>
<html>
	<head>
		<title>
			Reporte de Egresos Por Proyecto
		</title>
		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/reportes.css"></link>
		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>		<!-- Efectos del Menú -->

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

			#HACEMOS UN PAR DE BUSQUEDAS PARA IDENTIFICA EL PRIMER Y ULTIMO REGISTRO DE LA COLUMAN 'numeroProyecto'
			#	CONEXION
			$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
				or die("Problemas con la conexi&oacute;n a la base de datos");
				$con->query("SET NAMES 'utf8'");

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
			}

			if($proy[0] == 999999)
			{
			#	BUSCA EL MENOR NUMERO
			$menor = mysqli_query($con, "SELECT egr.nombreProyecto, pro.numeroProyecto
											FROM egresos AS egr
											JOIN proyectos AS pro
												ON egr.nombreProyecto = pro.nombreProyecto
											ORDER BY numeroProyecto LIMIT 1")
				or die(mysqli_error($con));

			#	BUSCAMOS LOS VALORES DE LA TABLA
			$egresos = mysqli_query($con, "SELECT pro.numeroProyecto, egr.nombreProyecto,
												 egr.cap2000, egr.cap3000,
												egr.importeTotal, egr.comprobado, egr.restaComprobar
												FROM egresos AS egr
												INNER JOIN	proyectos AS pro
													ON	egr.nombreProyecto = pro.nombreProyecto
												ORDER BY numeroProyecto")
				or die(mysqli_error($con));
			}
			else
			{
				#	BUSCA EL MENOR NUMERO
				$menor = mysqli_query($con, "SELECT egr.nombreProyecto, pro.numeroProyecto
												FROM egresos AS egr
												JOIN proyectos AS pro
													ON egr.nombreProyecto = pro.nombreProyecto
												WHERE pro.numeroProyecto = '$proy[0]' OR
																	pro.numeroProyecto = '$proy[1]' OR pro.numeroProyecto = '$proy[2]' OR
																	pro.numeroProyecto = '$proy[3]' OR pro.numeroProyecto = '$proy[4]' OR
																	pro.numeroProyecto = '$proy[5]' OR pro.numeroProyecto = '$proy[6]' OR
																	pro.numeroProyecto = '$proy[7]' OR pro.numeroProyecto = '$proy[8]' OR
																	pro.numeroProyecto = '$proy[9]'
												ORDER BY numeroProyecto LIMIT 1")
					or die(mysqli_error($con));

				#	BUSCAMOS LOS VALORES DE LA TABLA
				$egresos = mysqli_query($con, "SELECT pro.numeroProyecto, egr.nombreProyecto,
													 egr.cap2000, egr.cap3000,
													egr.importeTotal, egr.comprobado, egr.restaComprobar
													FROM egresos AS egr
													INNER JOIN	proyectos AS pro
														ON	egr.nombreProyecto = pro.nombreProyecto
													WHERE pro.numeroProyecto = '$proy[0]' OR
																	pro.numeroProyecto = '$proy[1]' OR pro.numeroProyecto = '$proy[2]' OR
																	pro.numeroProyecto = '$proy[3]' OR pro.numeroProyecto = '$proy[4]' OR
																	pro.numeroProyecto = '$proy[5]' OR pro.numeroProyecto = '$proy[6]' OR
																	pro.numeroProyecto = '$proy[7]' OR pro.numeroProyecto = '$proy[8]' OR
																	pro.numeroProyecto = '$proy[9]'
													ORDER BY numeroProyecto")
					or die(mysqli_error($con));
			}

			echo '<fieldset id="base">';
			echo '<fieldset id="formulario">
					<legend>Reporte de Egresos Por Proyecto</legend>';
			echo '<table class="tablaReport" border="1">
					<tr>
						<td>No. de Proyecto</td>
						<td>Nombre del Proyecto</td>
						<td>Suma del Cap&iacute;tulo 2000</td>
						<td>Suma del Cap&iacute;tulo 3000</td>
						<td>Total Ejercido</td>
						<td>Comprobado</td>
						<td>Resta por Comprobar</td>
						</tr>';
			#TERMINAN LAS BUSQUEDAS, Y COMIENZAN LOS PROCESO PARA ALMACENAR VARIABLES Y VALORES DE LA TABLA
			#	ALMACENA VARIABLE CON EL VALOR MAS BAJO
			while($reg = mysqli_fetch_array($menor))
			{
				$primer = $reg['numeroProyecto'];
			}

			# DECLARAMOS ALGUNAS VARIABLES DE REPORTE X PROYECTO
			$numProy = $primer;
			$nombreProy;
			$suma2000 = 0;
			$suma3000 = 0;
			$sumaImporteTotal = 0;
			$sumaComprobado = 0;
			$sumaRestaComprobar = 0;

			//variables de la suma final
			$sSuma2000 = 0;
			$sSuma3000 = 0;
			$sImporteTotal = 0;
			$sComprobado = 0;
			$sRestaComprobar = 0;

			#CICLO MIENTRAS SE RECORRA EL ARREGLO DE EGRESOS
			while ($reg = mysqli_fetch_array ($egresos))
			{
				if($numProy == $reg['numeroProyecto'])	#Cuando es igual debe guardar el nombre y sumar capitulos, importes, comprobados y resta por comprobar
				{
					$nombreProy = $reg['nombreProyecto'];

					$suma2000 = $reg['cap2000'] + $suma2000;
					$suma3000 = $reg['cap3000'] + $suma3000;
					$sumaImporteTotal = $reg['importeTotal'] + $sumaImporteTotal;
					$sumaComprobado  =  $reg['comprobado'] + $sumaComprobado;
					$sumaRestaComprobar = $reg['restaComprobar'] + $sumaRestaComprobar;

					$sSuma2000 = $reg['cap2000'] + $sSuma2000;
					$sSuma3000 = $reg['cap3000'] + $sSuma3000;
					$sImporteTotal = $reg['importeTotal'] + $sImporteTotal;
					$sComprobado = $reg['comprobado'] + $sComprobado;
					$sRestaComprobar = $reg['restaComprobar'] + $sRestaComprobar;
				}
				else	#Cuando es diferente escribe las variables guardadas, resetea las sumas y registra nuevas variables
				{
					echo '<tr>
							<td>'.$numProy.'</td>
							<td>'.$nombreProy.'</td>
							<td>$'.number_format($suma2000, 2,'.',',').'</td>
							<td>$'.number_format($suma3000, 2,'.',',').'</td>
							<td>$'.number_format($sumaImporteTotal, 2,'.',',').'</td>
							<td>$'.number_format($sumaComprobado, 2,'.',',').'</td>
							<td>$'.number_format($sumaRestaComprobar, 2,'.',',').'</td></tr>';

					$numProy = $reg['numeroProyecto'];
					$nombreProy = $reg['nombreProyecto'];
					$suma2000 = 0;
					$suma3000 = 0;
					$sumaImporteTotal = 0;
					$sumaComprobado = 0;
					$sumaRestaComprobar = 0;
					$suma2000 = $reg['cap2000'] + $suma2000;
					$suma3000 = $reg['cap3000'] + $suma3000;
					$sumaComprobado = $reg['comprobado'] + $sumaComprobado;
					$sumaRestaComprobar = $reg['restaComprobar'] + $sumaRestaComprobar;

					$sSuma2000 = $reg['cap2000'] + $sSuma2000;
					$sSuma3000 = $reg['cap3000'] + $sSuma3000;
					$sImporteTotal = $reg['importeTotal'] + $sImporteTotal;
					$sComprobado = $reg['comprobado'] + $sComprobado;
					$sRestaComprobar = $reg['restaComprobar'] + $sRestaComprobar;
				}
			}
			echo '<tr>
					<td>'.$numProy.'</td>
					<td>'.$nombreProy.'</td>
					<td>$'.number_format($suma2000, 2,'.',',').'</td>
					<td>$'.number_format($suma3000, 2,'.',',').'</td>
					<td>$'.number_format($sumaImporteTotal, 2,'.',',').'</td>
					<td>$'.number_format($sumaComprobado, 2,'.',',').'</td>
					<td>$'.number_format($sumaRestaComprobar, 2,'.',',').'</td></tr>';

			echo '<tr>
					<td>&nbsp;</td>
					<td>Total:</td>
					<td>$'.number_format($sSuma2000, 2,'.',',').'</td>
					<td>$'.number_format($sSuma3000, 2,'.',',').'</td>
					<td>$'.number_format($sImporteTotal, 2,'.',',').'</td>
					<td>$'.number_format($sComprobado, 2,'.',',').'</td>
					<td>$'.number_format($sRestaComprobar, 2,'.',',').'</td>
				<tr>';

			echo '</table>';

			mysqli_close($con);
		?>
	</body>
</html>
