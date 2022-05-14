<?php 	require_once('menu.php');
require_once('../config.php');
header('Content-Type: text/html; charset=UTF-8');
?>

<html>
	<head>
		<title> Impresion de Cheques y Polizas N&oacute;mina </title>

		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/reportes.css"></link>

		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>		<!-- Efectos del Menú -->

		<script type="text/javascript">
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
		//-------------------------------------------------------------------------------
		?>
	<fieldset id="base">
		<fieldset id="formulario">
		<form method="post" action="NMimpresionChePol.php" name="form1">
			<table class="tablaForm">
				<?php
					$dat1 = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
						or die("Problemas con la conexi&oacute;n a la base de datos");
					$dat1->query("SET NAMES 'utf8'");

					$datos1 = mysqli_query($dat1, "SELECT noCheque FROM egresosnm
												ORDER BY noCheque LIMIT 1")
						or die(mysqli_error($dat1));
					while($reg = mysqli_fetch_array($datos1))
						{
							$dato1 = $reg['noCheque'];
						}

					$dat1 = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
						or die("Problemas con la conexi&oacute;n a la base de datos");
					$dat1->query("SET NAMES 'utf8'");

					$datos1 = mysqli_query($dat1, "SELECT noCheque FROM egresosnm
													ORDER BY noCheque DESC LIMIT 1")
						or die(mysqli_error($dat1));
					while($reg = mysqli_fetch_array($datos1))
					{
						$dato2 = $reg['noCheque'];
					}
				?>
				<tr>
					<td>	Buscar del Cheque: </td>
					<td>	<input type="text" name="valor1" <?php echo 'value="'.$dato1.'"'; ?> onkeypress="return justNumbers(event);">	</td>
					<td>	Al Cheque:	</td>
					<td>	<input type="text" name="valor2" <?php echo 'value="'.$dato2.'"'; ?> onkeypress="return justNumbers(event);">	</td>
				</tr>
				<tr>
					<td colspan="4"><input type="submit" value="Buscar" required></td>
				</tr>
				</form>
			</table>
		</fieldset>
		<?php
		//--------------------------------------------------------------------------------
		//--Conexion con el servidor
		$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
					or die("Problemas con la conexi&oacute;n a la base de datos");
					$con->query("SET NAMES 'utf8'");

		if(!isset($_POST['valor1']) && !isset($_POST['valor2']))
		{
			echo '<fieldset id="formulario">
					<legend>Lista de Egresos de N&oacute;mina</legend>
					Por favor realiza tu b&uacute;squeda
				';
		}
		else
		{
		$valor1 = $_POST['valor1'];
		$valor2 = $_POST['valor2'];
			$egresos = mysqli_query($con, "SELECT noCheque, fechaElaboracion, nombre,
												concepto, observaciones, cap1000
												FROM egresosnm
												WHERE noCheque BETWEEN $valor1 AND $valor2
												ORDER BY noCheque")
					or die(mysqli_error($con));

				echo '<fieldset id="formulario">
							<legend>Lista de Egresos de N&oacute;mina</legend>';
				echo '<table class="tablaReport" border="1">
						<tr><td>No. de Cheque</td>
							<td>Fecha de Elaboraci&oacute;n</td>
							<td>Nombre</td>
							<td>Concepto</td>
							<td>ImporteTotal</td>
							</tr>';

				while ($reg = mysqli_fetch_array ($egresos))
				{
					echo '<tr>
							<td>'.$reg['noCheque'].'</td>
							<td>'.$reg['fechaElaboracion'].'</td>
							<td>'.$reg['nombre'].'</td>
							<td>'.$reg['concepto'].'</td>
							<td>$'.$reg['cap1000'].'</td>
						</tr>';
				}

				echo '</table>';
			}
			mysqli_close($con);
			?>
			</fieldset>
		<fieldset id="formulario">
				<legend>Qu&eacute; Cheques deseas imprimir?</legend>
				<form method="post" action="NMgeneraChePol.php" name="form2">
				<table class="tablaForm">
					<tr>
						<td>Imprimir del Cheque:</td>
						<td> <input type="text" name="cheque1" <?php echo 'value="'.$dato1.'"'; ?> onkeypress="return justNumbers(event);">	</td>
						<td> Al Cheque:</td>
						<td> <input type="text" name="cheque2" <?php echo 'value="'.$dato2.'"'; ?> onkeypress="return justNumbers(event);">	</td>
					</tr>
					<tr>
						<td colspan="4"><input type="submit" value="Imprimir"></td>
					</tr>
					</form>
				</table>
			</fieldset>
		</fieldset>
	</body>
</html>
