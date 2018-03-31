<?php
error_reporting(0);
  $scene_name=$_POST['SCENE_NAME'];
  $name=$_POST['name'];
  $len=$_POST['Len'];
  $con=mysqli_connect("localhost","root","huang110");
  mysqli_query($con,"set name 'utf-8'");
  if(!$con)
  {
    echo "error";
  } 
  else
  {
    mysqli_select_db($con,"LOT");
    $equ=implode(',',$name);
    mysqli_query($con,"update scene_mode_name set equ='$equ' where name='$scene_name' ");
    mysqli_close($con);
  }
?>