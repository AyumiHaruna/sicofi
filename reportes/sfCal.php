<?php
	require_once('../config.php');
	session_start();
	header('Content-Type: text/html; charset=UTF-8');

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");


	error_reporting(E_ERROR | E_WARNING | E_PARSE);

	$first = $_GET['first'];
	$second = $_GET['second'];


	echo 'Primero '.$first.'Segundo '.$second;
	$ingresos = mysqli_query($con, "SELECT ing.noSolFon, ing.numProy, ing.tipo, ing.concepto,
									ing.mes, ing.fechaElab, ing.SFcap1000, ing.SFcap2000, ing.SFcap3000, ing.SFcap4000, ing.SFcap5000,
									ing.SFtotal, pro.nombreProyecto
									FROM ingresos AS ing
									INNER JOIN proyectos AS pro
									ON ing.numProy = pro.numeroProyecto
									WHERE noSolFon BETWEEN $first AND $second
									ORDER BY noSolFon")
			or die(mysqli_error($con));

	echo '
		<table class="tablaReport">
			<tr>
				<td>No. S.F.</td>
				<td>No. Proy</td>
				<td>Nombre del Proyecto</td>
				<td>Tipo</td>
				<td>Concepto</td>
				<td>Mes</td>
				<td>Fecha de Elaboraci&oacute;n</td>
				<td>S.F. 1000</td>
				<td>S.F. 2000</td>
				<td>S.F. 3000</td>
				<td>S.F. 4000</td>
				<td>S.F. 5000</td>
				<td>S.F. Total</td>
			</tr>';


	$suma1000 = 0;
	$suma2000 = 0;
	$suma3000 = 0;
	$suma4000 = 0;
	$suma5000 = 0;
	$sumaT = 0;
	while($reg = mysqli_fetch_array($ingresos))
	{
		echo'
			<tr>
				<td>'.$reg['noSolFon'].'</td>
				<td>'.$reg['numProy'].'</td>
				<td>'.utf8_encode($reg['nombreProyecto']).'</td>
				<td>'.$reg['tipo'].'</td>
				<td>'.$reg['concepto'].'</td>
				<td>'.$reg['mes'].'</td>
				<td>'.$reg['fechaElab'].'</td>
				<td>$'.number_format($reg['SFcap1000'],2,'.',',').'</td>
				<td>$'.number_format($reg['SFcap2000'],2,'.',',').'</td>
				<td>$'.number_format($reg['SFcap3000'],2,'.',',').'</td>
				<td>$'.number_format($reg['SFcap4000'],2,'.',',').'</td>
				<td>$'.number_format($reg['SFcap5000'],2,'.',',').'</td>
				<td>$'.number_format($reg['SFtotal'],2,'.',',').'</td>
			</tr>
		';

		$suma1000 = $reg['SFcap1000'] + $suma1000;
		$suma2000 = $reg['SFcap2000'] + $suma2000;
		$suma3000 = $reg['SFcap3000'] + $suma3000;
		$suma4000 = $reg['SFcap4000'] + $suma4000;
		$suma5000 = $reg['SFcap5000'] + $suma5000;
		$sumaT = $reg['SFtotal'] + $sumaT;
	}
	echo'
			<tr>
				<td colspan="6"> &nbsp; </td>
				<td><b>Total:</b></td>
				<td><b>$'.number_format($suma1000,2,'.',',').'</b></td>
				<td><b>$'.number_format($suma2000,2,'.',',').'</b></td>
				<td><b>$'.number_format($suma3000,2,'.',',').'</b></td>
				<td><b>$'.number_format($suma4000,2,'.',',').'</b></td>
				<td><b>$'.number_format($suma5000,2,'.',',').'</b></td>
				<td><b>$'.number_format($sumaT,2,'.',',').'</b></td>
			</tr>
		</table>
	';

	mysqli_close($con);
?>
