<?php
   $a=array();
   $A=array();
    $con=mysqli_connect("localhost","root","huang110");
    if(!$con)
    {
    	echo "错误!无法连接到数据库";
    }
    else
    {
       mysqli_query($con,"set name utf-8");
       mysqli_select_db($con,"LOT");
       $i=0;
       $result=mysqli_query($con,"select * from User_Define_ON");
       while($row=mysqli_fetch_array($result,MYSQLI_NUM))
       {
          $b=array("$row[0]"=>"yes");
          $a=$a+$b;
          $i++;
       }  
        $result=mysqli_query($con,"select * from User_Define_OFF");
       while($row=mysqli_fetch_array($result,MYSQLI_NUM))
       { 
          $B=array("$row[0]"=>"no");
          $A=$A+$B;
          $i++;
       }  
     mysqli_close($con);
    }  
   $a=array("on"=>$a);
   $A=array("off"=>$A);
   $A=$A+$a;
  
   
   echo json_encode($A);
  

?>