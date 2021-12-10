<?php

include_once '../controller/utils/classJsonToTable.php';
include_once '../constantes.php';

/*
param['class'] el nombre de la clase
param['caption'] el nombre de la tabla
param["maxCeldaPorFila"] maximo número de celdas por fila

"TABLE", "table table-bordered", "TABLEDARK", "table table-dark" 
"TABLEDARKESTRIPED", "table table-striped table-dark"
"TABLESTRIPED", "table table-striped", "TABLEHOVER", "table table-hover"
"TABLEHOVERDARK", "table table-hover table-dark", "TABLESMALLDARK", "table table-sm table-dark"
"TABLESMALL", "table table-sm"
*/

$confArch = HOME.VROPS."vROpsConf.json";  //Archivo de configuración

$param = array('class'=> TABLEHOVER, 'caption'=>'Configuración vROps', 'maxCeldaPorFila'=>2);

$obj = new JsonToTable($confArch, $param);

include "../view/encabezado.php";
include "../view/menu.php"; "encabezado.php";

?>

    <body>
        <div><h3><?php echo $param['caption'] ?></h3><div>

<?php 

echo $obj->getTable();

include '../view/bodyScripts.php';
?>
        
    </body>
</html>

