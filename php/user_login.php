<?php
    try{
        //Comprovem si l'usuari existeix
        $sql = "SELECT iduser,username,passHash,active FROM `users` WHERE mail=:mail OR username=:username";
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
                                if(!isset($_SESSION)) 
                                { 
                                    session_start(); 
                                } 
                                $_SESSION["iduser"] = $fila['iduser'];
                                setcookie('logged',0,time()+3600247); //Crearem una cookie per indicar que l'usuari acaba d'entrar i aixi poder mostrar-li la seva última foto penjada
                                header('Location: ./php/home.php');
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