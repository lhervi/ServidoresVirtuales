<?php

if(session_status() !== PHP_SESSION_ACTIVE) session_start();

/*
//======= [REVISAR] Desactivado el reenvío a vrops.php dado que ahora se permite el cambio de servidor

$loginOk=false;
$existeLogin = array_key_exists("login", $_SESSION);
if($existeLogin) $loginOk = $_SESSION['login']===true;

if($loginOk && $existeLogin){
    header("Location: /STISCR/vROps/view/Vrops.php", true);
    ///var/www/html/STISCR/vROps/view/Vrops.php
}
*/

ini_set('memory_limit', '-1');
ini_set('max_execution_time', '-1');
ini_set('display_errors', 1);

//Error -------------------------------------------
error_reporting(E_ALL);
ini_set('display_errors', '1');
//Error -------------------------------------------

include_once "../classVropsConf.php";
include "../view/../../view/encabezado.php";

include_once '../../controller/utils/classUtils.php';

$directoio = HOME . SALIDAS;
Utils::limpiarDirectorio($directoio);
$directoio = HOME . STATS;
Utils::limpiarDirectorio($directoio);

function servers(){
    $servers = VropsConf::getCampo('vropsServers');
    if($servers['error']){
        die('no se pudo obtener la lista de servidores del archivo de configuración');
    }else{
        foreach($servers['vropsServers'] as $ind=>$serv){
            echo "<option value='" . $ind . "'>" . $serv . "</option>" . PHP_EOL;
        }
    }

}

?>
<body class="m-0 vh-100 row justify-content-center align-items-center">
<div class="container col-auto">
    <div class="d-inline-flex justify-content-center align-items-center p-2 .bg-light">    

        <div class="border border-secondary p-3 mb-2 bg-light text-dark rounded">

            <form action="../procesarIngreso.php" method="post" class="form-group">

                <img src="../../view/../view/icons/people.svg" alt="ingreso" class="iconMedium">

                <div class="form-group"><h2>Datos de acceso</h2><br>
                    
                    <lable for="userBA">Usuario</lable><br>
                    <input type="text" Id="userBA" name="userBA" placeholder="usuarioBA" class='campo'><br><br>
                    <lable for="passwordBA">Password</lable><br>
                    <input type="password" Id="passwordBA" name="passwordBA" class='campo'><br><br>                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="vropsServer">Servidor</label>
                        </div>
                        <select class="custom-select" id="vropsServer" name="vropsServer">
                            <?php servers(); ?>
                        </select>
                    </div>                   
                    <br><br>
                </div>

                    <div><button id="submit" type="submit" class="btn btn-primary" disabled>Enviar</button></div><br>
                   
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

<script>    
    function todoOk(){               
        userBa = document.getElementById("userBA").value == "" ? false : true;
        passwordBA = document.getElementById("passwordBA").value == "" ? false : true;             
        return userBA && passwordBA;
    }

    function habilitar(){
        if (todoOk()){            
            document.getElementById('submit').disabled=false;
        }else{            
            document.getElementById('submit').disabled=true;
        }
    }
    
    document.getElementById("userBA").addEventListener('keypress', habilitar);
    document.getElementById("passwordBA").addEventListener('keypress', habilitar);
    document.getElementById("userBA").addEventListener('change', habilitar);
    document.getElementById("passwordBA").addEventListener('change', habilitar);

</script>

</body>
</html>
<?php


?>