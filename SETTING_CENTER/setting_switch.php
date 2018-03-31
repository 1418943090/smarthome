<?php
  error_reporting(0);
  $sce_name=$_POST['name'];
  $status=$_POST['status'];
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
      case 0: $T=0;break;
      case 1:    $T=1;break;
      case 2:   $T=2;break;
     }
 //***********都是每天有效*或一个为每天 一个为当天*******************    
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
   if(($T==0&&$row[6]==2) ||($T==1&&$row[6]==2))
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

function equ_isnull_check($con,$name)
{
  
  mysqli_select_db($con,"SETTING");
  $result=mysqli_query($con,"select equ_name from timing where name='$name'");
  $row=mysqli_fetch_array($result,MYSQLI_NUM);

  if($row[0]=="")
  {
    return 0;
  }
  else return 1;
}
function smart_equ_check($con,$name)
{
   
  
   $A=array();
   mysqli_select_db($con,"SETTING");
   $result=mysqli_query($con,"select equ_name from timing where name='$name'");
   $row=mysqli_fetch_array($result,MYSQLI_NUM);
   $array=explode(',',$row[0]);
   mysqli_select_db($con,"LOT");
   $smart_result=mysqli_query($con,"select equ,name,status from smart");
   $bool=0;
        while($smart_row=mysqli_fetch_array($smart_result,MYSQLI_NUM))
        { 
              $rep_equ=array();
            if($smart_row[2]=='on')//如果该智能设置为开启状态
            {

              $smart_equ_array=explode(',',$smart_row[0]);
              foreach ($smart_equ_array as $sma_key => $sma_value) {
               
               foreach ($array as $set_key => $set_value) 
               {
                if($set_value==$sma_value)
                     {
                     $bool=1;
                      array_push($rep_equ,$sma_value); 
                      $smart_name=$smart_row[1];
                    }
                }
            }
          }   
         if($bool==1)
        {
        $string_equ=implode(',',$rep_equ);
          $data=array(
               "smart_name"=>$smart_name,
                "equ"=>$string_equ,
              );
        array_push($A,$data);
        $bool=0;
         }
        }
  if(empty($A)==0)//如果有冲突的设备
        {
            $B=array(
              'cai'=>"LOVE",
               'inf'=>$A
            );
            echo json_encode($B);
            return 0;
     }
     else
  return 1;
}
  $con=mysqli_connect("localhost","root","huang110");
  if(!$con)
  {
    echo "error";
  }
  else
  {
    if($status=="on")
    {
    mysqli_query($con,"set name 'utf-8'");
   //if(smart_equ_check($con,$sce_name))
  //  {

   // }
  //else{
        if(equ_isnull_check($con,$sce_name))
       {
          mysqli_select_db($con,"SETTING");
         $result=mysqli_query($con,"select * from timing where name='$sce_name'");
         $row=mysqli_fetch_array($result,MYSQLI_NUM);
         $type=$row[6];
         $s_hour=$row[2];
         $s_minute=$row[3];
         $e_hour=$row[4];
         $e_minute=$row[5];
         $week=explode(',',$row[7]);
         $equ_name=explode(',',$row[0]);
         $name=$sce_name;
         $date=$row[1];
         if(clash_check($name,$type,$s_hour,$s_minute,$e_hour,$e_minute,$week,$equ_name,$con)=='true')
         {
        
         if(smart_equ_check($con,$sce_name))
          {
             mysqli_select_db($con,"SETTING");
            mysqli_query($con,"update set_name set status='$status' where name='$sce_name'");
           mysqli_close($con);
          echo 'success';
           }
       }
       else {
       mysqli_close($con);
       }
     }
       else  {
        echo 'equ_null';
        mysqli_close($con);
        }
   }
   else
   {  
      mysqli_query($con,"set name 'utf-8'");
      mysqli_select_db($con,"SETTING");
      mysqli_query($con,"update set_name set status='$status' where name='$sce_name'");
      mysqli_close($con);
      echo "success";
   }
  }
?>