<?php
    use PHPMailer\PHPMailer\PHPMailer;
    require '../vendor/autoload.php';
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
    $mail->Username = 'imaginestprueba@gmail.com';
    $mail->Password = 'Educem00.';

    //Dades del correu electrònic
    $mail->SetFrom('emissor@gmail.com','imagiNest Team');
    $mail->Subject = 'Restablecer contraseña imagiNest';
    $mail->MsgHTML("<p>Se ha solicitado restablecer la contraseña para el usuario $username</p><a href='http://localhost/imaginest/php/resetPassword.php?code=$resetPassCode&mail=$userEmail'>¡Restablece tu contraseña ahora!</a>");
    //$mail->addAttachment("fitxer.pdf");
    
    //Destinatari
    $address = $userEmail; //Li enviarem a l'adreça de correu registrada
    $mail->AddAddress($address, $userFirstName." ".$userLastName);

    //Enviament
    $result = $mail->Send();
    if(!$result){
        echo 'Error: ' . $mail->ErrorInfo;
    }