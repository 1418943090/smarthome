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
       $result=mysqli_query($con,"select * from timing where name='$name'");
       while($row = mysqli_fetch_array($result,MYSQLI_NUM))
       {
          $equ_name=explode(',',$row[0]);
          $shour=$row[2];
          $sminute=$row[3];
          $ehour=$row[4];
          $eminute=$row[5];
          $type=$row[6];
          $days=explode(',',$row[7]);
          $set_name=$row[8];
      }
        $a=array(
       "equ_name"=>$equ_name,
          "shour"=>$shour,
        "sminute"=>$sminute,
          "ehour"=>$ehour,
        "eminute"=>$eminute,
           "type"=>$type,
           "days"=>$days,
       "set_name"=>$set_name
   );
      echo json_encode($a);  
      mysqli_close($con);
  }
?>