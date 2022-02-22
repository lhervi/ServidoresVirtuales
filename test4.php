<?php

for ($i=1; $i<8; $i++){
    file_put_contents("/var/www/html/STISCR/carpetaPrueba/ej" . $i . ".json", "nada");
}

function limpiarDirectorio(string $dir, array $exceptions = array()){
    
    $listado = scandir($dir);    
    unset($listado[array_search('..', $listado, true)]);
    unset($listado[array_search('.', $listado, true)]);    
    
    foreach($listado as $file){
        
        $noExcluido = array_search($file, $exceptions) === false ? true : false;
        
        if($noExcluido){           
            $f = $dir . "/" . $file;
            unlink($f);
        }        
    }    
    return true;
}

function listarDirectorio(string $dir){
   
    $listado = scandir($dir);
    unset($listado[array_search(".", $listado)]);
    unset($listado[array_search("..", $listado)]);
    return $listado;
}  

function imprimirListado($listado){
    echo "<ul>";
    foreach ($listado as $fileName){
        echo "<li>" .  $fileName . "</li>";
    }
    echo "</ul>";
}

$dir = "/var/www/html/STISCR/carpetaPrueba";
$exc = ["ej3.json"];

$list = listarDirectorio($dir);
imprimirListado($list);

$ok = limpiarDirectorio($dir, $exc);



if ($ok){
    echo "todo salió fino";
}

?>