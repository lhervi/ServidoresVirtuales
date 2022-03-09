<?php
 
 include_once './constantes.php';
 include HOME . '/view/encabezado.php';

 function enlace(string $id, string $link){
    $func = "document.getElementById('$id').addEventListener('click',function(){";    
    $func .= PHP_EOL . "location.href ='" . $link . "';" . PHP_EOL . "})" . PHP_EOL;                    
    return $func;
 }

    $clase = function($str, int $tam=3){
        static $val=true;
        
        if ($val){
            $val = false;
            return "<div class='estatus1'><h" . $tam . ">" . $str . "</h" . $tam . "</div>";            
        }else{
            $val = true;
            return "<div class='estatus2'><h" . $tam . ">" . $str . "</h" . $tam . "</div>";           
        }
    }

    

?>


<body>
<br/>
<?php

function estiloEstatus(){
    return null;
}

 echo $clase(" Culminó con éxito la carga de los registros");
 echo $clase("resultado1");      
 echo $clase("prueba de resultados");
 echo $clase("mas resultados que mostrar");
 echo '<div id="regresar" style="cursor:pointer; width:200px"><h3>>>>Regresar</h3></div>';
 echo "<script>" . PHP_EOL;      
 echo (enlace('regresar', INICIO));
 echo "</script>";


// scriptTag(enlace('regresar', INICIO));

/*

function scriptTag($func, $param){
    
    echo "<script>" . PHP_EOL;
    
    if(is_array($param)){
        $func(implode(" ,", $param));
    }else{
        $func($param);
    }
    
    echo "</script>";
 }

$func = function (string $fName, array $param){     
    return [$fName](explode(" ,", $param));
}

$arg = array('regresar', INICIO);
*/
?>

</body>
</html>
