<?php
    session_start();
    if(!isset($_SESSION["iduser"])){
        header('Location: ./index.php?redirected');
        exit;
    }else{
        require_once("./database_connect.php");
        try{
            //Obtindrem les dades de l'usuari
            $sql = "SELECT username,userFirstName,userLastName,language FROM `users` WHERE iduser=:iduser";
            $dadesUsuari = $db->prepare($sql);
            $dadesUsuari->execute(array(
                ':iduser' => $_SESSION["iduser"]
            ));
            if($dadesUsuari){
                foreach ($dadesUsuari as $fila) {
                    //Guardarem les dades de l'usuari
                    $username = $fila['username'];
                    $userFirstName = $fila['userFirstName'];
                    $userLastName = $fila['userLastName'];
                    $language = $fila['language'];
                }
                if($_SERVER["REQUEST_METHOD"] == "POST"){
                    //Obtindrem les noves dades per POST
                    if(isset($_POST["username"]) && isset($_POST["userFirstName"]) && isset($_POST["userLastName"]) && isset($_POST["language"])){
                        //Comprovem si el nom d'usuari han estat registrats per un altre usuari
                        $sql = "SELECT iduser FROM `users` WHERE username=:username and iduser!=:iduser";
                        $dadesUsuari = $db->prepare($sql);
                        $dadesUsuari->execute(array(
                            ':iduser' => $_SESSION["iduser"],
                            ':username' => $_POST["username"]
                        ));
                        if($dadesUsuari){
                            $count = $dadesUsuari->rowcount();
                            if($count>0){
                                $updated=false;
                            }
                            else{
                                //Actualitzem les dades
                                $sql = "UPDATE `users` SET username=:username, userFirstName=:userFirstName, userLastName=:userLastName, language=:language WHERE iduser=:iduser";
                                $update = $db->prepare($sql);
                                $update->execute(array(
                                    ':iduser' => $_SESSION["iduser"],
                                    ':username' => $_POST["username"],
                                    ':userFirstName' => $_POST["userFirstName"],
                                    ':userLastName' => $_POST["userLastName"],
                                    ':language' => $_POST["language"]
                                ));
                                if($update){
                                    $username = $_POST['username'];
                                    $userFirstName = $_POST['userFirstName'];
                                    $userLastName = $_POST['userLastName'];
                                    $language = $_POST['language'];
                                    $updated=true;
                                }else{
                                    print_r($db->errorinfo());
                                }
                            }
                        }
                    }
                }
            }
        }catch(PDOException $e){
            echo 'Error con la BDs: ' . $e->getMessage();
        }
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
    <?php if(isset($updated) && $updated==true){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        Se han actualizado los datos del usuario correctamente
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }else if(isset($updated) && $updated==false){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        Se ha encontrado un usuario ya registrado con el e-mail o nombre de usuario introducidos
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
    ?>
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
            <a href="./account_settings.php"><i class="fa fa-arrow-left"></i> Volver a la configuración de la cuenta</a>
            <h2>Ajustes del usuario</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                <p><strong>Aviso:</strong> Para modificar la dirección de correo electrónico registrada será necessario contactar con imagiNest.</p>
                <label for="fname">Nombre de usuario:</label><br>
                <input type="text" id="username" name="username" value="<?php echo $username?>"><br>
                <label for="fname">Nombre:</label><br>
                <input type="text" id="userFirstName" name="userFirstName" value="<?php echo $userFirstName?>"><br>
                <label for="fname">Apellidos:</label><br>
                <input type="text" id="userLastName" name="userLastName" value="<?php echo $userLastName?>"><br>
                <label for="lname">Idioma del usuario:</label><br>
                <select name="language">
                    <?php if($language=="es"){echo "<option value='es' selected>Español</option><option value='en'>English</option><option value='cat'>Català</option>";
                    }else if($language=="en"){echo "<option value='es'>Español</option><option value='en' selected>English</option><option value='cat'>Català</option>";
                    }else{echo "<option value='es'>Español</option><option value='en'>English</option><option value='cat' selected>Català</option>";}?>
                </select><br><br>
                <input type="submit" value="Guardar cambios">
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