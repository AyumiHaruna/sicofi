<?php
//---------------------------------------------------------------------------------------------
//		llamado de funciones y conexiones
//
//---------------------------------------------------------------------------------------------
	require_once('menu.php');
	require_once('../config.php');
	session_start();
	header('Content-Type: text/html; charset=UTF-8');

	$noSolFon =  $_REQUEST['codigo'];

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");

	$ingresos = mysqli_query($con, "SELECT validado, numProy, obs FROM ingresos WHERE noSolFon  =  $noSolFon")
				or die(mysqli_error($con));

	while($reg = mysqli_fetch_array($ingresos))
	{
		$validado = $reg['validado'];
		$numProy = $reg['numProy'];
		$obs = $reg['obs'];
	}

?>

<html>
	<head>
		<title> Lista de Comprobaciones de cap&iacute;tulo </title>

		<link rel="stylesheet" type="text/css" href="../css2/menu.css">
		<link rel="stylesheet" type="text/css" href="../css2/reportes.css">

		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>
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

				<legend>Lista de Comprobaciones S.F. No:  <?php echo $noSolFon ?></legend>

				<table>
					<tr>
						<td>Desea capturar una nueva Caratula?</td>
						<td style="padding: 20px; color:#d50000 !important; border:solid 1px #455a64;">Observaciones:</td>
					</tr>
					<tr>
						<td><?php					echo '<a href="compIngresos4.php?codigo='.$_REQUEST['codigo'].'"> <input type="button" class="button" value="capturar"> </a>';		?></td>
						<td style="padding: 20px; color:#616161; border:solid 1px #455a64;"> <?php echo $obs ?> </td>
					</tr>
				</table>





			</fieldset>

			<fieldset id="formulario">

<?php	echo '<a href="compIngresos2.php?codigo='.$numProy.'"><img src="../imagen/atras.png" width="50" height="50"></img></a>';
	?>
				<table class="tablaReport">
					<tr>
						<td>No. S.F.</td>
						<td>Caratula</td>
						<td>No. Transferencia</td>
						<td>Fecha de Caratula</td>
						<td>Importe de Trans.</td>
						<td>Importe Comprobado</td>
						<td>Imprimir</td>
						<td>Modificar</td>
						<td>Eliminar</td>
					</tr>
<?php
				$comp = mysqli_query($con, "SELECT * FROM comprobacion WHERE noSolFon =  $noSolFon")
					or die(mysqli_error($con));

				$sumaComp = 0;
				while($reg = mysqli_fetch_array($comp))
				{
					echo'
					<tr>
						<td>'.$reg['noSolFon'].'</td>
						<td>'.$reg['caratula'].'</td>
						<td>'.$reg['trans'].'</td>
						<td>'.$reg['fechaAct'].'</td>
						<td>$'.number_format($validado, 2,'.',',').'</td>
						<td>$'.number_format($reg['comprobado'], 2,'.',',').'</td>
						<td><a href="compIngresosImp.php?codigo='.$reg['caratula'].'&codigo2='.$reg['noSolFon'].'" target="_blank"><img src="../imagen/imprimir.png" width="35" height="35"></img></a></td>
						<td><a href="compIngresosMod.php?codigo='.$reg['caratula'].'&codigo2='.$reg['noSolFon'].'"><img src="../imagen/writeNegro.png" width="35" height="35"></img></a></td>
						<td><a href="compIngresosDel.php?codigo='.$reg['caratula'].'&codigo2='.$reg['noSolFon'].'"><img src="../imagen/delete.png" width="35" height="35"></img></a></td>
					</tr>
					';
					$sumaComp = $reg['comprobado'] + $sumaComp;
				}

				$sumaComp = $validado - $sumaComp;
				echo'
					<tr>
						<td colspan="4">&nbsp;</td>
						<td><b>Resta Comprobar:</b></td>
						<td><b>$'.number_format($sumaComp, 2,'.',',').'</b></td>
					</tr>
				';
?>

				</table>
			</fieldset>
		</fieldset>
	</body>
</html>

<?php
	mysqli_close($con);
?>
