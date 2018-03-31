<?php
error_reporting(0);
  $name=$_POST['name'];
  $status=$_POST['status'];
  function equ_rep_check($con,$name)
  {
     $A=array();
    
     $smart_name='';
     $smart_result=mysqli_query($con,"select equ from smart where name='$name'");
     $row_equ=mysqli_fetch_array($smart_result,MYSQLI_NUM);
     $array_equ=explode(',',$row_equ[0]);
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
                "equ"=>$equ
              );
              array_push($A,$data);
              $bool=0;
            }
         }
         if(empty($A)==0)
         {
          $B=array(
               "cai"=>"LOVEYOU",
               "inf"=>$A
            );
          echo json_encode($B);
          return 1;
         }
         else return 0;
    }
function setting_equ_check($con,$name)
{
   $A=array();
   mysqli_select_db($con,'LOT');
   $result1=mysqli_query($con,"select equ from smart where name='$name'");
   $array1=mysqli_fetch_array($result1,MYSQLI_NUM);
   $smart_equ_array=explode(',',$array1[0]);
   mysqli_select_db($con,"SETTING");
   $result2=mysqli_query($con,"select equ_name,name from timing");
   $bool=0;

   while($array2=mysqli_fetch_array($result2,MYSQLI_NUM))
   {
           $rep_equ=array();
           $result3=mysqli_query($con,"select status from set_name where name='$array2[1]'");
           $set_status=mysqli_fetch_array($result3,MYSQLI_NUM);
           if($set_status[0]=='on')
           {
           
              $setting_equ_array=explode(',',$array2[0]);
               {
                  foreach($setting_equ_array as $key1=>$value1)
                  {
                     
                     foreach($smart_equ_array as $key2=>$value2)
                     {
                        if($value2==$value1)
                        {
                           $bool=1;
                           array_push($rep_equ,$value2);
                           $set_name=$array2[1];
                        }
                     }
                  }
               }
           }
         if($bool==1)
          {
         
            $string_equ=implode(',',$rep_equ);
            $date=array(
              "setting_name"=>$set_name,
              "equ"=>$string_equ,
            );
            array_push($A,$date);
            $bool=0;
          }
   }
   if(empty($A)==0)
   {
      $B=array(
          'cai'=>"LOVE",
          'inf'=>$A
      );
      echo json_encode($B);
      return 'false';
   }
   else return 'true';


}
   $con=mysqli_connect("localhost","root","huang110");
   if(!$con)
   {
   echo 'error';
   }
   else{
     
      mysqli_query($con,"set name 'utf8'");
      mysqli_select_db($con,"LOT");
      $result=mysqli_query($con,"select equ from smart where name='$name'");
      $row=mysqli_fetch_array($result,MYSQLI_NUM);
      if($row[0]=="")
      {
        echo "equ_null";
      }
      else {
      if($status=='on')
      {

        if(equ_rep_check($con,$name))
        {

        }
        else {
          if(setting_equ_check($con,$name)=='true')
          {
           mysqli_select_db($con,"LOT");
           mysqli_query($con,"update smart set status='$status' where name='$name'");
          }
        }

      }
      else {
        mysqli_query($con,"update smart set status='$status' where name='$name'");
       
      }
      }
      mysqli_close($con);
   }
?>