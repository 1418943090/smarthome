<?php
error_reporting(0);
$type=$_POST['type'];
$s_hour=$_POST['s_hour'];
$s_minute=$_POST['s_minute'];
$e_hour=$_POST['e_hour'];
$e_minute=$_POST['e_minute'];
$week=$_POST['week'];
$equ_name=$_POST['equ_name'];
$name=$_POST['name'];
$date=$_POST['date'];
$string=implode(',',$equ_name);
function equ_check($equname1,$equname)
{
   $equname2=explode(',',$equname);
   $arr=array_intersect($equname1,$equname2);
   $A=array_values($arr);
   return $A;
}
function clash_check($name,$type,$s_hour,$s_minute,$e_hour,$e_minute,$week,$equ_name,$con)
{
    $A=array();
    mysqli_select_db($con,"SETTING");
 
    $result2=mysqli_query($con,"select * from set_name");
    while($sta=mysqli_fetch_array($result2,MYSQLI_NUM))
    {
    if($sta[1]=='on'&&$sta[0]!=$name)
    {
    $result=mysqli_query($con,"select * from timing where name='$sta[0]'");
    $row=mysqli_fetch_array($result,MYSQLI_NUM);
     switch($type)
     {
      case'everyday': $T=0;break;
      case'today':    $T=1;break;
      case'define':   $T=2;break;
     }
 //***********都是每天有效********************    
      if(($T==$row[6]&&$T==0)||($T==0&&$row[6]==1)||($T==1&&$row[6]==0))
      {
         
//******************************************************       
          if(($s_hour==$row[4]&&$s_minute==$row[5])||($e_hour==$row[2]&&$e_minute==$row[3]))
             {
              $data=array();
              $equ=equ_check($equ_name,$row[0]);
             if(empty($equ)==0)
             {
              if($s_hour==$row[4]&&$s_minute==$row[5])
              {
                $hour=$s_hour;
                $minute=$s_minute;
              }
              else
              {
                $hour=$e_hour;
                $minute=$e_minute;
              }
              $data=array(
                "test"=>$row[8],
                "equ"=>$equ,
                "hour"=>$hour,
                "minute"=>$minute,
                "type"=>$row[6]
              );
              array_push($A,$data);
            }
          }
      }
//****************新任务为自定义模式********************************
    if(($T==2&&$row[6]==0) ||($T==2&&$row[6]==1))
    {
        if(($s_hour==$row[4]&&$s_minute==$row[5])||($e_hour==$row[2]&&$e_minute==$row[3]))
      {
            switch(date('D'))
      {
        case 'Mon':$xinqi='monday';break;
        case 'Tue':$xinqi='tuesday';break;
        case 'Wed':$xinqi='wednesday';break;
        case 'Thu':$xinqi='thursday';break;
        case 'Fri':$xinqi='friday';break;
        case 'Sat':$xinqi='saturday';break;
        case 'Sun':$xinqi='sunday';break;
      }
      foreach($week as $key=>$value)
      {
         if($value==$xinqi)
         {
             $data=array();
              $equ=equ_check($equ_name,$row[0]);

             if(empty($equ)==0)
             {
              if($s_hour==$row[4]&&$s_minute==$row[5])
              {
                $hour=$s_hour;
                $minute=$s_minute;
              }
              else
              {
                $hour=$e_hour;
                $minute=$e_minute;
              }
              $data=array(
                "test"=>$row[8],
                "equ"=>$equ,
                "hour"=>$hour,
                "minute"=>$minute,
                "type"=>$row[6]
              );
              array_push($A,$data);
            }
              
         }
      }
      }
   }
//*************************************************************
   if(($T==0&&$row[6]==2) ||($T==1&&$row[6]=2))
    {
        if(($s_hour==$row[4]&&$s_minute==$row[5])||($e_hour==$row[2]&&$e_minute==$row[3]))
      {
            switch(date('D'))
      {
        case 'Mon':$xinqi='monday';break;
        case 'Tue':$xinqi='tuesday';break;
        case 'Wed':$xinqi='wednesday';break;
        case 'Thu':$xinqi='thursday';break;
        case 'Fri':$xinqi='friday';break;
        case 'Sat':$xinqi='saturday';break;
        case 'Sun':$xinqi='sunday';break;
      }
      $weeks=explode(',',$row[7]);
      foreach($weeks as $key=>$value)
      {
         if($value==$xinqi)
         {
             $data=array();
             $equ=equ_check($equ_name,$row[0]);
             if(empty($equ)==0)
             {
              if($s_hour==$row[4]&&$s_minute==$row[5])
              {
                $hour=$s_hour;
                $minute=$s_minute;
              }
              else
              {
                $hour=$e_hour;
                $minute=$e_minute;
              }
              $data=array(
                "test"=>$row[8],
                "equ"=>$equ,
                "hour"=>$hour,
                "minute"=>$minute,
                "type"=>$row[6]
              );
               array_push($A,$data);
            }
         }
      }
      }
   }
//*********************************************************************
   if($T==2&&$row[6]==2)
   {
        if(($s_hour==$row[4]&&$s_minute==$row[5])||($e_hour==$row[2]&&$e_minute==$row[3]))
        {
        $M=explode(',',$row[7]);
        $N=$week;
        $arr=array_intersect($M,$N);
        $H=array_values($arr);
        if(empty($H)==0)
        {
             $data=array();
             $equ=equ_check($equ_name,$row[0]);
             $string=implode(',',$H);
             if(empty($equ)==0)
             {
              if($s_hour==$row[4]&&$s_minute==$row[5])
              {
                $hour=$string."-".$s_hour;
                $minute=$s_minute;
              }
              else
              {
                $hour=$string."-".$e_hour;
                $minute=$e_minute;
              }
              $data=array(
                "test"=>$row[8],
                "equ"=>$equ,
                "hour"=>$hour,
                "minute"=>$minute,
                "type"=>$row[6]
              );
               array_push($A,$data);
            }
        }
      }
   }
  }
  }
    if(empty($A)==1)
    {
      return 'true';
    }
    else
    {
    echo json_encode($A);
    mysqli_close($con);
    return 'false';
    }
}
//***************************************************
function mysql($name,$type,$date,$s_hour,$s_minute,$e_hour,$e_minute,$equ_name,$con)
{
          mysqli_select_db($con,"SETTING");
          mysqli_query($con,"select * from set_name where name='$name'");
          $q=mysqli_affected_rows($con);
          $string=implode(',',$equ_name);
          if($type=='define')
          {
            $week=$_POST['week'];
            $days=implode(',',$week);   
            if(!$q)  
            mysqli_query($con,"insert into timing(equ_name,shour,sminute,ehour,eminute,type,days,name) values('$string','$s_hour','$s_minute','$e_hour','$e_minute','2','$days','$name')");
            else{
              mysqli_query($con,"update  timing  set equ_name='$string',shour='$s_hour',sminute='$s_minute',ehour='$e_hour',eminute='$e_minute',type='2',days='$days' where name='$name'");
            }
          }
          else
          {
            $i=0;
            if($type=='today')
            {
               $i=1;
            }
             if(!$q)
             mysqli_query($con,"insert into timing(equ_name,date,shour,sminute,ehour,eminute,type,days,name) values('$string','$date','$s_hour','$s_minute','$e_hour','$e_minute','$i','','$name')");
             else
            {
             mysqli_query($con,"update timing  set equ_name='$string',date='$date',shour='$s_hour',sminute='$s_minute',ehour='$e_hour',eminute='$e_minute',type='$i' where name='$name' ");
            }
          }
           if(!$q)
         mysqli_query($con,"insert into set_name(name) values('$name')");
         mysqli_close($con);
         echo 'true';
}
function status_check($con,$name)
{
   mysqli_select_db($con,"SETTING");
   $result=mysqli_query($con,"select * from set_name where name='$name'");
   $row=mysqli_fetch_array($result,MYSQLI_NUM);
   if($row[1]=='off')
     return 'true';
   else return 'false';
}

$con=mysqli_connect("localhost","root","huang110");
      mysqli_query($con,"set name 'utf-8'");
      if(!$con)
      {
        echo "error";
      }
      
      // if(status_check($con,$name)=='true')
      // {
      //     mysql($name,$type,$date,$s_hour,$s_minute,$e_hour,$e_minute,$equ_name,$con);    
      // }
      // else
      // {
      //    if(clash_check($name,$type,$s_hour,$s_minute,$e_hour,$e_minute,$week,$equ_name,$con)=='true')
      //        mysql($name,$type,$date,$s_hour,$s_minute,$e_hour,$e_minute,$equ_name,$con); 
      // }
?>
