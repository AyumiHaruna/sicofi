<?php 	require_once('menu.php');
		require_once('config.php');
		header('Content-Type: text/html; charset=UTF-8');

		$anio = date('Y');
		$con = mysqli_connect($server, $user, $pass, $database.$anio)
			or die("Problemas con la conexi&oacute;n a la base de datos");
			$con->query("SET NAMES 'utf8'");
?>

<html>
	<head>
		<title> Registro de Usuarios</title>

		<link rel="stylesheet" type="text/css" href="css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="css2/registro.css"></link>

		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>		<!-- Efectos del Menú -->

		<script type="text/javascript">

		var count = 0;
		function conMayusculas(field)
		{					// -- Cambia a Mayusculas
			field.value = field.value.toUpperCase()
		}

		function justNumbers(e)
        {
			var keynum = window.event ? window.event.keyCode : e.which;
			if ((keynum == 8) || (keynum == 46))
			return true;

			return /\d/.test(String.fromCharCode(keynum));
        }

		function limpiar()
		{
			caja=document.forms["form1"].elements;
			caja["lista"].value="";
			count = 0;
		}

		function agregar()
		{
			if(count < 10)
			{
				caja=document.forms["form1"].elements;
				var  texto= (caja["lista"].value);
				var  numProy= (caja["proyectos"].value);

				if(numProy == 'todos')
				{
					count = 10;
					texto = 'todos';
				}
				else
				{
					if (texto == '')
					{
						texto = ' - '+numProy;
					}
					else
					{
						texto = texto+' - '+numProy;
					}

				count ++;
				}


				caja["lista"].value=texto;
			}
			else
			{
				alert('Haz ingresado ya 10 Proyectos, en caso de necesitar mas selecciona "TODOS LOS PROYECTOS"');
			}
		}

		 window.addEventListener('load',inicio,false);

		  function inicio()
		  {
			document.getElementById("form1").addEventListener('submit',validar,false);
		  }

		  function validar(evt)
		  {
			 var pass1 = document.getElementById("pass1").value;
			 var pass2 = document.getElementById("pass2").value;

			 if (pass1 != pass2)
			 {
			   alert('Los Passwords no coinciden');
			   evt.preventDefault();
			 }
		  }
		</script>
	</head>

	<body>
		<?php
				session_start();

			if($_SESSION == NULL)
			{
				echo $menu[0];
			}
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

				$anio = $_SESSION['anio'];
			}
		?>

<!-- IIIIIIIIIIIIIIIIIIIIIIIIIIII 	Aqui se inicia el formulario  IIIIIIIIIIIIIIIIIIIIIIIIIIII -->
		<fieldset id="base">
		<fieldset id="formulario">
			<h2> Registro de Usuario </h2>
				<form method="post" action="registroSend.php" id="form1" name="form1">
				<table class="tablaForm" border="0">
				   <tr>
						<td colspan="1">Nombre:</td>
						<td colspan="1"><input type="text" name="nombre" onChange="conMayusculas(this)"required></td>
				   </tr>
				   <tr>
						<td colspan="1">ID:</td>
						<td colspan="1"><input type="text" name="id" required></td>
				   </tr>
				   <tr>
						<td colspan="1">Password:</td>
						<td colspan="1"><input type="password" name="pass1" id="pass1" required></td>
				   </tr>
				   <tr>
						<td colspan="1">Repita su Password:</td>
						<td colspan="1"><input type="password" name="pass2" id="pass2" required></td>
				   </tr>

				   <tr>
						<td colspan="1">@EMAIL:</td>
						<td colspan="1"><input type="mail" name="mail" id="mail" required></td>
				   </tr>
				   <tr>
						<td colspan="1">A&ntilde;o Al que desea ingresar:</td>
						<td colspan="1"><select name="anio">
<?php
											$year = date("Y");
											$year = $year-1;
											echo '<option value="'.$year.'">'.$year.'</option>';	$year = $year+1;
											echo '<option value="'.$year.'" SELECTED>'.$year.'</option>';		$year = $year+1;
											echo '<option value="'.$year.'">'.$year .'</option>';
?>
										</select></td>
				   </tr>
				</table>
			</fieldset>
			<br>
			<fieldset id="formulario">
				<legend> Proyectos </legend>
				Agregue a la lista los proyectos que necesita ver en el sistema, en caso de necesitar mas de 10 eliga la opcion "TODOS LOS PROYECTOS":
				<table class="tablaForm" border="0">
				   <tr>
					<td colspan="">
						<select id="proyectos" name="proyectos">
							<option value=""> - - - </option>
							<option value="todos"> TODOS LOS PROYECTOS </option>
<?php
										$registros = mysqli_query($con, "SELECT nombreProyecto, numeroProyecto FROM proyectos ORDER BY numeroProyecto")
											or die(mysqli_error($con));

										while($reg = mysqli_fetch_array($registros))
										{
											echo '<option value="'.$reg['numeroProyecto'].'">'.$reg['numeroProyecto'].' - '.$reg['nombreProyecto'].'</option>';
										}?>

					</td>
					<td>
						<input type="button" value="Agregar" onClick="agregar()">
					</td>
				   </tr>
				   <tr>
					<td>
						 Aqu&iacute; se ir&aacute;n listando los proyectos agregados (Max. 10):
					</td>
				   </tr>

				   <tr>
					<td>
						<textarea name="lista" readonly></textarea>
					</td>
					<td>
						<input type="button" value="Limpiar" onClick="limpiar()">
					</td>
				   </tr>

				   <tr>
					<td>
						<input type="submit" id="submit" value="Enviar">
					</td>
				   </tr>

				</table>
			</fieldset>
		</fieldset>
	</body>
</html>
