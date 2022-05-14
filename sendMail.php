<html>
	<head></head>
	<body>
		<?php
		//Almacenamos variables para enviar el mensaje
			$nombre = $_POST['nombre'];
			$email = $_POST['email'];
			$asunto = $_POST['asunto'];
			$comentarios = $_POST['comentarios'];
			$fecha = date("d-m-Y (H:i:s)");

			header('Content-Type: text/html; charset=UTF-8');
		//Datos del mensaje
		$mensaje = '<h2>Comentario recibido desde la pag de SICOFI/Contacto</h2><br>
					<b>Fecha: </b>'.$fecha.'<br>
					<b>Nombre: </b>'.$nombre.' <br>
					<b>E-mail: </b>'.$email.'<br><br>
					<h3>Asunto: '.$asunto.'</h3><br>
					'.$comentarios;

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
		// $mail->Host = 'correo.inah.gob.mx';		---

		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		$mail->Port = 587;

		//Set the encryption system to use - ssl (deprecated) or tls
		$mail->SMTPSecure = 'tls';

		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;

		//Username to use for SMTP authentication - use full email address for gmail
		$mail->Username = "dieter_ramirez@inah.gob.mx";

		//Password to use for SMTP authentication
		$mail->Password = "administracion";

		//Set who the message is to be sent from
		$mail->setFrom("dieter_ramirez@inah.gob.mx", "Sicofi");

		//Set an alternative reply-to address
		//$mail->addReplyTo('dieter_ramirez@inah.gob.mx', 'Dieter Ramirez');
		$mail->addReplyTo('dieter_ramirez@inah.gob.mx', 'Dieter Ramirez');

		//Set who the message is to be sent to
		$mail->addAddress('dieter_ramirez@inah.gob.mx', 'Dieter Ramirez');
		$mail->addAddress('luis_serna@inah.gob.mx', 'Luis Serna');

		//$mail->addAddress('erickalangonzalez@gmail.com', 'Erick Gmail');

		//Set the subject line
		$mail->Subject = $asunto;

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
		?>
	<script languaje="javascript">
			location.href ="contacto.php";
	</script>
	</body>
</html>
