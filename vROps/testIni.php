<?php

$files = glob('/STISCR/vROps/salidas'); // get all file names
foreach($files as $file){ // iterate files
  if(is_file($file))
    echo($file) . "<br/>"; // 
    //unlink();
}

/*
echo "<br/><br/>";
phpinfo();
echo "<br/><br/>";
xdebug_info();
echo "<br/><br/>";
*/
//var_dump(php_ini_loaded_file(), php_ini_scanned_files());

?>