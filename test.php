<?php

$name='aaa';
     $con=mysqli_connect("localhost","root","huang110");
     if(!$con)
     {
     	echo "error";
     }
     else
     {
     mysqli_query($con,"set name utf-8");
     mysqli_select_db($con,"LOT");
    // mysqli_query($con,"update Node_Information set status='$status' where name='$name'");
     $result=mysqli_query($con,"select ID from Node_Information where name='$name'");
     $a=mysqli_fetch_array($result,MYSQLI_NUM);
     echo $a[0];
     mysqli_close($con);
     
    }
?>
