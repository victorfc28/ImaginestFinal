<?php
session_start();
$fotoencontrada=false;
if(!isset($_SESSION["iduser"])){
  header("Location: ./index.php?redirected");
  exit;
}else{
  //Carreguem l'idioma de la sessió
  $idioma = $_SESSION["language"];

  require_once "./php/database_connect.php";
  $sql = "SELECT url,photoText,users.username FROM photos INNER JOIN users on photos.iduser = users.iduser WHERE photos.url = :url";
  $foto = $db->prepare($sql);
  $foto->execute(array(
    ':url' => $_GET["url"],
  ));
  $contenidofoto = $foto->fetch(PDO::FETCH_ASSOC);
  if($contenidofoto!=false)
  {
    $textofoto = $contenidofoto["photoText"];
    buscarhashtags($textofoto);

    $fotoencontrada=true;
    $sql = "SELECT likes, dislikes from photos WHERE url = :url;";
    $likesX = $db->prepare($sql);
    $likesX->execute(array(
        ':url' => $_GET["url"],
    ));
    $likesX2 = $likesX->fetch(PDO::FETCH_ASSOC);
    $likes = $likesX2["likes"];
    $dislikes = $likesX2["dislikes"];


      //like hover
  $sql = "SELECT photoID FROM photos WHERE url = :url";
  $idphotox = $db->prepare($sql);
  $idphotox->execute(array(
      ':url' => $_GET["url"]
  ));
  $idphoto = $idphotox->fetch(PDO::FETCH_ASSOC);

  $idphoto = $idphoto["photoID"];

  $sql = "SELECT * FROM Fa_like WHERE iduser=:iduser AND photoID = :photoid";
        $likex = $db->prepare($sql);
        $likex->execute(array(
            ':iduser' => $_SESSION["iduser"],
            ':photoid'=>$idphoto
        ));
        $like = $likex->fetch(PDO::FETCH_ASSOC);
        if($like != false)
        {
          if($like["likea"] == 1) $likeencontrado=true;
          else if($like["dislikea"] == 1) $dislikeencontrado=true;
        }

  //like hover



  }




  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Adjuntarem el fitxer per penjar les fotos
    if(isset($_POST["like"])) include_once "./php/likephoto.php";
    if(isset($_POST["dislike"])) include_once "./php/dislikephoto.php";   
}

  //Carregarem el fitxer d'idiomes
  require_once("./langs/lang-".$idioma.".php");
}

function buscarhashtags(&$textofoto)
{
    //$textoTemporal = explode("#", $textofoto);
    $qttHashtag = preg_match_all('/#(\w)*/', $textofoto, $matches);
    
    foreach ($matches[0] as $tag) {
      $tag2 = $tag;
      $tag2 = str_replace("#","",$tag);
      $replace = '<a class="linkhashtag"href="./hashtag.php?hashtag='.$tag2.'">'.$tag.'</a>';
      $textofoto = str_replace($tag, $replace, $textofoto);
    }
}



?>
<!DOCTYPE html>
<html lang=<?php echo "$idioma"?>>
<head>
<title>imagiNest - <?php echo IDIOMES['VIEWPHOTO']; ?></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="./images/icons/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="./css/foto.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&family=Roboto:wght@100&display=swap" rel="stylesheet">
    <style>
      <?php
        if($fotoencontrada == false){
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
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?url=".$_GET["url"]?>" method="POST">
  <div class="contenedorfoto">
    <div class="contenedorCard">
      <div class="card">
        <img class="card-img-top" src="<?php if (isset( $_GET["url"]) && $fotoencontrada) {
          echo $_GET["url"];
        }?>">
      <div class="card-body">
      <?php if (isset( $_GET["url"]) && $fotoencontrada) {
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

            echo '<div class="contenedortextofoto"><b>' . $contenidofoto["username"] . ": </b>" . $textofoto.'</div>';
          }?>

      </div>
    </div>
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