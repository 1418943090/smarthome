<?php
$type=$_POST['type'];
$filename = '../mqtt.txt';
$A=array();
$fh = fopen($filename, "a");
function fun($con,$status,$filename)
{
   $result=mysqli_query($con," select * from Node_Information ");
   while($row = mysqli_fetch_array($result,MYSQLI_NUM))
  {
    if($row[2]!=$status)
    {
      mysqli_query($con,"update  Node_Information set status='$status' where name='$row[0]'");
       $data="{$row[3]}:{$status}";
    fwrite( $GLOBALS['fh'], $data."\r\n");
    }
    
  }
   
    fclose($GLOBALS['fh']);//使用全局变量 $G['']
    mysqli_close($con);
}
$con=mysqli_connect("localhost","root","huang110");
     mysqli_query($con,"set name 'utf8'");
      if(!$con)
      {
        echo "error";
      }
      else
      {
         mysqli_select_db($con,"LOT");
        if($type==0) //2 open 先关掉所有的
          {
            $status="off";
            //echo 'off_all';
          }
        if($type==1) //3 close 打开所有的
          {
            $status="on";
            //echo 'on_all';
          }
        if($type==0||$type==1)
       fun($con,$status,$filename);
       echo $status;
//---------------------------------------------------------------------------------------------------------------------------
      //  if($type==2||$type==3) 
      //  {
      //        echo $type;
      //        if($type==2)
      //          $table="User_Define_ON";
      //        if($type==3)
      //          $table="User_Define_OFF";

      //    $result=mysqli_query($con," select * from Node_Information ");
      //    while($row = mysqli_fetch_array($result,MYSQLI_NUM))
      //    {
      //          $Result1=mysqli_query($con,"select * from $table where name='$row[0]'");
      //          $i=mysqli_affected_rows($con);
      //          if($i==1)
      //            $b=array("$row[0]"=>"yes");//************
      //          echo $i;
      //          if($row[2]=="on"&&$i==0)
      //            {
      //             mysqli_query($con,"update  Node_Information set status='off' where name='$row[0]'");
      //             $data="{$row[0]}:off";
      //             fwrite($fh, $data."\r\n");
      //            }
      //          if($row[2]=="off"&&$i==1)
      //            {
      //             mysqli_query($con,"update  Node_Information set status='on' where name='$row[0]'");
      //             $data="{$row[0]}:on";
      //             fwrite($fh, $data."\r\n");
      //            } 

      //    }
      //    fclose($fh);
      //    mysqli_close($con);
      // }
//-------------------------------------------------------------------------------------------------------------------- 
      }
?>
