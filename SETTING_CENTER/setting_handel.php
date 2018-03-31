<?php

   $filename='../mqtt.txt';
   $fh=fopen($filename,"a");
   //设置为亚洲时区
   if(date_default_timezone_get() != "1Asia/Shanghai") 
    {
   	  date_default_timezone_set("Asia/Shanghai");
    }
 function gettime()
 {
    $showtime=date("Y-m-d H:i:s",time());
    $time=strtotime($showtime);
    $arr['y']=date('Y',$time);//年份
    $arr['m']=date('m',$time);//月份
    $arr['d']=date('d',$time);//日期
    $arr['h']=date('H',$time);//小时
    $arr['i']=date('i',$time);//分钟
    $arr['s']=date('s',$time);//秒
    $arr['w']=date(date('w'));//星期几 数字表示
    return $arr;
 }
//*******************************************************
 function status_check($name)
 {
     $con=$GLOBALS['con'];
     mysqli_select_db($con,"LOT");
     $result=mysqli_query($con,"select * from Node_Information where name='$name'");
     $row=mysqli_fetch_array($result,MYSQLI_NUM);
     return $row[2];
 }
 //******************************************************
 function f_everyday($row,$arr)
 {
 	if(($shour==$arr['h'])&&($sminute==$arr['i']))
 	{
       $equ_name=explode(',',$row[0]);
       foreach($equ_name as $key=>$value)
       {
       	  if(status_check($value)=='off')
          {
             $data="{$value}:on";
             fwrite( $GLOBALS['fh'], $data."\r\n");
          }
       }
       echo "ad";
 	}
 }  
 //*****************************************************
 function f_today($row,$arr)
 {
    echo $row[6];
 }
 //***************************************************
 function f_define($row,$arr)
 {
   echo $row[6];

 }
 //***************************************************************
   $con=mysqli_connect("localhost","root","huang110");

   if(!$con)
   {

   }
   else
   {
     mysqli_query($con,"set name 'utf-8'");
     mysqli_select_db($con,"SETTING");
     $result=mysqli_query($con,"select * from timing");
  while(1)
  {
      while($row=mysqli_fetch_array($result,MYSQLI_NUM))
      {

      	$arr=gettime();
     	 $result1=mysqli_query($con,"select * from set_name where name='$row[8]'");
     	 $status=mysqli_fetch_array($result1,MYSQLI_NUM);
     	 if($status='on')
     	 {
           switch($row[6])
           {
            	case 0: f_everyday($row,$arr);break;
          	  case 1: f_today($row,$arr);break;
          	  case 2: f_define($row,$arr);break;
           }
        } 
      }
      sleep(60);
     }
   }
?>