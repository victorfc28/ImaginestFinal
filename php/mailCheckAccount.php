<?php
    if(isset($_SESSION["iduser"])){
        header('Location: ./home.php');
        exit;
    }else{
        if(isset($_GET["code"]) && isset($_GET["mail"])){
          //Comprovem si el codi i el correu són correctes
          require_once("./database_connect.php");
          try{
            $sql = "SELECT activationCode FROM `users` WHERE activationCode=:code AND mail=:mail";
            $usuaris = $db->prepare($sql);
            $usuaris->execute(array(
              ':code' => $_GET["code"],
              ':mail' => $_GET["mail"]
            ));
            if($usuaris){
              $count = $usuaris->rowcount();
              if($count==1){
                //Activarem el compte de l'usuari
                $sql = "UPDATE `users` SET active=:active, activationDate=sysdate(), activationCode=:activationCode WHERE mail=:mail";
                $update = $db->prepare($sql);
                $update->execute(array(
                  ':mail' => $_GET["mail"],
                  ':active' => 1,
                  ':activationCode' => NULL
                ));
                if($update){
                  header('Location: ../index.php?verified');
                  exit;
                }else{
                  print_r($db->errorinfo());
                }
              }else{
                header('Location: ../index.php?redirected');
              }
            }
          }catch(PDOException $e){
            echo 'Error con la BDs: ' . $e->getMessage();
          }
        }
        else{
          header('Location: ../index.php?redirected');
          exit;
        }
    }
?>