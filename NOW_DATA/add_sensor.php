<?php
error_reporting(0);
  $id=$_POST['id'];
  $name=$_POST['name'];
  $sensor_type=$_POST['sensor_type'];
  $net_type=$_POST['net_type'];
  $con=mysqli_connect("localhost","root","huang110");
  if(!$con)
  {
  	echo "error";
  }
  else
  {
    mysqli_query($con,"set name 'utf8'");
    mysqli_select_db($con,"DATA");
    mysqli_query($con,"select * from Now_Data where id='$id'");
    $i=mysqli_affected_rows($con);
    if($i==1)
    {
    	 echo 'idexist';
    }
    else 
    {
       mysqli_query($con,"select * from Now_Data where name='$name'");
       $i=mysqli_affected_rows($con);
       if($i==1)
       {
         echo 'namexist';
       }
       else 
       {
         $Result=mysqli_query($con,"select * from sensor where id='$id'");
         $i=mysqli_affected_rows($con);
         if($i==0)
         {
           echo 'noexist';
         }
         else
         {
           $row=mysqli_fetch_array($Result,MYSQLI_NUM);
           mysqli_query($con,"insert into Now_Data(id,name,sensor_type,net_type) values('$id','$name','$row[1]','$row[2]')");
           mysqli_query($con,"create table $id(
                                  data varchar(20),
                                  year int(5),
                                  month int(5),index index_a(month),
                                  week int(2),index index_b(week),
                                  days int(2),index index_c(days),
                                  hour int(2),index index_d(hour),
                                  minute int(2),index index_e(minute)
                                   );");
         }
       } 
     mysqli_close($con);
   }
}
?>