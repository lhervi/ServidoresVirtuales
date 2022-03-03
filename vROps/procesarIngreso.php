<?php

if(session_status() !== PHP_SESSION_ACTIVE) session_start();


ini_set('memory_limit', '-1');
ini_set('max_execution_time', '-1');

include_once '../constantes.php';
include_once '../controller/utils/classDecodeJsonFile.php';
//include_once './classVropsToken.php';
$home = HOME;
include_once HOME . '/vROps/classVropsToken.php';
include_once HOME . '/vROps/classVropsConf.php';

if (REPORTERRORACTIVE){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

/*
Esta página recibe los datos del user y password y los procesa, para decidir si dar o no acceso a la aplicación
Si todo está bien, redirecciona a Vrops.php. En caso contrario, da un mensaje de error
*/

$confArray = DecodeJF::decodeJsonFile(HOME . VROPS . "vROpsConf.json");

if ($confArray['error']){   
    die("<h3>" . $confArray['mensaje'] . "</h3>");
}else{
    unset($confArray['error']);
    $confArray['userBA'] = "INTRA\\" . $_POST['userBA'];
    $confArray['passwordBA'] = $_POST['passwordBA'];
    $servers = VropsConf::getCampo('vropsServers');
    $server = $servers['vropsServers'][intval($_POST['vropsServer'])];
    $confArray['vropsServer'] = $server;

    //Validar usuario y contraseña a través de clase    --------- PENDIENTE -----------

    //============= [MEJORAR] =============== [CAMBIAR] ==========================

    //file_put_contents(HOME . VROPS . "vROpsConf.json", json_encode($confArray));
    
    //file_put_contents(HOME . VROPS . "vROpsConf.json", "{");
    
    $jsonContent = "{" . PHP_EOL;
    foreach($confArray as $ind=>$data){        
        if ($ind=="end") {
            break;      
        }else{
            $jsonContent .= '"' . $ind . '":' . json_encode($data) . "," . PHP_EOL;            
        }                
    }
    $jsonContent .= '"end":true' . PHP_EOL . '}';

    //echo "<div>" . $jsonContent . "</div><br/><br/>";    

    file_put_contents(HOME . VROPS . "vROpsConf.json", $jsonContent);
    
    /*
    foreach($confArray as $ind=>$data){
        $jsonContent = '"' . $ind . '":' . json_encode($data) . "," . PHP_EOL;
        file_put_contents(HOME . VROPS . "vROpsConf.json", $jsonContent, FILE_APPEND);
    }
    */
   
    //file_put_contents(HOME . VROPS . "vROpsConf.json", '"end":true}', FILE_APPEND); == [ELIMINAR] ==

    $tokenArray = VropsToken::getVropsToken();

   
    if ($tokenArray['error']){                
        
        $_SESSION['loging']=false;        
        //header("Location:../view/index.php", true);       
        //D:\xampp\htdocs\STISCR\vROps\view\Vrops.php
        header("Location:../view/index.php", true);
    }else{
        $_SESSION['login']=true;
        header("Location: /STISCR/vROps/view/Vrops.php", true);
    }    
    
}       

?>