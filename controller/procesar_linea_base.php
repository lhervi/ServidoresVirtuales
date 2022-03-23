<?php

ini_set('max_execution_time', '-1');
ini_set('display_errors', 1);

include_once '../constantes.php';
include "../view/encabezado.php";
include "../view/menu.php";
require_once 'classGetCSV.php';

require_once 'classVerExisteLB.php';
require_once 'classOperCSV.php';
require_once 'classObjCSV.php';
require_once 'classTrasObj.php';

if (session_status()!=PHP_SESSION_ACTIVE){
    session_start();
}
?>
<body>

<?php

/*
Revisa la fecha del archivo y la compara con la lista de archivos procesados
Si la fecha coincide, notifica que el archivo ya ha sido cargado antes y regresa la fecha de carga
Obtiene los atributos del archivo y los presenta por pantalla: 
    - número de registros totales
    - Número de registros que se pueden cargar a la BD
    - Nombre de los campos identidicados dentro del archivo
    - Número de registros con problemas de compatibilidad
*/

    //incorporación de la clase para leer el archivo y obtener sus atributos

    $configuracion = file_get_contents("configuracion.json");
    $json_configuracion = json_decode($configuracion, true);
    
    $user=$json_configuracion["user"];
    $password=$json_configuracion["password"];
    $networkDrive=$json_configuracion["networkDrive"];

    if (isset($_POST["linea_base"])){
        $archivo = $_POST["linea_base"];
        $SESSION["linea_base"] = $_POST["linea_base"];
    }elseif(isset($_SESSION["linea_base"])){
        $archivo = $_SESSION["linea_base"];
        $SESSION["linea_base"] = $_POST["linea_base"];
    }    
    //Crea el objeto getSCV con las credenciales suministradas en el archivo de configuración
    //global $t;
    $t = new getCSV($user, $password, $networkDrive, $archivo);    
    //$t = new getCSV($networkDrive, $archivo);    
    //Carga los datos del archivo seleccionado en el objeto getSVC
    $t->loadFile();

    $tras = new TrasObj(); //Instancia de clase para traspasar valores de un objeto a otro
    
    $_SESSION['csvObject'] = new ObjCSV();

    $tras->traspasar($t->getObjCSV(), $_SESSION['csvObject']); //Para que los datos persistan

    $objLB= new OperCSV($t->getObjCSV());

    

    $fechaMod = $objLB->getFileModifiedDate();
    $numReg = $objLB->getNumeroDeRegistros();
    $encabezado = $objLB->getEncabezado();
    
    ?>

    <Table class="table table-bordered">
        <thead><h3>Estatus del archivo</h3></thead> 
        <tbody>
            <tr>
                <td>
                    <?php                
                        $obj = new ExisteLineaBase();                         
                        $resp = $obj->existeLB($fechaMod);
                        if ($resp[0]){          
                            echo "Esta linea base fue cargada el día: " . $resp[1];
                        }
                    ?>            
                <td>
            </tr> 
        </tbody>
    </table>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre del archivo seleccionado</th>
                    <th>Número de registros</th>
                    <th>Útima modificación del archivo fuente</th>
                </tr>         
            </thead>    
        <tbody>
            <tr>            
                <td><?php echo ($archivo); ?></td>
                <td><?php echo ($numReg); ?></td>
                <td><?php echo ($fechaMod); ?></td>
            </tr>
        </tbody> 
    </table class="table table-bordered">
        <table>            
            <thead>
                <tr>
                    <th colspan="1">Nombre de los campos del archivo fuente</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="1"><?php echo ($encabezado); ?></td>
                </tr>
            </body>
        </table>  

    <br/><br/>

    <!-- Forma con botón para preguntar si se desean agregar los registros a la BD -->

    <form id="forma_registro" action="cargar_linea_base.php" method="post">  

    <label for="cargar">Presiona "Cargar" si desea cargar los registros de la linea base a la BD</label>    
    <input id="cargar" type="submit" value="Cargar" onclick="confirmarCargarRegistros()">
            
    </form> 

    <br/><br/>
    <div><h3>Registros</h3></div> 

    <?php 

        echo ($objLB->getAlltable());
        echo ("<br/><h4> Fin de los registros</h4>");
        
        include '../view/bodyScripts.php';

        // include(bodyScripts.php): failed to open stream: No such file or directory in 
        //D:\xampp\htdocs\STI\controller\procesar_linea_base.php on line 100

    ?>
    <script type="text/javascript">

    function confirmarCargarRegistros(e) {       
        e.preventDefault();
        if (window.confirm("¿Desea cargar este archivo en la BD?")){
            document.getElementById("cargar_registros").value="TRUE";
            document.getElementById("forma_registro").submit();
        }
    }

    </script>

    </body>
</html>
