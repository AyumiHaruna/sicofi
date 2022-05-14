<?php 	require_once('menu.php');
require_once('../config.php');
header('Content-Type: text/html; charset=UTF-8');
  ?>
<html>
	<head>
		<title> Validaci&oacute;n de Egresos Gasto B&aacute;sico</title>

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
	?>
<!-- IIIIIIIIIIIIIIIIIIIIIIIIIIII 	Inicia la tabla de registros 	IIIIIIIIIIIIIIIIIIIIIIIIIIII -->
		<fieldset id="base">
			<fieldset id="formulario">
				<legend> B&uacute;squeda </legend>
				<form method="post" action="GBvalidaEgresos.php" name="form1">
				<table class="tablaForm">
				    <tr>
						<td>No de Cheque:</td>
						<td>
							<select name="noCheque">
								<?php
									$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
										or die("Problemas con la conexi&oacute;n a la base de datos");
										$con->query("SET NAMES 'utf8'");
										
									$registros = mysqli_query($con, "SELECT noCheque FROM egresosgb ORDER BY noCheque")
									or die(mysqli_error($con));

									echo '<option value="0"> Todos </option>';
									while($reg = mysqli_fetch_array($registros))
									{
										echo '<option value="'.$reg['noCheque'].'">'.$reg['noCheque'].'</option>';
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" value="Buscar" required></td>
					</tr>
				</table>
			</fieldset>
		<br>
		<?php
			if(!isset($_POST['noCheque']))
			{
				$busqueda = 0;
			}

			else
			{
				$busqueda =  $_POST['noCheque'];
			}

			if($busqueda == 0)
			{
				$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
					or die("Problemas con la conexi&oacute;n a la base de datos");

				$registro = mysqli_query($con,"SELECT * FROM egresosgb")
					or die(mysqli_error($con));

				echo '<fieldset id="formulario">
							<legend> Lista de Egresos </legend>
							<table class="tablaReport">
								<tr class="even">
									<td> Validar </td>
									<td> No. de Cheque </td>
									<td> Fecha de Elaboraci&oacute;n </td>
									<td> Nombre </td>
									<td> Concepto </td>
									<td> Importe Total </td>
									<td> Resta por Comprobar </td>
								</tr>';

				while ($reg =  mysqli_fetch_array ($registro))
				{
					echo 		'<tr class="even">
									<td><a href="GBvalidaEgresos2.php?codigo='.$reg['noCheque'].'"> <img src="../Imagen/writeNegro.png" width="25" height="25"></img></a></td>
									<td>'.$reg['noCheque'].'</td>
									<td>'.$reg['fechaElaboracion'].'</td>
									<td>'.$reg['nombre'].'</td>
									<td>'.$reg['concepto'].'</td>
									<td>'.$reg['total'].'</td>
									<td>'.$reg['restaComprobar'].'</td>
								</tr>';
				}
				echo '		</table>';
			}

			else
			{
				$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
					or die("Problemas con la conexi&oacute;n a la base de datos");

				$registro = mysqli_query($con,"SELECT * FROM egresosgb
										WHERE noCheque ='$busqueda'")
					or die(mysqli_error($con));

				echo '<fieldset id="formulario">
							<legend> Lista de Egresos </legend>
							<table class="tablaReport">
								<tr class="even">
									<td> Validar </td>
									<td> No. de Cheque </td>
									<td> Fecha de Elaboraci&oacute;n </td>
									<td> Nombre </td>
									<td> Concepto </td>
									<td> Importe Total </td>
									<td> Resta por Comprobar </td>
								</tr>';

				while ($reg =  mysqli_fetch_array ($registro))
				{
					echo 		'<tr class="even">
									<td><a href="GBvalidaEgresos2.php?codigo='.$reg['noCheque'].'"> <img src="../Imagen/writeNegro.png" width="25" height="25"></img></a></td>
									<td>'.$reg['noCheque'].'</td>
									<td>'.$reg['fechaElaboracion'].'</td>
									<td>'.$reg['nombre'].'</td>
									<td>'.$reg['concepto'].'</td>
									<td>$'.number_format($reg['total'], 2,'.',',').'</td>
									<td>$'.number_format($reg['restaComprobar'], 2,'.',',').'</td>
								</tr>';
				}
				echo '		</table>';
			}
		?>		</fieldset>
				</fieldset>
	</body>
</html>
