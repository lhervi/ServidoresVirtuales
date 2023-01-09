<?php

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

include_once __DIR__ . '/../../constantes.php';
include_once HOME . '/controller/utils/classUtils.php';
include "../view/../../view/encabezado.php";
include "../view/../../view/menu.php";

//ini_set('session.save_path', '/opt/lornis/STISCR/sessionData');

if(session_status() !== PHP_SESSION_ACTIVE) session_start();

if (!isset($_SESSION['login']) || $_SESSION['login']===false){
    //header("Location: ingreso.php", true);
    header("Location: ./ingreso.php", true);  //provisional >ELIMINAR
    //D:\xampp\htdocs\STISCR\view\index.php
    //D:\xampp\htdocs\STISCR\vROps\view\ingreso.php
    //http://localhost/vROps/view/ingreso.php
}

// ========== [PROVISIONAL] [LINEAS COMENTADAS PROVISIONALMENTE]
$excep = array(VMWARETOKENFILE);
$directorio = HOME . SALIDAS;
Utils::limpiarDirectorio($directorio, $excep);
$directorio = HOME . STATS;
Utils::limpiarDirectorio($directorio);
//

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
                                    
                                    //Delegar a la clase classVropsInterface [PENDIENTE]
                                    
                                    include_once './../classVropsConf.php';
                                    $server = VropsConf::getCampo('vropsServer')['vropsServer'];
                                    echo '<div id="server" class="form-group form-check-inline"><h4>Servidor: ' . $server . '<br/></h4>'; 
                                    echo '<label for="server" id="labelServer" style="cursor:pointer">Cambiar servidor-></label>';
                                
                                ?>
                                    
                                </div><br/><br/>

                                <div class="form-group form-check-inline"><h5>Fecha del lapso <br/></h5>               
                                    <label for="mesConsulta">Mes a obtener</label>    
                                    <input type="month" id="mesConsulta" name="mesConsulta"><div id="alertaFecha" style="visibility:hidden; color:red">La fecha a consultar no puede ser anterior a seis meses, ni igual o posterior a la fecha actual</div>                                     
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
                                    <label for="AVG">AVG</label>
                                    <input type="radio" id="AVG" name="rollUpType" value="AVG" autofocus checked></br>               
                                </div><br/><br/>

                                <div class="form-group form-check-inline"><h5>Tipo de Recurso</h5>
                                    <?php           
                                    include_once './classVropsInterface.php';
                                    echo VropsInterface::getHTMLResourceKinds();                                    
                                    ?>                                   
                                </div><br/><br/>
                                <div><button type="submit"  class="btn btn-primary" id="botonEnviarForma" disabled>Enviar</button></div>
                                <spam id="existeConsulta"></spam>          

                            </form><br/><br/>      
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    <script text/javascript>     
    
        <?php
        
            //Crea la lista de consultas en formato json para que pueda ser leído en JavaScript
            include_once __DIR__ . '/../../constantes.php'; 
            include_once HOME . '/vROps/classLista.php';
            echo "const topeMeses = " . TOPEMESES . ";" . PHP_EOL;
            //$lista = Lista::getLista();            
            //echo "listaDeConsultas = " . $lista . ";";
        
        ?> 
        
        const mesConsulta =  document.getElementById("mesConsulta");

        const botonEnviar = document.getElementById('botonEnviarForma');

        
        mesConsulta.addEventListener('change', rangoFechaOk);
        mesConsulta.addEventListener('keypress', habilitar);        
        mesConsulta.addEventListener('change', habilitar);
        
        botonEnviar.addEventListener('click', enviar);
        
        function rangoFechaOk(){        
        
            const fecha = mesConsulta.value;
                        
            const año = parseInt(fecha.substring(0, 4));
            const mes = parseInt(fecha.substring(5));

            fechaActual = new Date();

            mesActual =  fechaActual.getMonth() + 1; //se suma 1 porque enero comienza en 0 para la función
            añoActual = fechaActual.getFullYear();

            //--------------------------------------------------------------------
            //Compensa el número de meses de diferencia entre mes y mes actual
            
            if((añoActual - año < 0) || (añoActual - año > 1)) {
                return false;            
            }
            
            if(añoActual - año == 1) {
                mesActual += 12; 
            }
            
            //--------------------------------------------------------------------
            //Daddo que los meses están acomodados, se puede hacer el siguiente cálculo
            if (mesActual-mes>0 && mesActual-mes<=topeMeses){
                return true;
            }
            
            //regresa falso si no se cumple ninguna de las anteriores
            return false;            
        }   

        function habilitar(){      
                
                const mesOk = mesConsulta.disabled == "" ? true : false;
                const fechaOk = rangoFechaOk() 
                const todoBien = mesOk && fechaOk;
                document.getElementById("botonEnviarForma").disabled= !todoBien;
                if(fechaOk){
                    document.getElementById("alertaFecha").style.visibility="hidden";
                }else{
                    document.getElementById("alertaFecha").style.visibility="visible";
                }
        }   
        

        function enviar(){           
           
            document.getElementById("loader").style.visibility= "visible"; 
            document.getElementById("loader").style.display= "block";
            document.getElementById('enviarForma').submit();
        }            

        document.getElementById("server").addEventListener("click", function(){
           
            location.href = "/STISCR/vROps/view/ingreso.php";            
            
        })
        

    </script>
    <?php include_once '../../view/bodyScripts.php';?>
    </body>
</html>