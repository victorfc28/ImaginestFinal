<?php
    session_start();
    if(!isset($_SESSION["iduser"])){
        header('Location: ../index.php?redirected');
        exit;
    }else{
        //Carreguem l'idioma de la sessió
        $idioma = $_SESSION["language"];

        if($_SERVER["REQUEST_METHOD"] == "POST"){
		    if(isset($_POST["deleteCheck"])){
                //Desactivarem el compte de l'usuari
                require_once("../php/database_connect.php");
                try{
                    $sql = "UPDATE `users` SET active = 0 WHERE iduser=:iduser";
                        $update = $db->prepare($sql);
                        $update->execute(array(
                            ':iduser' => $_SESSION["iduser"]
                        ));
                        if($update){
                            //Obtindrem nom i cognoms de l'usuari per enviar-li el mail
                            $sql = "SELECT mail,userFirstName,userLastName FROM `users` WHERE iduser=:iduser";
                            $usuaris = $db->prepare($sql);
                            $usuaris->execute(array(
                                ':iduser' => $_SESSION["iduser"]
                            ));
                            if($usuaris){
                                foreach ($usuaris as $fila) {
                                    $userEmail = $fila['mail'];
                                    $userFirstName = $fila['userFirstName'];
                                    $userLastName = $fila['userLastName'];
                                }
                            }
                            //Si s'ha desactivat el compte tancarem la sessió de l'usuari i el notificarem que s'ha desactivat el compte
                            require_once("../php/deactivateUserMailSend.php");
                            header('Location: ../logout.php?deactivate');
                            exit;
                        }
                }catch(PDOException $e){
                    echo 'Error con la BDs: ' . $e->getMessage();
                }
            }
        }
        //Carregarem el fitxer d'idiomes
        require_once("../langs/lang-".$idioma.".php");
    }
?>
<!DOCTYPE html>
<html lang=<?php echo "$idioma"?>>
<head>
    <title>imagiNest - <?php echo IDIOMES['DEACTIVATEACCOUNT']; ?></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../images/icons/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="../css/home.css">
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
            <a href="../settings/account_settings.php"><i class="fa fa-arrow-left"></i> <?php echo IDIOMES['BACKSETTINGS']; ?></a>
            <h2><?php echo IDIOMES['DEACTIVATEACCOUNT']; ?></h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                <p><strong><?php echo IDIOMES['ALERT']; ?></strong> <?php echo IDIOMES['DEACTIVATEACCOUNT_ALERT']; ?></p>
                <p><?php echo IDIOMES['DEACTIVATEACCOUNT_ALERT2']; ?></p>
                <input type="checkbox" id="deleteCheck" name="deleteCheck" required> <label for="deleteCheck"><?php echo IDIOMES['DEACTIVATEACCOUNT_CHECK']; ?></label><br>
                <input type="submit" value="<?php echo IDIOMES['DEACTIVATEACCOUNT']; ?>">
            </form>
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