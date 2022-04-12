<?php

class VropsInterface{

    static function getHTMLResourceKinds(){
        include_once __DIR__ . './../../constantes.php';        
        ///var/www/html/STISCR/vROps/classVropsConf.php
        include_once HOME . '/vROps/classVropsConf.php';

        

        $campoArray = VropsConf::getCampo('resourceKinds');

        if($campoArray['error']){
            return  null;
        }else{            
            
            $resourceKinds = $campoArray['resourceKinds'];
            $resourceKindsHTML="";
            foreach($resourceKinds as $resourceKind){
                $resourceKindsHTML .= '<input type="checkbox" id="' . $resourceKind .'" name="' .  $resourceKind . '" value="resourceKinds" checked>' . PHP_EOL;
                $resourceKindsHTML .= '<label for="' . $resourceKind . '">' . $resourceKind . '</label>' . PHP_EOL;
            }
            
            return $resourceKindsHTML;
        }

    }

}

?>