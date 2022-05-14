<?php
	require_once('config.php');

	$no = $_POST['no'];
	$anio = $_POST['anio'];
	$nombre = $_POST['nombre'];
	$id = $_POST['id'];
	$pass1 = $_POST['pass1'];
	$mail = $_POST['mail'];
	$val = $_POST['val'];


	$con = mysqli_connect($server, $user, $pass, $database.$anio)
			or die("problema con la conexi&oacute;n a la base de datos");
			$con->query("SET NAMES 'utf8'");

	$proy1 = $_POST['proy1'];
	$proy2 = $_POST['proy2'];
	$proy3 = $_POST['proy3'];
	$proy4 = $_POST['proy4'];
	$proy5 = $_POST['proy5'];
	$proy6 = $_POST['proy6'];
	$proy7 = $_POST['proy7'];
	$proy8 = $_POST['proy8'];
	$proy9 = $_POST['proy9'];
	$proy10 = $_POST['proy10'];



	mysqli_query($con, "UPDATE usuarios SET nombre = '$nombre',
										id = '$id',		password = '$pass1',
										mail = '$mail',		proy1 = '$proy1',
										proy2 = '$proy2',	proy3 = '$proy3',
										proy4 = '$proy4',	proy5 = '$proy5',
										proy6 = '$proy6',	proy7 = '$proy7',
										proy8 = '$proy8',	proy9 = '$proy9',
										proy10 = '$proy10',	val = $val
								WHERE no = $no")
		or die(mysqli_error($con));

	header('location: index.php');

	mysqli_close($con);


?>
