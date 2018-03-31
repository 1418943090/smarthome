<?php
  error_reporting(0);
  $num=$_POST['num'];
  $name=$_POST['name'];
  $scene_name=$_POST['SCENE_NAME'];
  $con=mysqli_connect("localhost","root","huang110");
  mysqli_query($con,"set name 'utf-8'");
  if(!$con)
  {
  	echo "error";
  }
  else
  {
     mysqli_select_db($con,"LOT");
     mysqli_query($con,"select * from scene_mode_name where name='$scene_name' ");
     $i= mysqli_affected_rows($con);
     if($i==1)
     {
       echo "exist";
     }
     else
     {
      $equ=implode(',',$name);
      mysqli_query($con,"insert into scene_mode_name(name,equ) values('$scene_name','$equ');");
      echo "success";
    }
     mysqli_close($con);
  }
?>