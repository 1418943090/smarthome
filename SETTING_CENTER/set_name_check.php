<?php
$name=$_POST['name'];
   $con=mysqli_connect("localhost","root","huang110");
   if(!$con)
   {

   }
   else
   {
     mysqli_query($con,"set name 'utf-8'");
     mysqli_select_db($con,"SETTING");
     mysqli_query($con,"select * from set_name where name='$name'");
     $i=mysqli_affected_rows($con);
     if($i==0)
     {
     	echo "true";
     }
     else
     {
     	echo "false";
     }
     mysqli_close($con);
   }

?>