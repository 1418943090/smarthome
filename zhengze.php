<?php

  $array=array("a"=>"1","b"=>"2","c"=>"3","d"=>"4","e"=>"5");

  // foreach ($array as $key => $value) {
  // 	echo $key."  ".$value.'<br>';
  // }
  
  // while($a=each($array))
  // {
    
  // 	echo $a[0]."  ".$a[1].'<br/>';
  // }

 while(!!list($a,$b)=each($array))
 {
    echo $a." ".$b.'<br>';

 }

?>