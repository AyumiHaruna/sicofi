<?php
	//------------------------------------------------------------
	//									CONFIGURACIONES
	//------------------------------------------------------------
	require_once('menu.php');
	require_once('../config.php');
	header('Content-Type: text/html; charset=UTF-8');
	session_start();
	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio']) or die("problema con la conexi&oacute;n a la base de datos");
	$con->query("SET NAMES 'utf8'");


	//------------------------------------------------------------
	//									CONSULTAS A MYSQL
	//------------------------------------------------------------
	//GET INGRESOS
	$ingresosSearch = mysqli_query($con, "SELECT ing.numProy,  pro.numeroProyecto, pro.nombreProyecto, ing.validado, ing.capT1, ing.capT2, ing.capT3, ing.capT4, ing.capT5, ing.comprobado
			FROM ingresos AS ing JOIN proyectos AS pro ON	ing.numProy = pro.numeroProyecto WHERE ing.validado > 1 ORDER BY pro.numeroProyecto") or die (mysqli_error($con));

	$ingresos = null;
	while($reg = mysqli_fetch_assoc($ingresosSearch)){
		$ingresos[] = $reg;
	}

	//------------------------------------------------------------
	//									ASIGNACIÓN DE VARIABLES
	//------------------------------------------------------------
	//obtenemos los datos de ingresos
	$vacia = ($ingresos == null)? true : false;
	$toPrint = '';
	if ($vacia != true){
		$numProy = $ingresos[0]['numeroProyecto'];			$nombreProy;
		$suma1000 = 0;			$suma2000 = 0; 			$suma3000 = 0;
		$suma4000 = 0;			$suma5000 = 0;			$suma = 0;
		$sumaUltimo = 0;		$sumaUltimo1000 = 0;		$sumaUltimo2000 = 0;
		$sumaUltimo3000 = 0;		$sumaUltimo4000 = 0;		$sumaUltimo5000 = 0;
		$sumaComprobado = 0;		$sumaRestaComprobar = 0;		$sUComprobado = 0;
		$sURestaComprobar = 0;

		$tCap1000 = 0;	//aqui almacenaremos todos los montos para hacer una suma al final de la tabla
		$tCap2000 = 0;		$tCap3000 = 0;		$tCap4000 = 0;
		$tCap5000 = 0;		$tMinistrado = 0;		$tComprobado = 0;
		$tRestaComprobar = 0;		$tPorcentaje = 0;

		$ultimo = end($ingresos);
		$ultimo = $ultimo['numeroProyecto'];
		// print_r( end($ingresos) );

		foreach ($ingresos as $key => $reg) {
			if($numProy == $reg['numeroProyecto'])  #Cuando es igual solo guarda variables y suma el total
			{
					$nombreProy = $reg['nombreProyecto'];
					$suma = $reg['validado'] + $suma;
					$suma1000 = $reg['capT1'] + $suma1000;
					$suma2000 = $reg['capT2'] + $suma2000;
					$suma3000 = $reg['capT3'] + $suma3000;
					$suma4000 = $reg['capT4'] + $suma4000;
					$suma5000 = $reg['capT5'] + $suma5000;
					$sumaComprobado = $reg['comprobado'] + $sumaComprobado;
					$sumaRestaComprobar = $reg['validado'] - $reg['comprobado'] + $sumaRestaComprobar;
			}
			else		#Cuando es diferente escribe las variables guardadas/ suma =0 / registra nuevas variables
			{
					$porcentaje = ($sumaComprobado / $suma) * 100;
					$toPrint .= '<tr>
							<td><a href="compIngresos2.php?codigo='.$numProy.'"> <img src="../Imagen/writeNegro.png" width="25" height="25"></img></a></td>
							<td>'.$numProy.'</td>
							<td>'.$nombreProy.'</td>
							<td>$'.number_format($suma1000, 2,'.',',').'</td>
							<td>$'.number_format($suma2000, 2,'.',',').'</td>
							<td>$'.number_format($suma3000, 2,'.',',').'</td>
							<td>$'.number_format($suma4000, 2,'.',',').'</td>
							<td>$'.number_format($suma5000, 2,'.',',').'</td>
							<td>$'.number_format($suma, 2,'.',',').'</td>
							<td>$'.number_format($sumaComprobado, 2,'.',',').'</td>
							<td>$'.number_format($sumaRestaComprobar, 2,'.',',').'</td>
							<td>%'.number_format($porcentaje, 2,'.',',').'</td>
							</tr>';
					//almacenamos los valores que suman al final
					$tCap1000 = $tCap1000 + $suma1000;
					$tCap2000 = $tCap2000 + $suma2000;
					$tCap3000 = $tCap3000 + $suma3000;
					$tCap4000 = $tCap4000 + $suma4000;
					$tCap5000 = $tCap5000 + $suma5000;
					$tComprobado = $tComprobado + $sumaComprobado;
					//------------------------------------------


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
					$suma = $reg['validado'] + $suma;
					$suma1000 = $reg['capT1'] + $suma1000;
					$suma2000 = $reg['capT2'] + $suma2000;
					$suma3000 = $reg['capT3'] + $suma3000;
					$suma4000 = $reg['capT4'] + $suma4000;
					$suma5000 = $reg['capT5'] + $suma5000;
					$sumaComprobado = $reg['comprobado'] + $sumaComprobado;
					$sumaRestaComprobar = $reg['validado'] - $reg['comprobado'] + $sumaRestaComprobar;
			}

			#Esta rutina aplicara solamente si el proyecto del ingreso es igual a nuestra variables último
			if($ultimo == $reg['numeroProyecto'])
			{
				$sumaUltimo = $reg['validado'] + $sumaUltimo;
				$sumaUltimo1000 = $reg['capT1'] + $sumaUltimo1000;
				$sumaUltimo2000 = $reg['capT2'] + $sumaUltimo2000;
				$sumaUltimo3000 = $reg['capT3'] + $sumaUltimo3000;
				$sumaUltimo4000 = $reg['capT4'] + $sumaUltimo4000;
				$sumaUltimo5000 = $reg['capT5'] + $sumaUltimo5000;
				$sUComprobado = $reg['comprobado'] + $sUComprobado;
				$sURestaComprobar = $reg['validado'] - $reg['comprobado'] + $sURestaComprobar;
			}
		}			//end foreach

		$porcentaje = ($sUComprobado / $sumaUltimo) * 100;
		$toPrint .= '<tr>
					<td><a href="compIngresos2.php?codigo='.$numProy.'"> <img src="../Imagen/writeNegro.png" width="25" height="25"></img></a></td>
					<td>'.$numProy.'</td>
					<td>'.$nombreProy.'</td>
					<td>$'.number_format($sumaUltimo1000, 2,'.',',').'</td>
					<td>$'.number_format($sumaUltimo2000, 2,'.',',').'</td>
					<td>$'.number_format($sumaUltimo3000, 2,'.',',').'</td>
					<td>$'.number_format($sumaUltimo4000, 2,'.',',').'</td>
					<td>$'.number_format($sumaUltimo5000, 2,'.',',').'</td>
					<td>$'.number_format($sumaUltimo, 2,'.',',').'</td>
					<td>$'.number_format($sUComprobado, 2,'.',',').'</td>
					<td>$'.number_format($sURestaComprobar, 2,'.',',').'</td>
					<td>%'.number_format($porcentaje, 2,'.',',').'</td>
			</tr>';


			//almacenamos los valores que suman al final
					$tCap1000 = $tCap1000 + $suma1000;
					$tCap2000 = $tCap2000 + $suma2000;
					$tCap3000 = $tCap3000 + $suma3000;
					$tCap4000 = $tCap4000 + $suma4000;
					$tCap5000 = $tCap5000 + $suma5000;
					$tComprobado = $tComprobado + $sumaComprobado;

					$tMinistrado = $tCap1000 + $tCap2000 + $tCap3000 + $tCap4000 + $tCap5000;
					$tRestaComprobar = $tMinistrado - $tComprobado;
					$tporcentaje = ($tComprobado / $tMinistrado) * 100;
			//------------------------------------------

			$toPrint .= '<tr>
					<td colspan="2">&nbsp;</td>
					<td>Totales:</td>
					<td>$'.number_format($tCap1000, 2,'.',',').'</td>
					<td>$'.number_format($tCap2000, 2,'.',',').'</td>
					<td>$'.number_format($tCap3000, 2,'.',',').'</td>
					<td>$'.number_format($tCap4000, 2,'.',',').'</td>
					<td>$'.number_format($tCap5000, 2,'.',',').'</td>
					<td>$'.number_format($tMinistrado, 2,'.',',').'</td>
					<td>$'.number_format($tComprobado, 2,'.',',').'</td>
					<td>$'.number_format($tRestaComprobar, 2,'.',',').'</td>
					<td>%'.number_format($tporcentaje, 2,'.',',').'</td>
				<tr>';
	} else {
		$toPrint = '<tr><td colspan="12">Lista Vacia</td></tr>';
	}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">

		<title> Comprobación de Ingresos </title>

		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/reportes.css"></link>

		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>		<!-- Efectos del Menú -->
	</head>
	<body>
		<?php
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
				<legend>Selecci&oacute;n de Ingresos por Proyecto</legend>
				<table class="tablaReport" border = "1" >
					<tr>
						<td>Editar</td>						<td>No. de Proyecto </td>
						<td>Nombre del Proyecto </td>						<td>Cap 1000</td>
						<td>Cap 2000</td>						<td>Cap 3000</td>
						<td>Cap 4000</td>						<td>Cap 5000</td>
						<td>Ministrado</td>						<td>Comprobado</td>
						<td>Resta por Comprobar</td>						<td>Porcentaje de Comprobaci&oacute;n</td>
					</tr>
						<?php echo $toPrint; ?>
				</table>
			</fieldset>
		</fieldset>

	</body>
</html>
