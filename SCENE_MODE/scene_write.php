<?php
//error_reporting(0);
  $scene=$_POST['scene_name'];
  $filename='../mqtt.txt';
  $fh=fopen($filename,"a");
  $con=mysqli_connect("localhost","root","huang110");
      
       if(!$con)
       {
           echo "error";
       }
       else
       {
           mysqli_query($con,"set name 'utf-8'");
           mysqli_select_db($con,"LOT");
           mysqli_query($con,"delete from current_scene");
           mysqli_query($con,"insert into current_scene(name) values('$scene') ");
           $result=mysqli_query($con,"select * from scene_mode_name where name='$scene'");
           $row=mysqli_fetch_array($result,MYSQLI_NUM);
           $equ=explode(',',$row[1]);
           $result=mysqli_query($con,"select * from Node_Information");
           while($row=mysqli_fetch_array($result,MYSQLI_NUM))
           {
                  $bool=0;
                  foreach($equ as $key=>$value)
                   {
                        if($value==$row[0])
                           $bool=1; 
                   }
                if($row[2]=='on'&&$bool==0)
                {
                   mysqli_query($con,"update  Node_Information set status='off' where name='$row[0]'");
                   $result1=mysqli_query($con,"select ID from Node_Information where name='$row[0]'");
                   $ID=mysqli_fetch_array($result1,MYSQLI_NUM);
                   $data="{$ID[0]}:off";

                  fwrite($fh,$data."\r\n");
                  
                }
                if($row[2]=='off'&&$bool==1)
                {
                   mysqli_query($con,"update  Node_Information set status='on' where name='$row[0]'");
                    $result1=mysqli_query($con,"select ID from Node_Information where name='$row[0]'");
                   $ID=mysqli_fetch_array($result1,MYSQLI_NUM);
                   $data="{$ID[0]}:on";
                  fwrite($fh,$data."\r\n");
                }
            }
       fclose($fh);
       mysqli_close($con);
       }
?>