<?php
  error_reporting(0);
  $name=$_POST['name'];
  $con=mysqli_connect("localhost","root","huang110");
  if(!$con)
  {
     echo "error";
  }
  else
  {
    mysqli_query($con,"set name 'utf-8'");
    mysqli_select_db($con,"SETTING");
    mysqli_query($con,"delete from set_name where name='$name'");
    mysqli_query($con,"delete from timing where name='$name'");
    echo 'true';
    mysqli_close($con);
  }
?>