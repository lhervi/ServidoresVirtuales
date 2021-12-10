<?php

include_once '../model/dbConexion.php';

class ExisteLineaBase {  //Revisar funcionamiento y manejo de las condiciones

    private $existeLineaBase;

    function existeLB($fecha) {
        
        if (isset($fecha)) {

            $consulta = "SELECT fecha, fecha_carga FROM linea_base where fecha='" . $fecha . "' LIMIT 1;";
        
            $conexion = new Conexion();

            $res = $conexion->consultar($consulta);

            if (isset($res[0])){
                if ($res[0][0]==$fecha){
                    $lineaBaseStatus[0] = TRUE;     // Determina verdadero porque el archivo existe en la BD
                    $lineaBaseStatus[1] = $res[0][1];  // res1 contiene la fecha de creación del archivo
                }else{
                    $lineaBaseStatus[0] = FALSE;     // Determina verdadero porque el archivo existe en la BD
                    $lineaBaseStatus[1] = null;  // res1 contiene la fecha de creación del archivo
                }
            }else{
                $lineaBaseStatus[0] = null;     // Determina verdadero porque el archivo existe en la BD
                $lineaBaseStatus[1] = null;
            }
            return $lineaBaseStatus;
        }else{
            die("hay un problema para identiicar la fecha de la última modificación del archivo");
        }

    } //Cierre de funcion

} //Cierre de clase

?>