<?php
      $account=$_POST['account'];
      $password=$_POST['password'];

      $con=mysqli_connect("localhost","root","huang110");
      mysqli_query($con,"set name ust-8");
      mysqli_select_db($con,"user");
      $result=mysqli_query($con,"select * from user where account='frank'");
      
     
     if($result=='null')
     	echo "haung";
     if (mysqli_num_rows($result)==0) {
       echo "flase";
     }
    else {
       $row=mysqli_fetch_assoc($result);
      
       if($password==$row['password'])
          echo  "true";
       else 
          echo "false";      
  }
      mysqli_close($con);
?>