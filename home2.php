<?php
session_start();
if (!isset($_SESSION["iduser"])) {
    header("Location: ./index.php?redirected");
    exit;
} else {
    require_once "./database_connect.php";
    if (isset($_COOKIE["logged"]) && $_COOKIE["logged"] == 0) {
        $sql = "SELECT count(*) FROM photos WHERE iduser=:iduser";
        $fotos = $db->prepare($sql);
        $fotos->execute(array(
            ':iduser' => $_SESSION["iduser"],
        ));
        $numfotos = $fotos->fetch(PDO::FETCH_ASSOC);
        if ($numfotos["count(*)"] > 0) {
            $sql = "SELECT url,photoText FROM photos WHERE iduser=:iduser ORDER BY publishDate DESC LIMIT 1";
            $ultimafoto = $db->prepare($sql);
            $ultimafoto->execute(array(
                ':iduser' => $_SESSION["iduser"],
            ));
            $urlfoto = $ultimafoto->fetch(PDO::FETCH_ASSOC);

            $_SESSION["lastPhoto"] = $urlfoto["url"]; //Guardar la URL de la foto en la variable de sesion lastPhoto
            $textofoto = $urlfoto["photoText"];
            setcookie('logged', 1, time() + 3600247); //Modificarem la cookie per saber que l'usuari ja ha accedit anteriorment
        } else {
            $_SESSION["lastPhoto"] = null;
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

        $sql = "SELECT url,photoText FROM photos ORDER BY RAND() LIMIT 1;";
        $ultimafoto = $db->prepare($sql);
        $ultimafoto->execute(array(
            ':iduser' => $_SESSION["iduser"],
        ));
        $urlfoto = $ultimafoto->fetch(PDO::FETCH_ASSOC);
        if ($urlfoto != false && $numfotos > 1) {
            while ($_SESSION["lastPhoto"] == $urlfoto["url"]) {
                $sql = "SELECT url,photoText FROM photos ORDER BY RAND() LIMIT 1;";
                $ultimafoto = $db->prepare($sql);
                $ultimafoto->execute(array(
                    ':iduser' => $_SESSION["iduser"],
                ));
                $urlfoto = $ultimafoto->fetch(PDO::FETCH_ASSOC);
            }
            $_SESSION["lastPhoto"] = $urlfoto["url"]; //Guardar la URL de la foto en la variable de sesion lastPhoto
            $textofoto = $urlfoto["photoText"];
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include_once "./photoShare.php";
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
    <link rel="stylesheet" type="text/css" href="./css/home2.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
<div class="contenedorlogo">
<img class ="logo" src="./images/imaginestW.png"></img>
  <div class="navbartop">
  <a class="itemnavbar"href="./home.php">
      <i class="material-icons nav__icon">home</i>
    </a>
    <button  class="botonColgarFoto itemnavbar"data-bs-toggle="modal" data-bs-target="#popup">
      <i class="material-icons nav__icon">add_a_photo</i>
    </button>
    <a class ="itemnavbar" href="./logout.php" >
      <i class="material-icons nav__icon">person</i>
    </a>

  </div>
</div>
<?php if ((isset($error)) && $error == 2) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Perfecto!</strong> Has colgado una foto.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}?>
<div class="contenedorCard">
    <div class="card">
      <img class="card-img-top" src="<?php if (isset($urlfoto) && $urlfoto != false) {
        echo "./" . $urlfoto["url"];
    }
    ?>" alt="Card image cap">
      <div class="card-body">
        <p class="card-text"><?php if (isset($urlfoto) && $urlfoto != false) {
    echo $urlfoto["photoText"];
}
?></p>
      </div>
    </div>
</div>

<!--<div class="contenedorfoto">
        <form action="<?php /*echo htmlspecialchars($_SERVER["PHP_SELF"]); */?>">
            <div class="imagencarrousel">
              <input type="submit"  class="material-icons elementocarrousel botonizquierda"name="dislike" value="keyboard_arrow_left"></input>
              <img src="<?php /*if (isset($urlfoto) && $urlfoto != false) {
    echo "./" . $urlfoto["url"];
}*/
?>"  class="imagencarrousel2">
              <input type="submit"  class="material-icons elementocarrousel botonderecha"name="like" value="keyboard_arrow_right"></input>
            </div>
        </form>


        <div class="textofoto">
        <?php /*if (isset($urlfoto) && $urlfoto != false) {
    echo $urlfoto["photoText"];
}*/
?>
        </div>
</div>-->

  <!-- Site footer -->
<footer class="site-footer footer">
<div class="textofoto">
Copyright © 2021 All Rights Reserved by Imaginest.
</div>

</footer>
<!-- Modal -->
<footer class="modal fade" id="popup" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
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
</footer>




<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
</body>
</html>