<?php 	require_once('menu.php');
		require_once('../config.php');
		session_start();
		$noIng = $_GET['codigo'];
		header('Content-Type: text/html; charset=UTF-8');
		?>

<html>
	<head>
		<title>	Editor de Ingresos </title>
		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/reportes.css"></link>
		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>		<!-- Efectos del Menú -->
		<script type="text/javascript">

		function conMayusculas(field) {					// -- Cambia a Mayusculas
			field.value = field.value.toUpperCase()
		}

		function fncSumar()								// -- Realiza las sumas de capitulos y arroja resultado
		{
			caja=document.forms["form1"].elements;
			var cap1000 = Number(caja["cap1000"].value);
			var cap2000 = Number(caja["cap2000"].value);
			var cap3000 = Number(caja["cap3000"].value);
			var cap4000 = Number(caja["cap4000"].value);
			var cap5000 = Number(caja["cap5000"].value);
			importeTotal=cap1000+cap2000+cap3000+cap4000+cap5000;
			if(!isNaN(importeTotal))
			{
				caja["importeTotal"].value=cap1000+cap2000+cap3000+cap4000+cap5000;
			}
		}

			function justNumbers(e)
        {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46))
        return true;

        return /\d/.test(String.fromCharCode(keynum));
        }

		</script>
	</head>

	<body>
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

//Inicia Formulario de Editar Proyectos 2

		$nombre = $_REQUEST['codigo'];

			$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
				or die("Problemas con la conexi&oacute;n a la base de datos");
				$con->query("SET NAMES 'utf8'");

			$ingresos = mysqli_query($con, "SELECT * FROM ingresos
													WHERE numProy = '$_REQUEST[codigo]' AND tipo = 'INGRESO'
													ORDER BY no")
						or die(mysqli_error($con));

			$proyectos = mysqli_query($con, "SELECT * FROM proyectos
														WHERE numeroProyecto = '$_REQUEST[codigo]' ")
						or die(mysqli_query($con));

				while ($reg = mysqli_fetch_array ($proyectos))
				{
					$Bproy =  $reg['nombreProyecto'];
					$BnumProy =  $reg['numeroProyecto'];
				}


			echo '<fieldset id="base">
					<fieldset id="formulario">
						<legend>'.$BnumProy.'  -  '. $Bproy .'</legend>';
			echo '<table class="tablaReport" border="1">
					<tr>
						<td>Editar</td>
						<td>No</td>
						<td>Concepto</td>
						<td>No. Solicitud de Fondos</td>
						<td>Fecha de Elaboraci&oacute;n</td>
						<td>Cap&iacute;tulo 1000</td>
						<td>Cap&iacute;tulo 2000</td>
						<td>Cap&iacute;tulo 3000</td>
						<td>Cap&iacute;tulo 4000</td>
						<td>Cap&iacute;tulo 5000</td>
						<td>Importe Total</td>
						</tr>';


			while ($reg = mysqli_fetch_array ($ingresos))
			{
				echo '<tr>';
				echo'<td><a href="editaIngresos3.php?codigo='.$reg['no'].'"> <img src="../Imagen/writeNegro.png" width="25" height="25"></img></a></td>';
				echo   '<td>'.$reg['no'].'</td>
						<td>'. $reg['concepto'].'</td>
						<td>'.$reg['noSolFon'].'</td>
						<td>'.$reg['fechaElab'].'</td>
						<td>$'.number_format($reg['SFcap1000'], 2,'.',',').'</td>
						<td>$'.number_format($reg['SFcap2000'], 2,'.',',').'</td>
						<td>$'.number_format($reg['SFcap3000'], 2,'.',',').'</td>
						<td>$'.number_format($reg['SFcap4000'], 2,'.',',').'</td>
						<td>$'.number_format($reg['SFcap5000'], 2,'.',',').'</td>
						<td>$'.number_format($reg['SFtotal'], 2,'.',',').'</td>
					</tr>';
			}
			echo '</table>';

			mysqli_close($con);
		?>
	</body>
</html>
