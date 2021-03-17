<?php
session_start();
if (!isset($_SESSION["iduser"])) {
    header("Location: ./index.php?redirected");
    exit;
} else {
    //Carreguem l'idioma de la sessió
    $idioma = $_SESSION["language"];
    $homemode=true;
    require_once "./php/database_connect.php";
    if (isset($_COOKIE["logged"]) && $_COOKIE["logged"] == 0) {
        $sql = "SELECT count(*) FROM photos WHERE iduser=:iduser";
        $fotos = $db->prepare($sql);
        $fotos->execute(array(
            ':iduser' => $_SESSION["iduser"],
        ));
        $numfotos = $fotos->fetch(PDO::FETCH_ASSOC);
        if ($numfotos["count(*)"] > 0) {
            $sql = "SELECT url,photoText,users.username FROM photos INNER JOIN users on photos.iduser = users.iduser WHERE users.iduser=:iduser ORDER BY publishDate DESC LIMIT 1";
            $ultimafoto = $db->prepare($sql);
            $ultimafoto->execute(array(
                ':iduser' => $_SESSION["iduser"],
            ));
            $urlfoto = $ultimafoto->fetch(PDO::FETCH_ASSOC);

            $_SESSION["lastPhoto"] = $urlfoto["url"]; //Guardem la URL de la foto en la variable de sessió lastPhoto
            $textofoto = $urlfoto["photoText"];
            include_once "./php/findHashtags.php";
            setcookie('logged', 1, time() + 3600247); //Modificarem la cookie per saber que l'usuari ja ha accedit anteriorment

            include_once "./php/findInteraccion.php";

        } else {
            $_SESSION["lastPhoto"] = null;
            $numfotos = 0;
            setcookie('logged', 1, time() + 3600247);
        }
    } else if (isset($_COOKIE["logged"]) && $_COOKIE["logged"] == 1) {
        //Comprobarem el número de fotos penjades
        $sql = "SELECT count(*) as Total FROM photos;";
        $countfotos = $db->query($sql);
        if ($countfotos) {
            foreach ($countfotos as $fila) {
                $numfotos = $fila['Total'];
            }
        }

        //Si no hi ha fotos penjades
        if ($numfotos != 0) {
            $sql = "SELECT url,photoText,users.username FROM photos INNER JOIN users on photos.iduser = users.iduser ORDER BY RAND() LIMIT 1;";
            $ultimafoto = $db->prepare($sql);
            $ultimafoto->execute(array(
                ':iduser' => $_SESSION["iduser"],
            ));
            $urlfoto = $ultimafoto->fetch(PDO::FETCH_ASSOC);
            $textofoto = $urlfoto["photoText"];
            if ($urlfoto != false && $numfotos > 1) {
                while ($_SESSION["lastPhoto"] == $urlfoto["url"]) {
                    $sql = "SELECT url,photoText,users.username FROM photos INNER JOIN users on photos.iduser = users.iduser ORDER BY RAND() LIMIT 1;";
                    $ultimafoto = $db->prepare($sql);
                    $ultimafoto->execute(array(
                        ':iduser' => $_SESSION["iduser"],
                    ));
                    $urlfoto = $ultimafoto->fetch(PDO::FETCH_ASSOC);
                }
                $_SESSION["lastPhoto"] = $urlfoto["url"]; //Guardem la URL de la foto en la variable de sessió lastPhoto
                $textofoto = $urlfoto["photoText"];
                
            }
            include_once "./php/findHashtags.php";
            include_once "./php/findInteraccion.php";
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //Adjuntarem el fitxer per penjar les fotos
        if (isset($_POST["photoDescription"])) {
            include_once "./php/photoShare.php";
        }

        if (isset($_POST["like"])) {
            include_once "./php/like.php";
            $sql = "SELECT likes, dislikes from photos WHERE url = :url;";
            $likesX = $db->prepare($sql);
            $likesX->execute(array(
                ':url' => $urlfoto["url"],
            ));
            $likesX2 = $likesX->fetch(PDO::FETCH_ASSOC);
            $likes = $likesX2["likes"];
            $dislikes = $likesX2["dislikes"];
        }
        if (isset($_POST["dislike"])) {
            include_once "./php/dislike.php";
            $sql = "SELECT likes, dislikes from photos WHERE url = :url;";
            $likesX = $db->prepare($sql);
            $likesX->execute(array(
                ':url' => $urlfoto["url"],
            ));
            $likesX2 = $likesX->fetch(PDO::FETCH_ASSOC);
            $likes = $likesX2["likes"];
            $dislikes = $likesX2["dislikes"];
        }
    }

    //Carregarem el fitxer d'idiomes
    require_once "./langs/lang-" . $idioma . ".php";
}
?>
<!DOCTYPE html>
<html lang=<?php echo "$idioma" ?>>
<head>
<title>imagiNest - <?php echo IDIOMES['HOME']; ?></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="./images/icons/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="./css/.css">
    <link rel="stylesheet" type="text/css" href="./css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&family=Roboto:wght@100&display=swap" rel="stylesheet">
    <style>
      <?php
if ($numfotos == 0) {
    echo '.contenedorfoto{
          display:none !important;
          }';
}
?>
    </style>
</head>
<body>
  <div class="contenedorlogo">
    <a href="./home.php"><img class ="logo" src="./images/imaginestW.png"></img></a>
    <div class="navbartop">
      <a class="itemnavbar"href="./home.php">
        <i class="material-icons nav__icon">home</i>
      </a>
      <button  class="botonColgarFoto itemnavbar"data-bs-toggle="modal" data-bs-target="#popup">
        <i class="material-icons nav__icon">add_a_photo</i>
      </button>
      <a class ="itemnavbar" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="material-icons nav__icon">person</i>
      </a>
      <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink">
        <li><a class="dropdown-item" href="./profile.php"><?php echo IDIOMES['MYPROFILE']; ?></a></li>
        <li><a class="dropdown-item" href="./settings/account_settings.php"><?php echo IDIOMES['SETTINGS']; ?></a></li>
        <li><a class="dropdown-item" href="./logout.php"><?php echo IDIOMES['LOGOUT_BUTTON']; ?></a></li>
      </ul>
    </div>
  </div>
  <?php if ((isset($error)) && $error == 2) {
    echo '<div class="alerta alert alert-success alert-dismissible fade show" role="alert">' .
        IDIOMES['UPLOAD_SUCCESS'] .
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}?>

  <form action="<?php

if (isset($urlfoto["url"])) {
    echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?url=" . $urlfoto["url"];
} else {
    echo htmlspecialchars($_SERVER["PHP_SELF"])

    ;
}
?>" method="POST">
    <div class="contenedorfoto">
      <?php if ($numfotos != 0) {
    echo '<input type="submit" class="material-icons iconosfoto botonizquierda"name="dislike" value="thumb_down_off_alt"></input>';
}?>
    <div class="contenedorCard">
      <div class="card">
        <img class="card-img-top" src="<?php if (isset($urlfoto) && $urlfoto != false) {
    echo $urlfoto["url"];
}?>">
        <div class="card-body">
          <?php if (isset($urlfoto) && $urlfoto != false) {
    if(isset($likeencontrado) && $likeencontrado)
    {
      echo'<div class="contenedorlikes">
      <input type="submit"  class="material-icons iconosfoto2hover"name="like" value="mood"></input>'.$likes.'
      <input type="submit" class="material-icons iconosfoto2" name="dislike" value="mood_bad"></input>'.$dislikes.'
      </div>';
    }
    else if(isset($dislikeencontrado) && $dislikeencontrado)
    {
      echo'<div class="contenedorlikes">
      <input type="submit"  class="material-icons iconosfoto2" name="like" value="mood"></input>'.$likes.'
      <input type="submit" class="material-icons iconosfoto2hover" name="dislike" value="mood_bad"></input>'.$dislikes.'
      </div>';
    }
    else
    {
      echo'<div class="contenedorlikes">
      <input type="submit"  class="material-icons iconosfoto2"name="like" value="mood"></input>'.$likes.'
      <input type="submit" class="material-icons iconosfoto2" name="dislike" value="mood_bad"></input>'.$dislikes.'
      </div>';
    }

    echo '<div class="contenedortextofoto"><b>' . $urlfoto["username"] . ": </b>" . $textofoto . '</div>';
}?>
        </div>
      </div>
    </div>
  <?php if ($numfotos != 0) {
    echo '<input type="submit"  class="material-icons iconosfoto botonderecha"name="like" value="thumb_up_off_alt"></input>';
}?>
  </form>
  </div>

  <?php
if ($numfotos == 0) {
    echo '<div class="nophoto">
      <br><br><p class="nophototext">' . IDIOMES['NOPHOTOS'] . '</p>
      </div>';
}?>

  <!-- Site footer -->
  <div class="footer">
    <div class="textofooter">
      Copyright © 2021 All Rights Reserved by Imaginest.
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="popup" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel"><?php echo IDIOMES['UPLOADPHOTO_TITLE']; ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
        <div class="modal-body">
            <label for="myfile"><?php echo IDIOMES['SELECTIMAGE']; ?></label>
            <input type="file" id="myfile" name="myfile" accept="image/*" class="form-control"><br>
            <label for="photoDescription"><?php echo IDIOMES['DESCIMAGE']; ?></label><br>
            <textarea class="form-control" name="photoDescription" cols="40" rows="5" style='resize: none;'></textarea><br>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo IDIOMES['CLOSE_BUTTON']; ?></button>
          <button type="submit" class="btn btn-primary"><?php echo IDIOMES['UPLOAD']; ?></button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
</body>
</html>