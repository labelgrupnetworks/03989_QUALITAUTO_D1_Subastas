<?php
define("NAME_NOMBRE", 'nombre');
define("NAME_MENSAJE", 'message');
define("NAME_PHONE", 'telefono');
define("NAME_EMAIL", 'email');

function getFechaFormateada(){
    $tz = 'Europe/Madrid';
    $timestamp = time();
    $dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
    $dt->setTimestamp($timestamp); //adjust the object to correct timestamp
    return $dt->format('d-m-Y H:i:s');
}


if (isset($_POST[NAME_NOMBRE], $_POST[NAME_EMAIL])) {

    
    $nombre = $_POST[NAME_NOMBRE];
    $mensaje = $_POST[NAME_MENSAJE];
    $telefono = $_POST[NAME_PHONE];
    $email = $_POST[NAME_EMAIL]; 


    //Comprobación de campos vacíos

    if (trim($nombre)  != "" && trim($email) != "") {
        require 'phpmailer/PHPMailerAutoload.php';
        require 'phpmailer/class.phpmailer.php';

        $body = "<h1>Datos - Landing Colecciones Ansorena</h1>" .
                "<p>Nombre: " . $nombre . " </p>" .
                 "<p>Email: " . $email . " </p>" .
                "<p>Telefono: " . $telefono . "</p>" .
                "<p>Mensaje: " . $mensaje . "</p>";
 
        $mail = new PHPMailer();


        $mail->CharSet = "UTF-8";

       $mail->From = 'subastasansorena@gmail.com';
        $mail->FromName = 'Ansorena';
        
        
      $mail->addAddress('galeria@ansorena.com'); // Add a recipient
        $mail->addAddress('mario@e-strategia.es'); // Add a recipient
        

        $mail->Subject = 'Contacto desde Landing Colecciones Ansorena';

        $mail->isHTML(true);

        $mail->Body = $body;

            if ($mail->send()) {
                try {
                    $rutaFichero = "csv/contactos.csv";
                    $fichero = fopen($rutaFichero, "a");
                    if(is_file($rutaFichero) && is_readable($rutaFichero) && filesize($rutaFichero) == 0){
                        $lineaCSV = array("Nombre", "Email", "Teléfono", "Mensaje", "Fecha de alta");
                        fputcsv($fichero, $lineaCSV);
                    }                        

                    $lineaCSV = array($nombre, $email, $telefono, $mensaje, getFechaFormateada());
                    fputcsv($fichero, $lineaCSV);
                    fclose($fichero);
                } catch (Exception $e) {}
            header('Location: https://galeria.ansorena.com/es/exposicion_actual/gracias/');
        } else {
            echo $mail->ErrorInfo;
        }
    } else {
        header('Location: https://galeria.ansorena.com/es/exposicion_actual/gracias/');
    }
}


