<?php

include_once "./controller/utils/classDecodeJsonFile.php";

include "./constantes.php";
$a=5;
$conf = DecodeJF::decodeJsonFile(ARCHIVODECONFIGURACION);
var_dump($conf);

//echo "<pre><h2> " . URLTAIL . " </h2></pre>";

?>