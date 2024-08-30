<?php 

if(session_status() !== PHP_SESSION_ACTIVE) session_start();

$ExisteLogin = array_key_exists('login', $_SESSION);
$loginFalso = $_SESSION['login']===false;

if (!$ExisteLogin || $loginFalso){
    header("Location: ../vROps/view/ingreso.php", true);
}

include_once "./../constantes.php";
include_once "./../vROps/model/vropsConfigDB.php";
//D:\xampp\htdocs\CTISCR\vROps\model\vropsConfigDB.php
include "encabezado.php";
include "menu.php";

?>
    <!--div class="d-inline-flex justify-content-center align-items-center p-2 .bg-light"> -->
    <body class="m-0 vh-100 col justify-content-left align-items-top">       

        <div class="container justify-content-left style="background-color: #eee; height: 150px;">
            <br><br><br>
            <h4>Aplicación para obtener los datos de vROPs y enviarlos al DataStage</h4>
            <br><br>
            <h1>vROPs -> DataStage</h1>
        <div/>
        
    <?php include "bodyScripts.php" ?>
    </body>
</html>