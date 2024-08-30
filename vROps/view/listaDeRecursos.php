<?php
include_once "../../view/encabezado.php";
include_once "../../view/css/style.css";
?>
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
<body>
<?php

include_once "../../constantes.php";
include_once "../model/vropsConfigDB.php";
include_once '../../controller/utils/classUtils.php';
include_once '../../controller/utils/classDecodeJsonFile.php';
include_once '../model/classCargarResourceList.php';

$fechaInicio = $_POST['fechaInicio'];
$fechaFin = $_POST['fechaFin'];

$file = "/var/www/html/CTISCR/vROps/salidas/hostsystemResourceListArray.json";
$file = HOME . SALIDAS . ALLRESOURCELIST;

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
    /*
    $result = CargarResourceList::insertRegistrosResourceList($arrayProv);

    if ($result>0){ //evalúa si se insertaron los registros en la base de datos
      echo "<tr><td align='center' colspan='". $cols ."'><div><h2> Se incertaron " . $result  . " registros</h2></div></td></tr>";
    }
    */
?>
</tr>

<?php

//$file = HOME . SALIDAS . ALLRESOURCELIST;
//$arrayProv = CargarResourceList::readResourceListArray($file);
//$result = CargarResourceList::insertRegistrosResourceList($arrayProv);

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
