<?php

if(session_status() !== PHP_SESSION_ACTIVE) session_start();

if(array_key_exists("login", $_SESSION) && $_SESSION['login']===true){
    header("Location: /STISCR/vROps/view/Vrops.php", true);
    ///var/www/html/STISCR/vROps/view/Vrops.php
}

ini_set('memory_limit', '-1');
ini_set('max_execution_time', '-1');
ini_set('display_errors', 1);

//Error -------------------------------------------
error_reporting(E_ALL);
ini_set('display_errors', '1');
//Error -------------------------------------------
include "../view/../../view/encabezado.php";

?>
<body class="m-0 vh-100 row justify-content-center align-items-center">
<div class="container col-auto">
    <div class="d-inline-flex justify-content-center align-items-center p-2 .bg-light">    

        <div class="border border-secondary p-3 mb-2 bg-light text-dark rounded">

            <form action="../procesarIngreso.php" method="post" class="form-group">

                <img src="../../view/../view/icons/people.svg" alt="ingreso" class="iconMedium">

                <div class="form-group"><h2>Datos de acceso</h2><br>

                    <lable for="userBA">Usuario</lable><br>
                    <input type="text" Id="userBA" name="userBA" placeholder="usuarioBA"><br><br>
                    <lable for="passwordBA">Password</lable><br>
                    <input type="password" Id="passwordBA" name="passwordBA"><br><br>
                    
                </div>

                    <div><button id="submit" type="submit" class="btn btn-primary">Enviar</button></div><br>
                    <?php                       
                        
                        if (isset($_SESSION['loging'])===true){
                        //if (true){
                            $a=5;
                            if($_SESSION['loging']===false){
                                $a=5;

                    ?>
                                <div><lable for="submit">Acceso denegado</lable><br></div>
                    <?php  
                            }else{
                                $a=5;
                    ?>
                                <div><lable for="submit">Acceso concedido</lable><br></div>
                    <?php
                            }    
                        }        
                    ?>
            </form>

        </div>

    </div>
</div>

<?php include '../../view/bodyScripts.php';?>

</body>
</html>
<?php


?>