<?php
	session_start();
	if(isset($_SESSION["username"])){
		header('Location: ./home.php');
		exit;
	}
	if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["secretUserEmail"]) && isset($_POST["secretPass"])){
            $userEmail = utf8_encode(base64_decode($_POST["secretUserEmail"]));
			$password = utf8_encode(base64_decode($_POST["secretPass"]));

			require_once("./database_connect.php");
			require_once("./user_login.php");
		}
	}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>imagiNest - Login</title>
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
	<?php
		if(isset($_GET["registered"])){
			echo '<script>alert("Usuario registrado correctamente")</script>';
		}
		else if(isset($_GET["logout"])){
			echo '<script>alert("Has cerrado sesión correctamente")</script>';
		}
		else if(isset($_GET["verified"])){
			echo '<script>alert("La cuenta se ha verificado correctamente")</script>';
		}
		else if(isset($_GET["sent"])){
			echo '<script>alert("Se ha enviado un email para poder restablecer la contraseña")</script>';
		}
		else if(isset($_GET["expired"])){
			echo '<script>alert("El enlace para restablecer la contraseña ha expirado")</script>';
		}
		else if(isset($_GET["passChanged"])){
			echo '<script>alert("Se ha restablecido la contraseña correctamente")</script>';
		}
        else if(isset($error) && $error==2){
            echo '<script>alert("Las credenciales son erróneas, vuelve a iniciar sesión")</script>';
		}
		else if(isset($error) && $error==1){
			echo '<script>alert("Cuenta inactiva")</script>';
		}
    ?>
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="./images/logo-imaginest.png" alt="imagiNest"><br><br>
					<p>A great photography web app!</p>
				</div>
				<!-- Els camps ja son requerits per defecte a la plantilla, ja que mostra a l'usuari que ha d'introduïr -->
				<form class="login100-form validate-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" onsubmit="return cod_Base64()">
					<span class="login100-form-title">imagiNest</span>
					<span class="login100-form-text">Accede a tu cuenta</span>
					<div class="wrap-input100 validate-input" data-validate="Introduce un email o nombre de usuario válido">
						<input id="userEmail" class="input100" type="text" name="userEmail" placeholder="Usuario o email" maxlength="40">
						<span class="focus-input100"></span>
						<input id="secretUserEmail" type="hidden" name="secretUserEmail">
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>
					<div class="wrap-input100 validate-input" data-validate="Introduce la contraseña">
						<input id="pass" class="input100" type="password" name="pass" placeholder="Contraseña">
						<span class="focus-input100"></span>
						<input id="secretPass" type="hidden" name="secretPass">
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">Iniciar sesión</button>
					</div>
					<div class="text-center p-t-12">
						<span class="txt1">¿Has olvidado</span>
						<a class="txt2" href="#" data-toggle="modal" data-target="#exampleModalCenter">tu usuario/email o contraseña?</a>
					</div>
					<div class="text-center p-t-136">
						<span class="txt1">¿No tienes cuenta imagiNest?</span>
						<a class="txt2" href="./register.php">¡Regístrate!<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i></a>
					</div>
				</form>
				<script>
                    function cod_Base64(){
                        document.getElementById('secretUserEmail').value = btoa(document.getElementById('userEmail').value);
                        document.getElementById('secretPass').value = btoa(document.getElementById('pass').value);
                        return true;
                    }
                </script>

				<!-- Pop up per restaurar la contrasenya -->
				<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLongTitle">¿Has olvidado tu contraseña?</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          							<span aria-hidden="true">&times;</span>
        						</button>
      						</div>
							<form action="<?php echo htmlspecialchars("./resetPasswordSend.php");?>" method="POST">
      							<div class="modal-body">
									<span class="login100-form-title">imagiNest</span>
									<span class="login100-form-text">Restablecer contraseña</span>
									<p>Introduce tu nombre de usuario o dirección de correo electrónico para restablecer la contraseña</p>
									<div class="wrap-input100">
										<input id="userEmailReset" class="input100" type="text" name="userEmailReset" placeholder="Usuario o email" maxlength="40" required>
											<span class="focus-input100"></span>
											<span class="symbol-input100">
												<i class="fa fa-envelope" aria-hidden="true"></i>
											</span>
									</div>	
								</div>
      							<div class="modal-footer">
        							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        							<button type="submit" class="btn btn-primary">Restablecer contraseña</button>
      							</div>
							</form>
    					</div>
  					</div>
				</div>
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