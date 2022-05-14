<?php 	require_once('menu.php');
		require_once('config.php');
		header('Content-Type: text/html; charset=UTF-8');


		$anio = $_REQUEST['anio'];
		$no = $_REQUEST['no'];

//CONEXION
		$con = mysqli_connect($server, $user, $pass, $database.$anio)
			or die("Problemas con la conexi&oacute;n a la base de datos");
			$con->query("SET NAMES 'utf8'");

//B�SQUEDAS
		$usuario = mysqli_query($con, "SELECT * FROM usuarios WHERE no = $no")
				or die(mysqli_error($con));

		if($reg = mysqli_fetch_array($usuario))
		{
			$id = $reg['id'];				$password = $reg['password'];
			$nombre = $reg['nombre'];		$mail = $reg['mail'];
			$proy1 = $reg['proy1'];			$proy2 = $reg['proy2'];
			$proy3 = $reg['proy3'];			$proy4 = $reg['proy4'];
			$proy5 = $reg['proy5'];			$proy6 = $reg['proy6'];
			$proy7 = $reg['proy7'];			$proy8 = $reg['proy8'];
			$proy9 = $reg['proy9'];			$proy10 = $reg['proy10'];
			$val = $reg['val'];
		}

?>

<html>
	<head>
		<title> VAlidacion de Registro de Usuarios </title>

		<link rel="stylesheet" type="text/css" href="css2/menu.css"></link>
		<link rel="stylesheet" type="text/css" href="css2/registro.css"></link>

		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/script.js"></script>		<!-- Efectos del Men� -->

		<script type="text/javascript">


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

		</script>
	</head>

	<body>
		<?php
				session_start();

			if($_SESSION == NULL)
		{
			echo '<script languaje="javascript">
					alert("Area restringida, redireccionando...");
					location.href="index.php";
					</script>';
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
			}

echo'
<!-- IIIIIIIIIIIIIIIIIIIIIIIIIIII 	Aqui se inicia el formulario  IIIIIIIIIIIIIIIIIIIIIIIIIIII -->
		<fieldset id="base">
		<fieldset id="formulario">
			<h2> Validaci&oacute;n de Registro de Usuario </h2>
				<form method="post" action="registroValSend.php" id="form1" name="form1">
				<input type="hidden" name="anio" value="'.$anio.'">
				<input type="hidden" name="no" value="'.$no.'">

				Usuario Validado?';
						if($val == '0'){ echo'    NO <input type="radio" name="val" value="0"  checked>  SI <input type="radio" name="val" value="1">';}
							else{ echo'     NO <input type="radio" name="val" value="0">  SI <input type="radio" name="val" value="1" checked>';}

echo'			<table class="tablaForm" border="0">
				   <tr>
						<td colspan="1">Nombre:</td>
						<td colspan="1"><input type="text" name="nombre" onChange="conMayusculas(this)" value="'.$nombre.'" required></td>
				   </tr>
				   <tr>
						<td colspan="1">ID:</td>
						<td colspan="1"><input type="text" name="id" value="'.$id.'" required></td>
				   </tr>
				   <tr>
						<td colspan="1">Password:</td>
						<td colspan="1"><input type="password" name="pass1" id="pass1" value="'.$password.'" required></td>
				   </tr>

				   <tr>
						<td colspan="1">@EMAIL:</td>
						<td colspan="1"><input type="mail" name="mail" id="mail" value="'.$mail.'" required></td>
				   </tr>
				 </table>
				 <table class="tablaForm" border="0">
				   <tr>
						<td colspan="4">Lista de Proyectos Ingresados</td>
					</tr>
					<tr>
						<td>Proyecto 1:</td>	<td><input type="text" name="proy1" value="'.$proy1.'" onkeypress="return justNumbers(event);" ></td>
						<td>Proyecto 2:</td>	<td><input type="text" name="proy2" value="'.$proy2.'" onkeypress="return justNumbers(event);" ></td>
					<tr>

					<tr>
						<td>Proyecto 3:</td>	<td><input type="text" name="proy3" value="'.$proy3.'" onkeypress="return justNumbers(event);" ></td>
						<td>Proyecto 4:</td>	<td><input type="text" name="proy4" value="'.$proy4.'" onkeypress="return justNumbers(event);" ></td>
					<tr>

					<tr>
						<td>Proyecto 5:</td>	<td><input type="text" name="proy5" value="'.$proy5.'" onkeypress="return justNumbers(event);" ></td>
						<td>Proyecto 6:</td>	<td><input type="text" name="proy6" value="'.$proy6.'" onkeypress="return justNumbers(event);" ></td>
					<tr>

					<tr>
						<td>Proyecto 7:</td>	<td><input type="text" name="proy7" value="'.$proy7.'" onkeypress="return justNumbers(event);" ></td>
						<td>Proyecto 8:</td>	<td><input type="text" name="proy8" value="'.$proy8.'" onkeypress="return justNumbers(event);" ></td>
					<tr>

					<tr>
						<td>Proyecto 9:</td>	<td><input type="text" name="proy9" value="'.$proy9.'" onkeypress="return justNumbers(event);" ></td>
						<td>Proyecto 10:</td>	<td><input type="text" name="proy10" value="'.$proy10.'" onkeypress="return justNumbers(event);" ></td>
					<tr>

				   <tr>
					<td colspan="4">
						<input type="submit" id="submit" value="Enviar">
					</td>
				   </tr>

				</table>
				</form>
			</fieldset>
		</fieldset>';

		mysqli_close($con);
?>
	</body>
</html>
