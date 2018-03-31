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
        mysqli_select_db($con,"LOT");
        $result=mysqli_query($con," select * from smart ");
        while($row = mysqli_fetch_array($result,MYSQLI_NUM))
         {
          $b=array("$row[0]"=>$row[6]);
          $a=$a+$b; 
         }
         echo json_encode($a);
        mysqli_close($con);
         }
        

?>