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
     * clase método estático que regresa un <div> que contiene un <spam> en el que se muestra
     * un text que se pasa como parámetro
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
    
    /**
     * loader método estático para manejar un objeto loader desde javascript
     *
     * @param  string $class el nombre de la clase, por defecto es "loader"
     * @return string regresa el html que corresponde al Loader
     */
    static function loader(string $class="loader"){        
        return '<div id="loader" class="' . $class . '" style="display:none;"></div>';
    }
    
    /**
     * showLoader método estático que muestra el objeto loader
     *
     * @return void
     */
    static function showLoader(){            
            $show = 'function showLoader(e){' . PHP_EOL;
            $show .= 'document.getElementById("loader").style.visibility= "visible";' . PHP_EOL;
            $show .= 'document.getElementById("loader").style.display= "block";' . PHP_EOL . '}';
            return $show;                   
    }

    static function bindLoaderSubmit($id, $link){        
        $func = "e.preventDefault()";
        $func .= "document.getElementById('$id').addEventListener('click',function(){" . PHP_EOL; 
        $func .= "document.getElementById('enviarForma').submit();";   
        $func .= PHP_EOL . "location.href ='" . $link . "';" . PHP_EOL . "})" . PHP_EOL;                    
        return $func;
    }            
        
    
    
    /**
     * hideLoader método estático que oculta el objeto loader
     *
     * @return void
     */
    static function hideLoader(){
        $show = 'function hideLoader(){' . PHP_EOL;       
        $show .= 'document.getElementById("loader").style.display= "none";' . PHP_EOL . '}';
        return $show;                   
    }

}


?>