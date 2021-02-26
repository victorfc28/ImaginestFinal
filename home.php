<?php
session_start();
if (!isset($_SESSION["iduser"])) {
header("Location: ./index.php?redirected");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $fileSize = $_FILES["myfile"]["size"];
  $photoText = $_POST["photoDescription"];
  if($fileSize > 3000000){ //Només es podran pujar fotografies que ocupin fins a 3MB
      echo "<br>El archivo subido ocupa demasiado (>3MB)";
      return;
  }
  $fileName = hash('sha256', $_FILES["myfile"]["name"]).rand(); //Farem un hash al nom del fitxer i li concatenarem un rand
  $fileInfo = pathinfo($_FILES["myfile"]["name"]);
  $fileLocation = "uploads/" . $fileName . "." . $fileInfo['extension'];
  $res = move_uploaded_file($_FILES["myfile"]["tmp_name"],$fileLocation); //Mourem el fitxer a uploads i li concatenarem el hash amb l'extensió del fitxer
  if($res){
      //Si s'ha publicat la fotografia l'usuari tornarà a la pàgina Home
      require_once("./database_connect.php");
      $sql = "INSERT INTO `photos` values(:photoID,sysdate(),:photoText,:likes,:dislikes,:link,:iduser)";
                $insert = $db->prepare($sql);
                $insert->execute(array(
                    ':photoID' => NULL,
                    ':photoText' => $photoText,
                    ':likes' => 0,
                    ':dislikes' => 0,
                    ':link' => "uploads/" . $fileName . "." . $fileInfo['extension'],
                    ':iduser' =>  $_SESSION["iduser"]
                ));
                if($insert){
                  echo("Foto subida");
                  $qttHashtag = preg_match_all('/#(\w)*/', $photoText, $matches);
                  foreach ($matches[0] as $tag) {
                    $sql = "SELECT tagName FROM `tags` WHERE tagName=:tag";
                    $tags = $db->prepare($sql);
                    $tags->execute(array(
                        ':tag' => $tag,
                    ));
                    if($tags){
                        $count = $tags->rowcount();
                        if($count==0){
                          $sql = "INSERT INTO `tags` values(:tagName)";
                          $insert = $db->prepare($sql);
                          $insert->execute(array(
                            ':tagName' => $tag
                          ));
                        }
                    }else{
                      print_r($db->errorinfo());
                    }

                    $sql = 'SELECT photoID FROM `photos` WHERE url = :link';
                    $preparada = $db->prepare($sql);
                    $preparada->execute(array(':link' => $fileLocation));
                    $idphoto = $preparada->fetch(PDO::FETCH_ASSOC);

                    $sql = "INSERT INTO `te` values(:photoID,:tagName)";
                    $insert = $db->prepare($sql);
                    $insert->execute(array(
                      ':photoID' => $idphoto,
                      ':tagName' => $tag
                    ));
                  }
                }
  }else{
    echo "<br>Error en la publicación";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>imagiNest - Home</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="css/.css">
    <link rel="stylesheet" type="text/css" href="./css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
<div class="contenedorlogo">
<img class ="logo" src="./images/imaginest.png"></img>
</div>

  <div class="container d-flex justify-content-center mt-200">
      <div class="row">
          <div class="col-md-12">
              <div class="stars">
                  <form action=""> 
                    <input class="star star-5" id="star-5" type="radio" name="star" /> <label class="star star-5" for="star-5"></label> 
                    <input class="star star-4" id="star-4" type="radio" name="star" /> <label class="star star-4" for="star-4"></label> 
                    <input class="star star-3" id="star-3" type="radio" name="star" /> <label class="star star-3" for="star-3"></label> 
                    <input class="star star-2" id="star-2" type="radio" name="star" /> <label class="star star-2" for="star-2"></label> 
                    <input class="star star-1" id="star-1" type="radio" name="star" /> <label class="star star-1" for="star-1"></label> 
                  </form>
              </div>
          </div>
      </div>
</div>
<div class="contenedorfoto">
        <div id="carouselInicio" class="carousel slide imagencarrousel" data-bs-ride="carousel">
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselInicio"  data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <div class="carousel-inner imagencarrousel">
            <div class="carousel-item active imagencarrousel">
              <img src="./images/wallpaper/bow-lake-5854210_1920.jpg" class="imagencarrousel">
            </div>
            <div class="carousel-item imagencarrousel">
              <img src="./images/wallpaper/leaves-5839550_1920.jpg" class="imagencarrousel">
            </div>
            <div class="carousel-item imagencarrousel">
              <img src="./images/wallpaper/tree-5887086_1920.jpg" class="imagencarrousel">
            </div>
          </div>

          <button class="carousel-control-next" type="button" data-bs-target="#carouselInicio"  data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>


        <div class="textofoto">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
        Duis id metus est. Morbi quis neque commodo, ornare dolor non, lacinia arcu. Donec
        at varius urna. Vestibulum ante ipsum primis in 
        </div>
  </div>
<!-- Modal -->
<div class="modal fade" id="popup" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Sube una foto!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data">
      <div class="modal-body">
          <label for="photoDescription">Describe tu fotografía:</label><br>
          <textarea name="photoDescription" cols="40" rows="5"></textarea><br>
          <label for="myfile">Selecciona una imagen:</label>
          <input type="file" id="myfile" name="myfile" accept="image/*"><br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>

<nav class="navC">
  <a href="#" class="nav__link">
    <i class="material-icons nav__icon">home</i>
  </a>
  <button class="nav__link" data-bs-toggle="modal" data-bs-target="#popup">
    <i class="material-icons nav__icon">add_a_photo</i>
  </button>
  <a href="#" class="nav__link">
    <i class="material-icons nav__icon">person</i>
  </a>
</nav>




<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>
</body>
</html>