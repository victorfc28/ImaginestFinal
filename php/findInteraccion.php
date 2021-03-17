<?php
$sql = "SELECT likes, dislikes from photos WHERE url = :url;";
$likesX = $db->prepare($sql);
$likesX->execute(array(
    ':url' => $urlfoto["url"],
));
$likesX2 = $likesX->fetch(PDO::FETCH_ASSOC);
$likes = $likesX2["likes"];
$dislikes = $likesX2["dislikes"];

//like hover
$sql = "SELECT photoID FROM photos WHERE url = :url";
$idphotox = $db->prepare($sql);
$idphotox->execute(array(
    ':url' => $urlfoto["url"],
));
$idphoto = $idphotox->fetch(PDO::FETCH_ASSOC);

$idphoto = $idphoto["photoID"];

$sql = "SELECT * FROM Fa_like WHERE iduser=:iduser AND photoID = :photoid";
$likex = $db->prepare($sql);
$likex->execute(array(
    ':iduser' => $_SESSION["iduser"],
    ':photoid' => $idphoto,
));
$like = $likex->fetch(PDO::FETCH_ASSOC);
if($like != false)
{
  if($like["likea"] == 1) $likeencontrado=true;
  else if($like["dislikea"] == 1) $dislikeencontrado=true;
}

//like hover


?>