<?php 

    include_once "encabezado.php";
    include_once "menu.php";
 
 ?>

    <body class="m-0 vh-100 col justify-content-left align-items-top">
        <div class="container justify-content-left">

            <h1>Gestión de carga de la linea base</h1>
        
            <form id="enviarForma" action="../controller/procesar_linea_base.php" method="post">            
                <label for="linea_base">Elije el archivo de linea base a cargar:</label></br>
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
        <?php include "bodyScripts.php" ?>
    </body>
</html>