<?php

include "../view/../../view/encabezado.php";
include "../../controller/utils/classDecodeJsonFile.php";

$resourceKindsArray = DecodeJF::decodeJsonFile(HOME.VROPS."vROpsConf.json");

?>

<body class="m-0 vh-100 row justify-content-center align-items-center">
    <div class="container col-auto">
        <div class="d-inline-flex justify-content-center align-items-center p-2 .bg-light">
            <div class="border border-secondary p-3 mb-2 bg-light text-dark rounded">

                <form action="../procesarConfiguracion.php" method="post" class="form-group">

                    <img src="../../view/../view/icons/toggles.svg" alt="ingreso" class="iconMedium">

                    <div class="form-group"><h2>Gestión de la configuración vROps</h2><br>

                        
                        <div class="form-group form-check-block">
                            <input type="text" Id="userVrops" name="userVrops" placeholder="Usuario vROps">
                            <lable for="userVrops">Usuario vROps</lable>
                             <br>  <br>
                             <input type="password" Id="passwordVrops" name="passwordVrops" placeholder="Password Vrops">
                            <lable for="userVrops">Password vROps</lable>                    
                            
                        </div><br>
                        <div class="form-group form-check-block">
                        <input type="text" Id="prefix" name="prefix" placeholder="\\INX">
                            <lable for="prefix">Prefijo de Usuario</lable>
                            
                        </div><br>
                        <div class="form-group form-check-block">
                        <input type="text" Id="proxy" name="proxy" placeholder="proxy.com"> 
                            <lable for="proxy">Proxy</lable><br><br>
                            <input type="number" Id="segmentos" name="segmentos" placeholder="100" min="50" max="500">
                            <lable for="segmentos">Segmentos</lable>
                            
                        </div><br>                
                        <div class="form-group form-check-block">
                        <input type="text" Id="certficado" name="certficado" placeholder="STI/vROps/vrops.pem">    
                        <lable for="certfirefox">Ruta del certficado del browswer</lable>
                            
                        </div><br><br>
                        <div class="form-group form-check-block"><h5>Tipo de Recurso</h5>
                            <lable for="certfirefox"><h6>Tipo de recursos "Resourcekinds"</h6></lable>                                                
                           
<?php
if ($resourceKindsArray['error']){
    die($resourceKindsArray['mensaje']);
}else{
    if (array_key_exists('resourceKinds', $resourceKindsArray)){        
        foreach($resourceKindsArray['resourceKinds'] as $ind=>$resourceKinds){
                            
                    echo '<input type="checkbox" id="' . $resourceKinds . ' " name=" '. $resourceKinds .' " value="' . $resourceKinds .'" checked>';
                    echo '<label for="' . $resourceKinds. '">' . $resourceKinds. '</label><br>';

        }
    }
}
?>                      
                    </div><br>

                    <div class="form-group form-check-block">
                        
<?php 
    if (array_key_exists("resourceKinds", $resourceKindsArray) && is_array($resourceKindsArray['resourceKinds']) && count($resourceKindsArray['resourceKinds'])>0){
        foreach($resourceKindsArray['resourceKinds'] as $ind=>$resourceKinds){
            if(array_key_exists($resourceKinds, $resourceKindsArray)){
                echo '<label for="' . $resourceKinds."statkeys". '">' . '<h5>Parámetros estadísticos de ' . $resourceKinds. '</h5><br></label><br>';
                $statList= implode(", ", $resourceKindsArray[$resourceKinds]);
                echo '<textarea Id="'. $resourceKinds."statkeys" . '" name="'. $resourceKinds.'statkeys" rows="7" cols="50">' . $statList . '</textarea><br><br>';
            }
        }  
    }  
?>
                            
                        </div><br>

                        <div><button type="submit" class="btn btn-primary">Enviar</button></div>

                </form>

            </div>

        </div>
    </div>
<?php include '../../view/bodyScripts.php';?>
</body>
<?php

?>