<?php

if(session_status() !== PHP_SESSION_ACTIVE) session_start();


ini_set('memory_limit', '-1');
ini_set('max_execution_time', '-1');


include_once '../vROps/classVropsToken.php';
include_once '../constantes.php';
include_once '../controller/utils/classDecodeJsonFile.php';



if (REPORTERRORACTIVE){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

/*
Esta página recibe los datos del user y password y los procesa, para decidir si dar o no acceso a la aplicación
Si todo está bien, redirecciona a Vrops.php. En caso contrario, da un mensaje de error
*/

$confArray = DecodeJF::decodeJsonFile(HOME . VROPS . "vROpsConf2.json");

if ($confArray['error']){
    if(REPORTERRORACTIVE){
        echo __FILE__ . " linea: " . __LINE__;
        echo "<br/>";
        echo "contenido de confArray: ";
        echo "<br/>";
        print_r($confArray);        
        echo "=========================================";
    }
    die("<h3>" . $confArray['mensaje'] . "</h3>");
}else{
    
    $confArray['userBA'] = "INTRA\\" . $_POST['userBA'];
    $confArray['passwordBA'] = $_POST['passwordBA'];

    //Validar usuario y contraseña a través de clase    --------- PENDIENTE -----------

    if (file_exists(HOME . VROPS . "vROpsConf.json")){
        unlink(HOME . VROPS . "vROpsConf.json");
    }

    //file_put_contents(HOME . VROPS . "vROpsConf.json", json_encode($confArray));
    
    file_put_contents(HOME . VROPS . "vROpsConf.json", "{", FILE_APPEND);
    foreach($confArray as $ind=>$data){
        $jsonContent = '"' . $ind . '":' . json_encode($data) . "," . PHP_EOL;
        file_put_contents(HOME . VROPS . "vROpsConf.json", $jsonContent, FILE_APPEND);
    }
   
    file_put_contents(HOME . VROPS . "vROpsConf.json", '"end":true}', FILE_APPEND);

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
        /*
        if (!isset($_SESSION['login']) || $_SESSION['login']===false){
            header("Location: /STISCR/vROps/view/ingreso.php", true);
            //D:\xampp\htdocs\STISCR\view\index.php
            //D:\xampp\htdocs\STISCR\vROps\view\ingreso.php
            //http://localhost/vROps/view/ingreso.php
            //D:\xampp\htdocs\STISCR\vROps\procesarIngreso.php
            //http://localhost/STISCR/vROps/vROps/view/ingreso.php
        }
        */

?>