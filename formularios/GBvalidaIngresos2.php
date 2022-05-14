<?php 	require_once('menu.php');
require_once('../config.php');
header('Content-Type: text/html; charset=UTF-8');
  ?>
<html>
	<head>
		<title> Validaci&oacute;n de Ingresos Gasto B&aacute;sico </title>

		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="../css2/formularios.css"></link>

		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>		<!-- Efectos del Menú -->

				<script type="text/javascript">

		function fncSumar()								// -- Realiza las sumas de comprobaciones
		{
			caja=document.forms["form1"].elements;
			var total = Number(caja["total"].value);
			var comprobacion1 =  Number(caja["comprobacion1"].value);
			var comprobacion2 =  Number(caja["comprobacion2"].value);

			comprobado=comprobacion1+comprobacion2;

			if(comprobado > total)
			{
				alert("Tus Comprobaciones son mayores al Improte Total");
			}
			if(!isNaN(comprobado))
			{
				caja["comprobado"].value=comprobacion1+comprobacion2;
			}

			var total = Number(caja["total"].value);
			var comprobado = Number(caja["comprobado"].value);

			restaComprobar=total-comprobado;
			porcentaje=comprobado*100;
			porcentaje = porcentaje/total;

			if(!isNaN(restaComprobar))
			{
				caja["restaComprobar"].value=total-comprobado;
			}
			if(!isNaN(porcentaje))
			{
				xpor = comprobado*100/total;
				xpor = xpor.toFixed(2);
				caja["porcentaje"].value=xpor;
			}
		}

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
				
			$registro = mysqli_query($con, "SELECT * FROM ingresosgb
										WHERE no = '$_REQUEST[codigo]'")
				or die(mysqli_error($con));

			if($reg = mysqli_fetch_array($registro))
				{
					echo '<form method="post" action="GBvalidaIngresosSube.php" name="form1">
							<fieldset id="formulario">
									<legend>Validacion de Ingresos Gasto B&aacute;sico</legend>
									<table class="tablaForm" border="0">
										<tr>
											<th collspan="6" center>Datos Generales</td>
										</tr>
										<tr>
											<td colspan="2">N&uacute;mero:</td>
											<td colspan="2"> Fecha de Dep&oacute;sito: </td>
											<td colspan="2"> Tipo: </td>
										</tr>
										<tr>
											<td colspan="2"> <input type="text" name="no" value="'.$reg['no'].'" readonly> </td>
											<td colspan="2"> <input type="text" name="fechaDeposito" value="'.$reg['fechaDeposito'].'" readonly> </td>
											<td colspan="2"> <input type="text" name="tipo" value="'.$reg['tipo'].'" readonly> </td>
										</tr>
										<tr>
											<td colspan="2"> Cap2000:</td>
											<td colspan="2"> Cap3000: </td>
											<td colspan="2"> Concepto: </td>
										</tr>
										<tr>
											<td colspan="2"> <input type="text" name="cap2000" value="'.$reg['cap2000'].'" readonly> </td>
											<td colspan="2"> <input type="text" name="cap3000" value="'.$reg['cap3000'].'" readonly> </td>
											<td colspan="2" rowspan="3"> <textarea name="concepto" readonly> '.$reg['concepto'].' </textarea> </td>
										</tr>
										<tr><td coslpan="4">&nbsp;</td></tr>
										<tr>
											<th coslpan="4">Comprobaciones</th>
										</tr>
										<tr>
											<td colspan="2">&nbsp;</td>
											<td> Numero de Comprobaci&oacute;n </td>
											<td coslpan="2"> &nbsp; </td>
											<td> Monto de la Comprobaci&oacute;n </td>
										</tr>
										<tr>
											<td> &nbsp; </td>
											<td>1.-</td>
											<td> <input type="text" name="noComprobacion1" value="'.$reg['noComprobacion1'].'"> </td>
											<td> &nbsp; </td>
											<td> $ <input type="text" name="comprobacion1" value="'.$reg['comprobacion1'].'" onkeypress="return justNumbers(event);"  onKeyUp="fncSumar()"> </td>
										<tr/>
										<tr>
											<td> &nbsp; </td>
											<td>2.-</td>
											<td> <input type="text" name="noComprobacion2" value="'.$reg['noComprobacion2'].'"> </td>
											<td> &nbsp; </td>
											<td> $ <input type="text" name="comprobacion2" value="'.$reg['comprobacion2'].'" onkeypress="return justNumbers(event);"  onKeyUp="fncSumar()"> </td>
										<tr/>
										<tr>
											<td colspan="2"> Total: </td>
											<td colspan="2"> Comprobado: </td>
											<td colspan="2" rowspan="2"> &nbsp; </td>
										<tr/>
										<tr>
											<td colspan="2"> $<input type="text" name="total" value="'.$reg['total'].'" onChange="fncSumar()"  readonly> </td>
											<td colspan="2"> $<input type="text" name="comprobado" value="'.$reg['comprobado'].'" onChange="fncSumar()" readonly> </td>
										</tr>
										<tr>
											<td colspan="2"> Resta Comprobar: </td>
											<td colspan="2"> % de Comprobaci&oacute;n: </td>
											<td colspan="2" rowspan="2"> <input type="submit" value="Enviar"> </td>
										<tr/>
										<tr>
											<td colspan="2"> $<input type="text" name="restaComprobar" value="'.$reg['restaComprobar'].'" readonly> </td>
											<td colspan="2"> %<input type="text" name="porcentaje" value="'.$reg['comprobado'].'" readonly> </td>
										</tr>
									</table>
								</fieldset>
							</fieldset>
						</form>
					';
				}
		?>

	</body>
</html>
