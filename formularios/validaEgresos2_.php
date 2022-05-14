<?php 	require_once('menu.php');
require_once('../config.php');
header('Content-Type: text/html; charset=UTF-8');
?>
<html>
	<head>
		<title> Validaci&oacute;n de Egresos </title>

		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/formularios.css"></link>

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
		<?php
			$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
				or die("Problemas con la conexi&oacute;n a la base de datos");
				$con->query("SET NAMES 'utf8'");

			$registro = mysqli_query($con, "SELECT * FROM egresos
										WHERE noCheque =  '$_REQUEST[codigo]'")
				or die(mysqli_error($con));

			if($reg = mysqli_fetch_array($registro))
				{
					echo '<form method="post" action="validaEgresosSube.php" name="form1">
							<fieldset id="formulario">
								<table class="tablaForm" border="0">
									<tr>
										<th colspan="3">Datos Generales</th>
										<th></th>
										<th></th>
										<th>Comprobaciones:</th>
										<th></th>
										<th>Validaciones:</th>
										<th></th>
										<th>Fecha de Comprobaci&oacute;n:</th>
									</tr>
									<tr>
										<td>No. de Cheque:</td>
										<td colspan="2"> <input type="text" name="noCheque" value="'.$reg['noCheque'].'" class="readonly" readonly> </td>
										<td></td>
										<td>$</td>
										<td><input type="text" name="comprobacion1" value="'.$reg['comprobacion1'].'" onkeypress="return justNumbers(event);"  onKeyUp="fncSumar()"></td>
										<td></td>
										<td>
											<select name="validacionComp1">';
												if($reg['validacionComp1'] == 0)
												{
													echo '<option value="0" selected> </option>
														<option value="1"> Validado </option>
														<option value="2"> No Validado </option>';
												}
												else
												{
													if($reg['validacionComp1'] == 1)
													{
														echo '<option value="0"> </option>
														<option value="1" selected> Validado </option>
														<option value="2"> No Validado </option>';
													}
													else
													{
														if($reg['validacionComp1'] == 2)
														{
															echo '<option value="0"> </option>
															<option value="1"> Validado </option>
															<option value="2" selected> No Validado </option>';
														}
													}
												}
					echo					'</select>
										</td>
										<td></td>
										<td> <input type="date" name="fechaComp1" value="'.$reg['fechaComp1'].'"> </td>
									</tr>
									<tr>
										<td>Fecha de Elaboraci&oacute;n:</td>
										<td colspan="2"> <input type="date" name="fechaElaboracion" value="'.$reg['fechaElaboracion'].'" class="readonly" readonly> </td>
										<td></td>
										<td>$</td>
										<td> <input type="text" name="comprobacion2" value="'.$reg['comprobacion2'].'" onkeypress="return justNumbers(event);"  onKeyUp="fncSumar()" > </td>
										<td></td>
										<td>
										<select name="validacionComp2">';
												if($reg['validacionComp2'] == 0)
												{
													echo '<option value="0" selected> </option>
														<option value="1"> Validado </option>
														<option value="2"> No Validado </option>';
												}
												else
												{
													if($reg['validacionComp2'] == 1)
													{
														echo '<option value="0"> </option>
														<option value="1" selected> Validado </option>
														<option value="2"> No Validado </option>';
													}
													else
													{
														if($reg['validacionComp2'] == 2)
														{
															echo '<option value="0"> </option>
															<option value="1"> Validado </option>
															<option value="2" selected> No Validado </option>';
														}
													}
												}
					echo					'</select>

										</td>
										<td></td>
										<td> <input type="date" name="fechaComp2" value="'.$reg['fechaComp2'].'"> </td>
									</tr>
									<tr>
										<td>Nombre:</td>
										<td colspan="2"> <input type="text" name="nombre" value="'.$reg['nombre'].'" class="readonly" readonly> </td>
										<td></td>
										<td>$</td>
										<td> <input type="text" name="comprobacion3" value="'.$reg['comprobacion3'].'" onkeypress="return justNumbers(event);" onKeyUp="fncSumar()"> </td>
										<td></td>
										<td>
										<select name="validacionComp3">';
												if($reg['validacionComp3'] == 0)
												{
													echo '<option value="0" selected> </option>
														<option value="1"> Validado </option>
														<option value="2"> No Validado </option>';
												}
												else
												{
													if($reg['validacionComp3'] == 1)
													{
														echo '<option value="0"> </option>
														<option value="1" selected> Validado </option>
														<option value="2"> No Validado </option>';
													}
													else
													{
														if($reg['validacionComp3'] == 2)
														{
															echo '<option value="0"> </option>
															<option value="1"> Validado </option>
															<option value="2" selected> No Validado </option>';
														}
													}
												}
					echo					'</select>
										</td>
										<td></td>
										<td> <input type="date" name="fechaComp3" value="'.$reg['fechaComp3'].'"></td>
									</tr>
									<tr>
										<td>Concepto:</td>
										<td colspan="2"></td>
										<td></td>
										<td>$</td>
										<td> <input type="text" name="comprobacion4" value="'.$reg['comprobacion4'].'" onkeypress="return justNumbers(event);" onKeyUp="fncSumar()"> </td>
										<td></td>
										<td>
											<select name="validacionComp4">';
												if($reg['validacionComp4'] == 0)
												{
													echo '<option value="0" selected> </option>
														<option value="1"> Validado </option>
														<option value="2"> No Validado </option>';
												}
												else
												{
													if($reg['validacionComp4'] == 1)
													{
														echo '<option value="0"> </option>
														<option value="1" selected> Validado </option>
														<option value="2"> No Validado </option>';
													}
													else
													{
														if($reg['validacionComp4'] == 2)
														{
															echo '<option value="0"> </option>
															<option value="1"> Validado </option>
															<option value="2" selected> No Validado </option>';
														}
													}
												}
					echo					'</select>

										</td>
										<td></td>
										<td> <input type="date" name="fechaComp4" value="'.$reg['fechaComp4'].'">  </td>
									</tr>
									<tr>
										<td colspan="3"> <input type="text" name="concepto" value="'.$reg['concepto'].'" class="readonly" readonly> </td>
										<td></td>
										<td>$</td>
										<td> <input type="text" name="comprobacion5" value="'.$reg['comprobacion5'].'" onkeypress="return justNumbers(event);" onKeyUp="fncSumar()"> </td>
										<td></td>
										<td>
											<select name="validacionComp5">';
												if($reg['validacionComp5'] == 0)
												{
													echo '<option value="0" selected> </option>
														<option value="1"> Validado </option>
														<option value="2"> No Validado </option>';
												}
												else
												{
													if($reg['validacionComp5'] == 1)
													{
														echo '<option value="0"> </option>
														<option value="1" selected> Validado </option>
														<option value="2"> No Validado </option>';
													}
													else
													{
														if($reg['validacionComp5'] == 2)
														{
															echo '<option value="0"> </option>
															<option value="1"> Validado </option>
															<option value="2" selected> No Validado </option>';
														}
													}
												}
					echo					'</select>
										</td>
										<td></td>
										<td> <input type="date" name="fechaComp5" value="'.$reg['fechaComp5'].'"></td>
									</tr>
									<tr>
										<td colspan="3">Nombre del Proyecto:</td>
										<td></td>
										<td>$</td>
										<td> <input type="text" name="comprobacion6" value="'.$reg['comprobacion6'].'" onkeypress="return justNumbers(event);" onKeyUp="fncSumar()"> </td>
										<td></td>
										<td>
											<select name="validacionComp6">';
												if($reg['validacionComp6'] == 0)
												{
													echo '<option value="0" selected> </option>
														<option value="1"> Validado </option>
														<option value="2"> No Validado </option>';
												}
												else
												{
													if($reg['validacionComp6'] == 1)
													{
														echo '<option value="0"> </option>
														<option value="1" selected> Validado </option>
														<option value="2"> No Validado </option>';
													}
													else
													{
														if($reg['validacionComp6'] == 2)
														{
															echo '<option value="0"> </option>
															<option value="1"> Validado </option>
															<option value="2" selected> No Validado </option>';
														}
													}
												}
					echo					'</select>
										</td>
										<td></td>
										<td> <input type="date" name="fechaComp6" value="'.$reg['fechaComp6'].'"> </td>
									</tr>
									<tr>
										<td colspan="3"> <input type="text" name="nombreProyecto" value="'.$reg['nombreProyecto'].'" class="readonly" readonly> </td>
										<td colspan="7"> </td>
									</tr>
									<tr>
										<td>Folio:</td>
										<td colspan="2"> <input type="text" name="folio" value="'.$reg['folio'].'" class="readonly" readonly></td>
										<td></td>
										<td></td>
										<td>Comprobado:</td>
										<td></td>
										<td>Resta por Comprobar</td>
										<td></td>
										<td>Porcentaje Comprobado:</td>
									</tr>
									<tr>
										<td>Importe Total:</td>
										<td>$</td>
										<td> <input type="text" name="importeTotal" value="'.$reg['importeTotal'].'" onChange="fncSumar()" class="readonly" readonly></td>
										<td></td>
										<td>$</td>
										<td> <input type="text" name="comprobado" onChange="fncSumar()" class="readonly" readonly> </td>
										<td>$</td>
										<td> <input type="text" name="restaComprobar" class="readonly" readonly> </td>
										<td>%</td>
										<td> <input type="text" name="porcentaje" class="readonly" readonly> </td>
									</tr>
									<tr>
										<td colspan="8" align="center">
											<input type="submit" value="Enviar">
										</td>
									</tr>
								</table>
							</fieldset>
							</form>
			</fieldset>

			<br>
			<fieldset id="formulario">
					<legend> Observaciones ! </legend>
					<text>'.$reg['observaciones'].'</text>
			</fieldset>';
				}
		?>

		<script type="text/javascript">

			function fncSumar()								// -- Realiza las sumas de comprobaciones
			{
				caja=document.forms["form1"].elements;
				var importeTotal = Number(caja["importeTotal"].value);
				var comprobacion1 =  Number(caja["comprobacion1"].value);
				var comprobacion2 =  Number(caja["comprobacion2"].value);
				var comprobacion3 =  Number(caja["comprobacion3"].value);
				var comprobacion4 =  Number(caja["comprobacion4"].value);
				var comprobacion5 =  Number(caja["comprobacion5"].value);
				var comprobacion6 =  Number(caja["comprobacion6"].value);

				comprobado=comprobacion1+comprobacion2+comprobacion3+
					comprobacion4+comprobacion5+comprobacion6;

				if(comprobado > importeTotal)
				{
					alert("Tus Comprobaciones son mayores al Improte Total");
				}
				if(!isNaN(comprobado))
				{
					caja["comprobado"].value=comprobacion1+comprobacion2+comprobacion3+
					comprobacion4+comprobacion5+comprobacion6;
				}

				var importeTotal = Number(caja["importeTotal"].value);
				var comprobado = Number(caja["comprobado"].value);

				restaComprobar=importeTotal-comprobado;
				porcentaje=comprobado*100;
				porcentaje = porcentaje/importeTotal;

				if(!isNaN(restaComprobar))
				{
					caja["restaComprobar"].value=importeTotal-comprobado;
				}
				if(!isNaN(porcentaje))
				{
					xpor = comprobado*100/importeTotal;
					xpor = xpor.toFixed(2);
					caja["porcentaje"].value=xpor;
				}
			}

			function justNumbers(e)						// -- Solo permite la captura de números en estos campos
			{
				var keynum = window.event ? window.event.keyCode : e.which;
				if ((keynum == 8) || (keynum == 46))
				return true;

				return /\d/.test(String.fromCharCode(keynum));
			}


		</script>

	</body>
</html>
