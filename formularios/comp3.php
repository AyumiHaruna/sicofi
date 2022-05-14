<?php
	require_once("../config.php");
	session_start();
	header('Content-Type: text/html; charset=UTF-8');

	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("Problema s con la conexi&oacute;n a la base de deatos");
		$con->query("SET NAMES 'utf8'");

	$proy = $_POST['proy'];

	$x = 1;
	while( $x != 999999 )
	{
		if ( !isset($_POST['no'.$x]))
		{	$x = 999999;	}
		else
		{

			$no[$x] = $_POST['no'.$x];

			if(!isset($_POST['ofMod'.$x]))
			{ $ofMod[$x] = 0; }
			else
			{ $ofMod[$x] = $_POST['ofMod'.$x]; }

			if(!isset($_POST['subComp'.$x]))
			{ $subComp[$x] = 0; }
			else
			{ $subComp[$x] = $_POST['subComp'.$x]; }

			$x++;
		}
	}

	/*for($y = 1; $y <= count($no); $y++)
	{
		echo $no[$y].$ofMod[$y].$subComp[$y]. '<br>';
	}*/


	for($y = 1; $y <= count($no); $y++)
	{
		mysqli_query($con, "UPDATE ingresos
						SET ofMod = $ofMod[$y],
						subComp = $subComp[$y]
						WHERE no = $no[$y]")
		or die(mysqli_error($con));
	}


	mysqli_close($con);

	header("location:../formularios/comp2.php?codigo=$proy");
?>
