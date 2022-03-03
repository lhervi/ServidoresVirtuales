<?php

include_once "./controller/utils/classDecodeJsonFile.php";                   
include "./constantes.php";
include_once HOME . '/vROps/classVropsConf.php';
include_once HOME . "/controller/utils/classFechas.php";

/*
$a=5;
$conf = DecodeJF::decodeJsonFile(ARCHIVODECONFIGURACION);
var_dump($conf);
*/

echo "1604395966369";
echo "<br><br>";
echo Fechas::getDatefromMiliSeconds(1604395966369);
echo "<br><br>";
$vropsServer = VropsConf::getCampo('vropsServer')['vropsServer']; //vropsServer
echo $vropsServer;
//echo $vropsServer['vropsServer'];
//

//echo "<pre><h2> " . URLTAIL . " </h2></pre>";

?>