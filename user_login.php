<?php
    try{
        //Comprovem si l'usuari existeix
        $sql = "SELECT username,passHash,active FROM `users` WHERE mail=:mail OR username=:username";
        $usuaris = $db->prepare($sql);
        $usuaris->execute(array(
            ':mail' => $userEmail,
            ':username' => $userEmail
        ));
        if($usuaris){
            $count = $usuaris->rowcount();
            if($count==1){
                //Comprovarem si la contrasenya es correcte
                foreach ($usuaris as $fila) {
                    if($fila['passHash']==password_verify($password,$fila['passHash'])){
                        if($fila['active']==1){
                            $sql = "UPDATE `users` SET lastSignIn = sysdate() WHERE mail=:mail OR username=:username";
                            $update = $db->prepare($sql);
                            $update->execute(array(
                                ':mail' => $userEmail,
                                ':username' => $userEmail
                            ));
                            if($update){
                                //Si s'han verificat les credencials i s'ha actualitzat la data d'accés iniciarem la sessió
                                session_start();
                                $_SESSION["username"] = $fila['username'];
                                header('Location: home.php');
                                exit;
                            }else{
                                print_r($db->errorinfo());
                            }
                        }else $error=1;
                    }else $error=2;
                }
            }else $error=2;
        }
    }catch(PDOException $e){
        echo 'Error con la BDs: ' . $e->getMessage();
    }