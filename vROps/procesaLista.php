<?php

//
include_once __DIR__ . '/../constantes.php';
include_once HOME . './classLista.php';

if (isset($_GET['consulta'])){
    
    $consulta = $_GET['consulta'];

    $consultaHash = hash("md5", $consulta);

    $result = Lista::existeEnLista($consultaHash);

    return $result;
}


?>