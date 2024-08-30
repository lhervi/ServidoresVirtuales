<?php

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$_SESSION['prueba'] = 4; //[ELIMINAR]


ini_set('memory_limit', '-1');
ini_set('max_execution_time', '-1');

include_once '../constantes.php';
include_once HOME . '/controller/utils/classDecodeJsonFile.php';
include_once HOME . '/controller/utils/classUtils.php';
include_once HOME . '/vROps/classVropsToken.php';
include_once HOME . '/vROps/classVropsConf.php';


/*
Esta página recibe los datos del user y password y los procesa, para decidir si dar o no acceso a la aplicación
Si todo está bien, redirecciona a Vrops.php. En caso contrario, da un mensaje de error
*/

$directoio = HOME . SALIDAS;
Utils::limpiarDirectorio($directoio);
$directoio = HOME . STATS;
Utils::limpiarDirectorio($directoio);



$confArray = DecodeJF::decodeJsonFile(HOME . VROPS . "vROpsConf.json");

if ($confArray['error']){   
    die("<h3>" . $confArray['mensaje'] . "</h3>");
}else{
    unset($confArray['error']);
    $confArray['userBA'] = "INX\\" . $_POST['userBA'];
    $confArray['passwordBA'] = $_POST['passwordBA'];
    $servers = VropsConf::getCampo('vropsServers');
    $server = $servers['vropsServers'][intval($_POST['vropsServer'])];
    $confArray['vropsServer'] = $server;

    //Validar usuario y contraseña a través de clase    --------- PENDIENTE -----------

    //============= [MEJORAR] =============== [CAMBIAR] ==========================
       
    $jsonContent = "{" . PHP_EOL;
    foreach($confArray as $ind=>$data){        
        if ($ind=="end") {
            break;      
        }else{
            $jsonContent .= '"' . $ind . '":' . json_encode($data) . "," . PHP_EOL;            
        }                
    }
    $jsonContent .= '"end":true' . PHP_EOL . '}';
    
    file_put_contents(HOME . VROPS . "vROpsConf.json", $jsonContent);
    
    $tokenArray = VropsToken::getVropsToken();

   
    if ($tokenArray['error']){                
        
        $_SESSION['loging']=false;        

        header("Location:../view/index.php", true);
        exit();
    }else{
        $_SESSION['login']=true;
        $a;
        header("Location: /CTISCR/vROps/view/Vrops.php", true);
        exit();
    }    
    
}       

?>