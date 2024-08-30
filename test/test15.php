<?php
/*
Clase que se encarga de registrar las consultas que se van realizando para luego evitar que se repitan

La clase recibe los parámetros de la consulta y los almacena en un archivo de claves

*/

$arrayText[]="Hola como estás"; 
$arrayText[]="Hola como estás";
$arrayText[]="Bien gracias y tu";
$arrayText[]="Hola como estás";
$arrayText[]="Hola como estás";
$arrayText[]="Bien gracias y tu";

$provMd5=array();
$provSha256=array();

function agregarConsulta(array $listaDeConsultas, string $consulta){
    include_once '/var/www/html/CTISCR/constantes.php';
    include_once HOME . '/controller/utils/classFechas.php';
    include_once HOME . './vROps/classVropsConf.php';
    //Crear un arreglo con 12 posiciones, en la que en cada una se almacena las últimas 12
    //consultas que se han realizado a un arreglo de consultas
    //El arreglo tiene como clave de cada elemento, el hash de la consulta
    
    $clave = hash("md5", $consulta);
    if(array_key_exists($clave, $listaDeConsultas)){
        $error['error'] = true;
        $error['mensaje'] = "la consulta ya había sido realizada y el resultado se encuentra en la BD";
        $error['fecha'] = $listaDeConsultas[$clave]['fecha'];
        $error['user'] = $listaDeConsultas[$clave]['user'];
        $error['user'] = $listaDeConsultas[$clave]['pos'];
        return $error;
    }else{
        $listaDeConsultas['error'] = false;
        $listaDeConsultas[$clave]['fecha'] = Fechas::getMilisecondsFromDate();
        $listaDeConsultas[$clave]['user'] = VropsConf::getCampo('userBA')['userBA'];
        $tamañoLista = count($listaDeConsultas);
        if ($tamañoLista<TAMAÑOLISTACONSULTA){ 
            $listaDeConsultas[$clave]['pos'] = $tamañoLista;
        }else{
            array_shift($listaDeConsultas); //Elimina el primer
            $listaDeConsultas[$clave]['pos'] = $tamañoLista;            
        }
    }
    return json_encode($listaDeConsultas);
}

function createHash(array $lista){
    global $provMd5, $provSha256;
    
    foreach ($lista as $ind=>$item){        
        $provMd5[]=hash('md5', $item);
        $provSha256[]=hash('Sha256', $item);
    }    
}

function impProv(array $listaHash){
    //global $provMd5, $provSha256;
    foreach ($listaHash as $ind=>$item){
        echo $ind . " " . $item . "<br/>";
    }
    echo "<br/>";
}

createHash($arrayText);
impProv($provMd5);
impProv($provSha256);




?>