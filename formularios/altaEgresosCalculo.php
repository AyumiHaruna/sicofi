<!DOCTYPE html>
<?php 	require_once('../config.php');
		session_start();?>
<html>
	<head>
		<style>
			.tabla {
				margin:0px;padding:0px;
				width:100%;
				box-shadow: 10px 10px 5px #888888;
				border:1px solid #0000bf;				
			}.tabla table{
				border-collapse: collapse;
					border-spacing: 0;
				width:100%;
				height:100%;
				margin:0px;padding:0px;
			}.tabla tr:nth-child(odd){ background-color:#cbbafc; }
			.tabla tr:nth-child(even)    { background-color:#ffffff; }.tabla td{
			vertical-align:middle;
				border:1px solid #0000bf;
				border-width:0px 1px 1px 0px;
				text-align:right;
				padding:6px;
				font-size:10px;
				font-family:Helvetica;
				font-weight:normal;
				color:#000000;
			}
			.tabla tr:first-child td{


				background-color:#7597ff;
				border:0px solid #0000bf;
				text-align:center;
				border-width:0px 0px 1px 1px;
				font-size:12x;
				font-family:Helvetica;
				font-weight:bold;
				color:#ffffff;
			}
			
		</style>
	</head>
	<body>

		<?php
			$q = intval($_GET['q']);
	// HACEMOS LAS BUSQUEDAS CORRESPONDIENTES PARA GENERAR LO DISPONIBLE DEL PROYECTO
			$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
				or die("Problemas con la conexi&oacute;n a la base de datos");

			/*mysqli_select_db($con,"ajax_demo");
			$sql="SELECT * FROM user WHERE id = '".$q."'";
			$result = mysqli_query($con,$sql);*/
			
	#IIIIIIIIIIIIIIIIIIII	Búsqueda de "Lista de Proyectos" 	IIIIIIIIIIIIIIIIIIII
			$proyectos = mysqli_query($con, "SELECT nombreProyecto, numeroProyecto
											FROM proyectos
											WHERE numeroProyecto = '$q'")			//REVISAR POSIBLE FALLA EN LA ESTRUCTURA
				or die(mysqli_error($con));		

	#IIIIIIIIIIIIIIIIIIII	Búsqueda de "Lista de Ingresos"	IIIIIIIIIIIIIIIIIIII
			$ingresos = mysqli_query($con, "SELECT ing.numProy, ing.validado, ing.capT1, ing.capT2, ing.capT3,
											pro.numeroProyecto
											FROM ingresos AS ing
											RIGHT JOIN proyectos AS pro
												ON	ing.numProy = pro.numeroProyecto
											WHERE ing.numProy = '$q'")	
				or die(mysqli_error($con));
				
	#IIIIIIIIIIIIIIIIIIII	"Búsqueda Lísta de Egresos"	IIIIIIIIIIIIIIIIIIII
			$egresos =  mysqli_query($con, "SELECT egr.nombreProyecto, egr.importeTotal, egr.cap1000, egr.cap2000, egr.cap3000,
											pro.numeroProyecto
											FROM egresos AS egr
											RIGHT JOIN proyectos AS pro
												ON egr.nombreProyecto = pro.nombreProyecto
											WHERE numeroProyecto = '$q'")	
				or die(mysqli_error($con));
				
	
	//---------- ASINGAMOS LOS VALORES DEL PROYECTO AL ARREGLO --------------
			while($reg = mysqli_fetch_array ($proyectos))
			{
				$tablaArreglo[0] = $reg['numeroProyecto'];
				$tablaArreglo[1] = utf8_encode($reg['nombreProyecto']);
			}
	
	//-----------	HACEMOS LA SUMATORIA DE TODOS LOS RESULTADOS DE INGRESOS --------------
			$ingSuma1000 = 0;
			$ingSuma23000 = 0;
			while($reg = mysqli_fetch_array($ingresos))
			{
				$ingSuma1000 = $reg['capT1'] + $ingSuma1000;
				$ingSuma23000 = $reg['capT2'] + $reg['capT3'] +$ingSuma23000;
			}
			
	//-----------	HACEMOS LA SUMATORIA DE TODOS LOS RESULTADOS DE EGRESOS ------------
	
			$egrSuma1000 = 0;
			$egrSuma23000 = 0;
			while($reg = mysqli_fetch_array($egresos))
			{
				$egrSuma1000 = $reg['cap1000'] + $egrSuma1000;
				$egrSuma23000 = $reg['cap2000'] + $reg['cap3000'] + $egrSuma23000;
			}
			
	//-----------	REALIZAMOS LA DIFERENCIA ENTRE LOS INGRESOS Y LOS EGRESOS ----------
	
			$tablaArreglo[2] = $ingSuma1000 - $egrSuma1000;
			$tablaArreglo[3] = $ingSuma23000 - $egrSuma23000;
	//----------------------------------------------------------------------		
			//ESCRIBE LOS RESULTADOS DEL ARREGLO 
			echo '<table class="tabla">
			<tr>
				<td>No. de Proyecto</td>
				<td>Nombre del Proyecto</td>
				<td>Disponible Cap1000</td>
				<td>Disponible 2000 y 3000</td>
			</tr>
			<tr>
				<td>'. $tablaArreglo[0] .'</td>
				<td>'. $tablaArreglo[1].'</td>';
				if($tablaArreglo[2] < 0 || $tablaArreglo[2] == 0)
				{
					echo '<td><font color="red">'. number_format($tablaArreglo[2], 2,'.',',').'</font></td>';
				}
				else
				{
					echo '<td>'. number_format($tablaArreglo[2], 2,'.',',') .'</td>';
				}
				
				if($tablaArreglo[3] < 0 || $tablaArreglo[3] == 0)
				{
					echo '<td><font color="red">'.number_format($tablaArreglo[3], 2,'.',',') .'</font></td>';
				}
				else
				{
					echo '<td>'. number_format($tablaArreglo[3], 2,'.',',') .'</td>';
				}
				
	echo	'</tr></table>';
			mysqli_close($con);
		?>
	</body>
</html>