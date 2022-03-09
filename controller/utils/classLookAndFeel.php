<?php

class LookAndFeel{
    
    /**
     * enlace método estático que asigna un enlace a un id dado a través de una función javascript que se activa 
     * al hacer click sobre el elemento
     *
     * @param  string $id el id del elemento
     * @param  string $link el URL al que va a apuntar el link que se asigna al elemento click
     * @return string regresa una cadena con la función javascript que apunta al enlace  
     */
    static function enlace(string $id, string $link){
        $func = "document.getElementById('$id').addEventListener('click',function(){";    
        $func .= PHP_EOL . "location.href ='" . $link . "';" . PHP_EOL . "})" . PHP_EOL;                    
        return $func;
    }
        
    /**
     * clase método estático que regresa un <div> que contiene un <spam> y cuyo
     *
     * @param  string $str un string con el mensaje a insertar
     * @param  int $tam Determina el tamaño de la fuente y por defecto su valor es 3
     * @return string Regresa un string de la siguiente forma: <div class=[estatus1|estatus2]><spam>El contenido</spam></div>
     */
    static function estatusX($str, int $tam=3){
            static $val=true;
            
            if ($val){
                $val = false;
                return "<div class='estatus1'><spam><h" . $tam . ">" . $str . "</h" . $tam . "<spam></div>";            
            }else{
                $val = true;
                return "<div class='estatus2'><spam><h" . $tam . ">" . $str . "</h" . $tam . "<spam></div>";           
            }
    }

    static function estatus($str, int $tam=3){                
        return "<div class='estatus'><spam><h" . $tam . ">" . $str . "</h" . $tam . "<spam></div>";            
        
        
    }

}


?>