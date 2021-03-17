<?php
    session_start();
    if(!isset($_SESSION["iduser"])){
        header('Location: ../index.php?redirected');
        exit;
    }else{
        //Carreguem l'idioma de la sessió
        $idioma = $_SESSION["language"];

        //Carregarem el fitxer d'idiomes
        require_once("../langs/lang-".$idioma.".php");
    }
?>
<!DOCTYPE html>
<html lang=<?php echo "$idioma"?>>
<head>
    <title>imagiNest - <?php echo IDIOMES['ACCOUNT_SETTINGS']; ?></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../images/icons/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="../css/settings.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
    <div class="contenedorlogo">
        <img class ="logo" src="../images/imaginestW.png"></img>
            <div class="navbartop">
                <a class="itemnavbar"href="../home.php">
                    <i class="material-icons nav__icon">home</i>
                </a>
                <a class ="itemnavbar" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="material-icons nav__icon">person</i>
                </a>
                <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink">
                    <li><a class="dropdown-item" href="../profile.php"><?php echo IDIOMES['MYPROFILE']; ?></a></li>
                    <li><a class="dropdown-item" href="./account_settings.php"><?php echo IDIOMES['SETTINGS']; ?></a></li>
                    <li><a class="dropdown-item" href="../logout.php"><?php echo IDIOMES['LOGOUT_BUTTON']; ?></a></li>
                </ul>
            </div>
    </div>
    <div class="contenedorCard">
        <div class="card">
            <a href="../home.php"><i class="fa fa-arrow-left"></i> <?php echo IDIOMES['BACKHOME']; ?></a>
            <div class="contenedorOpciones">
            <h2 class ="tituloOpciones"><?php echo IDIOMES['ACCOUNT_SETTINGS']; ?></h2>
            <div class ="link"><a href="./user_settings.php"><?php echo IDIOMES['USER_SETTINGS']; ?></a></div>
            <div class ="link"><a  href="./change_password.php"><?php echo IDIOMES['CHANGEPASSWORD']; ?></a></div>
            <div class ="link"><a  href="./deactivate_account.php"><?php echo IDIOMES['DEACTIVATEACCOUNT']; ?></a></div>
        </div>    
    </div>
  <!-- Site footer -->
  <div class="footer">
    <div class="textofooter">
      Copyright © 2021 All Rights Reserved by Imaginest.
    </div>
  </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
</body>
</html>