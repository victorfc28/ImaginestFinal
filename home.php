<?php
    session_start();
    if(!isset($_SESSION["username"])){
        header('Location: ./index.php?redirected');
        exit;
    }
?>
<!DOCTYPE html>
<html>
<head>
<title>imagiNest - Home</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
    <style>
        body {
            background-image: url('./images/logo-imaginest.png');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center; 
        }
    </style>
</head>
<body>
    <h2>Página HOME</h2>
    <?php echo "<p>¡Hola " . $_SESSION["username"] . "!</p>";?>
    <p><a href="./logout.php">Cerrar sesión</a></p>
</body>
</html>