<?php
error_reporting(0);
  $name=$_POST['name'];
  $sensor=$_POST['sensor'];
  $compare=$_POST['compare'];
  $value=$_POST['date'];
  $operate=$_POST['operate'];
  $equ_name=$_POST['equ_name'];
 function equ_rep_check($con,$name,$array_equ)
 {
      $A=array();
      $bool=0;
      $result1=mysqli_query($con,"select equ,name,status from smart");
         while($row1=mysqli_fetch_array($result1,MYSQLI_NUM))
       {
          $rep_equ=array();
         if($row1[1]!=$name && $row1[2]=='on')
            {
                $array_equ1=explode(',',$row1[0]);
                   foreach($array_equ1 as $key2=>$value2)
                {
                     foreach($array_equ as $key1=>$value1)
                   {
                     if($value1==$value2)
                     {
                      array_push($rep_equ,$value2);
                      $smart_name=$row1[1];
                      $bool=1;
                     }
                   }
                }

            }
            if($bool==1)
            {
               $equ=implode(',',$rep_equ);
               $data=array(
                "smart_name"=>$smart_name,
               "equ"=>$equ,
               );
             array_push($A,$data);
             $bool=0;
            }
       }
        
         if(empty($A)==0)
         {
          $B=array(
            "cai"=>"ILOVEYOU",
             "inf"=>$A  
            );
          echo json_encode($B);
          return 1;
         }
         else return 0;

 }
 function setting_equ_check($con,$array_equ)
 {
     
     $bool=0;
     $A=array();
     mysqli_select_db($con,"SETTING");
      $result1=mysqli_query($con,"select equ_name,name from timing");
         while($row1=mysqli_fetch_array($result1,MYSQLI_NUM))
       {
           $rep_equ=array();
           $result3=mysqli_query($con,"select status from set_name where name='$row1[1]'");
           $set_status=mysqli_fetch_array($result3,MYSQLI_NUM);
           if($set_status[0]=='on')
            {
                $array_equ1=explode(',',$row1[0]);
                   foreach($array_equ1 as $key2=>$value2)
                {  
                     foreach($array_equ as $key1=>$value1)
                   {
                     
                     if($value1==$value2)
                     {
                      $bool=1;
                      array_push($rep_equ,$value2);
                      $smart_name=$row1[1];
                     }
                   }
                }

            }
       

           if($bool==1)
        {
        $string_equ=implode(',',$rep_equ);
        
          $data=array(
               "setting_name"=>$smart_name,
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
            return 1;
     }
     else
  return 0;



 }
   $con=mysqli_connect("localhost","root","huang110");
   if(!$con)
   {
     
   }
   else{
     mysqli_query($con,"set name 'utf8'");
     mysqli_select_db($con,"LOT");
     $result=mysqli_query($con,"select status from smart where name='$name'");
     $row=mysqli_fetch_array($result,MYSQLI_NUM);
     if($row[0]=='on')
     {

      if(equ_rep_check($con,$name,$equ_name))
      {
      }
     else{

        if(setting_equ_check($con,$equ_name))
        {

        }
        else
        {
           mysqli_select_db($con,"LOT");
        $string_equ=implode(',',$equ_name);
        mysqli_query($con,"update smart set sensor='$sensor',compare='$compare', value='$value' ,operate='$operate' ,equ='$string_equ' where name='$name'");
         }

        }
      }
      else
      {
         mysqli_select_db($con,"LOT");
         $string_equ=implode(',',$equ_name);
      mysqli_query($con,"update smart set sensor='$sensor',compare='$compare', value='$value' ,operate='$operate' ,equ='$string_equ' where name='$name'");
      }
   }
   mysqli_close($con);
?>