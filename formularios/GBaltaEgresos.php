<!DOCTYPE html5>
<?php 	require_once('menu.php');
require_once('../config.php');
header('Content-Type: text/html; charset=UTF-8');
?>
<html>
	<head>
		<title>Registro de Egresos Gasto B&aacute;sico</title>

		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/formularios.css"></link>

		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>		<!-- Efectos del Menú -->

		<script type="text/javascript">

		function conMayusculas(field)
		{					// -- Cambia a Mayusculas
			field.value = field.value.toUpperCase()
		}

		function justNumbers(e)
        {
			var keynum = window.event ? window.event.keyCode : e.which;
			if ((keynum == 8) || (keynum == 46))
			return true;

			return /\d/.test(String.fromCharCode(keynum));
        }

		function fncSumar()								// -- Realiza las sumas de capitulos y arroja resultado
		{
			caja=document.forms["form1"].elements;
			var cap2000 = Number(caja["cap2000"].value);
			var cap3000 = Number(caja["cap3000"].value);
			total=cap2000+cap3000;
			if(!isNaN(total))
			{
				caja["total"].value=cap2000+cap3000;
			}
		}

		function showNum(str)
		{
				if (str == "") {
					document.getElementById("txtHint").innerHTML = "";
					return;
				} else {
					if (window.XMLHttpRequest) {
						// code for IE7+, Firefox, Chrome, Opera, Safari
						xmlhttp = new XMLHttpRequest();
					} else {
						// code for IE6, IE5
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
						}
					}
					xmlhttp.open("GET","altaEgresosCalculo.php?q="+str,true);
					xmlhttp.send();
				}
			}

		</script>
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
	?>
<!-- IIIIIIIIIIIIIIIIIIIIIIIIIIII 	Aqui se inicia el formulario  IIIIIIIIIIIIIIIIIIIIIIIIIIII -->

		<fieldset id="base">
			<fieldset id="formulario">
				<legend> Registro de Egresos Gasto B&aacute;sico </legend>
				<form method="post" action="GBaltaEgresosSube.php" name="form1">

				<table class="tablaForm" border="0">
				    <tr>
						<td colspan="2">No. de Cheque:</td>
						<td><input type="text" name="nocheque" maxlength="10" onkeypress="return justNumbers(event);" required></td>

						<td colspan="2">&Uacute;litmo cheque registrado:</td>
						<td><?php
								$dat1 = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
									or die("Problemas con la conexi&oacute;n a la base de datos");
									$dat1->query("SET NAMES 'utf8'");
								$datos1 = mysqli_query($dat1, "SELECT noCheque FROM egresosgb
														ORDER BY noCheque DESC LIMIT 1")
									or die(mysqli_error($dat1));
								while($reg = mysqli_fetch_array($datos1))
								{
									$dato = $reg['noCheque'];
								}
								echo '<input type="text" name="ultimo" value="'.$dato.'" disabled>';

								mysqli_close($dat1);
							?>
						</td>
												<td>Fecha de Elaboraci&oacute;n:</td>

						<td><input type="date" name="fechaElaboracion"></td>
					</tr>
					<tr>
						<td colspan="2">Nombre:</td>
						<td colspan="6"><input type="text" name="nombre" onChange="conMayusculas(this)" required></td>
					</tr>
					<tr>
						<td colspan="2">Concepto:</td>
						<td colspan="6"><input type="text" name="concepto" onChange="conMayusculas(this)" required></td>
					</tr>
				</table>

			<br>
			<fieldset id="formulario">
				<legend> Montos </legend>
				<table class="tablaForm">
					<tr>
						<td colspan="2">Capitulo 2000:</td>
						<td colspan="2">Capitulo 3000:</td>
						<td colspan="2">Total:</td>
					</tr>
					<tr>
						<td>$</td>
						<td><input type="text" name="cap2000" onkeypress="return justNumbers(event);" onKeyUp="fncSumar()" value="0" required></td>
						<td>$</td>
						<td><input type="text" name="cap3000" onkeypress="return justNumbers(event);" onKeyUp="fncSumar()" value="0" required></td>
						<td>$</td>
						<td><input type="text" name="total" readonly> </td>
					</tr>
					<tr>	<td> &nbsp; </td>		</tr>

		<?php
		//	---------------------  Aquí haremos las búsquedas correspondientes para el calculo de la cantidad Disponible ---------------
			$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
				or die("Problemas con la conexi&oacute;n a la base de datos");
				$con->query("SET NAMES 'utf8'");

			$ingresosgb = mysqli_query($con, "SELECT total FROM ingresosgb")
				or die(mysqli_error($con));

			$egresosgb = mysqli_query($con, "SELECT total  FROM egresosgb")
				or die(mysqli_query($con));
			//	---sumaremos lo que hay en ingresos---
			$ingTotal = 0;
			while($ing = mysqli_fetch_array($ingresosgb))
			{
				$ingTotal  = $ing['total']  + $ingTotal;
			}
			//	---sumaermos lo que hay en egresos---
			$egrTotal = 0;
			while($egr = mysqli_fetch_array($egresosgb))
			{
				$egrTotal = $egr['total']  + $egrTotal;
			}
			$total = $ingTotal - $egrTotal;
			mysqli_close($con);


		echo	'<tr>
						<td colspan="6"> Actualmente hay Disponible: <font color="blue">$'.number_format($total,2,'.',',').'</font></td>
					</tr>';
		?>
				</table>
			</fieldset>
			<br>
			<fieldset id="formulario">
				<legend>Observaciones </legend>
				<table class="tablaForm">
						<td><textarea id="area" name="observaciones" rows="4" value=""></textarea></td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" value="Enviar"></td>
					</tr>

				</table>
			</fieldset>
		</fieldset>
	</body>
</html>
