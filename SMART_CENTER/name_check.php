<?php
  $name=$_POST['name'];
   $con=mysqli_connect("localhost","root","huang110");
   if(!$con)
   {

   }
   else{

      mysqli_query($con,"set name 'utf8'");
      mysqli_select_db($con,"LOT");
      mysqli_query($con,"select  * from smart where name='$name'");
      $i=mysqli_affected_rows($con);
      if($i==1)
      {
        echo 'nameexist';
      }
      mysqli_close($con);
   }
?>