<!DOCTYPE html>
<html>
<body onload="maxYmin()">

<input type="month" id="myMonth" min="2014-04" max="" >

<p>Click the button to display the value of the max attribute of the month field.</p>

<button onclick="myFunction()">Try it</button>

<p><strong>Note:</strong> input elements with type="month" do not show as any date field/calendar in Firefox.</p>


<p id="demo1"></p>
<br/>
<p id="demo2"></p>

<script>

/*
const videoElement = document.querySelector('video');
videoElement.audioTracks.onchange = (event) => {
    console.log(`'${event.type}' event fired`);
};
*/

document.getElementById("myMonth").addEventListener("change", (e) => {
  
  const month = document.getElementById("myMonth");  
    //convertir month.value en un valor fecha comparable con month.max
    //Esto implica crear una función que dada año-mes retorne un número
    if(month.value>month.max || month.value<month.min){
      alert("el mes no puede ser mayor al mes actual ni menor a " +  month.min);   
    }
  });

function maxYmin(){
  const d= new Date();
  const fec = d.getFullYear() + "-" + (d.getMonth()+1);
  document.getElementById("myMonth").max = fec; 
  document.getElementById("myMonth").min = minFec(); 
}

function myFunction() {
  const max = document.getElementById("myMonth").max;
  const min = document.getElementById("myMonth").min;
  document.getElementById("demo1").innerHTML = "el mínimo es: " + min;
  document.getElementById("demo2").innerHTML = "el máximo es: " + max;
}

function minFec(){
    //fecha de hoy menos 7 meses, considerando la posiblidad de 6 meses atrás sin incluir el que está corriendo
    const d= new Date();
    const mesActual = d.getMonth(); //regresa el mes en formato numérico del 0 al 11    
    const año = (mesActual<7) ? d.getFullYear()-1 : d.getFullYear();    
    const mesMin = año + "-" + ((Math.abs(d.getMonth()-7))+1);
    return mesMin;
}

</script>

</body>
</html>