<?php

include_once './constantes.php';
include_once './controller/utils/classDecodeJsonFile.php';
include_once './vROps/classVropsConf.php';
include_once './vROps/classCurl.php';
  //$file = "/var/www/html/CTISCR/vROps/vROpsConf.json";

function echoArray($ind, $valor){
    if(is_array($valor)){
        echo '<h3>"' . $ind . '":"</h3>';
        foreach($valor as $ind=>$val){           
                echoArray($ind, $val);            
            }
    }else{
        if (is_numeric($ind)){
            echo '<h3>"' . $valor . '"' .  "</h3>";
        }else{
            echo '<h3>"' . $ind . '":"' . $valor . '"' .  "</h3></br>";
        }
        
    }
}
    


function curlSetOpt($curl, $param=[]){      
    
    $arch = fopen($param['arch'] , "w") or exit ("no se pudo abrir el archivo que almacenará el token");
    
    curl_setopt($curl, CURLOPT_HTTPHEADER, $param['header']);                   //1
    curl_setopt($curl, CURLOPT_PROXY, $param['proxy']);                         //2
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                         //3
    curl_setopt($curl, CURLOPT_URL, $param['url']);                             //4
    curl_setopt($curl, CURLOPT_PROXYUSERPWD, $param['userproxy']);              //5
             
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                           //6
    curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);                           //7
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);                           //8
    curl_setopt($curl, CURLOPT_CAINFO, $param['certfirefox']);                  //9
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 300);                            //10
    if ($param['GET']){ 
        curl_setopt($curl, CURLOPT_HTTPGET, 1);                                 //11
    }else{
        curl_setopt($curl, CURLOPT_POST, 1);                                    //12
        curl_setopt($curl, CURLOPT_POSTFIELDS, $param['campos']);               //13
    }
    curl_setopt($curl, CURLOPT_FILE, $arch);                                    //14   
}      

$objConf = new VropsConf("tipoVmwareToken"); //arreglo con los datos de la configuración que provienen de vROpsConf.json
$param = $objConf->getParam();
foreach($param as $ind=>$val){
    echoArray($ind, $val);
}
$curl = curl_init(); 
echo "<br/><br/>";

Curl::curlSetOpt($curl, $param); //configura el Curl
$result = curl_exec($curl);  //regresa true si no hubo error
curl_close($curl);

?>