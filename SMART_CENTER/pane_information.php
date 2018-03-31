<?php

  $name=$_POST['name'];
  $con=mysqli_connect("localhost","root","huang110");
  if(!$con)
  {
    echo "error";
  }
  else
  {
      mysqli_query($con,"set name 'utf-8'");
       mysqli_select_db($con,"LOT");
       $result=mysqli_query($con,"select * from smart where name='$name'");
      $row = mysqli_fetch_array($result,MYSQLI_NUM);
      
      $equ_string=explode(',',$row[5]);
        $a=array(
        
         "sensor"=>$row[1],
         "compare"=>$row[2],
         "value"=>$row[3],
         "operate"=>$row[4],
         "equ"=>$equ_string
       );
      echo json_encode($a);  
      mysqli_close($con);
  }
?>