<?php 	require_once('menu.php');
require_once('../config.php');
header('Content-Type: text/html; charset=UTF-8');
 ?>
<html>
	<head>
		<title> Cancelaci&oacute;n de Egresos </title>

		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/reportes.css"></link>

		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>		<!-- Efectos del Menú -->

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
				<legend> Cancelaciones </legend>
				<form method="post" action="cancelaEgresos.php" name="form1">
				<table class="busqueda">
				    <tr>
						<td colspan ="3">Buscar No. de Cheque:</td>
						<td>
							<select name="noCheque">
								<?php
									$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
										or die("Problemas con la conexi&oacute;n a la base de datos");
										$con->query("SET NAMES 'utf8'");
									$registros = mysqli_query($con, "SELECT noCheque FROM egresos ORDER BY noCheque")
									or die(mysqli_error($con));

									while($reg = mysqli_fetch_array($registros))
									{
										echo '<option value="'.$reg['noCheque'].'">'.$reg['noCheque'].'</option>';
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="3"><input type="submit" value="Buscar" required></td>
					</tr>
				</table>
			</fieldset>
			<br>
			<fieldset id="formulario">
				<?php
					if(!isset($_POST['noCheque']))
					{
						$busqueda = 0;
						echo '<b> Por favor realice su B&uacute;squeda </b>';
					}

					else
					{
						$busqueda =  $_POST['noCheque'];
						$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
							or die("Problemas con la conexi&oacute;n a la base de datos");

						$registro = mysqli_query($con,"SELECT * FROM egresos
												WHERE noCheque ='$busqueda'")
							or die(mysqli_error($con));

						echo '		<table class="tablaReport">
										<tr class="even">
											<td> Cancelar </td>
											<td> No. de Cheque </td>
											<td> Fecha de Elaboraci&oacute;n </td>
											<td> Nombre </td>
											<td> Concepto </td>
											<td> Importe Total </td>
											<td> Resta por Comprobar </td>
											<td> Nombre del Proyecto </td>
										</tr>';

						while ($reg =  mysqli_fetch_array ($registro))
						{
							echo 		'<tr class="even">
											<td><a href="cancelaEgresosSube.php?codigo='.$reg['noCheque'].'"'?>onClick="alert(' Seguro que deseas cancelar el Cheque? ')"<?php echo '> <img src="../Imagen/cancel.png" width="25" height="25"></img></a></td>
											<td>'.$reg['noCheque'].'</td>
											<td>'.$reg['fechaElaboracion'].'</td>
											<td>'.$reg['nombre'].'</td>
											<td>'.$reg['concepto'].'</td>
											<td>'.$reg['importeTotal'].'</td>
											<td>'.$reg['restaComprobar'].'</td>
											<td>'.$reg['nombreProyecto'].'</td>
										</tr>';
						}
						echo '		</table>';
					}
				?>
			</fieldset>
		</fieldset>
	</body>
</html>
