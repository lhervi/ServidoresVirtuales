<?php
include "../view/encabezado.php";
include "../view/menu.php";
require_once '../model/CargarLineaBase.php';
require_once 'classOperCSV.php';
require_once 'classTrasObj.php';
require_once '../model/configdb.php';
?>

<body>

<?php

if (session_status()!=PHP_SESSION_ACTIVE){
    session_start();
}

$csvObj = $_SESSION["csvObject"];

//$obj = new ObjCSV();
$obj = new ObjCSV();

$tras = new TrasObj();

$tras->traspasar($csvObj, $obj);

$objLineaBase = new OperCSV($obj);

$p = new CargarLineaBase($objLineaBase);

$p->cargarLineaBase();

echo "<h3>La linea base fue cargada exitosamente!</h3><br/>";
echo"En total se cargaron " . $objLineaBase->getNumeroDeRegistros() . " en la base de datos: " . BASE_DE_DATOS;

?>

<br/>