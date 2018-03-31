<?php
error_reporting(0);
$name=$_POST['name'];
$status=$_POST['status'];
$ID=$_POST['ID'];
$con=mysqli_connect("localhost","root","huang110");
      if(!$con)
      {
        echo  "error";
      }
      else
      {
        mysqli_query($con,"set name 'utf8'");
        mysqli_select_db($con,"LOT");
        $Result1=mysqli_query($con,"select * from Node_Information where ID='$ID'");
        $i=mysqli_affected_rows($con);
        if($i==1)//新设备的ID已经存在
        {
           echo "idexist";
        }
        else
        {
          $Result1=mysqli_query($con,"select * from Node_Information where name='$name'");
          $i=mysqli_affected_rows($con);
          if($i==1)//新设备的名字已存在
          {
            echo 'nameexist';
          }
          else
          {
            $result1=mysqli_query($con,"select * from equ where id='$ID'");
            $i=mysqli_affected_rows($con);
            if($i==0)//设备ID不存在
            {
              echo "noexist";
            }
            else//检查通过添加设备
            {
              $equ_information=mysqli_fetch_array($result1,MYSQLI_NUM);
              $equ_style=$equ_information[1];
              mysqli_query($con,"insert into Node_Information(name,style,status,ID) values('$name','$equ_style','$status','$ID')");
            }
          }
        }
        mysqli_close($con);
      }
?>