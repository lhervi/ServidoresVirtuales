<?php 

    include_once __DIR__ . '/../../constantes.php';
    include_once HOME . "/view/encabezado.php";
    include_once HOME . "/view/menu.php";
 
 ?>

    <body>
        <div class="dashboard">

            <div class="dashboardTitle"><h1>Dashboard</h1></div>
        
            <form id="enviarForma" action="" method="post">            
                <label for="Mes">Mes</label></br>
                <input type="file" accept=".csv" id="linea_base" name="linea_base"></br></br>
                <input id="revisar" type="button" value="Revisar archivo" onclick="enviar()" style="display:none;">
            </form>

            
                       
            <div id="loader" class="loader"></div>                 
            
            

            <script text/javascript>

                var lb=document.getElementById("linea_base"); 

                lb.onchange = validar;

                function validar(){
                    if (lb.files.length>0){
                        if (lb.files.item(0).name.length>0){
                        
                            document.getElementById("revisar").style.display="block";
                        }
                    }else{
                            document.getElementById("revisar").style.display="none";
                                            
                    }
                }
                
                function enviar(){                                
                    document.getElementById("loader").style.visibility= "visible"; 
                    document.getElementById("loader").style.display= "block";
                    document.getElementById('enviarForma').submit();
                }            

            </script>
        </div>
        <?php include_once '../../view/bodyScripts.php';?>
    </body>
</html>