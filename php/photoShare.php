<?php
  $error=0;
  $fileSize = $_FILES["myfile"]["size"];
  $photoText = $_POST["photoDescription"];
  if($fileSize > 3000000){ //Només es podran pujar fotografies que ocupin fins a 3MB
      echo "<br>El archivo subido ocupa demasiado (>3MB)";
      $error=1;
      return;
  }
  $fileName = hash('sha256', $_FILES["myfile"]["name"]).rand(); //Farem un hash al nom del fitxer i li concatenarem un rand
  $fileInfo = pathinfo($_FILES["myfile"]["name"]);
  $fileLocation = "./uploads/" . $fileName . "." . $fileInfo['extension'];
  $res = move_uploaded_file($_FILES["myfile"]["tmp_name"],$fileLocation); //Mourem el fitxer a uploads i li concatenarem el hash amb l'extensió del fitxer
  if($res){
      require_once("./php/database_connect.php");
      $sql = "INSERT INTO `photos` values(:photoID,sysdate(),:photoText,:likes,:dislikes,:link,:iduser)";
                $insert = $db->prepare($sql);
                $insert->execute(array(
                    ':photoID' => NULL,
                    ':photoText' => $photoText,
                    ':likes' => 0,
                    ':dislikes' => 0,
                    ':link' => "./uploads/" . $fileName . "." . $fileInfo['extension'],
                    ':iduser' =>  $_SESSION["iduser"]
                ));
                if($insert){
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
                      ':photoID' => $idphoto["photoID"],
                      ':tagName' => $tag
                    ));
                    setcookie('logged',0,time()+3600247);
                  }
                  $error=2;

                }
  }else{
    echo "<br>Error en la publicación";
  }
?>
