<?php

/**
 * ArrayToJson
 * Clase para procesar un arreglo (parámetro de entrada) y regresarlo en formato json dentro de otro arreglo (salida)
 *
 */
class ArrayToJson{
    
    /**
     * ArrayToJsonEncode
     *Método estático para trasnformar un arreglo a json. 
     * Si todo está bien, la salida es:
     * ['error'] = false y ['json'] = con la entada en formato json Esta salida ha sido previamente validada 
     * de que su longitud sea mayor a 0.
     * 
     * Si hubo un error, o la longitud del arreglo en formato json es de 0, la salida es:
     * 'error'] = true y ['mensaje'] = "el arreglo no pudo ser transformado a json";
     * 
     * 
     * @param  array 
     * @return array
     */
    static function ArrayToJsonEncode(array $array){
        
        $prov=json_encode($array);

        if (strlen($prov)>0){ 

            $resp['error'] = false;
            $resp['json'] = $prov;
            return $resp;

        }else{

            $resp['error'] = true;
            $resp['mensaje'] = "el arreglo no pudo ser transformado a json";
            return $resp;

        }
        
    }
}

?>