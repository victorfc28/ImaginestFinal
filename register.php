<?php
	//He realitzat de més a més que les dades de l'usuari siguin xifrades a Base64 quan viatgi per POST.
	session_start();
	if(isset($_SESSION["username"])){
		header('Location: ./home.php');
		exit;
	}
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST["secretUsername"]) && isset($_POST["secretEmail"]) && isset($_POST["secretFirstName"]) && isset($_POST["secretLastName"]) && isset($_POST["secretPass"])){
            $username = strtolower(utf8_encode(base64_decode($_POST["secretUsername"])));
			$userEmail = strtolower(utf8_encode(base64_decode($_POST["secretEmail"])));
			$userFirstName = ucwords(strtolower(utf8_encode(base64_decode($_POST["secretFirstName"]))));
			$userLastName = ucwords(strtolower(utf8_encode(base64_decode($_POST["secretLastName"]))));
			$password = utf8_encode(base64_decode($_POST["secretPass"]));

			require_once("./database_connect.php");
			require_once("./user_insert.php");
		}
	}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>imagiNest - Registro</title>
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
		if(isset($count) && $count>0) echo '<script>alert("Ya hay una cuenta registrada con la dirección de email o nombre de usuario proporcionados")</script>';
	?>
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="./images/logo-imaginest.png" alt="imagiNest"><br><br>
					<p>A great photography web app!</p>
				</div>
				<!-- Els camps ja son requerits per defecte a la plantilla, ja que mostra a l'usuari que ha d'introduïr -->
				<form class="login100-form validate-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" onsubmit="return verifyPass()">
					<span class="login100-form-title">imagiNest</span>
					<span class="login100-form-text">Registro de usuario</span>
					<div class="wrap-input100 validate-input" data-validate="Introduce un nombre de usuario">
						<input id="username" class="input100" type="text" name="username" placeholder="Nombre de usuario*" maxlength="16">
						<span class="focus-input100"></span>
						<input id="secretUsername" type="hidden" name="secretUsername">
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>
					<div class="wrap-input100 validate-input" data-validate="Introduce una dirección de correo">
						<input id="userEmail" class="input100" type="email" name="mail" placeholder="Dirección de correo*" maxlength="40">
						<span class="focus-input100"></span>
						<input id="secretEmail" type="hidden" name="secretEmail">
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>
					<div class="wrap-input100 validate-input" data-validate="Introduce tu nombre">
						<input id="userFirstName" class="input100" type="text" name="userFirstName" placeholder="Nombre" maxlength="60">
						<span class="focus-input100"></span>
						<input id="secretFirstName" type="hidden" name="secretFirstName">
						<span class="symbol-input100">
							<i class="fa fa-font" aria-hidden="true"></i>
						</span>
					</div>
					<div class="wrap-input100 validate-input" data-validate="Introduce tu apellido">
						<input id="userLastName" class="input100" type="text" name="userLastName" placeholder="Apellidos" maxlength="120">
						<span class="focus-input100"></span>
						<input id="secretLastName" type="hidden" name="secretLastName">
						<span class="symbol-input100">
							<i class="fa fa-bold" aria-hidden="true"></i>
						</span>
					</div>
					<div class="wrap-input100 validate-input" data-validate="Introduce una contraseña">
						<input id="pass" class="input100" type="password" name="pass" placeholder="Contraseña*">
						<span class="focus-input100"></span>
						<input id="secretPass" type="hidden" name="secretPass">
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					<div class="wrap-input100 validate-input" data-validate="Vuelve a introducir la contraseña">
						<input id="passVerify" class="input100" type="password" name="passVerify" placeholder="Verificar contraseña*">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">Regístrate</button>
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
                        document.getElementById('secretUsername').value = btoa(document.getElementById('username').value);
                        document.getElementById('secretEmail').value = btoa(document.getElementById('userEmail').value);
						document.getElementById('secretFirstName').value = btoa(document.getElementById('userFirstName').value);
						document.getElementById('secretLastName').value = btoa(document.getElementById('userLastName').value);
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