

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
include "./vROps/model/vropsConfigDB.php";
include_once './controller/utils/classUtils.php';
include_once './controller/utils/classDecodeJsonFile.php';
include_once './vROps/model/classCargarResourceList.php';
include_once '';

$fechaInicio = $_POST['fechaInicio'];
$fechaFin = $_POST['fechaFin'];


//$file = HOME . "/CTISCR/vROps/salidas/hostsystemResourceListArray.json";
$file = "/var/www/html/CTISCR/vROps/salidas/hostsystemResourceListArray.json";
$mesConsulta = 5;

//función para crear la tabla resourceList del mes si no existe


//Esta función estará en CTISCR/vROps/model/classCargarResourList.php


//función para insertar los registros en la tabla

//función para verificar que los registros que se desean insertar ya no estén


?>

<div><h1>Elementos del archivo hostsystemResourceListArray.json</h1></div>
<br/>
<!--style=" background-repeat:no-repeat; width:450px;margin:0;" cellpadding="0" cellspacing="0" border="0"-->
<table>
<tr>
<?php

    $cols=1; //Se inicia el contador en uno porque hay una celda adicional que corresponde con el número de registro
    $arrayProv = CargarResourceList::readResourceListArray($file);

    //crea el encabezado de la tabla 

    echo "<th><h4>#</h4></th>"; //añade un campo contador con #
    foreach($arrayProv[0] as $ind=>$reg){  
      echo "<th><div><h4>" . $ind . "</h4></div></th>";   //Se añaden todos los campos del encabezado
      $cols++;
    }

    //pasa la información a la base de datos
    $result = CargarResourceList::insertRegistrosResourceList($arrayProv, $mesConsulta);

    if ($result>0){ //evalúa si se insertaron los registros en la base de datos
      echo "<tr><td align='center' colspan='". $cols ."'><div><h3> Se incertaron " . $result  . " registros</h3></div></td></tr>";
    }
?>
</tr>

<?php

//Llena la tabla con los valores de los campos
foreach($arrayProv as $ind => $reg){
  echo "<tr>";  
  echo "<td align='center'>" . strval($ind + 1)  . "</td>";
  foreach($reg as $valor){
    echo "<td><div>" . $valor . "</div></td>";
  }
  echo "</tr>";  
}

$numReg = count($arrayProv);



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
$lista = ["/var/www/html/CTISCR/ejemplo/archivo_a_saltar", "/var/www/html/CTISCR/ejemplo/otro_archivo_a_excluir"];
$result = Utils::eraseFiles($ruta, $lista);
echo "Archivos excluidos: " . $result['excluidos'] . "<br>";
echo "Archivos eliminados: " . $result['eliminados'] . "<br>";
echo "Archivos raros: " . $result['rarezas'] . "<br>";

/*
function eliminar(array $files, array $lista=null){
  foreach($files as $file){ // iterate files
    //if(is_file($file) && ($file!="/var/www/html/CTISCR/vROps/salidas/VmwareToken.json"))
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