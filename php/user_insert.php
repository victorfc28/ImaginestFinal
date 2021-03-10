<?php
    try{
        //Comprovem si l'usuari ja està registrat
        $sql = "SELECT mail,username FROM `users` WHERE mail=:mail OR username=:username";
        $usuaris = $db->prepare($sql);
        $usuaris->execute(array(
            ':mail' => $userEmail,
            ':username' => $username
        ));
        if($usuaris){
            $count = $usuaris->rowcount();
            if($count<1){
                //En cas que rowcount retorni 0 vol dir que no l'haurà trobat
                $activationCode = hash('sha256', rand());
                $sql = "INSERT INTO `users` values(:iduser,:mail,:username,:passHash,:userFirstName,:userLastName,sysdate(),:lastSignIn,:removeDate,:active,:activationDate,:activationCode,:resetPass,:resetPassExpiry,:resetPassCode)";
                $insert = $db->prepare($sql);
                $insert->execute(array(
                    ':iduser' => NULL,
                    ':mail' => $userEmail,
                    ':username' => $username,
                    ':passHash' => password_hash($password, PASSWORD_DEFAULT),
                    ':userFirstName' => $userFirstName,
                    ':userLastName' => $userLastName,
                    ':lastSignIn' => NULL,
                    ':removeDate' => NULL,
                    ':active' => 0,
                    ':activationDate' => NULL,
                    ':activationCode' => $activationCode,
                    ':resetPass' => NULL,
                    ':resetPassExpiry' => NULL,
                    ':resetPassCode' => NULL
                ));
                if($insert){
                    //Si s'ha inserit farem l'enviament del correu de confirmació
                    require_once("./registerMailSend.php");
                    //Farem la redirecció a la pàgina principal
                    header('Location: ../index.php?registered');
                    exit;
                }else{
                    print_r($db->errorinfo());
                }
            }
        }
    }catch(PDOException $e){
        echo 'Error con la BDs: ' . $e->getMessage();
    }