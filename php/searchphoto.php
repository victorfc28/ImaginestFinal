<?php
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
            $sql = "SELECT url,photoText,users.username, users.iduser FROM photos INNER JOIN users on photos.iduser = users.iduser ORDER BY RAND() LIMIT 1;";
            $ultimafoto = $db->prepare($sql);
            $ultimafoto->execute(array(
                ':iduser' => $_SESSION["iduser"],
            ));
            $urlfoto = $ultimafoto->fetch(PDO::FETCH_ASSOC);
            $textofoto = $urlfoto["photoText"];
            if ($urlfoto != false && $numfotos > 1) {
                while ($_SESSION["lastPhoto"] == $urlfoto["url"]) {
                    $sql = "SELECT url,photoText,users.username, users.iduser FROM photos INNER JOIN users on photos.iduser = users.iduser ORDER BY RAND() LIMIT 1;";
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
        ?>