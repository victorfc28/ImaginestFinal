<?php
    use PHPMailer\PHPMailer\PHPMailer;
    require 'vendor/autoload.php';
    $mail = new PHPMailer();
    $mail->CharSet = 'utf-8'; //Indicarem UTF-8 per poder visualitzar d'entre altres els accents
    $mail->IsSMTP();

    //Agafarem el contingut de la plantilla HTML
    $body = file_get_contents("emailTemplate.html");
    //Ara declarem els arrays per després reemplaçar-los
    $array1 = array("((code))","((mail))");
    $array2 = array($activationCode,$userEmail);
    $newBody = str_replace($array1,$array2,$body);

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
    $mail->isHTML(true); //Indicarem que el contingut del missatge es HTML
    $mail->Subject = 'Activa tu cuenta imagiNest';
    $mail->MsgHTML($newBody);
    //$mail->addAttachment("fitxer.pdf");
    
    //Destinatari
    $address = $userEmail; //Li enviarem a l'adreça de correu registrada
    $mail->AddAddress($address, $userFirstName." ".$userLastName);

    //Enviament
    $result = $mail->Send();
    if(!$result){
        echo 'Error: ' . $mail->ErrorInfo;
    }