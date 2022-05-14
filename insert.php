<?php require_once('config.php');
header('Content-Type: text/html; charset=UTF-8');?>
<html>
	<head>
		<title> INSERT </title>
	</head>

	<body>
		<form method="post" action="insert2.php"" name="form1">


			ID: <input type="text" name="id" required><br>
			PASSWORD: <input type="text" name="password" required><br>
			NOMBRE: <input type="text" name="nombre" required><br>
			NIVEL: <select name="nivel" required>
						<option value="1"> 1 </option>
						<option value="2"> 2 </option>
						<option value="3"> 3 </option>
						<option value="4"> 4 </option>
					</select> <br>
			PROYECTO 1: <select name="proy1">

					<option value="999999"> 999999 </option>


					<?php
						$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
							or die("Problemas con la conexi&oacute;n a la base de datos");
							$con->query("SET NAMES 'utf8'");
						$registros = mysqli_query($con, "SELECT nombreProyecto, numeroProyecto FROM proyectos ORDER BY numeroProyecto")
							or die(mysqli_error($con));

							while($reg = mysqli_fetch_array($registros))
							{
								echo '<option value="'.$reg['numeroProyecto'].'">'.$reg['numeroProyecto'].' - '.$reg['nombreProyecto'].'</option>';
							}
					?>
					</select><br>

			PROYECTO 2: <select name="proy2">
					<option value="">VACIO</option>
					<?php
						$registros = mysqli_query($con, "SELECT nombreProyecto, numeroProyecto FROM proyectos ORDER BY numeroProyecto")
							or die(mysqli_error($con));

							while($reg = mysqli_fetch_array($registros))
							{
								echo '<option value="'.$reg['numeroProyecto'].'">'.$reg['numeroProyecto'].' - '.$reg['nombreProyecto'].'</option>';
							}
					?>
					</select><br>

			PROYECTO 3: <select name="proy3">
					<option value="">VACIO</option>
					<?php
						$registros = mysqli_query($con, "SELECT nombreProyecto, numeroProyecto FROM proyectos ORDER BY numeroProyecto")
							or die(mysqli_error($con));

							while($reg = mysqli_fetch_array($registros))
							{
								echo '<option value="'.$reg['numeroProyecto'].'">'.$reg['numeroProyecto'].' - '.$reg['nombreProyecto'].'</option>';
							}
					?>
					</select><br>

			PROYECTO 4: <select name="proy4">
					<option value="">VACIO</option>
					<?php
						$registros = mysqli_query($con, "SELECT nombreProyecto, numeroProyecto FROM proyectos ORDER BY numeroProyecto")
							or die(mysqli_error($con));

							while($reg = mysqli_fetch_array($registros))
							{
								echo '<option value="'.$reg['numeroProyecto'].'">'.$reg['numeroProyecto'].' - '.$reg['nombreProyecto'].'</option>';
							}
					?>
					</select><br>

			PROYECTO 5: <select name="proy5">
					<option value="">VACIO</option>
					<?php
						$registros = mysqli_query($con, "SELECT nombreProyecto, numeroProyecto FROM proyectos ORDER BY numeroProyecto")
							or die(mysqli_error($con));

							while($reg = mysqli_fetch_array($registros))
							{
								echo '<option value="'.$reg['numeroProyecto'].'">'.$reg['numeroProyecto'].' - '.$reg['nombreProyecto'].'</option>';
							}
					?>
					</select><br>

			PROYECTO 6: <select name="proy6">
					<option value="">VACIO</option>
					<?php
						$registros = mysqli_query($con, "SELECT nombreProyecto, numeroProyecto FROM proyectos ORDER BY numeroProyecto")
							or die(mysqli_error($con));

							while($reg = mysqli_fetch_array($registros))
							{
								echo '<option value="'.$reg['numeroProyecto'].'">'.$reg['numeroProyecto'].' - '.$reg['nombreProyecto'].'</option>';
							}
					?>
					</select><br>

			PROYECTO 7: <select name="proy7">
					<option value="">VACIO</option>
					<?php
						$registros = mysqli_query($con, "SELECT nombreProyecto, numeroProyecto FROM proyectos ORDER BY numeroProyecto")
							or die(mysqli_error($con));

							while($reg = mysqli_fetch_array($registros))
							{
								echo '<option value="'.$reg['numeroProyecto'].'">'.$reg['numeroProyecto'].' - '.$reg['nombreProyecto'].'</option>';
							}
					?>
					</select><br>

			PROYECTO 8: <select name="proy8">
					<option value="">VACIO</option>
					<?php
						$registros = mysqli_query($con, "SELECT nombreProyecto, numeroProyecto FROM proyectos ORDER BY numeroProyecto")
							or die(mysqli_error($con));

							while($reg = mysqli_fetch_array($registros))
							{
								echo '<option value="'.$reg['numeroProyecto'].'">'.$reg['numeroProyecto'].' - '.$reg['nombreProyecto'].'</option>';
							}
					?>
					</select><br>

			PROYECTO 9: <select name="proy9">
					<option value="">VACIO</option>
					<?php
						$registros = mysqli_query($con, "SELECT nombreProyecto, numeroProyecto FROM proyectos ORDER BY numeroProyecto")
							or die(mysqli_error($con));

							while($reg = mysqli_fetch_array($registros))
							{
								echo '<option value="'.$reg['numeroProyecto'].'">'.$reg['numeroProyecto'].' - '.$reg['nombreProyecto'].'</option>';
							}
					?>
					</select><br>

			PROYECTO 10: <select name="proy10">
					<option value="">VACIO</option>
					<?php
						$registros = mysqli_query($con, "SELECT nombreProyecto, numeroProyecto FROM proyectos ORDER BY numeroProyecto")
							or die(mysqli_error($con));

							while($reg = mysqli_fetch_array($registros))
							{
								echo '<option value="'.$reg['numeroProyecto'].'">'.$reg['numeroProyecto'].' - '.$reg['nombreProyecto'].'</option>';
							}
					?>
					</select><br>
			<input type="submit" value="Enviar">

		</form>
	</body>
</html>
