<?php
	require_once('menu.php');
	require_once('../config.php');
	header('Content-Type: text/html; charset=UTF-8');
	session_start();
?>
<html>
	<head>
		<title> Validaci&oacute;n de Solicitudes de Fondos </title>

		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/formularios.css"></link>

		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>		<!-- Efectos del Menú -->

		<script type="text/javascript">
			function fncSumar()								// -- Realiza las sumas de comprobaciones
			{
				caja=document.forms["form1"].elements;

				var porcentaje = Number(caja["porcentaje"].value);
				var falta = Number(caja["falta"].value);
				var SFtotal = Number(caja["SFtotal"].value);
				var validado = Number(caja["validado"].value);
				var cap1_1 =  Number(caja["cap1_1"].value);
				var cap1_2 =  Number(caja["cap1_2"].value);
				var cap2_1 =  Number(caja["cap2_1"].value);
				var cap2_2 =  Number(caja["cap2_2"].value);
				var cap3_1 =  Number(caja["cap3_1"].value);
				var cap3_2 =  Number(caja["cap3_2"].value);
				var cap4_1 =  Number(caja["cap4_1"].value);
				var cap4_2 =  Number(caja["cap4_2"].value);
				var cap5_1 =  Number(caja["cap5_1"].value);
				var cap5_2 =  Number(caja["cap5_2"].value);
				var SFcap1000 =  Number(caja["SFcap1000"].value);
				var SFcap2000 =  Number(caja["SFcap2000"].value);
				var SFcap3000 =  Number(caja["SFcap3000"].value);
				var SFcap4000 =  Number(caja["SFcap4000"].value);
				var SFcap5000 =  Number(caja["SFcap5000"].value);
				var capT1 =  Number(caja["capT1"].value);
				var capT2 =  Number(caja["capT2"].value);
				var capT3 =  Number(caja["capT3"].value);
				var capT4 =  Number(caja["capT4"].value);
				var capT5 =  Number(caja["capT5"].value);
				var resta1 =  Number(caja["resta1"].value);
				var resta2 =  Number(caja["resta2"].value);
				var resta3 =  Number(caja["resta3"].value);
				var resta4 =  Number(caja["resta3"].value);
				var resta5 =  Number(caja["resta3"].value);


				validado = cap1_1+ cap1_2+ cap2_1+ cap2_2+ cap3_1+ cap3_2+ cap4_1+ cap4_2+ cap5_1+ cap5_2;

				if(validado > SFtotal)
				{
					alert("El monto Validado es mayor a lo Solicitado");
				}
				if(!isNaN(validado))
				{
					caja["validado"].value= cap1_1+ cap1_2+ cap2_1+ cap2_2+ cap3_1+ cap3_2+ cap4_1+ cap4_2+ cap5_1+ cap5_2;
				}

				falta=SFtotal-validado;
				porcentaje = validado*100;
				porcentaje = porcentaje/SFtotal;

				if(!isNaN(falta))
				{
					caja["falta"].value=SFtotal-validado;
				}
				if(!isNaN(porcentaje))
				{
					xpor = validado*100/SFtotal;
					xpor = xpor.toFixed(2);
					caja["porcentaje"].value=xpor;
				}

				capT1 = cap1_1 - cap1_2;
					if(!isNaN(capT1))
					{
						caja["capT1"].value=cap1_1 + cap1_2;
					}
				capT2 = cap2_1 - cap2_2;
					if(!isNaN(capT2))
					{
						caja["capT2"].value=cap2_1 + cap2_2;
					}
				capT3 = cap3_1 - cap3_2;
					if(!isNaN(capT3))
					{
						caja["capT3"].value=cap3_1 + cap3_2;
					}
				capT4 = cap4_1 - cap4_2;
					if(!isNaN(capT4))
					{
						caja["capT4"].value=cap4_1 + cap4_2;
					}
				capT5 = cap5_1 - cap5_2;
					if(!isNaN(capT5))
					{
						caja["capT5"].value=cap5_1 + cap5_2;
					}


				resta1 = SFcap1000 - cap1_1 - cap1_2;
					if(!isNaN(resta1))
					{
						caja["resta1"].value=SFcap1000 - cap1_1 - cap1_2;
					}
				resta2 = SFcap2000 - cap2_1 - cap2_2;
					if(!isNaN(resta2))
					{
						caja["resta2"].value=SFcap2000 - cap2_1 - cap2_2;
					}
				resta3 = SFcap3000 - cap3_1 - cap3_2;
					if(!isNaN(resta3))
					{
						caja["resta3"].value=SFcap3000 - cap3_1 - cap3_2;
					}
				resta4 = SFcap4000 - cap4_1 - cap4_2;
					if(!isNaN(resta4))
					{
						caja["resta4"].value=SFcap4000 - cap4_1 - cap4_2;
					}
				resta5 = SFcap5000 - cap5_1 - cap5_2;
					if(!isNaN(resta5))
					{
						caja["resta5"].value=SFcap5000 - cap5_1 - cap5_2;
					}

				if(validado > SFtotal)
				{
					alert("El monto Validado es mayor a lo Solicitado");
				}
				if(!isNaN(validado))
				{
					caja["validado"].value=cap1_1+ cap1_2+ cap2_1+ cap2_2+ cap3_1+ cap3_2+ cap4_1+ cap4_2+ cap5_1+ cap5_2;
				}
			}

			function justNumbers(e)
			{
				var keynum = window.event ? window.event.keyCode : e.which;
				if ((keynum == 8) || (keynum == 46))
				return true;

				return /\d/.test(String.fromCharCode(keynum));
			}

			$(document).ready(function() { 
				fncSumar();
			});
		</script>
	</head>

	<body>
<?php
		//-------------------------	INICIA MENÚ	--------------------------

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
	<fieldset id="formulario">
		<?php
			$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
				or die("Problemas con la conexi&oacute;n a la base de datos");
				$con->query("SET NAMES 'utf8'");

			$registro = mysqli_query($con, "SELECT * FROM ingresos
										WHERE no = '$_REQUEST[codigo]'")
				or die(mysqli_error($con));

			while($reg = mysqli_fetch_array($registro))
				{
					echo '<form method="post" action="validaSFSube.php" name="form1">
						<fieldset id="base">
							<fieldset id="formulario">
							<fieldset>
								<legend>Validaci&oacute;n de Solicitudes de Fondos</legend>
								<table class="tablaForm" border="0">
									<tr>
										<th colspan="3"> DATOS GENERALES </th>
									</tr>
									<tr>
										<td>No. Solicitud de Fondos</td>
										<td colspan="2">Concepto</td>
									</tr>

									<tr>
										<td>
											<input type="hidden" name="no" value="'.$reg['no'].'">
											<input type="text" name="noSolFon" class="readonly" value="'.$reg['noSolFon'].'" readonly> </td>
										<td colspan="2"> <input type="text" name="concepto"  class="readonly" value="'.$reg['concepto'].'"  readonly> </td>
									</tr>

									<tr>
										<td>Nombre de Proyecto </td>
									</tr>';

									$num = $reg['numProy'];
									$proyecto = mysqli_query($con, "SELECT * FROM proyectos
															WHERE numeroProyecto = '$num' " )
											or die(mysqli_error($con));

									while($reg1 = mysqli_fetch_array($proyecto))
									{
											$nombre = $reg1['nombreProyecto'];
									}

					echo			'<tr>
										<td colspan="3"><input type="text" name="nombreProyecto" class="readonly" value="'.$reg['numProy'].' - '.$nombre.'"  readonly>	<input type="hidden" name="numProy" value="'.$reg['numProy'].'"></td>
									</tr>

									<tr>
										<td> Fecha de Elaboraci&oacute;n </td>
										<td> Tipo </td>
										<td> Operaci&oacute;n </td>
									</tr>

									<tr>
										<td><input type="date" name="fechaElab" class="readonly" value="'.$reg['fechaElab'].'" readonly> </td>
										<td> <input type="text" name="tipo" class="readonly" value="'.$reg['tipo'].'" readonly> </td>
										<td> <input type="text" name="operacion" class="readonly" value="'.$reg['operacion'].'" readonly></td>
									</tr>

									<tr><td colspan="3"> Observaciones: </td></tr>
									<tr><td colspan="3"><textarea disabled>'.$reg['obs'].'</textarea></td></tr>

									<tr>
										<td> &nbsp; </td>
									</tr>
								</table>
							</fieldset>
							</fieldset>
								<br>
							<fieldset  id="formulario">
							<fieldset>
								<table class="tablaForm" border="0">
									<tr>
										<th colspan="3"> DATOS DE VALIDACI&Oacute;NES </th>
									</tr>
									<tr>
										<th colspan="3"> 1a. VALIDACI&Oacute;N </th>
									</tr>

									<tr>
										<td> &nbsp; </td>
										<td> Fecha de Dep&oacute;sito 1</td>
										<td> No Autorizaci&oacute;n 1</td>
									</tr>';

						//almacenamos fecha del servidor para FechaElab Auto
						$time = date("Y-m-d");
						if($reg['fechaDep1'] == '0000-00-00')
						{
							echo	'<tr>
										<td> &nbsp; </td>
										<td> <input type="date" name="fechaDep1" value="'.$time.'" required> </td>
										<td> <input type="text" name="noAut1" value="0" required> </td>
									</tr>

									<tr>
										<td> cap 1000 (1) </td>
										<td> cap 2000 (1) </td>
										<td> cap 3000 (1) </td>
										<td> cap 4000 (1) </td>
										<td> cap 5000 (1) </td>
									</tr>

									<tr>
										<td> $ <input type="text" name="cap1_1" value="'.$reg['SFcap1000'].'" onkeypress="return justNumbers(event);" onKeyUp="fncSumar()"> </td>
										<td> $ <input type="text" name="cap2_1" value="'.$reg['SFcap2000'].'" onkeypress="return justNumbers(event);" onKeyUp="fncSumar()"> </td>
										<td> $ <input type="text" name="cap3_1" value="'.$reg['SFcap3000'].'" onkeypress="return justNumbers(event);" onKeyUp="fncSumar()"> </td>
										<td> $ <input type="text" name="cap4_1" value="'.$reg['SFcap4000'].'" onkeypress="return justNumbers(event);" onKeyUp="fncSumar()"> </td>
										<td> $ <input type="text" name="cap5_1" value="'.$reg['SFcap5000'].'" onkeypress="return justNumbers(event);" onKeyUp="fncSumar()"> </td>
									</tr>';
						}

						else
						{
							echo	'<tr>
										<td> &nbps; </td>
										<td> <input type="date" name="fechaDep1" value="'.$reg['fechaDep1'].'" required> </td>
										<td> <input type="text" name="noAut1" value="'.$reg['noAut1'].'" required> </td>
									</tr>

									<tr>
										<td> cap 1000 (1) </td>
										<td> cap 2000 (1) </td>
										<td> cap 3000 (1) </td>
										<td> cap 4000 (1) </td>
										<td> cap 5000 (1) </td>
									</tr>

									<tr>
										<td> $ <input type="text" name="cap1_1" value="'.$reg['cap1_1'].'" onkeypress="return justNumbers(event);" onKeyUp="fncSumar()"> </td>
										<td> $ <input type="text" name="cap2_1" value="'.$reg['cap2_1'].'" onkeypress="return justNumbers(event);" onKeyUp="fncSumar()"> </td>
										<td> $ <input type="text" name="cap3_1" value="'.$reg['cap3_1'].'" onkeypress="return justNumbers(event);" onKeyUp="fncSumar()"> </td>
										<td> $ <input type="text" name="cap4_1" value="'.$reg['cap4_1'].'" onkeypress="return justNumbers(event);" onKeyUp="fncSumar()"> </td>
										<td> $ <input type="text" name="cap5_1" value="'.$reg['cap5_1'].'" onkeypress="return justNumbers(event);" onKeyUp="fncSumar()"> </td>
									</tr>';
						}



						echo'	<tr>
										<td> &nbsp; </td>
									</tr>

									<tr>
										<th colspan="3"> 2a. VALIDACI&Oacute;n</th>
									</tr>


									<tr>
										<td> &nbsp; </td>
										<td> Fecha de Dep&oacute;sito 2</td>
										<td> No Autorizaci&oacute;n 2</td>
									</tr>

									<tr>
										<td> &nbsp; </td>
										<td> <input type="date" name="fechaDep2" value="'.$reg['fechaDep2'].'" > </td>
										<td> <input type="text" name="noAut2" value="'.$reg['noAut2'].'" > </td>
									</tr>

									<tr>
										<td> cap 1000 (2) </td>
										<td> cap 2000 (2) </td>
										<td> cap 3000 (2) </td>
										<td> cap 4000 (2) </td>
										<td> cap 5000 (2) </td>
									</tr>

									<tr>
										<td> $ <input type="text" name="cap1_2" value="'.$reg['cap1_2'].'" onkeypress="return justNumbers(event);" onKeyUp="fncSumar()"> </td>
										<td> $ <input type="text" name="cap2_2" value="'.$reg['cap2_2'].'" onkeypress="return justNumbers(event);" onKeyUp="fncSumar()"> </td>
										<td> $ <input type="text" name="cap3_2" value="'.$reg['cap3_2'].'" onkeypress="return justNumbers(event);" onKeyUp="fncSumar()"> </td>
										<td> $ <input type="text" name="cap4_2" value="'.$reg['cap4_2'].'" onkeypress="return justNumbers(event);" onKeyUp="fncSumar()"> </td>
										<td> $ <input type="text" name="cap5_2" value="'.$reg['cap5_2'].'" onkeypress="return justNumbers(event);" onKeyUp="fncSumar()"> </td>
									</tr>
								</table>
							</fieldset>
							</fieldset>
								<br>
							<fieldset  id="formulario">
							<fieldset>
								<table class="tablaForm" border="0">
									<tr>
										<th colspan="3"> MONTOS EN CAP&Iacute;TULOS </th>
									</tr>

									<tr>
										<td> Solicitado Cap 1000 </td>
										<td> Solicitado Cap 2000 </td>
										<td> Solicitado Cap 3000 </td>
										<td> Solicitado Cap 4000 </td>
										<td> Solicitado Cap 5000 </td>
									</tr>

									<tr>
										<td> $ <input type="text" name="SFcap1000" class="readonly" value="'.$reg['SFcap1000'].'" readonly> </td>
										<td> $ <input type="text" name="SFcap2000" class="readonly" value="'.$reg['SFcap2000'].'" readonly> </td>
										<td> $ <input type="text" name="SFcap3000" class="readonly" value="'.$reg['SFcap3000'].'" readonly> </td>
										<td> $ <input type="text" name="SFcap4000" class="readonly" value="'.$reg['SFcap4000'].'" readonly> </td>
										<td> $ <input type="text" name="SFcap5000" class="readonly" value="'.$reg['SFcap5000'].'" readonly> </td>
									</tr>

									<tr>
										<td> Ministrado Cap 1000 </td>
										<td> Ministrado Cap 2000 </td>
										<td> Ministrado Cap 3000 </td>
										<td> Ministrado Cap 4000 </td>
										<td> Ministrado Cap 5000 </td>
									</tr>

									<tr>
										<td> $ <input type="text" name="capT1" class="readonly" value="0.00" readonly> </td>
										<td> $ <input type="text" name="capT2" class="readonly" value="0.00" readonly> </td>
										<td> $ <input type="text" name="capT3" class="readonly" value="0.00" readonly> </td>
										<td> $ <input type="text" name="capT4" class="readonly" value="0.00" readonly> </td>
										<td> $ <input type="text" name="capT5" class="readonly" value="0.00" readonly> </td>
									</tr>

									<tr>
										<td> Restante Cap 1000 </td>
										<td> Restante Cap 2000 </td>
										<td> Restante Cap 3000 </td>
										<td> Restante Cap 4000 </td>
										<td> Restante Cap 5000 </td>
									</tr>

									<tr>
										<td> $ <input type="text" name="resta1" class="readonly" value="0.00" readonly> </td>
										<td> $ <input type="text" name="resta2" class="readonly" value="0.00" readonly> </td>
										<td> $ <input type="text" name="resta3" class="readonly" value="0.00" readonly> </td>
										<td> $ <input type="text" name="resta4" class="readonly" value="0.00" readonly> </td>
										<td> $ <input type="text" name="resta5" class="readonly" value="0.00" readonly> </td>
									</tr>

									<tr>
										<td> &nbsp; </td>
									</tr>

									<tr>
										<th colspan="3"> MONTOS TOTALES </th>
									</tr>
									<tr>
										<td> Monto Solicitado </td>
										<td> Monto Ministrado </td>
										<td> Monto Faltante </td>
									</tr>

									<tr>
										<td> $ <input type="text" name="SFtotal" class="readonly" value="'.$reg['SFtotal'].'" readonly> </td>
										<td> $ <input type="text" name="validado" class="readonly" value="'.$reg['validado'].'" readonly> </td>
										<td> $ <input type="text" name="falta" class="readonly" value="0.00" readonly> </td>
									</tr>

									<tr>
										<td> Porcentaje Ministrado</td>
										<td> %<input type="text" name="porcentaje" class="readonly" value="0" readonly></td>
									</tr>

									<tr>
										<td>&nbsp;</td>
									</tr>

									<tr>
										<td colspan="2"> <input type="submit" value="Enviar"></td>
									</tr>
								</table>
							</fuieldset>
							</fieldset>
						</fieldset>';
				}
		?>

	</body>
</html>
