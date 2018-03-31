<?php
error_reporting(0);
   $id=$_POST['id'];
   $con=mysqli_connect("localhost","root","huang110");
   if(!$con)
   {
   	echo "error";
   }
   else
   {
   	mysqli_query($con,"set name 'utf8'");
   	mysqli_select_db($con,"DATA");
   	mysqli_query($con,"delete from $id");
   	echo "success";
   }
  mysqli_close($con);

?>