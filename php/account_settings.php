<?php
    session_start();
    if(!isset($_SESSION["iduser"])){
        header('Location: ./index.php?redirected');
        exit;
    }
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../images/icons/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="../css/.css">
    <link rel="stylesheet" type="text/css" href="../css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&family=Roboto:wght@100&display=swap" rel="stylesheet"> 
</head>
<body>
    <div class="contenedorlogo">
        <img class ="logo" src="../images/imaginestW.png"></img>
            <div class="navbartop">
                <a class="itemnavbar"href="../home.php">
                    <i class="material-icons nav__icon">home</i>
                </a>
                <a class ="itemnavbar" href="../logout.php" >
                    <i class="material-icons nav__icon">person</i>
                </a>
            </div>
    </div>
    <div class="contenedorCard">
        <div class="card">
            <a href="../home2.php"><i class="fa fa-arrow-left"></i> Volver a la página principal</a>
            <h2>Configuración de la cuenta</h2>
            <a href="./user_settings.php">Ajustes del usuario</a><br>
            <a href="./change_password.php">Cambiar contraseña</a><br>
            <a href="./deactivate_account.php">Desactivar cuenta</a><br>
        </div>    
    </div>
    <!-- Site footer -->
    <footer class="site-footer footer">
        <div class="textofoto">
            Copyright © 2021 All Rights Reserved by Imaginest.
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
</body>
</html>