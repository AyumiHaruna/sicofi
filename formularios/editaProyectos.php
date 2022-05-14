<?php 	require_once('menu.php');
		require_once('../config.php');
		header('Content-Type: text/html; charset=UTF-8');
	?>

<html>
	<head>
		<title>	Editor de Proyectos </title>

		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/reportes.css"></link>

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

//inicia busqueda de proyectos

		$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
			or die("problemas con la conexi&oacute;n a la base de datos");
			$con->query("SET NAMES 'utf8'");

		$busqueda = mysqli_query($con, "SELECT numeroProyecto, nombreProyecto FROM proyectos")
			or die(mysqli_error($con));

		echo '
		<fieldset id="base">
		<fieldset id="formulario">
			<legend>Busqueda de Proyecto</legend>
				<form method="post" action="editaProyectos.php" name="form1">
					<table class="tablaForm">
						<tr>
							<td> Buscar el Proyecto: </td>
							<td>
								<select name="val1">
									<option value="0">Todos</option>';
									while ($reg = mysqli_fetch_array ($busqueda))
									{
										echo '<option value="'.$reg['numeroProyecto'].'">'.$reg['numeroProyecto'].' -- '.$reg['nombreProyecto'].'</option>';
									}
			echo'				</select>
							</td>
						</tr>

						<tr>
							<td colspan="1"><input type="submit" value="Buscar" required></td>
						</tr>
					</table>
				</form>
			</fieldset>
		<br>
		';

		if(!isset($_POST['val1']))
		{
			$proyecto = mysqli_query($con, "SELECT * FROM proyectos ORDER BY numeroProyecto")
				or die(mysqli_error($con));
		}

		else
		{
			if($_POST['val1'] == 0)
			{
				$proyecto = mysqli_query($con, "SELECT * FROM proyectos ORDER BY numeroProyecto")
					or die(mysqli_error($con));
			}

			else
			{
				$val1 = $_POST['val1'];
				$proyecto = mysqli_query($con, "SELECT * FROM proyectos WHERE numeroProyecto = $val1")
					or die(mysqli_error($con));
			}
		}

		echo '
					<fieldset id="formulario">
						<table class="tablaReport" border="0">
							<tr>
								<td>Editar</td>
								<td>No. de proyecto</td>
								<td>Nombre de proyecto</td>
								<td>Total Autorizado</td>
							<tr>';

		while($reg = mysqli_fetch_array ($proyecto))
		{
			echo '

							<tr>
								<td><a href="editaProyectos2.php?codigo='.$reg['numeroProyecto'].'"><img src="../Imagen/writeNegro.png" width="25" height="25"></img></a></td>
								<td>'.$reg['numeroProyecto'].'</td>
								<td>'.$reg['nombreProyecto'].'</td>
								<td>$'.number_format($reg['totalAutorizado'], 2,'.',',').'</td>
							</tr>

			';
		}

			echo '		</table>
				</fieldset>';
	mysqli_close($con);
	?>
	</body>
</html>
