<?php
	require_once('../config.php');
	session_start();
	header('Content-Type: text/html; charset=UTF-8');
	$fecha = date("d-m-Y");

	$numProy = $_POST['numProy'];
	$no = $_POST['no'];

	if( !isset($_POST['fechaDep1'] ))
	{$fechaDep1 = '0000-00-00';} else{$fechaDep1 = $_POST['fechaDep1'];}

	if( !isset($_POST['fechaDep2']))
	{$fechaDep2 = '0000-00-00';} else{$fechaDep2 = $_POST['fechaDep2'];}

	if( !isset($_POST['noAut1'] ))
	{$noAut1 = 0;} else{$noAut1 = $_POST['noAut1'];}

	if( !isset($_POST['noAut2'] ))
	{$noAut2 = 0;} else{$noAut2 = $_POST['noAut2'];}

	$cap1_1 = $_POST['cap1_1'];
	$cap1_2 = $_POST['cap1_2'];
	$cap2_1 = $_POST['cap2_1'];
	$cap2_2 = $_POST['cap2_2'];
	$cap3_1 = $_POST['cap3_1'];
	$cap3_2 = $_POST['cap3_2'];
	$cap4_1 = $_POST['cap4_1'];
	$cap4_2 = $_POST['cap4_2'];
	$cap5_1 = $_POST['cap5_1'];
	$cap5_2 = $_POST['cap5_2'];

	$capT1 = 	$cap1_1 + $cap1_2;
	$capT2 = 	$cap2_1 + $cap2_2;
	$capT3 = 	$cap3_1 + $cap3_2;
	$capT4 = 	$cap4_1 + $cap4_2;
	$capT5 = 	$cap5_1 + $cap5_2;

	$validado = $_POST['validado'];

#INSERSI�N DE DATOS EN MYSQL
	$con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio'])
		or die("problema con la conexi&oacute;n a la base de datos");
		$con->query("SET NAMES 'utf8'");
echo "UPDATE ingresos SET
					fechaDep1 = '$fechaDep1',
					fechaDep2 = '$fechaDep2',
					noAut1 = $noAut1,
					noAut2 = $noAut2,
					cap1_1 = $cap1_1,
					cap1_2 = $cap1_2,
					cap2_1 = $cap2_1,
					cap2_2 = $cap2_2,
					cap3_1 = $cap3_1,
					cap3_2 = $cap3_2,
					cap4_1 = $cap4_1,
					cap4_2 = $cap4_2,
					cap5_1 = $cap5_1,
					cap5_2 = $cap5_2,
					capT1 = $capT1,
					capT2 = $capT2,
					capT3 = $capT3,
					capT4 = $capT4,
					capT5 = $capT5,
					validado = $validado
					WHERE no = $no";
	mysqli_query($con, "UPDATE ingresos SET
						fechaDep1 = '$fechaDep1',
						fechaDep2 = '$fechaDep2',
						noAut1 = $noAut1,
						noAut2 = $noAut2,
						cap1_1 = $cap1_1,
						cap1_2 = $cap1_2,
						cap2_1 = $cap2_1,
						cap2_2 = $cap2_2,
						cap3_1 = $cap3_1,
						cap3_2 = $cap3_2,
						cap4_1 = $cap4_1,
						cap4_2 = $cap4_2,
						cap5_1 = $cap5_1,
						cap5_2 = $cap5_2,
						capT1 = $capT1,
						capT2 = $capT2,
						capT3 = $capT3,
						capT4 = $capT4,
						capT5 = $capT5,
						validado = $validado
						WHERE no = $no")
		or die(mysqli_error($con));


	$ingresos = mysqli_query($con, "SELECT mes FROM ingresos WHERE no = $no")
			or die(mysqli_error($con));
	while($reg = mysqli_fetch_array($ingresos))
	{	$mes = $reg['mes']; 	}

//ELABORAMOS EL ENVIO DE CORREO A LOS ENCARGANDOS DE PROYECTO

	$proyecto = mysqli_query($con, "SELECT mail, nombre FROM usuarios
													WHERE proy1 = 999999 OR
													proy1 = $numProy OR proy2 = $numProy OR
													proy3 = $numProy OR proy4 = $numProy OR
													proy5 = $numProy OR proy6 = $numProy OR
													proy7 = $numProy OR proy8 = $numProy OR
													proy9 = $numProy OR proy10 = $numProy ")
					or die(mysqli_error($con));


//Determinamos los capitulos que recibieron recursos

	if($capT1 > 0)
	{
		$t1 = 'Cap. 1000';
	}
	else	{	$t1 = '';	}
	if($capT2 > 0)
	{
		if($t1 == '')
		{
			$t2 = 'Cap. 2000';
		}
		else
		{
			$t2 = ', Cap. 2000';
		}
	}
	else	{	$t2 = '';	}
	if($capT3 > 0)
	{
		if($t1 == '' && $t2 == '')
		{
			$t3 = 'Cap. 3000';
		}
		else{	$t3 = ', Cap. 3000';	}
	}
	else	{	$t3 = '';	}
	if($capT4 > 0)
	{
		if($t1 == '' && $t2 == '' && $t3 == '')
		{
			$t4 = 'Cap. 4000';
		}
		else{	$t4 = ', Cap. 4000';	}
	}
	else	{	$t4 = '';	}
	if($capT5 > 0)
	{
		if($t1 == '' && $t2 == '' && $t3 == '' && $t4 == '')
		{
			$t5 = 'Cap. 5000';
		}
		else{	$t5 = ', Cap. 5000';	}
	}
	else	{	$t5 = '';	}

//Conseguirmos el mes al que aplica el ingreso

	require '../class.phpmailer.php';

	while($reg = mysqli_fetch_array($proyecto))
	{
		if(isset($reg['mail']))
		{
			$mensaje = '<h2>Comentario recibido desde la pag de SICOFI</h2><br>
						<b>Fecha: </b>'.$fecha.'<br>	Aviso de la Subdirecci&oacute;n Administrativa<br>
						<h3>Asunto: Aviso SICOFI ingresos en cuenta para su Proyecto No.'.$numProy.'</h3><br>
						Estimado Responsable de Proyecto en la CNCPC<br>
						Me permito informarle que se han recibido ingresos para su proyecto con No. '.$numProy.'<br>
						En el mes de '.$mes.', para: '.$t1.$t2.$t3.$t4.$t5.'.<br>
						Favor de revisar los ingresos disponibles para tu proyecto en el sistema <a href="http://172.26.26.126/sicofi">SICOFI</a><br>
						Le enviamos un cordial saludo.';

			//Create a new PHPMailer instance
			$mail = new PHPMailer;

			//Tell PHPMailer to use SMTP
			$mail->isSMTP();

			//Enable SMTP debugging
			// 0 = off (for production use)
			// 1 = client messages
			// 2 = client and server messages
			$mail->SMTPDebug = 2;

			//Ask for HTML-friendly debug output
			$mail->Debugoutput = 'html';

			//Set the hostname of the mail server
			$mail->Host = 'correo.inah.gob.mx';

			//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
			$mail->Port = 587;

			//Set the encryption system to use - ssl (deprecated) or tls
			$mail->SMTPSecure = 'tls';

			//Whether to use SMTP authentication
			$mail->SMTPAuth = true;

			//Username to use for SMTP authentication - use full email address for gmail
			$mail->Username = $main_mail_id;

			//Password to use for SMTP authentication
			$mail->Password = $main_mail_pass;

			//Set who the message is to be sent from
			$mail->setFrom($main_mail_id, 'SICOFI');

			//Set an alternative reply-to address
			//$mail->addReplyTo('dieter_ramirez@inah.gob.mx', 'Erick');
			$mail->addReplyTo($main_mail_id, $main_mail_name);

			//Set who the message is to be sent to
			$mail->addAddress($reg['mail'], $reg['nombre']);

			//$mail->addAddress('erickalangonzalez@gmail.com', 'Erick Gmail');

			//Set the subject line
			$mail->Subject = 'Aviso SICOFI ingresos en cuenta para su Proyecto No.'.$numProy;

			//Read an HTML message body from an external file, convert referenced images to embedded,
			//convert HTML into a basic plain-text alternative body
			$mail->msgHTML($mensaje);
			//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));

			//Replace the plain text body with one created manually
			$mail->AltBody = 'This is a plain-text message body';

			//Attach an image file
			//$mail->addAttachment('images/phpmailer_mini.png');

			//send the message, check for errors
			if (!$mail->send()) {
				echo "Mailer Error: " . $mail->ErrorInfo;
			} else {
				echo "Message sent!";
			}
		}
	}

//------------------------------
	mysqli_close($con);

	echo '<script languaje="javascript">
			location.href ="../formularios/validaSF.php";
	</script>';

?>
