<?php 	require_once('menu.php');
require_once('../config.php');
header('Content-Type: text/html; charset=UTF-8');
?>
<html>
	<head>
		<title> Comprobaciones  </title>

		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/reportes.css"></link>

		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>		<!-- Efectos del Menú -->

	</head>

	<body>
	<?php
			//-------------------------	INICIA MENÚ	--------------------------
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
//termina menú y comenzamos haciendo las busquedas de la tabla

		$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");

		#	busca el numero menor
		$menor = mysqli_query($con, "SELECT ing.numProy, pro.numeroProyecto, ing.tipo
										FROM ingresos AS ing
										JOIN proyectos AS pro
											ON ing.numProy = pro.numeroProyecto
										WHERE ing.tipo = 'INGRESO' AND validado > 0
										ORDER BY numProy LIMIT 1")
			or die(mysqli_error($con));
		#	busca el numero mayor
		$mayor = mysqli_query($con, "SELECT ing.numProy, pro.numeroProyecto, ing.tipo
										FROM ingresos AS ing
										JOIN proyectos AS pro
											ON ing.numProy = pro.numeroProyecto
										WHERE ing.tipo = 'INGRESO' AND validado > 0
										ORDER BY numeroProyecto DESC LIMIT 1")
			or die(mysqli_error($con));
		#aqui mismo agregaré la busqueda que genera los valores de la tabla

		$ingresos = mysqli_query($con, "SELECT ing.numProy, ing.validado, ing.tipo,
											pro.numeroProyecto, pro.nombreProyecto, ing.subComp
											FROM ingresos AS ing
											JOIN proyectos AS pro
												ON	ing.numProy = pro.numeroProyecto
											WHERE ing.tipo = 'INGRESO' AND validado > 0
											ORDER BY pro.numeroProyecto")
			or die(mysqli_error($con));

		#TERMINAN LAS BUSQUEDAS, Y COMIENZAN LOS PROCESOS PARA ALMACENAR VARIABLES Y VALORES DE LA TABLA
			#	ALMACENA VARIABLE CON EL VALOR MAS BAJO (SOBRE LA BUSQUEDA DEL VALOR MAS BAJO)
			while($reg = mysqli_fetch_array($menor))
				{
					$primero = $reg['numProy'];
				}
			#	ALMACENA VARIABLE CON EL VALOR MAS ALTO (SOBRE LA BUSQUEDA DEL VALRO MAS ALTO)

			while($reg1 = mysqli_fetch_array($mayor))
				{
					$ultimo = $reg1['numProy'];
				}

			#DECLARAMOS ALGUNAS VARIABLES

			$numProy = $primero;
			$nombreProy;
			$sumaTotal = 0;		$UsumaTotal = 0;	$sumaDif;
			$sumaTotalC = 0;		$UsumaTotalC = 0; $UsumaDif;
			$cant = 0 ;		$cantComp = 0;
			$Ucant = 0;	$UcantComp =0;
			$porcentaje = 0;


			#INICIA LA CREACION DE LA TABLA

			echo '<fieldset id="base">';
			echo '<fieldset id="formulario">
					<legend>Comprobaciones</legend>
					Lista de Proyectos';
			echo '<table class="tablaReport" border = "1" >';
			echo '<tr>
					<td>Editar</td>
					<td>No. de Proyecto </td>
					<td>Nombre del Proyecto </td>
					<td>Monto Total</td>
					<td>Monto Comprobado</td>
					<td>A&uacute;n por Comprobar</td>

					<td>No. de Ingresos</td>
					<td>No. de Ing. Comprobados</td>
					<td>%</td>
				</tr>';
			#CICLO MIENTRAS SE RECORRA EL ARREGLO DE INGRESOS
			while ($reg = mysqli_fetch_array ($ingresos))
			{
				if($numProy == $reg['numProy'])  #Cuando es igual solo guarda variables y suma el total
				{
					$nombreProy = $reg['nombreProyecto'];
					$sumaTotal = $reg['validado'] + $sumaTotal;
					$cant++;

					if ($reg['subComp'] == 1 )
					{
						$sumaTotalC = $reg['validado'] + $sumaTotalC;
						$cantComp++;
					}

				}
				else		#Cuando es diferente escribe las variables guardadas/ suma =0 / registra nuevas variables
				{
					if($cantComp != 0)
					{
							$porcentaje = ($cantComp / $cant) * 100;
					}
					else{	$porcentaje = 0;}

					$sumaDif = $sumaTotal - $sumaTotalC;
						echo '<tr>
							<td><a href="comp2.php?codigo='.$numProy.'"> <img src="../Imagen/writeNegro.png" width="25" height="25"></img></a></td>
							<td>'.$numProy.'</td>
							<td>'.$nombreProy.'</td>
							<td>$'.number_format($sumaTotal, 2,'.',',').'</td>
							<td>$'.number_format($sumaTotalC, 2,'.',',').'</td>
							<td>$'.number_format($sumaDif, 2,'.',',').'</td>
							<td>'.$cant.'</td>
							<td>'.$cantComp.'</td>
							<td>%'.number_format($porcentaje, 2,'.',',').'</td>
							</tr>';

					$sumaTotal = 0;
					$sumaTotalC = 0;
					$cant = 0;
					$cantComp = 0;
					$sumaDif  = 0;

					$numProy = $reg['numeroProyecto'];
					$nombreProy = $reg['nombreProyecto'];
					$sumaTotal = $reg['validado'] + $sumaTotal;
					$cant++;

					if ($reg['subComp'] == 1 )
					{
						$sumaTotalC = $reg['validado'] + $sumaTotalC;
						$cantComp++;
					}
				}

						#Esta rutina aplicara solamente si el proyecto del ingreso es igual a nuestra variables último
				if($ultimo == $reg['numProy'])
				{
					$UsumaTotal = $reg['validado'] + $UsumaTotal;
					$Ucant++;

					if ($reg['subComp'] == 1 )
					{
						$UsumaTotalC = $reg['validado'] + $UsumaTotalC;
						$UcantComp++;
					}
				}
			}
			$UsumaDif = $UsumaTotal - $UsumaTotalC;
			if($UcantComp != 0)
					{
							$Uporcentaje = ($UcantComp / $Ucant) * 100;
					}
					else{	$Uporcentaje = 0;}
			echo '<tr>
						<td><a href="comp2.php?codigo='.$numProy.'"> <img src="../Imagen/writeNegro.png" width="25" height="25"></img></a></td>
							<td>'.$numProy.'</td>
							<td>'.$nombreProy.'</td>
							<td>$'.number_format($UsumaTotal, 2,'.',',').'</td>
							<td>$'.number_format($UsumaTotalC, 2,'.',',').'</td>
							<td>$'.number_format($UsumaDif, 2,'.',',').'</td>
							<td>'.$Ucant.'</td>
							<td>'.$UcantComp.'</td>
							<td>%'.number_format($Uporcentaje, 2,'.',',').'</td>
						</tr>';


			echo '</table>';
			mysqli_close($con);
	?>
	</body>
</html>
