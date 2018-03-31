<?php
  $name=$_POST['name'];
  $sensor=$_POST['sensor'];
  $compare=$_POST['compare'];
  $value=$_POST['date'];
  $operate=$_POST['operate'];
  $equ_name=$_POST['equ_name'];
 
   $con=mysqli_connect("localhost","root","huang110");
   if(!$con)
   {

   }
   else{

      mysqli_query($con,"set name 'utf8'");
      mysqli_select_db($con,"LOT");
      $string_equ=implode(',',$equ_name);
      mysqli_query($con,"insert smart(name,sensor,compare,value,operate,equ) values('$name','$sensor','$compare','$value','$operate','$string_equ')");
    mysqli_close($con);

   }
?>