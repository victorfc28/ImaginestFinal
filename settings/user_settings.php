<?php
session_start();
if (!isset($_SESSION["iduser"])) {
    header('Location: ../index.php?redirected');
    exit;
} else {
    //Carreguem l'idioma de la sessió
    $idioma = $_SESSION["language"];

    require_once "../php/database_connect.php";
    try {
        //Obtindrem les dades de l'usuari
        $sql = "SELECT username,userFirstName,userLastName,language FROM `users` WHERE iduser=:iduser";
        $dadesUsuari = $db->prepare($sql);
        $dadesUsuari->execute(array(
            ':iduser' => $_SESSION["iduser"],
        ));
        if ($dadesUsuari) {
            foreach ($dadesUsuari as $fila) {
                //Guardarem les dades de l'usuari
                $username = $fila['username'];
                $userFirstName = $fila['userFirstName'];
                $userLastName = $fila['userLastName'];
                $language = $fila['language'];
            }
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                //Obtindrem les noves dades per POST
                if (isset($_POST["username"]) && isset($_POST["userFirstName"]) && isset($_POST["userLastName"]) && isset($_POST["language"])) {
                    //Comprovem si el nom d'usuari han estat registrats per un altre usuari
                    $sql = "SELECT iduser FROM `users` WHERE username=:username and iduser!=:iduser";
                    $dadesUsuari = $db->prepare($sql);
                    $dadesUsuari->execute(array(
                        ':iduser' => $_SESSION["iduser"],
                        ':username' => $_POST["username"],
                    ));
                    if ($dadesUsuari) {
                        $count = $dadesUsuari->rowcount();
                        if ($count > 0) {
                            $updated = false;
                        } else {
                            //Actualitzem les dades
                            $sql = "UPDATE `users` SET username=:username, userFirstName=:userFirstName, userLastName=:userLastName, language=:language WHERE iduser=:iduser";
                            $update = $db->prepare($sql);
                            $update->execute(array(
                                ':iduser' => $_SESSION["iduser"],
                                ':username' => $_POST["username"],
                                ':userFirstName' => $_POST["userFirstName"],
                                ':userLastName' => $_POST["userLastName"],
                                ':language' => $_POST["language"],
                            ));
                            if ($update) {
                                $username = $_POST['username'];
                                $userFirstName = $_POST['userFirstName'];
                                $userLastName = $_POST['userLastName'];
                                $language = $_POST['language'];
                                $_SESSION["language"] = $_POST['language']; //Actualitzarem l'idioma de la sessió
                                $updated = true;
                            } else {
                                print_r($db->errorinfo());
                            }
                        }
                    }
                }
            }
        }
    } catch (PDOException $e) {
        echo 'Error con la BDs: ' . $e->getMessage();
    }
}
//Carregarem el fitxer d'idiomes
require_once "../langs/lang-" . $idioma . ".php";
?>
<!DOCTYPE html>
<html lang=<?php echo "$idioma" ?>>
<head>
    <title>imagiNest - <?php echo IDIOMES['USER_SETTINGS']; ?></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../images/icons/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="../css/settings.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
    <?php if (isset($updated) && $updated == true) {
    echo '<div class=" alerta alert alert-success alert-dismissible fade show" role="alert">' .
        IDIOMES['USERSETTINGS_SUCCESS']
        . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
} else if (isset($updated) && $updated == false) {
    echo '<div class=" alerta alert alert-danger alert-dismissible fade show" role="alert">' .
        IDIOMES['USERSETTINGS_DANGER']
        . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
}
?>
    <div class="contenedorlogo">
    <a href="../home.php"><img class ="logo" src="../images/imaginestW.png"></img></a>
            <div class="navbartop">
                <a class="itemnavbar"href="../home.php">
                    <i class="material-icons nav__icon">home</i>
                </a>
                <a class ="itemnavbar" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="material-icons nav__icon">person</i>
                </a>
                <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink">
                <li class="username"><div class="dropdown-item usernametext"><?php echo $_SESSION["username"] ?></div></li>
                    <li><a class="dropdown-item" href="../profile.php"><?php echo IDIOMES['MYPROFILE']; ?></a></li>
                    <li><a class="dropdown-item" href="./account_settings.php"><?php echo IDIOMES['SETTINGS']; ?></a></li>
                    <li><a class="dropdown-item" href="../logout.php"><?php echo IDIOMES['LOGOUT_BUTTON']; ?></a></li>
                </ul>
            </div>
    </div>
    <div class="contenedorCard">
        <div class="card">
            <a href="./account_settings.php"><i class="fa fa-arrow-left"></i> <?php echo IDIOMES['BACKSETTINGS']; ?></a>
            <div class="contenedorOpciones">
                <h2 class ="tituloOpciones"><?php echo IDIOMES['USER_SETTINGS']; ?></h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <p><strong><?php echo IDIOMES['ALERT']; ?></strong> <?php echo IDIOMES['USER_SETTINGS_ALERT']; ?></p>
                    <div class="filaOpciones">
                        <label for="fname"><?php echo IDIOMES['USER']; ?></label>
                        <input type="text" id="username" name="username" value="<?php echo $username ?>">
                    </div>
                    <div class="filaOpciones">

                        <label for="fname"><?php echo IDIOMES['USERFIRSTNAME']; ?></label>
                        <input type="text" id="userFirstName" name="userFirstName" value="<?php echo $userFirstName ?>">
                    </div>
                    <div class="filaOpciones">
                        <label for="fname"><?php echo IDIOMES['USERLASTNAME']; ?></label>
                        <input type="text" id="userLastName" name="userLastName" value="<?php echo $userLastName ?>">
                    </div>
                    <div class="filaOpciones">
                    <label for="lname"><?php echo IDIOMES['USER_LANGUAGE']; ?></label><br>
                    <select name="language">
                        <?php if ($language == "es") {echo "<option value='es' selected>Español</option><option value='en'>English</option><option value='cat'>Català</option>";
                            } else if ($language == "en") {echo "<option value='es'>Español</option><option value='en' selected>English</option><option value='cat'>Català</option>";
                            } else {echo "<option value='es'>Español</option><option value='en'>English</option><option value='cat' selected>Català</option>";}?>
                    </select>
                    </div>


                    <input class="opciones btn btn-secondary" type="submit" value="<?php echo IDIOMES['SAVECHANGES']; ?>">
                </form>
            </div>
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