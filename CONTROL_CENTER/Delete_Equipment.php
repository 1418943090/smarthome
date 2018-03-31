<?php
 error_reporting(0);
 $name=$_POST['name'];
 function timing_equ_del($con,$name)
 {
   $boo=0;
   mysqli_select_db($con,"SETTING");
   $result=mysqli_query($con,"select equ_name,name from timing");
   while($row=mysqli_fetch_array($result,MYSQLI_NUM))
   {
     $array=explode(',',$row[0]);
     foreach($array as $key=>$value)
     {
       if($value==$name)
       {
         unset($array[$key]);
         $boo=1;
       }
     }
     if($boo==1)
     {
       $boo=0;
       $string=implode(',',$array);
       if($string=="")
       {
         mysqli_query($con,"update set_name set status='off' where name='$row[1]'");
         echo 'warring';
       }
      mysqli_query($con,"update timing set equ_name='$string' where equ_name='$row[0]'");
     }
   }
}
function scene_equ_del($con,$name)
{
  $boo=0;
  mysqli_select_db($con,"LOT");   
  $result=mysqli_query($con,"select equ from scene_mode_name");
  while($row=mysqli_fetch_array($result,MYSQLI_NUM))
  {
    $array=explode(',',$row[0]); 
    foreach($array as $key=>$value)
    {
      if($value==$name)
      {
        unset($array[$key]);
        $boo=1;
      }
    }
    if($boo==1)
    {
      $boo=0;
      $string=implode(',',$array);      
      mysqli_query($con,"update scene_mode_name set equ='$string' where equ='$row[0]'");
    }
 }
}
function smart_equ_del($con,$name)
{
   $boo=0;
   mysqli_select_db($con,"LOT");
   $result=mysqli_query($con,"select equ,name from smart");
   while($row=mysqli_fetch_array($result,MYSQLI_NUM))
   {
      $array=explode(',',$row[0]);
      foreach($array as $key=>$value)
      {
        if($value==$name)
        {
          unset($array[$key]);
          $boo=1;
        }
      }
      if($boo==1)
      {
        $boo=0;
        $string=implode(',',$array);
        if($string=="")
        {
          mysqli_query($con,"update smart set status='off' where name='$row[1]'");
          echo 'warring';
        }
          mysqli_query($con,"update smart set equ='$string' where equ='$row[0]'"); 
      }
   }
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
        mysqli_query($con,"delete from Node_Information where name='$name'");
        timing_equ_del($con,$name);
        smart_equ_del($con,$name);
        scene_equ_del($con,$name);
        mysqli_close($con);
      }
?>
