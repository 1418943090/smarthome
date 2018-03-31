<?php
error_reporting(0);
$name=$_POST['name'];
$con=mysqli_connect("localhost","root","huang110");
if(!$con)
  {
  	echo 'error';
  }
 else{
      mysqli_query($con,"set name 'utf8'");
      mysqli_select_db($con,"LOT");
      mysqli_query($con,"delete from smart where name='$name'");
      mysqli_close($con);
      echo 'ok';
   }
?>