<?php

class Validaciones{

    static function camposNulos(array $arreglo){        
        $error['error']=false;
        $error['mensaje']="";
        foreach($arreglo as $ind=>$valor){
            if(is_null($valor)){
                $error['error']=true;
                $error['mensaje'] .=" el campo: " . $ind . "tiene un valor nulo". PHP_EOL;
            }
        }        
        return $error;
    }

}

?>