<?php
    if(!isset($_SESSION["username"])){
        header('Location: ./index.php');
        exit;
    }else if($_SERVER["REQUEST_METHOD"] == "POST"){
        $fileSize = $_FILES["myfile"]["size"];
        if($fileSize > 3000000){ //Només es podran pujar fotografies que ocupin fins a 3MB
            echo "<br>El archivo subido ocupa demasiado (>3MB)";
            return;
        }
        $fileName = hash('sha256', $_FILES["myfile"]["name"]).rand(); //Farem un hash al nom del fitxer i li concatenarem un rand
        $fileInfo = pathinfo($_FILES["myfile"]["name"]);
        $res = move_uploaded_file($_FILES["myfile"]["tmp_name"],
            "uploads/" . $fileName . "." . $fileInfo['extension']); //Mourem el fitxer a uploads i li concatenarem el hash amb l'extensió del fitxer
        if($res){
            //Si s'ha publicat la fotografia l'usuari tornarà a la pàgina Home
            header('Location: ./home.php');
            exit;
        }else{
            echo "<br>Error en la publicación";
        }
    }
?>
<!DOCTYPE html>
<html>
<title>imagiNest - Subir una foto</title>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data">
        <label for="photoDescription">Describe tu fotografía:</label><br>
        <textarea name="photoDescription" cols="40" rows="5"></textarea><br>
        <label for="myfile">Selecciona una imagen:</label>
        <input type="file" id="myfile" name="myfile" accept="image/*"><br>
        <input type="submit" value="Publicar fotografía">
    </form> 
</body>
</html>