<!DOCTYPE html>
<html>
<body onload="maxYmin()">

<?php
include_once './controller/utils/classFechas.php';
$fecMax = date("Y-m");
$monthMin = intval(date('m'))-7;
$añoMin = $monthMin>=0 ? date("Y") : strval(intval(date("Y"))-1);
$fecMin= $añoMin . $monthMin;
echo '<input type="month" id="myMonth" min="' . $fecMin . '" max="' . $fecMax .  '" >';

?>

<p>Click the button to display the value of the max attribute of the month field.</p>

<button onclick="myFunction()">Try it</button>

<p><strong>Note:</strong> input elements with type="month" do not show as any date field/calendar in Firefox.</p>


<p id="demo1"></p>
<br/>
<p id="demo2"></p>

<script>

document.getElementById("myMonth").addEventListener("change", (e) => {
  
  const month = document.getElementById("myMonth");      

    if(comparaFecha(month.value, month.max) || comparaFecha(month.min, month.value)){
      alert("el mes no puede ser mayor al mes actual ni menor a " +  month.min);   
      document.getElementById("myMonth").value="";
    }
    
    
  });

  function comparaFecha(fec1, fec2){
    fecInt1=fechaInt(fec1);
    fecInt2=fechaInt(fec2);
    if((fecInt1-fecInt2)>=0){
      return true
    }else{
      return false
    }
  }

  function fechaInt(fec){  
  //regresa un int que representa la fecha
  fecInt = Date.parse(fec = fec + "-01"); 
  return fecInt;
  }

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