<?php
//---------------------------------------------------------------------------------------------
//		llamado de funciones y conexiones
//
//---------------------------------------------------------------------------------------------
	require_once('menu.php');
	require_once('../config.php');
	session_start();
	header('Content-Type: text/html; charset=UTF-8');

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");
?>

<html>
	<head>
		<title> Reporte de Solicitudes de Fondos </title>

		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/reportes.css"></link>

		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>

		<script type="text/javascript">
			//------------------------------------------------------------------------------------
			//
			//		Script para actualizar consultas
			//
			//------------------------------------------------------------------------------------

			$(document).ready(function()
			{
				/* sfCal.php */

				$("#first").change(function(event)
				{
					caja=document.forms["busqueda"].elements;
					var first = (caja["first"].value);
					var second = (caja["second"].value);

					if(first != 0 && second != 0)
					{
						$("#reporte").html("<img src='../imagen/loading.gif' />");
						$("#reporte").load('sfCal.php?first='+first+'&second='+second);
					}
				}
				);

				$("#second").change(function(event)
				{
					caja=document.forms["busqueda"].elements;
					var first = (caja["first"].value);
					var second = (caja["second"].value);

					if(first != 0 && second != 0)
					{
						$("#reporte").html("<img src='../imagen/loading.gif' />");
						$("#reporte").load('sfCal.php?first=first&second=second');
					}
				}
				);
			});
		</script>
	</head>

	<body>
		<!-- IIIIIIIIIIIIIIIIIIIIIIIIIIII   Este cuadro contiene el Menú	IIIIIIIIIIIIIIIIIIIII -->
<?php
//---------------------------------------------------------------------------------------------
//		Bloque de seguridad 'LOGIN'
//
//---------------------------------------------------------------------------------------------

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
		<!-----------------------------------------------------------------------------------------------------
		--
		--		---INICIO DE PROGRAMA--------------------------------------------------------------------------
		--				Formulario (consulta del intervalo a buscar)
		--
		------------------------------------------------------------------------------------------------------->

		<fieldset id="base">
			<fieldset id="formulario">
				<legend>Reporte de Solicitudes de Fondos </legend>

<?php
	//-----Crearemos un arreglo con las Solicitudes de Fondos en Ingresos

	$solFon = mysqli_query($con, "SELECT noSolFon FROM ingresos ORDER BY noSolFon")
			or die(mysqli_error($con));
	$x = 0;
	while($reg = mysqli_fetch_array($solFon))
	{
		if($reg['noSolFon'] != 0)
		{
			$dato[$x] = $reg['noSolFon'];
			$x++;
		}
	}
	//-------------------------------------------------------------------
?>

				<form method="post" action="sf.php" name="busqueda" id="busqueda">
					<table class="tablaReport">
						<tr>
							<td colspan="2">Seleccione el Intervalo de S.F. que desee buscar</td>
						</tr>

						<tr>
							<td> <b>Buscar de la S.F:</b> </td>
							<td>
								<select name="first" id="first">
<?php
								echo '<option value="0"> --- </option>';
								for($x = 0; $x < count($dato); $x++)
								{
									echo '<option value="'.$dato[$x].'">'.$dato[$x].'</option>';
								}
?>
								</select>
							</td>
						</tr>

						<tr>
							<td> <b>A la S.F:</b> </td>
							<td>
								<select name="second" id="second">
<?php
									echo '<option value="0"> --- </option>';
								for($x = 0; $x < count($dato); $x++)
								{
									if(!isset($dato[$x+1]))
									{
										echo '<option value="'.$dato[$x].'" SELECTED>'.$dato[$x].'</option>';
									}
									else
									{
										echo '<option value="'.$dato[$x].'">'.$dato[$x].'</option>';
									}
								}
?>
								</select>
							</td>
						</tr>
					</table>
				</form>
			</fieldset>
			</fieldset>

			<fieldset id="base">
			<fieldset id="reporte">
				*Para visualizar el reporte, favor de seleccionar el intervalo de Solicitudes De Fondos en el men&uacute;
			</fieldset>
		</fieldset>
	</body>
</html>

<?php
	mysqli_close($con);
?>
