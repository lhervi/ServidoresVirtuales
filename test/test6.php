<?php


    include_once './constantes.php';
    include_once HOME . '/controller/utils/classDecodeJsonFile.php';

function arrayToJson(array $jsonArray){
    $prov = json_encode($jsonArray);
    $coma =",";
    $comaYsalto = "," . PHP_EOL;
    
    if($prov){
        $result['error'] = false;
        $result['json'] = str_replace($coma, $comaYsalto, $prov);
        return $result;
    }else{
        $result['error'] = true;
        $result['mensaje']="el arreglo no pudo ser transformado a .json";
        return $result;
    }
}

$archJson= HOME . "/vROps/vROpsConf.json";
$jsonArray = DecodeJF::decodeJsonFile($archJson);
if (!$jsonArray['error']){
    unset($jsonArray['error']);
    $salida = arrayToJson($jsonArray)['json'];
    file_put_contents("test6.json", $salida);
    echo $salida;
}else{
    echo $jsonArray['mensaje'];
}


?>