<?php 	require_once('menu.php');
		require_once('../config.php');
		session_start();
		header('Content-Type: text/html; charset=UTF-8');
		$no = $_GET['codigo'];		?>

<html>
	<head>
		<title>	Editor de Ingresos </title>

		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/formularios.css"></link>

		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>		<!-- Efectos del Menú -->
		<script type="text/javascript">

		function conMayusculas(field) {					// -- Cambia a Mayusculas
			field.value = field.value.toUpperCase()
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

		$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
				or die("Problemas con la conexi&oacute;n a la base de datos");
				$con->query("SET NAMES 'utf8'");

		$proyecto = mysqli_query($con, "SELECT * FROM ingresosnm
									WHERE no =  $no")
			or die(mysqli_error($con));

		while($reg = mysqli_fetch_array($proyecto))
		{
			echo '
				<fieldset id="base">
					<fieldset id="formulario">
						<legend> Modifica Ingresos de N&oacute;mina </legend>
						<form method="post" action="NMeditaIngresosSube.php" name="form1">
						<table class="tablaForm" border="0">
						   <tr>
								<td colspan="2">Tipo:</td>
								<td colspan="2">
									<select name="tipo" required>';
										if($reg['tipo'] == 'ingreso')
										{
											echo '<option value="ingreso" selected>Ingreso</option>
												<option value="reintegro">Reintegro</option>';
										}
										else
										{
											echo '<option value="ingreso">Ingreso</option>
												<option value="reintegro" selected>Reintegro</option>';
										}
			echo '					</select>
								</td>
								<td colspan="2">  No. de Ingreso </td>
								<td colspan="2"> <input type="text" name="no" class="readonly" value="'.$reg['no'].'" readonly> </td>
						   </tr>
						   <tr>
								<td colspan="2">Mes:</td>
									<td colspan="2">
										<select name="mes" required>';
											//variable para la comparacion del selected
											$mesesArray[0] = 'ENERO';	$mesesArray[1] = 'FEBRERO';	$mesesArray[2] = 'MARZO';	$mesesArray[3] = 'ABRIL';
											$mesesArray[4] = 'MAYO';	$mesesArray[5] = 'JUNIO';	$mesesArray[6] = 'JULIO';	$mesesArray[7] = 'AGOSTO';
											$mesesArray[8] = 'SEPTIEMBRE';	$mesesArray[9] = 'OCTUBRE';	$mesesArray[10] = 'NOVIEMBRE';	$mesesArray[11] = 'DICIEMBRE';
											$i = 0;
											for($i==0; $i<12; $i++)
											{
												if($mesesArray[$i] == $reg['mes'])
												{
													echo '<option value="'.$mesesArray[$i].'" selected>'.$mesesArray[$i].'</option>';
												}
												else
												{
													echo '<option value="'.$mesesArray[$i].'">'.$mesesArray[$i].'</option>';
												}
											}
			echo '					</select>
									</td>
								<td colspan="2">Fecha de Dep&oacute;sito:</td>
								<td colspan="2"><input type="date" value="'.$reg['fechaDeposito'].'" name="fechaDeposito"></td>
						   </tr>
						   <tr>
								<td colspan="2">No. de Autroizaci&oacute;n:</td>
								<td colspan="2"><input type="text" value="'.$reg['noAutorizacion'].'" name="noAutorizacion" onkeypress="return justNumbers(event);" required></td>
								<td colspan="2">No. de Quincena:</td>
								<td colspan="2"><input type="text" value="'.$reg['quincena'].'" name="quincena" onkeypress="return justNumbers(event);" required></td>
						   </tr>
						   <tr>
								<td> Concepto </td>
						   </tr>
						   <tr>
								<td colspan="8"> <input type="text" name="concepto" value="'.$reg['concepto'].'"  onChange="conMayusculas(this)"required> </td>
						   </tr>

						</table>
					</fieldset>
					<fieldset id="formulario">
						<legend> Montos </legend>
						<table class="tablaForm" border="0">
						   <tr>
								<td colspan="2">Importe Total (Cap1000):   $</td>
								<td colspan="6"><input type="text" value="'.$reg['cap1000'].'" name="cap1000" onkeypress="return justNumbers(event);" required></td>
						   </tr>

						   <tr>
							<td colspan="8">
								<input type="submit" value="Enviar">
							</td>
						   </tr>
						   </form>
						</table>
					</fieldset>
				</fieldset>
			';
		}
		?>
	</body>
</html>
