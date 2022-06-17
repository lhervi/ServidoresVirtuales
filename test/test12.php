<?php
 
 include_once './constantes.php';
 include HOME . '/view/encabezado.php';
 include_once HOME . '/controller/utils/classLookAndFeel.php'; 

?>

<body>
<br/>

<?php

 echo LookAndFeel::estatusX(" Culminó con éxito la carga de los registros");
 echo LookAndFeel::estatusX("resultado1");      
 echo LookAndFeel::estatusX("prueba de resultados");
 echo LookAndFeel::estatusX("mas resultados que mostrar");
 echo '<div id="regresar" class="estatus" style="cursor:pointer; width:200px"><h4>>>>Regresar</h4></div>';
 echo "<script>" . PHP_EOL;      
 echo (LookAndFeel::enlace('regresar', MENUINICIO));
 echo "</script>";
 
?>

</body>
</html>
