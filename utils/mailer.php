<?php
    // Incluye los archivos de PHPMailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require $dirBaseFile . '/src/PHPMailer/PHPMailer.php';
    require $dirBaseFile . '/src/PHPMailer/SMTP.php';
    require $dirBaseFile . '/src/PHPMailer/Exception.php';

    class Mailer 
    {
        public static function enviarEmail($dest, $asunto, $body)
        {
            $mail = new PHPMailer(true); // El 'true' habilita las excepciones

            // Configuración del servidor SMTP
            $mail->isSMTP(); // Usar SMTP
            $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
            $mail->SMTPAuth = true; // Habilitar autenticación SMTP
            $mail->Username = 'healthwaylp@gmail.com'; // Dirección de correo de Gmail
            $mail->Password = 'pavr ptrf ckbq dbaf'; // Contraseña de aplicación del email
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Habilitar encriptación SSL/TLS
            $mail->Port = 465; // Puerto SMTP para SSL/TLS

            // Remitente
            $mail->setFrom('healthwaylp@gmail.com', 'HealthWay SA.');

            try {
                // Destinatario
                $mail->addAddress($dest['email'], $dest['nombre'] . ' ' . $dest['apellido']);

                // Contenido del correo
                $mail->isHTML(true); // Formato HTML
                $mail->Subject = $asunto;
                $mail->Body    = $body;

                $mail->send();

                return true;
            } catch (Exception $e) {
                throw new Exception("El mensaje no pudo ser enviado. Error de PHPMailer: {$mail->ErrorInfo}");
            }
        }
        
    }

?>