<?php 	require_once('../config.php');
require_once('menu.php');
header('Content-Type: text/html; charset=UTF-8');
?>
<html>
	<head>
		<title> Diferencia Ingresos y Egresos 1000, 2000 y 3000</title>
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

			$miUsuario = $_SESSION['id'];

#--------------------------------------Busquedas-------------------------------------
			#HACEMOS LAS BÚSQUEDAS CORRESPONDIENTES PARA GENERAR LOS REPORTES
#IIIIIIIIIIIIIIIIIIII	Conexión	IIIIIIIIIIIIIIIIIIII
			$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
				or die("Problemas con la conexi&oacute;n a la base de datos");
				$con->query("SET NAMES 'utf8'");

			$proyecto = mysqli_query($con, "SELECT * FROM usuarios WHERE id = '$miUsuario'")
				or die(mysqli_error($con));

			while($reg = mysqli_fetch_array($proyecto))
			{
				$proy[0] = $reg['proy1'];
				$proy[1] = $reg['proy2'];
				$proy[2] = $reg['proy3'];
				$proy[3] = $reg['proy4'];
				$proy[4] = $reg['proy5'];
				$proy[5] = $reg['proy6'];
				$proy[6] = $reg['proy7'];
				$proy[7] = $reg['proy8'];
				$proy[8] = $reg['proy9'];
				$proy[9] = $reg['proy10'];
			}

			if($proy[0] == 999999)
			{
#IIIIIIIIIIIIIIIIIIII	Búsqueda de "Lista de Proyectos" 	IIIIIIIIIIIIIIIIIIII
			$proyectos = mysqli_query($con, "SELECT nombreProyecto, numeroProyecto,
											totalAutorizado
											FROM proyectos
											ORDER BY numeroProyecto")
				or die(mysqli_error($con));
#IIIIIIIIIIIIIIIIIIII	Búsqueda de "Lista de Ingresos"	IIIIIIIIIIIIIIIIIIII
			$ingresos = mysqli_query($con, "SELECT ing.no, ing.tipo, ing.concepto, ing.operacion, ing.mes, ing.numProy,
																	ing.noSolFon, ing.validado, ing.capT1, ing.capT2, ing.capT3,
																	pro.numeroProyecto, pro.nombreProyecto, ing.SFtotal
											FROM ingresos AS ing
											RIGHT JOIN proyectos AS pro
												ON	ing.numProy = pro.numeroProyecto
											ORDER BY pro.numeroProyecto")
				or die(mysqli_error($con));

# Búsqueda del Ingreso Menor
				$ingPrimer = mysqli_query($con, "SELECT ing.numProy, pro.numeroProyecto
												FROM ingresos AS ing
												JOIN proyectos AS pro
													ON ing.numProy = pro.numeroProyecto
												ORDER BY numeroProyecto LIMIT 1")
					or die(mysqli_error($con));

#	Almacena el valor mas Bajo de Ingresos


#IIIIIIIIIIIIIIIIIIII	"Búsqueda Lísta de Egresos"	IIIIIIIIIIIIIIIIIIII
			$egresos =  mysqli_query($con, "SELECT egr.nombreProyecto, egr.importeTotal, egr.cap1000, egr.cap2000, egr.cap3000,
											pro.numeroProyecto
											FROM egresos AS egr
											RIGHT JOIN proyectos AS pro
												ON egr.nombreProyecto = pro.nombreProyecto
											ORDER BY numeroProyecto")
				or die(mysqli_error($con));

#Búsqueda del Egreso Menor
				$egrPrimer = mysqli_query($con, "SELECT egr.nombreProyecto, pro. numeroProyecto
												FROM egresos AS egr
												JOIN proyectos AS pro
													ON egr.nombreProyecto = pro.nombreProyecto
												ORDER BY numeroProyecto LIMIT 1")
					or die(mysqli_error($con));
			}
			else
			{
				$proyectos = mysqli_query($con, "SELECT nombreProyecto, numeroProyecto,
											totalAutorizado
											FROM proyectos
											WHERE numeroProyecto = '$proy[0]' OR
																	numeroProyecto = '$proy[1]' OR numeroProyecto = '$proy[2]' OR
																	numeroProyecto = '$proy[3]' OR numeroProyecto = '$proy[4]' OR
																	numeroProyecto = '$proy[5]' OR numeroProyecto = '$proy[6]' OR
																	numeroProyecto = '$proy[7]' OR numeroProyecto = '$proy[8]' OR
																	numeroProyecto = '$proy[9]'
											ORDER BY numeroProyecto")
				or die(mysqli_error($con));
#IIIIIIIIIIIIIIIIIIII	Búsqueda de "Lista de Ingresos"	IIIIIIIIIIIIIIIIIIII
			$ingresos = mysqli_query($con, "SELECT ing.no, ing.tipo, ing.concepto, ing.operacion, ing.mes, ing.numProy,
																	ing.noSolFon, ing.validado, ing.capT1, ing.capT2, ing.capT3,
																	pro.numeroProyecto, pro.nombreProyecto, ing.SFtotal
											FROM ingresos AS ing
											RIGHT JOIN proyectos AS pro
												ON	ing.numProy = pro.numeroProyecto
											WHERE pro.numeroProyecto = '$proy[0]' OR
																	pro.numeroProyecto = '$proy[1]' OR pro.numeroProyecto = '$proy[2]' OR
																	pro.numeroProyecto = '$proy[3]' OR pro.numeroProyecto = '$proy[4]' OR
																	pro.numeroProyecto = '$proy[5]' OR pro.numeroProyecto = '$proy[6]' OR
																	pro.numeroProyecto = '$proy[7]' OR pro.numeroProyecto = '$proy[8]' OR
																	pro.numeroProyecto = '$proy[9]'
											ORDER BY pro.numeroProyecto")
				or die(mysqli_error($con));

# Búsqueda del Ingreso Menor
				$ingPrimer = mysqli_query($con, "SELECT ing.numProy, pro.numeroProyecto, pro.nombreProyecto
												FROM ingresos AS ing
												JOIN proyectos AS pro
													ON ing.numProy = pro.numeroProyecto
												WHERE pro.numeroProyecto = '$proy[0]' OR
																	pro.numeroProyecto = '$proy[1]' OR pro.numeroProyecto = '$proy[2]' OR
																	pro.numeroProyecto = '$proy[3]' OR pro.numeroProyecto = '$proy[4]' OR
																	pro.numeroProyecto = '$proy[5]' OR pro.numeroProyecto = '$proy[6]' OR
																	pro.numeroProyecto = '$proy[7]' OR pro.numeroProyecto = '$proy[8]' OR
																	pro.numeroProyecto = '$proy[9]'
												ORDER BY numeroProyecto LIMIT 1")
					or die(mysqli_error($con));

#	Almacena el valor mas Bajo de Ingresos


#IIIIIIIIIIIIIIIIIIII	"Búsqueda Lísta de Egresos"	IIIIIIIIIIIIIIIIIIII
			$egresos =  mysqli_query($con, "SELECT egr.nombreProyecto, egr.importeTotal, egr.cap1000, egr.cap2000, egr.cap3000,
											pro.numeroProyecto
											FROM egresos AS egr
											RIGHT JOIN proyectos AS pro
												ON egr.nombreProyecto = pro.nombreProyecto
											WHERE pro.numeroProyecto = '$proy[0]' OR
																	pro.numeroProyecto = '$proy[1]' OR pro.numeroProyecto = '$proy[2]' OR
																	pro.numeroProyecto = '$proy[3]' OR pro.numeroProyecto = '$proy[4]' OR
																	pro.numeroProyecto = '$proy[5]' OR pro.numeroProyecto = '$proy[6]' OR
																	pro.numeroProyecto = '$proy[7]' OR pro.numeroProyecto = '$proy[8]' OR
																	pro.numeroProyecto = '$proy[9]'
											ORDER BY numeroProyecto")
				or die(mysqli_error($con));

#Búsqueda del Egreso Menor
				$egrPrimer = mysqli_query($con, "SELECT egr.nombreProyecto, pro. numeroProyecto
												FROM egresos AS egr
												JOIN proyectos AS pro
													ON egr.nombreProyecto = pro.nombreProyecto
												WHERE pro.numeroProyecto = '$proy[0]' OR
																	pro.numeroProyecto = '$proy[1]' OR pro.numeroProyecto = '$proy[2]' OR
																	pro.numeroProyecto = '$proy[3]' OR pro.numeroProyecto = '$proy[4]' OR
																	pro.numeroProyecto = '$proy[5]' OR pro.numeroProyecto = '$proy[6]' OR
																	pro.numeroProyecto = '$proy[7]' OR pro.numeroProyecto = '$proy[8]' OR
																	pro.numeroProyecto = '$proy[9]'
												ORDER BY numeroProyecto LIMIT 1")
					or die(mysqli_error($con));
			}


			while($reg = mysqli_fetch_array($ingPrimer))
			{
				$ingMenor = $reg['numProy'];
			}

			while($reg = mysqli_fetch_array($egrPrimer))
			{
				$egrMenor = $reg['numeroProyecto'];
			}


#---------------------------------Arreglo------------------------------------------
			#ESTAS VARIABLES SERVIRAN PARA RECORRER EL ARREGLO
			$x = 0;
			$y = 1;
			#VAMOS A GENERAR NUESTRO ARREGLO
			$tablaArreglo[0][0] = "N&uacute;mero de Proyecto";
			$tablaArreglo[0][1] = "Nombre de Proyecto";
			$tablaArreglo[0][2] = "Total Autorizado Anual";
			$tablaArreglo[0][3] = "Ministrado";
			$tablaArreglo[0][4] = "Ejercido";
			$tablaArreglo[0][5] = "Disponible";
#----------------------------------Proyectos---------------------------------------
			#ORDENAMOS LA LISTA DE PROYECTOS SOBRE EL ARREGLO
			while($pro = mysqli_fetch_array($proyectos))
			{
				$tablaArreglo[$y][$x] = $pro['numeroProyecto'];
				$x++;
				$tablaArreglo[$y][$x] = $pro['nombreProyecto'];
				$x++;
				$tablaArreglo[$y][$x] = $pro['totalAutorizado'];
				$y++;
				$x = 0;
			}
#--------------------------------Ingresos------------------------------------------
			#ORDENAMOS LOS INGRESOS SOBRE EL ARREGLO
			$ingNombreProy;
			$y = 1;
			$ingSuma = 0;
			$ingNumProy = $ingMenor;

			while ($reg = mysqli_fetch_array ($ingresos))
			{
				if($ingNumProy == $reg['numeroProyecto'])
			#Cuando es igual solo guarda variables y suma el total
				{
					$ingNombreProy = $reg['nombreProyecto'];
					$ingSuma = $reg['capT2'] + $reg['capT3'] + $ingSuma;
				}
				else
			#Cuando es diferente escribe las variables guardadas/ suma =0 / registra nuevas variables
				{
					while($ingNumProy != $tablaArreglo[$y][$x])
					{
						$y++;
						$tablaArreglo[$y][3] = 0;
					}

					$tablaArreglo[$y][3] = $ingSuma;

					$ingSuma = 0;
					$ingNumProy = $reg['numeroProyecto'];
					$ingNombreProy = $reg['nombreProyecto'];
					$ingSuma = $reg['capT2'] + $reg['capT3'] + $ingSuma;
				}
				#Cuando $ingNumProy es igual al último numero de la tabla
			}

			$y++;
			$tablaArreglo[$y][3] = $ingSuma;


#------------------------------Egresos------------------------------------------

			$y = 1;
			$egrNumProy = $egrMenor;
			$egrNombreProy;
			$egrSuma = 0;

			while($reg = mysqli_fetch_array($egresos))
			{
				if($egrNumProy == $reg['numeroProyecto'])
				{
					$egrNombreProy = $reg['nombreProyecto'];
					$egrSuma = $reg['cap2000'] + $reg['cap3000'] + $egrSuma;
				}
				else
				{
					while($egrNumProy != $tablaArreglo[$y][$x])
					{
						$y++;
						$tablaArreglo[$y][4] = 0;
					}

					$tablaArreglo[$y][4] = $egrSuma;

					$egrSuma = 0;
					$egrNumProy = $reg['numeroProyecto'];
					$egrNombreProy = $reg['nombreProyecto'];
					$egrSuma = $reg['cap2000'] + $reg['cap3000'] + $egrSuma;
				}
			}

			$y++;
			$tablaArreglo[$y][4] = $egrSuma;

#------------------------- Calculamos la diferencia entre ministrado y ejercido -----------

			for($i=1; $i<count($tablaArreglo); $i++)
			{
				$tablaArreglo[$i][5] = $tablaArreglo[$i][3] - $tablaArreglo[$i][4];
			}
#-------------------------------------------------------------------------------
			#ESCRIBE EL ARREGLO GLOBAL

		echo '<fieldset id="base">
			<fieldset id="formulario">
				<legend>Reporte de Cap&iacute;tulo 2000 y 3000</legend>';
			echo '<table class="tablaReport" border="1">';
			for($i=0;$i<count($tablaArreglo);$i++)
			{
				echo '<tr>';
				for($j=0;$j<count($tablaArreglo[$i]);$j++)
				{
					if($j >= 2 && $i > 0)
					{
						if($tablaArreglo[$i][$j] <= 0 && $j == 5 )
						{
							echo '<td><font color="red"> $'.number_format($tablaArreglo[$i][$j],2,'.',',').'</font></td>';
						}
						else
						{
							echo '<td> $'.number_format($tablaArreglo[$i][$j],2,'.',',').'</td>';
						}
					}


					else
					{
						echo '<td>'.$tablaArreglo[$i][$j].'</td>';
					}
				}
				echo '</tr>';
			}

			//Este ciclo y los que siguen se encaminan a calcular el total de lo ministrado, ejercido y disponible
			$sMinistrado = 0;
			$sEjercido = 0;
			$sDisponible = 0;

			for($i=0; $i<count($tablaArreglo);$i++)
			{
				$sMinistrado = $tablaArreglo[$i][3] + $sMinistrado;
				$sEjercido = $tablaArreglo[$i][4] + $sEjercido;
				$sDisponible = $tablaArreglo[$i][5] + $sDisponible;
			}

			echo '<tr>
					<td colspan="2"> &nbsp; </td>
					<td>Total:</td>
					<td>$'.number_format($sMinistrado,2,'.',',').'</td>
					<td>$'.number_format($sEjercido,2,'.',',').'</td>
					<td>$'.number_format($sDisponible,2,'.',',').'</td>
				</tr>';

			echo '</table>';
		mysqli_close($con);
		?>
		</fieldset>
		</fieldset>
	</body>
</html>
