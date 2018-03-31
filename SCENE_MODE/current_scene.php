<?php
error_reporting(0);
  $name=$_POST['name'];
  $con=mysqli_connect("localhost","root","huang110");
  mysqli_query($con,"set name 'utf-8'");
  if(!$con)
  {
    echo "error";
  }
  else
 {
  mysqli_select_db($con,"LOT");
  mysqli_query($con,"delete from current_scene");
  mysqli_query($con,"insert into current_scene(name) values('$name') ");
  mysqli_close($con);
 }   
?>