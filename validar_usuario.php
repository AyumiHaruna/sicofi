<?php
	require_once('config.php');
	header('Content-Type: text/html; charset=UTF-8');
	$database = 'sicofi'.$_POST['anio'];

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("Problemas con la conexi&oacute;n a la Base de Datos");
	$con->query("SET NAMES 'utf8'");
	$id = $_POST["id"];
	$password = $_POST["password"];
	$anio = $_POST['anio'];

	$result = mysqli_query($con, "SELECT * FROM usuarios WHERE id = '$id' AND val = 1")
		or die(mysqli_error($con));

	if($row = mysqli_fetch_array($result))
	{
		if($row["password"] == $password)
		{
			session_start();
			$_SESSION['id'] = $id;
			$_SESSION['nivel'] = $row['nivel'];
			$_SESSION['anio'] = $anio;
			$nivel = $_SESSION['nivel'];
			$_SESSION['nombre'] = $row['nombre'];
			$nombre = $_SESSION['nombre'];
			$fecha = date('Y-m-d H:i:s');

			mysqli_query($con, "INSERT INTO bitacora(nombre, nivel, fecha)
						VALUES('$nombre', $nivel, '$fecha')")
							or die(mysqli_error($con));

			header("Location: index.php");
		}
		else
		{
			?>
				<script languaje="javascript">
					alert("Contraseña incorrecta");
					location.href = "index.php";
				</script>
			<?php
		}
	}
	else
	{
		?>
			<script languaje="javascript">
				alert("El nombre de usuario es incorrecto!!");
				location.href ="index.php";
			</script>
		<?php
	}

	mysqli_close($con);
?>
