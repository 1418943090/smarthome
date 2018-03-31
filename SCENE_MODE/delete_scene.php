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
   mysqli_select_db($con,"LOT");
   mysqli_query($con,"delete from scene_mode_name where name='$name'");
   $result=mysqli_query($con,"select * from current_scene");
   while($row=mysqli_fetch_array($result,MYSQLI_NUM))
   {
     if($row[0]==$name)
     {
        mysqli_query($con,"update current_scene set name=null where name='$name'");
     }

   }
    mysqli_close($con);
 }
?>