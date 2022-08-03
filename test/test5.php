<?php

/*

include_once "./controller/utils/classDecodeJsonFile.php";                   
include "./constantes.php";
include_once HOME . '/vROps/classVropsConf.php';
include_once HOME . "/controller/utils/classFechas.php";



/* ============ Función para editar un json
Pasos:
Recibir la dirección del json y el objeto a cambiar o añadir o eliminar, 
y el parámetro de la operación a realizar (add, del, upd)
Decodificar el json y convertirlo en un arreglo
Según la operación a realizar (add, del, upd)
- Si es add: buscar la posición (un entero) desde la que se desea anadir el elemento.
0 es la primera posición y así sucesivamente. Si no se especifica ninguna, se añade al final.
Retorna como salida, el nuevo archivo json con el elemento incorporado y un error = false 
o null y error=true si hubo problemas
- Si es del: se busca el elemento dentro del arreglo y se elimina. 
Retorna como salida,el nuevo archivo json sin el elemento eliminado y un error = false o null 
y error=true si hubo problemas
- Si es upd, busca el elemento dentro del arreglo y sustituye su valor por el nuevo suministrado
Retorna como salida,el nuevo archivo json con el elemento modificado y un error = false o null 
y error=true si hubo problemas
*/

/*

class gestionJsonFiles{    

    private $jsonArray;

    function __construct(string $archJson){
        include_once './constantes.php';
        include_once HOME . 'controller/utils/classDecodeJsonFile.php';

        $this->jsonArray = DecodeJF::decodeJsonFile($archJson);
        if ($this->jsonArray['error']){
            return null;
        }
    }

    private function arrayToJson(array $jsonArray){
        $prov = json_encode($jsonArray);
        $coma =",";
        $comaYsalto = "," . PHP_EOL;
        
        if($prov){
            $result['error'] = false;
            $result['json'] = str_replace($coma, $comaYsalto, $prov);
            return $result;
        }else{
            $result['error'] = true;
            $result['mensaje']="el arreglo no pudo ser transformado a .json";
            return $result;
        }
    }

    private function add(array $jsonArray, string $elem, $val, $pos=-1){

        $noExiste = !array_key_exists($this->jsonArray[$elem]);
        
        if ($pos<0 && $noExiste){
            $jsonArray[$elem] = $val;
        }else{

        }
        
        return null;
    }

    private function del(array $jsonArray, string $elem, $pos){
        
        return null;
    }

    private function upd(array $jsonArray, string $elem, $pos){
        
        return null;
    }
    
    
}

if ($confArray['error']){   
    die("<h3>" . $confArray['mensaje'] . "</h3>");
}else{
    unset($confArray['error']);
    $confArray['userBA'] = "INTRA\\" . $_POST['userBA'];
    $confArray['passwordBA'] = $_POST['passwordBA'];
    $servers = VropsConf::getCampo('vropsServers');
    $server = $servers['vropsServers'][intval($_POST['vropsServer'])];
    $confArray['vropsServer'] = $server;





$a=5;
$conf = DecodeJF::decodeJsonFile(ARCHIVODECONFIGURACION);
var_dump($conf);


//========= Convertir fecha de milisegundos a cadena de texto en formato año-mes-dia hora:min ===========

echo "1604395966369";
echo "<br><br>";
echo Fechas::getDatefromMiliSeconds(1604395966369);
echo "<br><br>";
$vropsServer = VropsConf::getCampo('vropsServer')['vropsServer']; //vropsServer
echo $vropsServer;
echo $vropsServer['vropsServer'];

*/

?>