<?php
session_start();
if (!isset($_SESSION["iduser"])) {
header("Location: ./index.php?redirected");
}
else
{
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    include_once("./photoShare.php");
  }
  $fechafoto = comprobarfotosUsuario();
  if($fechafoto != null)
  {

  }

}

function comprobarfotosUsuario()
{
  $fotos = 0;
  $fechaultimafoto = null;




  return $fechaultimafoto;
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
<?php if((isset($error))&&$error==2){
  echo'<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Perfecto!</strong> Has colgado una foto.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}?>
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="imagencarrousel">
              <input type="submit"  class="material-icons elementocarrousel botonizquierda"name="dislike" value="keyboard_arrow_left"></input>
              <img src="./images/wallpaper/leaves-5839550_1920.jpg"  class="imagencarrousel2">
              <input type="submit"  class="material-icons elementocarrousel botonderecha"name="like" value="keyboard_arrow_right"></input>
            </div>
        </form>


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
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
</body>
</html>