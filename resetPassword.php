<?php
    if(isset($_SESSION["username"])){
        header('Location: ./home.php');
        exit;
	}else if(isset($_GET["code"]) && isset($_GET["mail"])){
        //Comprovem si el codi i el correu són correctes
        require_once("./database_connect.php");
        try{
            $sql = "SELECT resetPassCode,resetPassExpiry FROM `users` WHERE resetPassCode=:code AND mail=:mail";
            $usuaris = $db->prepare($sql);
            $usuaris->execute(array(
                ':code' => $_GET["code"],
                ':mail' => $_GET["mail"]
            ));
            if($usuaris){
                $count = $usuaris->rowcount();
                if($count==1){
                    //Comprovarem si el codi es vàlid i si ha expirat
                   	foreach ($usuaris as $fila) {
						if($fila['resetPassCode']==$_GET["code"]){
							$expiryTime = $fila['resetPassExpiry'];
							if(strtotime("now")>(strtotime($expiryTime))){
								//En cas que hagi expirat tornarem a la pàgina principal
								header('Location: ./index.php?expired');
            					exit;
							}
						}else{
							header('Location: ./index.php?redirected');
							exit;
						}
					}
				}else{
					header('Location: ./index.php?redirected');
					exit;
				}
            }else{
				header('Location: ./index.php?redirected');
				exit;
			}
        }catch(PDOException $e){
            echo 'Error con la BDs: ' . $e->getMessage();
        }
    }else{
        header('Location: ./index.php?redirected');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>imagiNest - Restablecer contraseña</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
	<style type="text/css" media="screen">
		<?php
			//Farem que la imatge de fons canviï entre els 3 wallpapers
			$images = array("bow-lake-5854210_1920.jpg", "leaves-5839550_1920.jpg", "tree-5887086_1920.jpg");
			$value = rand(0,count($images)-1); //Utilitzarem la funció rand(Valor mínim, Valor màxim)
			echo ".container-login100{ background: url('./images/wallpaper/".$images[$value]."') no-repeat top right fixed;
				background-size: cover;
				-moz-background-size: cover;
				-webkit-background-size: cover;
				-o-background-size: cover;
			}";
		?> 
	</style>
</head>
<body>
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="./images/logo-imaginest.png" alt="imagiNest"><br><br>
					<p>A great photography web app!</p>
				</div>
				<!-- Els camps ja son requerits per defecte a la plantilla, ja que mostra a l'usuari que ha d'introduïr -->
				<form class="login100-form validate-form" action="<?php echo htmlspecialchars("./changePassword.php");?>" method="POST" onsubmit="return verifyPass()">
					<span class="login100-form-title">imagiNest</span>
					<span class="login100-form-text">Restablecer contraseña</span>
					<div class="wrap-input100">
						<input id="userEmail" class="input100" type="text" name="userEmail" value="<?php echo $_GET["mail"]?>" maxlength="40" readonly>
						<span class="focus-input100"></span>
						<input id="secretUserEmail" type="hidden" name="secretUserEmail">
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>
					<div class="wrap-input100 validate-input" data-validate="Introduce una nueva contraseña">
						<input id="pass" class="input100" type="password" name="pass" placeholder="Nueva contraseña*">
						<span class="focus-input100"></span>
						<input id="secretPass" type="hidden" name="secretPass">
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					<div class="wrap-input100 validate-input" data-validate="Vuelve a introducir la nueva contraseña">
						<input id="passVerify" class="input100" type="password" name="passVerify" placeholder="Verificar nueva contraseña*">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
                    <div class="container-login100-form-btn">
						<button class="login100-form-btn">Restablecer contraseña</button>
					</div>
                    <div class="text-center p-t-136">
						<span class="txt1">¿Tienes cuenta imagiNest?</span>
						<a class="txt2" href="./index.php">¡Inicia sesión!<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i></a>
					</div>
				</form>
				<script>
					function verifyPass(){
						if(document.getElementById('pass').value == document.getElementById('passVerify').value){
							cod_Base64();
						}else{
							alert('Las contraseñas no coinciden');
							return false;
						}
					}
                    function cod_Base64(){
						document.getElementById('secretUserEmail').value = btoa(document.getElementById('userEmail').value);
						document.getElementById('secretPass').value = btoa(document.getElementById('pass').value);
                        return true;
                    }
                </script>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>