<?php 	require_once('menu.php');
		require_once('../config.php');
		header('Content-Type: text/html; charset=UTF-8');
	?>

<html>
	<head>
		<title> Registro de Ingresos Gasto B&aacute;sico</title>

		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/formularios.css"></link>

		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>		<!-- Efectos del Menú -->

		<script type="text/javascript">

		function conMayusculas(field) {					// -- Cambia a Mayusculas
			field.value = field.value.toUpperCase()
		}

		function fncSumar()								// -- Realiza las sumas de capitulos y arroja resultado
		{
			caja=document.forms["form1"].elements;
			var cap2000 = Number(caja["cap2000"].value);
			var cap3000 = Number(caja["cap3000"].value);
			importeTotal=cap2000+cap3000;
			if(!isNaN(importeTotal))
			{
				caja["importeTotal"].value=cap2000+cap3000;
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
			<h2> Registro de Ingresos de Gasto B&aacute;sico </h2>
				<form method="post" action="GBaltaIngresosSube.php" name="form1">
				<table class="tablaForm" border="0">
				   <tr>
						<td colspan="2">Concepto:</td>
						<td colspan="6"><input type="text" name="concepto" onChange="conMayusculas(this)"required></td>
				   </tr>
				   <tr>
						<td colspan="2">Tipo:</td>
						<td colspan="2">
							<select name="tipo" required>
								<option value="INGRESO">Ingreso</option>
								<option value="REINTEGRO">Reintegro</option>
							</select>
						</td>
				   </tr>
				   <tr>
						<td colspan="2">Mes:</td>
							<td colspan="2">
								<select name="mes" required>
									<option value="enero">Enero</option> <option value="Febrero">Febrero</option>
									<option value="Marzo">Marzo</option> <option value="Abril">Abril</option>
									<option value="Mayo">Mayo</option> <option value="Junio">Junio</option>
									<option value="Julio">Julio</option> <option value="Agosto">Agosto</option>
									<option value="Septiembre">Septiembre</option> <option value="Octubre">Octubre</option>
									<option value="Noviembre">Noviembre</option> <option value="Diciembre">Diciembre</option>
								</select>
							</td>
						<td colspan="2">Fecha de Dep&oacute;sito:</td>
						<td colspan="2"><input type="date" name="fechaDeposito" value=”2014-01-01″></td>
				   </tr>
				   <tr>
						<td colspan="2">No. de Autorizaci&oacute;n:</td>
						<td colspan="2"><input type="text" name="noAutorizacion" onkeypress="return justNumbers(event);" required></td>
				   </tr>
				</table>
			</fieldset>
			<br>
			<fieldset id="formulario">
				<legend> Montos </legend>
				<table class="tablaForm" border="0">
				   <tr>
						<td></td>
						<td>Cap2000:</td>
						<td></td>
						<td>Cap3000:</td>
						<td></td>
						<td>Importe Total:</td>
				   </tr>
				   <tr>
						<td>$</td>
						<td><input type="text" name="cap2000" value="0" onKeyUp="fncSumar()" onkeypress="return justNumbers(event);" required></td>
						<td>$</td>
						<td><input type="text" name="cap3000" value="0" onKeyUp="fncSumar()" onkeypress="return justNumbers(event);" required></td>
						<td>$</td>
						<td><input type="text" name="importeTotal" value="0" disabled required></td>
				   </tr>
				   <tr>
					<td colspan="8">
						<input type="submit" value="Enviar">
					</td>
				   </tr>
				</table>
			</fieldset>
		</fieldset>
	</body>
</html>
