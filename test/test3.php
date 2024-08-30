<?php

//1636340399999
//1636343999999

/*
$mil = 1227643821310;
$seconds = $mil / 1000;
echo date("d/m/Y H:i:s", $seconds);
*/

$fec1 = 1636340399999;
$fec2 = 1636343999999;

$fechaString = "2021/07/11 22:59:59";

function getDatefromMiliSeconds(int $fecMiliSeconds, $tipo="Y/m/d H:i:s"){ 
    /*
        $tipo("F j, Y, g:i a");                 // March 10, 2001, 5:16 pm
        $tipo("m.d.y");                         // 03.192.01
        $tipo("j, n, Y");                       // 10, 3, 2001
        $tipo("Ymd");                           // 20010310
        $tipo('h-i-s, j-m-y, it is w Day');     // 05-16-18, 10-03-01, 1631 1618 6 Satpm01
        $tipo('\i\t \i\s \t\h\e jS \d\a\y.');   // it is the 10th day.
        $tipo("D M j G:i:s T Y");               // Sat Mar 10 17:16:18 MST 2001
        $tipo('H:m:s \m \i\s\ \m\o\n\t\h');     // 17:03:18 m is month
        $tipo("H:i:s");                         // 17:16:18
    
    */         

    $fecha = date($tipo, ($fecMiliSeconds/1000));
    return $fecha;
}

function getMilisecondsFromDate(string $fecha){

    if(is_null($fecha)){
        $fecMili = new DateTime();
    }else{
        $fecMili = new DateTime($fecha);
    }
    
    return  $fecMili->getTimestamp()*1000;

}

$getMili = function($fec){
    return getMilisecondsFromDate($fec);
};

$getFecha = function($fecMiliSeconds){
    return getDatefromMiliSeconds($fecMiliSeconds);
};

function fecha($parametro, $function){

    return $function($parametro);
    
}


//Función que dada un fecha, indique cuál la primera fecha y última de ese mes en fomato año/mes/dia hora:min:seg

//echo "fecha1 es: " . $fec1 . " y fecha2 es: " . $fec1;
echo "<br/><br/>";
echo "getMili: " . fecha($fechaString, $getMili);
echo "<br/><br/>";
echo "getFecha: " . fecha($fec1, $getFecha);
echo "<br/><br/>";

/*
echo "fecha1: " . getDatefromMiliSeconds($fec1);
echo "<br/><br/>";
echo "fecha2: " . getDatefromMiliSeconds($fec2); 
echo "<br/><br/>";
echo "fecha1 mes y año: " . getDatefromMiliSeconds($fec1, "m Y");
echo "<br/><br/>";
echo "fecha2 mes y año: " . getDatefromMiliSeconds($fec2, "m Y");
echo "<br/><br/>";
echo "fecha: " . date('d-m-Y H:i:s');
echo "<br/><br/>";
echo "De fecha microseconds: " . date('Ymd His'.substr((string)microtime(), 1, 8).' e');
echo "<br/><br/>";
echo "Microtime false: " . microtime(false);
echo "<br/><br/>";
echo "Microtime true: " . microtime(true);
echo "<br/><br/>";
echo "Microtime true x mil: " . microtime(true) * 1000;
echo "<br/><br/>";
echo "Microtime true x mil y pasado a int: " . intval(microtime(true) * 1000);
echo "<br/><br/>";
echo "fecha recovertida a string desde microtime true x mil y pasado a int: " . getDatefromMiliSeconds(intval(microtime(true) * 1000));
echo "<br/><br/>";
echo "De fecha a float: " . floatval(date('Ymd His'.substr((string)microtime(), 1, 8).' e'));
echo "<br/><br/>";
echo "De fecha a float * mil: " . floatval(date('Ymd His'.substr((string)microtime(), 1, 8).' e'))*1000;
echo "<br/><br/>";
echo "La fecha " . $fechaString . " en formato de milisegundos es: " . getMilisecondsFromDate($fechaString);
echo "<br/><br/>";
echo "La misma fecha transformada " . $fechaString . " nuevamente a string: " . getDatefromMiliSeconds(getMilisecondsFromDate($fechaString));
*/
?>