<?php

//ini_set('session.save_path', '/opt/lornis/CTISCR/sessionData');

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$_SESSION['prueba'] = 5;

/*
//======= [REVISAR] Desactivado el reenvío a vrops.php dado que ahora se permite el cambio de servidor

$loginOk=false;
$existeLogin = array_key_exists("login", $_SESSION);
if($existeLogin) $loginOk = $_SESSION['login']===true;

if($loginOk && $existeLogin){
    header("Location: /CTISCR/vROps/view/Vrops.php", true);
    ///var/www/html/CTISCR/vROps/view/Vrops.php
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

/* ========== [PROVISIONAL] [LINEAS COMENTADAS PROVISIONALMENTE]
$directoio = HOME . SALIDAS;
Utils::limpiarDirectorio($directoio);
$directoio = HOME . STATS;
Utils::limpiarDirectorio($directoio);
*/

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
                        
                        if (isset($_SESSION['loging']) && ($_SESSION['loging']===true)){
                    
                            echo '<div><lable for="submit">Acceso concedido</lable><br></div>';
                     
                        }elseif(isset($_SESSION['loging']) && ($_SESSION['loging']===false)){                                
                    
                            echo '<div><lable for="submit">Acceso denegado</lable><br></div>';
                    
                        }    
                                
                    ?>
            </form>

        </div>

    </div>
</div>

<?php include '../../view/bodyScripts.php';?>

<script>    
    
    function habilitar(){   
        const userBaInput =  document.getElementById("userBA");
        const passwordBAInput = document.getElementById("passwordBA");
        
        todoOk = ((userBaInput.value !="") && (passwordBAInput.value != "")) ? true : false;
            
        document.getElementById('submit').disabled=!todoOk; 
        //console.log('todo ok : ' + todoOk.toString() + '  userBa: ' + userBaInput.value + '  pass: ' + passwordBAInput.value);       
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