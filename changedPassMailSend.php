<?php
    use PHPMailer\PHPMailer\PHPMailer;
    require 'vendor/autoload.php';
    $mail = new PHPMailer();
    $mail->CharSet = 'utf-8'; //Indicarem UTF-8 per poder visualitzar d'entre altres els accents
    $mail->IsSMTP();

    //Configuració del servidor de Correu
    //Modificar a 0 per eliminar msg error
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    
    //Credencials del compte GMAIL
    $mail->Username = 'email@gmail.com';
    $mail->Password = 'password';

    //Dades del correu electrònic
    $mail->SetFrom('emissor@gmail.com','imagiNest Team');
    $mail->Subject = 'Contraseña restablecida imagiNest';
    $mail->MsgHTML("<p>Se ha modificado la contraseña de la cuenta con email $userEmail, si no has sido tú quien ha restablecido la contraseña... ¡You have been HACKED!</p>");
    //$mail->addAttachment("fitxer.pdf");
    
    //Destinatari
    $address = $userEmail; //Li enviarem a l'adreça de correu registrada
    $mail->AddAddress($address, $userFirstName." ".$userLastName);

    //Enviament
    $result = $mail->Send();
    if(!$result){
        echo 'Error: ' . $mail->ErrorInfo;
    }