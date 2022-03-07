<?php

include_once './constantes.php';

$fileDir = HOME . SALIDAS . ALLRESOURCELIST;

$file = file_get_contents($fileDir);

$fileArray = json_decode($file, true);

var_dump ($file);

//var_dump($fileArray[0]);


?>