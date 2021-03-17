<?php
session_start();
$fotoencontrada=false;
if (!isset($_SESSION["iduser"])) {
    header("Location: ../index.php?redirected");
    exit;
} else {
    require_once "./database_connect.php";
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
                    }

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
<html>
<head>
<title>imagiNest - Home</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../images/icons/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="../css/.css">
    <link rel="stylesheet" type="text/css" href="../css/foto.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&family=Roboto:wght@100&display=swap" rel="stylesheet"> 
    <style>
    <?php
if ($fotoencontrada == false) {
    echo '

      .contenedorfoto{
        display:none !important;

      }

      ';
}

?>
    </style>
</head>
<body>
<div class="contenedorlogo">
<a href="./home.php"><img class ="logo" src="../images/imaginestW.png"></img></a>
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
    <li><a class="dropdown-item" href="./perfil.php">Mi perfil</a></li>
    <li><a class="dropdown-item" href="./account_settings.php">Configuración</a></li>
    <li><a class="dropdown-item" href="./logout.php">Cerrar Sesión</a></li>
  </ul>
  </div>
</div>
<?php if ((isset($error)) && $error == 2) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Perfecto!</strong> Has colgado una foto.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}?>

<div class="contenedorfoto">
<div class="contenedorCard">
    <div class="card">
      <img class="card-img-top" src="<?php if (isset( $_GET["url"]) && $fotoencontrada) {
    echo  $_GET["url"];
}
?>" >
      <div class="card-body">
        <p class="card-text"><?php if (isset( $_GET["url"]) && $fotoencontrada) {
    echo "<b>" . $contenidofoto["username"] . ": </b>" . $textofoto;
}
?></p>

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
<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="popup" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Sube una foto!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
      <div class="modal-body">
          <label for="myfile">Selecciona una imagen:</label>
          <input type="file" id="myfile" name="myfile" accept="image/*" class="form-control"><br>
          <label for="photoDescription">Describe tu fotografía:</label><br>
          <textarea class="form-control" name="photoDescription" cols="40" rows="5" style='resize: none;'></textarea><br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
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