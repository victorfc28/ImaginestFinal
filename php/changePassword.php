<?php
    if(isset($_SESSION["iduser"])){
        header('Location: ../home.php');
        exit;
    }else if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST["secretUserEmail"]) && isset($_POST["secretPass"])){
			$userEmail = utf8_encode(base64_decode($_POST["secretUserEmail"]));
			$password = utf8_encode(base64_decode($_POST["secretPass"]));

			require_once("./database_connect.php");
			try{
				$sql = "UPDATE `users` SET passHash=:passHash, resetPass=:resetPass, resetPassExpiry=:resetPassExpiry, resetPassCode=:resetPassCode WHERE mail=:mail";
                $update = $db->prepare($sql);
               	$update->execute(array(
                	':mail' => $userEmail,
					':passHash' => password_hash($password, PASSWORD_DEFAULT),
					':resetPass' => NULL,
					':resetPassExpiry' => NULL,
					':resetPassCode' => NULL
                ));
                if($update){
					//Si s'ha restablert la contrasenya farem l'enviament del correu de confirmació
                    require_once("./changedPassMailSend.php");
					//Farem la redirecció a la pàgina principal
                  	header('Location: ../index.php?passChanged');
                  	exit;
				}else{
					print_r($db->errorinfo());
				}
			}catch(PDOException $e){
					echo 'Error con la BDs: ' . $e->getMessage();
			}
		}
    }else{
		header('Location: ../index.php?redirected');
		exit;
	}
?>