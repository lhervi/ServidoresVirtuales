<?php

include "../view/../../view/encabezado.php";
include "../view/../../view/menu.php";


if(session_status() !== PHP_SESSION_ACTIVE) session_start();

if (!isset($_SESSION['login']) || $_SESSION['login']===false){
    //header("Location: ingreso.php", true);
    header("Location: ./ingreso.php", true);  //provisional >ELIMINAR
    //D:\xampp\htdocs\STISCR\view\index.php
    //D:\xampp\htdocs\STISCR\vROps\view\ingreso.php
    //http://localhost/vROps/view/ingreso.php
}

?>
 

    <body body class="m-0 vh-100 col justify-content-left align-items-top">
        <div class="container justify-content-left">
            <div class="forma2">
                <div class="d-inline-flex  align-items-center p-2 .bg-light">
                    <h2>Gestión de métricas vROps</h2>
                </div><br/>

                <!- -->

                <div class="container col-auto">
                    <div class="d-inline-flex justify-content-start align-items-center p-2 .bg-light">
                        <div class="border border-secondary p-3 mb-2 bg-light text-dark rounded">
                        <div id="loader" class="loader" style="display:none;"></div>
            
                            <form id="enviarForma" action="../genStats.php" method="post" class="form-group">

                                

                                <?php 
                                    include_once './../classVropsConf.php';
                                    $server = VropsConf::getCampo('vropsServer')['vropsServer'];
                                    echo '<div id="server" class="form-group form-check-inline"><h5>Servidor: ' . $server . '<br/></h5>'; 
                                    echo '<label for="server" id="labelServer" style="cursor:pointer">Cambiar servidor-></label>';
                                
                                ?>
                                    
                                </div><br/><br/>

                                <div class="form-group form-check-inline"><h5>Fecha del lapso <br/></h5>               
                                    <label for="mesConsulta">Mes a obtener</label>    
                                    <input type="month" id="mesConsulta" name="mesConsulta">                                                 
                                </div><br/><br/>

                                <div class="form-group form-check-inline"><h5>Intervalo de medición</h5>
                                    <label for="HOURS">Horas</label>
                                    <input type="radio" id="HOURS" name="intervalType" value="HOURS" checked>
                                    <label for="MINUTES">Minutos</label>
                                    <input type="radio" id="MINUTES" name="intervalType" value="MINUTES">
                                </div><br/><br/>

                                <div class="form-group form-check-inline"><h5>Cuantificador del intervalo</h5>
                                    <label for="intervalQuantifier">Tipo de Intervalo</label>
                                    <input type="number" id="intervalQuantifier" name="intervalQuantifier" min="1" max="12" value="1">
                                </div><br/><br/>

                                <div class="form-group form-check-inline"><h5>Tipo de intervalo</h5>
                                    <label for="AVG">Hora</label>
                                    <input type="radio" id="AVG" name="rollUpType" value="AVG" autofocus checked></br>               
                                </div><br/><br/>

                                <div class="form-group form-check-inline"><h5>Tipo de Recurso</h5>
                                    <input type="checkbox" id="virtualmachine" name="virtualmachine" value="resourceKinds" checked>
                                    <label for="virtualmachine">Virtual Machine</label>
                                    <input type="checkbox" id="hostsystem" name="hostsystem" value="resourceKinds" checked>
                                    <label for="hostsystem">Hostsystem</label>     
                                </div><br/><br/>
                                <div><button type="submit"  class="btn btn-primary" id="enviarForma" onclick="enviar()">Enviar</button></div>          

                            </form><br/><br/>      
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <script text/javascript>

        function enviar(){                                
            document.getElementById("loader").style.visibility= "visible"; 
            document.getElementById("loader").style.display= "block";
            document.getElementById('enviarForma').submit();
        }            

        document.getElementById("server").addEventListener("click", function(){
            //alert ("Estoy aquí");
            location.href = "/STISCR/vROps/view/ingreso.php";            
        })
        

    </script>
    <?php include_once '../../view/bodyScripts.php';?>
    </body>
</html>

<?php

?>