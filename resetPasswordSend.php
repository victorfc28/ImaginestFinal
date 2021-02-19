<?php
    if(isset($_SESSION["username"])){
        header('Location: ./home.php');
        exit;
    }else if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["userEmailReset"])){
            require_once("./database_connect.php");
            try{
                //Comprovem si l'usuari està registrat
                $sql = "SELECT mail,username,userFirstName,userLastName FROM `users` WHERE mail=:mail OR username=:username";
                $usuaris = $db->prepare($sql);
                $usuaris->execute(array(
                    ':mail' => $_POST["userEmailReset"],
                    ':username' => $_POST["userEmailReset"]
                ));
                if($usuaris){
                    $count = $usuaris->rowcount();
                    if($count==1){
                        //En cas que rowcount retorni 0 vol dir que no l'haurà trobat
                        foreach ($usuaris as $fila) {
                            $resetPassCode = hash('sha256', rand());
                            $userEmail = $fila['mail'];
                            $username = $fila['username'];
                            $userFirstName = $fila['userFirstName'];
                            $userLastName = $fila['userLastName'];
                            $sql = "UPDATE `users` SET resetPass=:resetPass, resetPassExpiry=sysdate() + interval 30 minute, resetPassCode = :resetPassCode WHERE mail=:mail OR username=:username";
                            $update = $db->prepare($sql);
                            $update->execute(array(
                                ':mail' => $fila['mail'],
                                ':username' => $fila['username'],
                                ':resetPass' => 1,
                                ':resetPassCode' => $resetPassCode
                            ));
                            if($update){
                                //Si s'ha actualitzat farem l'enviament del correu de confirmació
                                require_once("./resetPassMailSend.php");
                                //Farem la redirecció a la pàgina principal
                                header('Location: ./index.php?sent');
                                exit;
                            }else{
                                print_r($db->errorinfo());
                            }
                        }
                    }else{
                        header('Location: ./index.php?redirected');
                        exit;
                    }
                }
            }catch(PDOException $e){
                echo 'Error con la BDs: ' . $e->getMessage();
            }
        }else{
            header('Location: ./index.php?redirected');
            exit;
        }
    }
?>