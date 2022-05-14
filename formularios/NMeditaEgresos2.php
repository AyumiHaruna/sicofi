<?php 	require_once('menu.php');
		require_once('../config.php');
		session_start();
		header('Content-Type: text/html; charset=UTF-8');
		$noEgr = $_GET['codigo'];		?>

<html>
	<head>
		<title>	Editor de Egresos Gasto N&oacute;mina</title>

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

		$proyecto = mysqli_query($con, "SELECT * FROM egresosnm
									WHERE noCheque =  $noEgr")			or die(mysqli_error($con));

		while($reg = mysqli_fetch_array($proyecto))
		{
			echo '
				<fieldset id="base">
					<fieldset id="formulario">
						<legend>Modifica Egresos de N&oacute;mina</legend>
						<form method="post" action="NMeditaEgresosSube.php" name="form1">
							<table class="tablaForm" border="0">
								<tr>
									<td>No. de Cheque:</td>
									<td><input type="text" value="'.$reg['noCheque'].'" name="noCheque"  class="readonly" maxlength="10" onkeypress="return justNumbers(event);" readonly></td>
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
								<td colspan="2">Capitulo 1000:</td>
							</tr>
							<tr>
								<td>$</td>
								<td><input type="text" value="'.$reg['cap1000'].'" name="cap1000"  onkeypress="return justNumbers(event);" required></td>
							</tr>
						</table>
					</fieldset>
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
