<?php 	require_once('menu.php');
		require_once('../config.php');
		session_start();
		header('Content-Type: text/html; charset=UTF-8');
		$noIng = $_GET['codigo'];		?>

<html>
	<head>
		<title>	Editor de Egresos Gasto B&aacute;sico</title>

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
					xmlhttp.open("GET","editaEgresosCalculo.php?q="+str,true);
					xmlhttp.send();
				}
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

		$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
				or die("Problemas con la conexi&oacute;n a la base de datos");
				$con->query("SET NAMES 'utf8'");

		$proyecto = mysqli_query($con, "SELECT * FROM egresosgb
									WHERE noCheque =  $noIng")
			or die(mysqli_error($con));

		while($reg = mysqli_fetch_array($proyecto))
		{
			echo '
				<fieldset id="base">
					<fieldset id="formulario">
						<legend> Modifica Egresos de Gasto B&aacute;sico </legend>
						<form method="post" action="GBeditaEgresosSube.php" name="form1">
							<table class="tablaForm" border="0">
								<tr>
									<td>No. de Cheque:</td>
									<td><input type="text" value="'.$reg['noCheque'].'" name="noCheque" maxlength="10" class="readonly" onkeypress="return justNumbers(event);" readonly></td>
									<td>Fecha de Elaboraci&oacute;n:</td>
									<td><input type="date" value="'.$reg['fechaElaboracion'].'" name="fechaElaboracion"></td>
								</tr>
								<tr>
									<td>Nombre:</td>
									<td colspan="3"><input type="text" value="'.$reg['nombre'].'" name="nombre" onChange="conMayusculas(this)" required></td>
								</tr>
								<tr>
									<td>Concepto:</td>
									<td colspan="3"><input type="text" value="'.$reg['concepto'].'" name="concepto" onChange="conMayusculas(this)" required></td>
								</tr>
							</table>
							<br>
					</fieldset>
					<fieldset id="formulario">
						<legend> Montos </legend>
						<table class="tablaForm">
							<tr>
								<td colspan="2">Capitulo 2000:</td>
								<td colspan="2">Capitulo 3000:</td>
							</tr>
							<tr>
								<td>$</td>
								<td><input type="text" value="'.$reg['cap2000'].'" name="cap2000" onKeyUp="fncSumar()" onkeypress="return justNumbers(event);" value="0" required></td>
								<td>$</td>
								<td><input type="text" value="'.$reg['cap3000'].'" name="cap3000" onKeyUp="fncSumar()" onkeypress="return justNumbers(event);" value="0" requierd></td>
							</tr>
							<tr>
								<td colspan="2">Total:</td>
							</tr>
							<tr>
								<td>$</td>
								<td><input type="text" value="'.$reg['total'].'" name="total" disabled></td>
							</tr>
						</table>
					</fieldset>
					<br>
					<fieldset id="formulario">
						<legend>Observaciones </legend>
						<table class="tablaForm">
							<tr>
								<td><textarea id="area" name="observaciones" rows="4" value="">'.$reg['observaciones'].'</textarea></td>
							</tr>
							<tr>
								<td colspan="2"><input type="submit" value="Enviar"></td>
							</tr>
						</table>
					</fieldset>
				</fieldset>';
		}
		?>
	</body>
</html>
