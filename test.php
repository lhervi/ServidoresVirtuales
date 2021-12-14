<?php

include "constantes.php";

echo HOME;
echo "<br/><br/>";

$files = glob(HOME . '/vROps/salidas/stats/*'); // get all file names

foreach($files as $file){ // iterate files
  //if(is_file($file) && ($file!="/var/www/html/STISCR/vROps/salidas/VmwareToken.json"))
  if(is_file($file))
    echo($file) . "<br/>"; // 
    //unlink();
}

/*
$var = array();
echo "el valor del count es: " . count($var);

/*$var = json_decode ('nada.txt', true);

    
    if($var){
      echo "Acepta la variable </br>"  ;
    }elseif(true){
        echo "Acepta true";
    }

    
    echo '<div class="w-100"  style="background-color: #ccc; padding: 15px; height: 250px; max-width: 100%;">';
    echo "<form action='loadVrops.php' method='POST'>";
    echo "<input type='submit'value='Continuar' id='continuar'></br>"; 
    echo "<label for='continuar' style='margin-top : 15px'>Presione el botón para continuar</label>";
    echo "</form>";
    echo "</div>";
    */
?>