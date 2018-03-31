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
       mysqli_query($con,"set name 'utf8'");
       mysqli_select_db($con,"DATA");
       $result=mysqli_query($con,"select id from Now_Data where name='$name' ");
       $row=mysqli_fetch_array($result,MYSQLI_NUM);
       echo $row[0];
    }
   mysqli_close($con);
?>