<?php 	require_once('menu.php');
require_once('../config.php');
header('Content-Type: text/html; charset=UTF-8');
?>
<html>
	<head>
		<title> Registro de Gasto básico y Nomina </title>

		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/formularios.css"></link>
		<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="../js/script.js"></script>		<!-- Efectos del Menú -->
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
			<h2> Captura  de Gasto básico / Nómina </h2>
			<fieldset id="datos">
				<legend> Datos Generales </legend>
				<form method="post" action="altaGbNmSube.php" name="form1">
				<table class="tablaForm" border="0">
				   <tr>
						<td colspan="2">Concepto:</td>
						<td colspan="6"><input type="text" name="concepto" onChange="conMayusculas(this)"required></td>
				   </tr>
				   <tr>
						<td colspan="2">Tipo:</td>
						<td colspan="2">
							<input type="text" value="INGRESO" name="tipo" readonly>
						</td>
				   </tr>
				   <tr>
						<td colspan="2">Mes:</td>
							<td colspan="2">
								<select name="mes" required>
									<option value="ENERO">Enero</option> <option value="FEBRERO">Febrero</option>
									<option value="MARZO">Marzo</option> <option value="ABRIL">Abril</option>
									<option value="MAYO">Mayo</option> <option value="JUNIO">Junio</option>
									<option value="JULIO">Julio</option> <option value="AGOSTO">Agosto</option>
									<option value="SEPTIEMBRE">Septiembre</option> <option value="OCTUBRE">Octubre</option>
									<option value="NOVIEMBRE">Noviembre</option> <option value="DICIEMBRE">Diciembre</option>
								</select>
							</td>
						<td colspan="1">Fecha de Dep&oacute;sito:</td>
						<td colspan="1"><input type="date" name="fechaDep1" value="<?php echo date('Y-m-d');?>"></td>
				   </tr>
				   <tr>
						<td colspan="2">No. de Autorizaci&oacute;n:</td>
						<td colspan="2"><input type="text" name="noAut1" onkeypress="return justNumbers(event);" required></td>
						<td>Tipo de Operaci&oacute;n:</td>
						<td>
							<select name="operacion">
								<option value="INVERSION"> Inversi&oacute;n </option>
								<option value="PROYECTOS" SELECTED> Proyectos </option>
								<option value="TERCEROS"> Terceros </option>
							</select>
						</td>
						<td></td>
						<td></td>
				   </tr>
				   <tr>
						<td colspan="6">Nombre del Proyecto:</td>
						<td></td>
						<td></td>
				   </tr>
				   <tr>
						<td colspan="8">
							<select name="numProy">
								<?php
										$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
											or die("Problemas con la conexi&oacute;n a la base de datos");
											$con->query("SET NAMES 'utf8'");

										$registros = mysqli_query($con, "SELECT nombreProyecto, numeroProyecto FROM proyectos WHERE tipo != 0 ORDER BY numeroProyecto") or die(mysqli_error($con));
										while($reg = mysqli_fetch_array($registros))
										{
											echo '<option value="'.$reg['numeroProyecto'].'">'.$reg['numeroProyecto'].' - '.$reg['nombreProyecto'].'</option>';
										}
									?>
							</select>
						</td>
				   </tr>
				</table>
			</fieldset>
		</fieldset><br>

		<fieldset id="formulario">
			<br>
			<fieldset id="montos">
				<legend> Montos </legend>
				<table class="tablaForm" border="0">
				   <tr>
						<td></td>
						<td>Cap1000:</td>
						<td></td>
						<td>Cap2000:</td>
						<td></td>
						<td>Cap3000:</td>
						<td></td>
						<td>Cap4000:</td>
						<td></td>
						<td>Cap5000:</td>
						<td></td>
						<td>Importe Total:</td>
				   </tr>
				   <tr>
						<td>$</td>
						<td><input type="text" name="cap1000" value="0" onKeyUp="fncSumar()" onkeypress="return justNumbers(event);" required></td>
						<td>$</td>
						<td><input type="text" name="cap2000" value="0" onKeyUp="fncSumar()" onkeypress="return justNumbers(event);" required></td>
						<td>$</td>
						<td><input type="text" name="cap3000" value="0" onKeyUp="fncSumar()" onkeypress="return justNumbers(event);" required></td>
						<td>$</td>
						<td><input type="text" name="cap4000" value="0" onKeyUp="fncSumar()" onkeypress="return justNumbers(event);" required></td>
						<td>$</td>
						<td><input type="text" name="cap5000" value="0" onKeyUp="fncSumar()" onkeypress="return justNumbers(event);" required></td>
						<td>$</td>
						<td><input type="text" name="importeTotal" value="0" disabled required></td>
				   </tr>
				   <tr>
					<td colspan="12">
						<input type="submit" value="Enviar">
					</td>
				   </tr>
				</table>
			</fieldset>
		</fieldset>
	</fieldset>
	</body>

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

			$( document ).ready(function() {

			});
	</script>
</html>
