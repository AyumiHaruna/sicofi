<?php 	require_once('menu.php');
		require_once('../config.php');
		header('Content-Type: text/html; charset=UTF-8');
		?>

<html>
	<head>
		<title> Editor de Ingresos </title>

		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/reportes.css"></link>

		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>		<!-- Efectos del Men� -->
	</head>

	<body>

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
//inicia busqueda de proyectos
		$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");

		#	busca el numero menor
		$menor = mysqli_query($con, "SELECT ing.numProy, pro.numeroProyecto, ing.tipo
										FROM ingresos AS ing
										JOIN proyectos AS pro
											ON ing.numProy = pro.numeroProyecto
										WHERE ing.tipo = 'INGRESO'
										ORDER BY numProy LIMIT 1")
			or die(mysqli_error($con));
		#	busca el numero mayor
		$mayor = mysqli_query($con, "SELECT ing.numProy, pro.numeroProyecto, ing.tipo
										FROM ingresos AS ing
										JOIN proyectos AS pro
											ON ing.numProy = pro.numeroProyecto
										WHERE ing.tipo = 'INGRESO'
										ORDER BY numeroProyecto DESC LIMIT 1")
			or die(mysqli_error($con));
		#aqui mismo agregar� la busqueda que genera los valores de la tabla

		$ingresos = mysqli_query($con, "SELECT ing.numProy, ing.SFtotal, ing.SFcap1000, ing.SFcap2000, ing.SFcap3000,
											ing.SFcap4000, ing.SFcap5000,
											pro.numeroProyecto, pro.nombreProyecto, ing.tipo
											FROM ingresos AS ing
											JOIN proyectos AS pro
												ON	ing.numProy = pro.numeroProyecto
											WHERE ing.tipo = 'INGRESO'
											ORDER BY pro.numeroProyecto")
			or die(mysqli_error($con));

		#TERMINAN LAS BUSQUEDAS, Y COMIENZAN LOS PROCESOS PARA ALMACENAR VARIABLES Y VALORES DE LA TABLA
			#	ALMACENA VARIABLE CON EL VALOR MAS BAJO (SOBRE LA BUSQUEDA DEL VALOR MAS BAJO)
			while($reg = mysqli_fetch_array($menor))
				{
					$primero = $reg['numeroProyecto'];
				}
			#	ALMACENA VARIABLE CON EL VALOR MAS ALTO (SOBRE LA BUSQUEDA DEL VALRO MAS ALTO)

			while($reg1 = mysqli_fetch_array($mayor))
				{
					$ultimo = $reg1['numeroProyecto'];
				}

			#DECLARAMOS ALGUNAS VARIABLES

			$numProy = $primero;
			$nombreProy;
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
			$sumaComprobado = 0;
			$sumaRestaComprobar = 0;
			$sUComprobado = 0;
			$sURestaComprobar = 0;

			#INICIA LA CREACION DE LA TABLA

			echo '<fieldset id="base">';
			echo '<fieldset id="formulario">
					<legend>Modifica Solicitudes de Fondos</legend>
					Solicitudes de Fondos por Proyecto';
			echo '<table class="tablaReport" border = "1" >';
			echo '<tr>
					<td>Editar</td>
					<td>No. de Proyecto </td>
					<td>Nombre del Proyecto </td>
					<td>Cap 1000</td>
					<td>Cap 2000</td>
					<td>Cap 3000</td>
					<td>Cap 4000</td>
					<td>Cap 5000</td>
					<td>Total</td>
				</tr>';
			#CICLO MIENTRAS SE RECORRA EL ARREGLO DE INGRESOS
			while ($reg = mysqli_fetch_array ($ingresos))
			{
				if($numProy == $reg['numeroProyecto'])  #Cuando es igual solo guarda variables y suma el total
				{
					$nombreProy = $reg['nombreProyecto'];
					$suma = $reg['SFtotal'] + $suma;
					$suma1000 += $reg['SFcap1000'];
					$suma2000 += $reg['SFcap2000'];
					$suma3000 += $reg['SFcap3000'];
					$suma4000 += $reg['SFcap4000'];
					$suma5000 += $reg['SFcap5000'];
				}
				else		#Cuando es diferente escribe las variables guardadas/ suma =0 / registra nuevas variables
				{
					echo '<tr>
							<td><a href="editaIngresos2.php?codigo='.$numProy.'"> <img src="../Imagen/writeNegro.png" width="25" height="25"></img></a></td>
							<td>'.$numProy.'</td>
							<td>'.$nombreProy.'</td>
							<td>$'.number_format($suma1000, 2,'.',',').'</td>
							<td>$'.number_format($suma2000, 2,'.',',').'</td>
							<td>$'.number_format($suma3000, 2,'.',',').'</td>
							<td>$'.number_format($suma4000, 2,'.',',').'</td>
							<td>$'.number_format($suma5000, 2,'.',',').'</td>
							<td>$'.number_format($suma, 2,'.',',').'</td>
							</tr>';

					$suma = 0;
					$suma1000 = 0;
					$suma2000 = 0;
					$suma3000 = 0;
					$suma4000 = 0;
					$suma5000 = 0;
					$sumaComprobado = 0;
					$sumaRestaComprobar = 0;
					$numProy = $reg['numeroProyecto'];
					$nombreProy = $reg['nombreProyecto'];
					$suma = $reg['SFtotal'] + $suma;
					$suma1000 += $reg['SFcap1000'];
					$suma2000 += $reg['SFcap2000'];
					$suma3000 += $reg['SFcap3000'];
					$suma4000 += $reg['SFcap4000'];
					$suma5000 += $reg['SFcap5000'];
				}

						#Esta rutina aplicara solamente si el proyecto del ingreso es igual a nuestra variables �ltimo
				if($ultimo == $reg['numeroProyecto'])
				{
					$sumaUltimo = $reg['SFtotal'] + $sumaUltimo;
					$sumaUltimo1000 += $reg['SFcap1000'];
					$sumaUltimo2000 += $reg['SFcap2000'];
					$sumaUltimo3000 += $reg['SFcap3000'];
					$sumaUltimo4000 += $reg['SFcap4000'];
					$sumaUltimo5000 += $reg['SFcap5000'];
				}
			}

			echo '<tr>
						<td><a href="editaIngresos2.php?codigo='.$numProy.'"> <img src="../Imagen/writeNegro.png" width="25" height="25"></img></a></td>
						<td>'.$numProy.'</td>
						<td>'.$nombreProy.'</td>
						<td>$'.number_format($sumaUltimo1000, 2,'.',',').'</td>
						<td>$'.number_format($sumaUltimo2000, 2,'.',',').'</td>
						<td>$'.number_format($sumaUltimo3000, 2,'.',',').'</td>
						<td>$'.number_format($sumaUltimo4000, 2,'.',',').'</td>
						<td>$'.number_format($sumaUltimo5000, 2,'.',',').'</td>
						<td>$'.number_format($sumaUltimo, 2,'.',',').'</td>
						</tr>';


			echo '</table>';
			mysqli_close($con);
	?>

	</body>
</html>
