<?php


header("Cache-Control: no-cache, must-revalidate"); 
header("Expires: Sat, 1 Jan 1900 05:00:00 GMT"); // 
include_once ("./../constantes.php");
include_once ("./../view/encabezado.php");

?>

<body>
<div class="container">

    <br/>
    <div><h1>Bitacora de procesamiento</h1></div>
    <div id="<?php echo BITACORADIV; ?>">


    </div>
    <div id="dataHistorica">

    </div>

</div>



<script>

    const bitacoradiv = document.getElementById("<?php echo BITACORADIV; ?>");  
    const loader = window.parent.document.getElementById('loader');
    const iframeBitacora = window.parent.document.getElementById('iframeBitacora');
    const dataHistorica = document.getElementById('dataHistorica');
    

    function readAndWrite(){
        fetch(<?php echo "'" . URLHOME . BITACORATEMPORALFILEPATH . "'";?>, {cache:"no-store"})        
            .then(response=>response.json())
            .then(data=>escribir(data));                                   
    } 

    function actualiza(){
        setTimeout(readAndWrite, <?php echo REFRESHTIMESECONDS ?>);
    }
    
    function añadirEventos(evento, index){

        if (!evento.fin){
    
            const itemEvento = document.createElement('div');
            itemEvento.className = 'claseEvento';

            const fechaEvento = document.createElement('div')
            fechaEvento.className = 'claseFecha';
            fechaEvento.id = index + 'fecha';
            fechaEvento.innerHTML = evento.fecha + " " + evento.indiceDelEvento;

            const contenidoEvento = document.createElement('div')
            contenidoEvento.className = 'claseContenido';
            contenidoEvento.id = index + 'contenido'
            contenidoEvento.innerHTML = evento.contenido;

            itemEvento.appendChild(fechaEvento);
            itemEvento.appendChild(contenidoEvento);

            bitacoradiv.appendChild (itemEvento);
        }
    }

    function escribir(data){               
        dataOk = Array.isArray(data.eventos);        
        if (dataOk){
            bitacoradiv.innerHTML ="";
            data.eventos.map(añadirEventos);
        }
        if (data.fin){  
            loader.style.display = "block";
            loader.style.visibility="none";   
            iframeBitacora.style.display = "block";
            iframeBitacora.style.visibility="visible";
        }else{
            setTimeout(readAndWrite, <?php echo REFRESHTIMESECONDS ?>);            
        }
    }    


    function mostrarHitorico(dataHitorica, index){
        
        const eventoHistorico = document.createElement('div');
        eventoHistorico.className = 'claseEvento';
        
        const itemHistorico = document.createElement('div');
        itemHistorico.className = 'claseFecha';
        itemHistorico.innerHTML = dataHitorica.timeStamp;

        const fechaEventoHistorico = document.createElement('div')
        fechaEventoHistorico.className = 'claseContenido';
        fechaEventoHistorico.innerHTML = dataHitorica.fechaHora;        

        eventoHistorico.appendChild(itemHistorico);
        eventoHistorico.appendChild(fechaEventoHistorico);

        dataHistorica.appendChild(eventoHistorico);
        
    }

    function writeDataHistorica(dataHistoricaFetch){        
        
        dataHistoricaFetch.map(mostrarHitorico);        
    }

    function leerDataHistorica(){
        fetch(<?php echo "'" . URLHOME . BITACORAHISTORICAFILEPATH . "'";?>, {cache:"no-store"})        
            .then(response=>response.json())
            .then(dataHistoricaFetch=>writeDataHistorica(dataHistoricaFetch)); 
    }

    readAndWrite();
    //leerDataHistorica();
    writeDataHistorica();
 
    </script>

<?php
include_once ("./../view/bodyScripts.php");
?>

</body>
</html>