<?php
 $name=$_POST['name'];
 $newname=$_POST['newname'];
 $con=mysqli_connect("localhost","root","huang110");
 mysqli_query($con,"set name 'utf8'");
 function equname_change($con,$name,$newname)
 {
    $result=mysqli_query($con,"select * from Node_Information where name='$newname'");
    $i=mysqli_affected_rows($con);
    if($i==1)
    {
      echo "exist";
      return 'false';
    }
    else
    {
      mysqli_query($con,"update Node_Information set name='$newname' where name='$name'");
      return 'true';
    }
}
function mode_equname_change($con,$name,$newname)
{
   $result=mysqli_query($con,"select * from scene_mode_name");
   while($row=mysqli_fetch_array($result,MYSQLI_NUM))
   {
       $array=explode(',',$row[1]);
       foreach ($array as $key => $value) 
       {
         if($value==$name)
       {
         $array[$key]=$newname;
       }
     }
     $string=implode(',',$array);
     mysqli_query($con,"update scene_mode_name set equ='$string' where equ='$row[1]'");     
   }
}
function timing_equname_change($con,$name,$newname)
{
   mysqli_select_db($con,"SETTING");
   $result=mysqli_query($con,"select * from timing");
   while($row=mysqli_fetch_array($result,MYSQLI_NUM))
   {
     $array=explode(',',$row[0]);
     foreach ($array as $key => $value) 
     {
       if($value==$name)
       {
         $array[$key]=$newname;
       }
     }
     $string=implode(',',$array);
     mysqli_query($con,"update timing set equ_name='$string' where equ_name='$row[0]'");
   }
}
function smart_equname_change($con,$name,$newname)
{
   $result=mysqli_query($con,"select * from smart");
   while($row=mysqli_fetch_array($result,MYSQLI_NUM))
   {
     $array=explode(',',$row[5]);
     foreach ($array as $key => $value) 
     {
       if($value==$name)
       {
         $array[$key]=$newname;
       }
     }
     $string=implode(',',$array);
     mysqli_query($con,"update smart set equ='$string' where equ='$row[5]'");
   }
}
   if(!$con)
   {
    echo "error";
   }
   else
   {
     mysqli_select_db($con,"LOT");
     if(equname_change($con,$name,$newname)=='true')
     {
       mode_equname_change($con,$name,$newname);
       smart_equname_change($con,$name,$newname);
       timing_equname_change($con,$name,$newname);
       echo 'true';
     }
   }
  mysqli_close($con);
?>