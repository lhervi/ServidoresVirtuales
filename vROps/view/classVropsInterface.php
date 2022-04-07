<?php

class VropsInterface{

    static function getHTMLResourceKinds(){
        include_once __DIR__ . './../../constantes.php';
        include_once HOME . './../classVropsConf.php';

        $resourceKindsHTML="";

        $campoArray = VropsConf::getCampo('resourceKinds');

        if($campoArray['error']){
            return  ;
        }else{
            
            unset($campoArray['error']);
            foreach($campoArray as $resourceKind){
                $resourceKindsHTML = '<input type="checkbox" id="' . $resourceKind .'" name="' .  $resourceKind . '" value="resourceKinds" checked>' . PHP_EOL;
                $resourceKindsHTML .= '<label for="' . $resourceKind . '">' . $resourceKind . '</label>' . PHP_EOL;
            }
            
            return $resourceKindsHTML;
        }

    }

}

?>