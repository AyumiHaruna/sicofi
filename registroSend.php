<html>
	<head></head>
	<body>
		<?php
		require_once('config.php');
		header('Content-Type: text/html; charset=UTF-8');
		$anio = $_POST['anio'];


		$con = mysqli_connect($server, $user, $pass, $database.$anio)
			or die("problema con la conexi&oacute;n a la base de datos");
			$con->query("SET NAMES 'utf8'");

		//Almacenamos variables para enviar el mensaje
			$nombre = $_POST['nombre'];
			$id = $_POST['id'];
			$pass = $_POST['pass1'];
			$proyectos = $_POST['lista'];
			$fecha = date("d-m-Y (H:i:s)");
			$mail = $_POST['mail'];

		//Dividimos la cadena de caracteres para insertar en DB

			if($_POST['lista'] == 'todos')
			{
				$proy[1] = 999999;
				$x = 2;
			}
			else
			{
				$proy = explode(" - ", $proyectos);
				$x = 1;
			}

			while($x <= 10)
			{
				if(!isset($proy[$x]))
				{	$proy[$x] = '';		}
				else
				{	$proy[$x] = (int)$proy[$x]; }
				$x++;
			}

		//Buscamos el ultimo usuario agregado a la Base e Datos

		$ultimo  = mysqli_query($con, "SELECT no FROM usuarios ORDER BY no DESC LIMIT 1")
			or die(mysqli_error($con));

		if($reg = mysqli_fetch_array($ultimo))
		{
			$no = $reg['no']+1;
		}

		//Insertamos el registro a la base de datos y lo dejamos sin validar

		mysqli_query($con, "INSERT INTO usuarios VALUES($no, '$id', '$pass', '$nombre', 4, '$mail',
							'$proy[1]', '$proy[2]', '$proy[3]', '$proy[4]', '$proy[5]', '$proy[6]',
							'$proy[7]', '$proy[8]', '$proy[9]', '$proy[10]', 0)")
			or die(mysqli_error($con));


		//Datos del mensaje
		$mensaje = '<h2>Comentario recibido desde la pag de SICOFI/ REGISTRO</h2><br>
					<b>Fecha: </b>'.$fecha.'<br>
					<h3>Asunto: Solicitud de Alta (Usuarios Sicofi) </h3><br>
					<br>
					<p> Se recibi&oacute; una solicitud para agregar un usuario con los siguietnes datos<br>
					Nombre: '.$nombre.'<br>
					ID: '.$id.'<br>
					Password: '.$pass.'<br>
					Mail: '.$mail.'<br>
					Con los siguientes proyectos: '.$proyectos.'<br>
					<br>
					Para Validar los datos y agregarlos a la DB favor de dar click en el siguiente enlace:  (*Antes debe estar Logueado en SICOFI)<br>
					<a href="http://172.26.26.142/sicofi/registroVal.php?no='.$no.'&anio='.$anio.'" target="_blank"> VALIDAR USUARIO EN LA DB </a><br>
					<br>
					Por seguridad, una vez validado el usuario Favor de eliminar este mensaje de la bandeja de correos
					<br>';


		//Codigo de envio de mensaje

		require 'class.phpmailer.php';

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
		$mail->setFrom($main_mail_id, "Sicofi");

		//Set an alternative reply-to address
		$mail->addReplyTo($main_mail_id, $main_mail_name);

		//Set who the message is to be sent to
		$mail->addAddress($main_mail_id, $main_mail_name);

		//$mail->addAddress('erickalangonzalez@gmail.com', 'Erick Gmail');

		//Set the subject line
		$mail->Subject = 'Solicitud de Alta USUARIOS SICOFI';

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
		mysqli_close($con);
		?>
	<script languaje="javascript">
			alert('Solicitud Enviada');
			location.href ="registro.php";
	</script>
	</body>
</html>
