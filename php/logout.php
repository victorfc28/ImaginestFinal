<?php
  session_start();
  if(!isset($_SESSION["iduser"])){
      header('Location: ../index.php?redirected');
      exit;
  }else{
      $_SESSION = array();
      session_destroy();
      setcookie(session_name(),"",time()-3600,"/");
      setcookie("logged","",time()-3600*24);
      header('Location: ../index.php?logout');
}
?>