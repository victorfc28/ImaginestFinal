<?php
     $url = $_GET["url"];

     $sql = "SELECT likes, dislikes from photos WHERE url = :url;";
     $likesX = $db->prepare($sql);
     $likesX->execute(array(
         ':url' => $_GET["url"],
     ));
     $likesX2 = $likesX->fetch(PDO::FETCH_ASSOC);
     $likes = $likesX2["likes"];
     $dislikes = $likesX2["dislikes"];
     

    $sql = "SELECT photoID FROM photos WHERE url = :url";
        $idphotox = $db->prepare($sql);
        $idphotox->execute(array(
            ':url' => $url
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


        if(!$like)
        {
            $sql = "INSERT INTO Fa_like values(1,0,:photoid,:iduser)";
            $insert = $db->prepare($sql);
            $insert->execute(array(
                ':photoid' => $idphoto,
                ':iduser' =>  $_SESSION["iduser"]
            ));
                        //busca likes
                        $sql = "SELECT count(*) as likes from Fa_like where photoID = :photoid and likea=1"; 
                        $likesXphoto = $db->prepare($sql);
                        $likesXphoto->execute(array(
                            ':photoid' => $idphoto,
                        ));
                        $likesX2photo = $likesXphoto->fetch(PDO::FETCH_ASSOC);
                        $likesphoto = $likesX2photo["likes"];

                        $sql = "SELECT count(*) as dislikes from Fa_like where photoID = :photoid and dislikea=1"; 
                        $dislikesXphoto = $db->prepare($sql);
                        $dislikesXphoto->execute(array(
                            ':photoid' => $idphoto,
                        ));
                        $dislikesX2photo = $dislikesXphoto->fetch(PDO::FETCH_ASSOC);
                        $dislikesphoto = $dislikesX2photo["dislikes"];

                        $sql = "UPDATE photos
                        SET likes = :likex, dislikes = :dislikex
                        WHERE photoID = :photoid";
                        $insert = $db->prepare($sql);
                        $insert->execute(array(
                            ':photoid' => $idphoto,
                            ':likex' => $likesphoto,
                            ':dislikex' => $dislikesphoto
                        ));
                        //buscalikes
        }
        else if($like["dislikea"] == 1)
        {
            $sql = "UPDATE Fa_like
            SET likea=1, dislikea=0 
            WHERE photoID = :photoid AND iduser = :iduser";
            $insert = $db->prepare($sql);
            $insert->execute(array(
                ':photoid' => $idphoto,
                ':iduser' =>  $_SESSION["iduser"]
            ));

                        //busca likes
                        $sql = "SELECT count(*) as likes from Fa_like where photoID = :photoid and likea=1"; 
                        $likesXphoto = $db->prepare($sql);
                        $likesXphoto->execute(array(
                            ':photoid' => $idphoto,
                        ));
                        $likesX2photo = $likesXphoto->fetch(PDO::FETCH_ASSOC);
                        $likesphoto = $likesX2photo["likes"];

                        $sql = "SELECT count(*) as dislikes from Fa_like where photoID = :photoid and dislikea=1"; 
                        $dislikesXphoto = $db->prepare($sql);
                        $dislikesXphoto->execute(array(
                            ':photoid' => $idphoto,
                        ));
                        $dislikesX2photo = $dislikesXphoto->fetch(PDO::FETCH_ASSOC);
                        $dislikesphoto = $dislikesX2photo["dislikes"];

                        $sql = "UPDATE photos
                        SET likes = :likex, dislikes = :dislikex
                        WHERE photoID = :photoid";
                        $insert = $db->prepare($sql);
                        $insert->execute(array(
                            ':photoid' => $idphoto,
                            ':likex' => $likesphoto,
                            ':dislikex' => $dislikesphoto
                        ));
                        //buscalikes
            

        }

        if(!(isset($homemode)))header("Refresh:0");

?>