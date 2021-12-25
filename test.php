

<!DOCTYPE html>
<html>
<head>

<style>
table, th, td {
  border: 1px solid grey;
  border-collapse: collapse;
  padding: 1px;
}
div {
  padding: 4px;
  margin: 1px;
}
</style>

<?php

include "constantes.php";
include_once './controller/utils/classUtils.php';
include_once './controller/utils/classDecodeJsonFile.php';

//$file = HOME . "/STISCR/vROps/salidas/hostsystemResourceListArray.json";
$file = "/var/www/html/STISCR/vROps/salidas/hostsystemResourceListArray.json";

$array = DecodeJF::decodeJsonFile($file);
$prov = array();
$arrayProv = array();

if($array['error']){
    die("hubo un error");
}else{
    foreach($array as $reg){      
        $prov['name'] = $reg['name'] ?? "";
        $prov['identifier'] = $reg['identifier'] ?? "";
        $prov['adapterKindKey'] = $reg['adapterKindKey'] ?? "";
        $prov['resourceKindKey'] = $reg['resourceKindKey'] ?? ""; 
        $prov['linkToSelf'] = $reg['links']['linkToSelf'] ?? "";  
        $prov['relationsOfResource'] = $reg['links']['relationsOfResource'] ?? "";
        $prov['propertiesOfResource'] = $reg['links']['propertiesOfResource'] ?? "";
        $prov['alertsOfResource'] = $reg['links']['alertsOfResource'] ?? "";
        $prov['symptomsOfResource'] = $reg['links']['symptomsOfResource'] ?? "";
        $prov['statKeysOfResource'] = $reg['links']['statKeysOfResource'] ?? "";
        $prov['latestStatsOfResource'] = $reg['links']['latestStatsOfResource'] ?? "";
        $prov['latestPropertiesOfResource'] = $reg['links']['latestPropertiesOfResource'] ?? "";
        $prov['credentialsOfResource'] = $reg['links']['credentialsOfResource'] ?? "";        
        $arrayProv[]=$prov;
  }
}

?>

<div><h1>Elementos del archivo hostsystemResourceListArray.json</h1></div>
<br/>
<!--style=" background-repeat:no-repeat; width:450px;margin:0;" cellpadding="0" cellspacing="0" border="0"-->
<table>
<tr>
<?php
      echo "<th><h4>#</h4></th>";
    foreach($prov as $ind=>$reg){  
      echo "<th><div><h4>" . $ind . "</h4></div></th>";   
    }
?>
</tr>

<?php
foreach($arrayProv as $ind => $reg){
  echo "<tr>";  
  echo "<td align='center'>" . strval($ind + 1)  . "</td>";
  foreach($reg as $valor){
    echo "<td><div>" . $valor . "</div></td>";
  }
  echo "</tr>";  
}

/*

$numReg = count($prov);
while ($numReg>=0){
  echo "<tr>";
  foreach($arrayProv[$numReg] as $ind=>$reg){
    if($numReg==10) {
      $a=2;
    }
    
    echo "<td>" . $reg[$ind] . "</td>";
    /*
    echo "<td>" . $reg['name'] . "</td>";
    echo "<td>" . $reg['identifier'] . "</td>";
    echo "<td>" . $reg['adapterKindKey'] . "</td>";
    echo "<td>" . $reg['resourceKindKey'] . "</td>";
    echo "<td>" . $reg['linkToSelf'] . "</td>";
    echo "<td>" . $reg['relationsOfResource'] . "</td>";
    echo "<td>" . $reg['propertiesOfResource'] . "</td>";
    echo "<td>" . $reg['alertsOfResource'] . "</td>";
    echo "<td>" . $reg['symptomsOfResource'] . "</td>";
    echo "<td>" . $reg['latestStatsOfResource'] . "</td>";
    echo "<td>" . $reg['latestPropertiesOfResource'] . "</td>";
    echo "<td>" . $reg['credentialsOfResource'] . "</td>";  
    
    
    //echo "<br/>"; //$prov[10]["name"]
  }  
  echo "</tr>";
  $numReg--;
}
*/
?>

</table>

<br/>

</body>
</html>

<?php


/*
$mil = 1227643821310;
$seconds = $mil / 1000;
echo date("d/m/Y H:i:s", $seconds);



/*
$ruta = HOME . "/ejemplo/*";
$lista = ["/var/www/html/STISCR/ejemplo/archivo_a_saltar", "/var/www/html/STISCR/ejemplo/otro_archivo_a_excluir"];
$result = Utils::eraseFiles($ruta, $lista);
echo "Archivos excluidos: " . $result['excluidos'] . "<br>";
echo "Archivos eliminados: " . $result['eliminados'] . "<br>";
echo "Archivos raros: " . $result['rarezas'] . "<br>";

/*
function eliminar(array $files, array $lista=null){
  foreach($files as $file){ // iterate files
    //if(is_file($file) && ($file!="/var/www/html/STISCR/vROps/salidas/VmwareToken.json"))
    if(is_file($file)) {

      if (in_array($file, $lista)){
        echo "<h3 style='color:red';>". $file .  " esta en la lista </h3> <br/>";
      }else{
        echo "<h3>" . $file . " no esta en la lista </h3><br/>";
      }
        //echo($file) . "<br/>"; // 
        //unlink();
    }
    
  }
}

  $files = glob(HOME . '/vROps/salidas/*'); // get all file names

  $lista = [HOME . "/vROps/salidas/VmwareToken.json", HOME . "/vROps/salidas/virtualmachineResourceListArray.json"];

  eliminar($files, $lista);


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