<?php

//include_once HOME . "/controller/utils/classUtils.php";

include_once "./controller/utils/classUtils.php";

for ($i=1; $i<8; $i++){
    file_put_contents("/var/www/html/CTISCR/carpetaPrueba/ej" . $i . ".json", "nada");
}

function imprimirListado($listado){
    echo "<ul>";
    foreach ($listado as $fileName){
        echo "<li>" .  $fileName . "</li>";
    }
    echo "</ul>";
}


$dir = "/var/www/html/CTISCR/carpetaPrueba";

$exc = ["ej3.json", "ej4.json"];

echo "<br><br>======== antes ===============</br>";

$list = Utils::listarDirectorio($dir);

imprimirListado($list);

echo "<br><br>======== despues ===============</br>";

$list = Utils::listarDirectorio($dir);

$ok = Utils::limpiarDirectorio($dir, $exc);

$list = Utils::listarDirectorio($dir);

imprimirListado($list);

if ($ok){
    echo "todo salió fino";
}

?>