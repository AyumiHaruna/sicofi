<?php 	require_once('menu.php');
require_once('../config.php');
header('Content-Type: text/html; charset=UTF-8');
  ?>
<html>
	<head>
		<title> Validaci&oacute;n de Egresos Gasto B&aacute;sico </title>

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
			var comprobacion3 =  Number(caja["comprobacion3"].value);
			var comprobado 	=  Number(caja["comprobado"].value) ;


			comprobado=comprobacion1+comprobacion2+comprobacion3;

			if(comprobado > total)
			{
				alert("Tus Comprobaciones son mayores al Improte Total");
			}
			if(!isNaN(comprobado))
			{
				caja["comprobado"].value=comprobacion1+comprobacion2+comprobacion3;
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

			$registro = mysqli_query($con, "SELECT * FROM egresosgb
										WHERE noCheque =  '$_REQUEST[codigo]'")
				or die(mysqli_error($con));

			if($reg = mysqli_fetch_array($registro))
				{
					echo '<form method="post" action="GBvalidaEgresosSube.php" name="form1">
							<fieldset id="formulario">
									<legend>Validaci&oacute;n de Egresos Gasto B&aacute;sico</legend>
									<table class="tablaForm">
										<tr>
											<th>&nbsp;</th>
											<th> Datos Generales </th>
											<th>&nbsp;</th>
											<th> Nombre de Comprobaci&oacute;n</th>
											<th>&nbsp;</th>
											<th> Monto de la Comprobaci&oacute;n</th>
										</tr>

										<tr>
											<td>No de Cheque: </td>
											<td> <input type="text" name="noCheque" value="'.$reg['noCheque'].'" class="readonly" readonly> </td>
											<td>&nbsp;</td>
											<td>1.-<input type="text" name="noComprobacion1" value="'.$reg['noComprobacion1'].'"> </td>
											<td>&nbsp;</td>
											<td>$<input type="text" name="comprobacion1" value="'.$reg['comprobacion1'].'" onkeypress="return justNumbers(event);"  onKeyUp="fncSumar()"></td>
										</tr>

										<tr>
											<td>Fecha de Elaboraci&oacute;n: </td>
											<td> <input type="date" name="fechaElaboracion" value="'.$reg['fechaElaboracion'].'"readonly> </td>
											<td>&nbsp;</td>
											<td> 2.-<input type="text" name="noComprobacion2" value="'.$reg['noComprobacion2'].'"> </td>
											<td>&nbsp;</td>
											<td>$<input type="text" name="comprobacion2" value="'.$reg['comprobacion2'].'" onkeypress="return justNumbers(event);"  onKeyUp="fncSumar()"></td>
										</tr>

										<tr>
											<td> Nombre: </td>
											<td> <input type="text" name="nombre" value="'.$reg['nombre'].'" class="readonly" readonly> </td>
											<td>&nbsp;</td>
											<td> 3.-<input type="text" name="noComprobacion3" value="'.$reg['noComprobacion3'].'"> </td>
											<td>&nbsp;</td>
											<td>$<input type="text" name="comprobacion3" value="'.$reg['comprobacion3'].'" onkeypress="return justNumbers(event);"  onKeyUp="fncSumar()"></td>
										</tr>

										<tr>
											<td colspan="3"> Concepto: </td>
											<td> Importe Total: </td>
											<td colspan="2"> &nbsp; </td>
										</tr>

										<tr>
											<td colspan="3"> <input type="text" name="concepto" value="'.$reg['concepto'].'" readonly> </td>
											<td> $ <input type="text" name="total" value="'.$reg['total'].'" onChange="fncSumar()"  readonly> </td>
											<td colspan="2"> &nbsp; </td>
										</tr>

										<tr>
											<td> &nbsp; </td>
											<td> Comprobado </td>
											<td> &nbsp; </td>
											<td> Resta por Comprobar </td>
											<td> &nbsp; </td>
											<td> Porcentaje Comprobado </td>
										</tr>

										<tr>
											<td> &nbsp; </td>
											<td> $<input type="text" name="comprobado" value="'.$reg['comprobado'].'" onChange="fncSumar()"  readonly> </td>
											<td> &nbsp; </td>
											<td> $<input type="text" name="restaComprobar" value="'.$reg['restaComprobar'].'" readonly> </td>
											<td> &nbsp; </td>
											<td>  %<input type="text" name="porcentaje" readonly> </td>
										</tr>

										<tr>
											<td colspan="6"> <input type="submit" value="Enviar"> </td>
										</tr>
									</table>
							</fieldset>
							</form>
			<br>
			<fieldset id="formulario">
					<legend> Observaciones ! </legend>
					<text>'.$reg['observaciones'].'</text>
			</fieldset>';
				}
		?>

	</body>
</html>
