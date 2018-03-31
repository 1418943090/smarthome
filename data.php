<?php

$boo="hour";
$data=array();
   $con=mysqli_connect("localhost","root","huang110");
   if(!$con)
   {

   }
   else
   {
   	echo "agag";
     mysqli_query($con,"set name 'utf8'");
     mysqli_select_db($con,"DATA");
     
     for($i=1;$i<=24;$i++)
     {
     $result=mysqli_query($con,"select AVG(data) from tem where hour='$i'");
      $row=mysqli_fetch_array($result,MYSQLI_NUM);
      array_push($data,$row[0]);
    }
    foreach ($data as $key => $value) {
    	echo $key." ".$value."</br>";
    }
    
     // for($i=1;$i<=24;$i++)
     // {
     //     $value=

     // }
     mysqli_close($con);
   }
  
?>