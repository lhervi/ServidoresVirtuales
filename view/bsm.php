<?php

include_once "./encabezado.php";
include_once "./menu.php";

if(session_status() !== PHP_SESSION_ACTIVE) session_start();


/*
if (!isset($_SESSION['login']) || $_SESSION['login']===false){
    header("Location:" . "./../vROps/view/ingreso.php", true);
    //D:\xampp\htdocs\STISCR\view\index.php
    //D:\xampp\htdocs\STISCR\vROps\view\ingreso.php
    //http://localhost/vROps/view/ingreso.php
}
*/

?>
    <body body class="">
        <div class=class="m-0 vh-100 col justify-content-left align-items-top">
            <div class="forma2">
                <div class="d-inline-flex  align-items-center p-2 .bg-light">
                    <h2>BSM</h2> 
                </div><br/>

                <div class="container col-auto">
                    <div class="d-inline-flex justify-content-start align-items-center p-2 .bg-light">
                        <div class="border border-secondary p-3 mb-2 bg-light text-dark rounded">
                        <div id="loader" class="loader" style="display:none;"></div>
            
                            <form id="enviarForma" action="/STISCR/bsm_directo/consultaBSM.php" method="post" class="form-group">                                 

                                <div class="form-group form-check-inline"><h5>Mes y Año<br/></h5>               
                                    <!--label for="mes"></label-->    
                                    <input type="month" id="mes" name="mes" min="2020-01">
                                </div><br/><br/>
                               
                                <div><button type="submit" class="btn btn-primary" id="botEnviarForma" disabled>Enviar</button></div>          

                            </form><br/><br/>      
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    <script text/javascript>    
    
        const m = document.getElementById("mes");
        m.addEventListener("change", evalFecha);
        
        function evalFecha(){
            
            const mes = document.getElementById("mes");
            const env = document.getElementById("botEnviarForma");
            
            if (mes.value.length == 7) {                    
                if(env.disabled==true){                    
                    env.disabled=false;                    
                }else{
                    env.disabled=false;                   
                }                              
            }else{
                alert("revise los datos de la fecha");
                env.disabled=true;
            }
        }      
        </script>
    <?php include './bodyScripts.php';?>
    </body>
</html>