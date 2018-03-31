<?php
//error_reporting(0);
$sensor=$_POST['sensor'];
$type=$_POST['type'];
$year=$_POST['year'];
$month=$_POST['month'];
$days=$_POST['days'];
$data=array();
//******************************************************************************
function type_hour($year,$month,$days,$sensor)
{
  $con=$GLOBALS['con'];
  for($i=1;$i<=24;$i++)
     {
      $result=mysqli_query($con,"select AVG(data) from $sensor where hour='$i' and year='$year' and month='$month' and days='$days' ");
      $row=mysqli_fetch_array($result,MYSQLI_NUM);
      array_push($GLOBALS['data'],$row[0]);
     }
}
//**************************************************************************************
function type_day($year,$month,$days,$sensor)
{
	$con=$GLOBALS['con'];
	for($i=1;$i<=31;$i++)
	{
		$result=mysqli_query($con,"select AVG(data) from $sensor where days='$i' and year='$year' and month='$month'");
		$row=mysqli_fetch_array($result,MYSQLI_NUM);
        array_push($GLOBALS['data'],$row[0]);
	}
}
//****************************************************************************************
function type_week($year,$month,$days,$sensor)
{
    $con=$GLOBALS['con'];
    $start=0;
    for($i=1;$i<=5;$i++)
    {
        $end=$start+7;
        $result=mysqli_query($con,"select AVG(data) from $sensor where days>'$start' and days<='$end' and year='$year' and month='$month' ");
        $start+=7;
        $row=mysqli_fetch_array($result,MYSQLI_NUM);
        array_push($GLOBALS['data'],$row[0]);
    }
}
//******************************************************************************
function type_month($year,$month,$days,$sensor)
{
    $con=$GLOBALS['con'];
    for($i=1;$i<=12;$i++)
    {
    	$result=mysqli_query($con,"select AVG(data) from $sensor where month='$i' and year='$year'");
    	$row=mysqli_fetch_array($result,MYSQLI_NUM);
    	array_push($GLOBALS['data'],$row[0]);
    }
}
//****************************************************************************
function type_year($year,$month,$days,$sensor)
{
}
//****************************************************
   $con=mysqli_connect("localhost","root","huang110");
   if(!$con)
   {
   	 echo 'error';
   }
   else
   {
      mysqli_query($con,"set name 'utf8'");
      mysqli_select_db($con,"DATA");
      $result=mysqli_query($con,"select sensor_type from Now_Data where name='$sensor'");
      $row=mysqli_fetch_array($result,MYSQLI_NUM); 
      $result1=mysqli_query($con,"select id from Now_Data where name='$sensor'");
      $sensor_array=mysqli_fetch_array($result1,MYSQLI_NUM);
      $sensor_id=$sensor_array[0];
      switch($type)
     {
       case  'thour':  type_hour($year,$month,$days,$sensor_id); break;
       case   'tday':   type_day($year,$month,$days,$sensor_id); break;
       case  'tweek':  type_week($year,$month,$days,$sensor_id); break;
       case 'tmonth': type_month($year,$month,$days,$sensor_id); break;
       case  'tyear':  type_year($year,$month,$days,$sensor_id); break;
     }
     $DATA=array(
      "sensor_type"=>$row[0],
      "data"=>$data
     );
      echo json_encode($DATA);
      mysqli_close($con);
   }
     
?>