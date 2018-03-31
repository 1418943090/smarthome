<?php
error_reporting(0);
$name=$_POST['name'];
$status=$_POST['status'];
$filename = "../mqtt.txt";

     $con=mysqli_connect("localhost","root","huang110");
     if(!$con)
     {
     	echo "error";
     }
     else
     {
     mysqli_query($con,"set name utf-8");
     mysqli_select_db($con,"LOT");
     mysqli_query($con,"update Node_Information set status='$status' where name='$name'");
     $result=mysqli_query($con,"select ID from Node_Information where name='$name'");
     $ID=mysqli_fetch_array($result,MYSQLI_NUM);
     mysqli_close($con);
     $data="{$ID[0]}:{$status}";
     $fh = fopen($filename, "a");
     fwrite($fh, $data."\r\n");
     fclose($fh);
    }
?>
