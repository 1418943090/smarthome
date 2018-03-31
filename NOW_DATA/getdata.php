<?php
error_reporting(0);
   $a=array();
   $con=mysqli_connect("localhost","root","huang110");
   if(!$con)
   {
      echo "error";
   }
   else
   {
     mysqli_query($con,"set name 'utf8'");
     mysqli_select_db($con,"DATA");
     $result=mysqli_query($con,"select * from Now_Data");
     while($row=mysqli_fetch_array($result,MYSQLI_NUM))
     {
         switch($row[3])
         {
           case 'tem': $val=$row[2].'℃';break;
           case 'hum': $val=$row[2].'%';break;
           case 'ill': $val=$row[2].'lx';break;
           default: $val=$row[2];break;
         }
         $b=array("$row[0]"=>$val);
         $a=$a+$b; 
     }
         echo json_encode($a);
         mysqli_close($con);
   }

?>