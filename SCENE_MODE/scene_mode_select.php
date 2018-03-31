<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html"; charset='utf-8'>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../assets/jquery.mobile-1.4.5.min.css">
<script src="../assets/jquery-2.1.4.min.js"></script>
<script src="../assets/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript">
	function Init(current_scene)
	{ 
    if(current_scene=="")
    {
      $('#current_scene').html("当前没有选择任何情景模式");
    }
    else
    {
      $('#current_scene').html(current_scene);
    }
       //alert(current_name);
	}
	var SCENE_NAME="";
  function mysql_error()
  {
    setTimeout(function(){
    $('#mysql_error').popup('open');
      },1000);
  }
	function f(scene_name)
	{
    SCENE_NAME=scene_name;
    $('#select_sure').popup("open");
    $('#popup_current_scene').html(SCENE_NAME);
	}
  function sure_select_scene(i)
  {
    if(i==1)
    {
      var data={scene_name:SCENE_NAME};
      $.ajax({
          url:"scene_write.php",
         data:data,
         type:'post',
     dataType:'text',
      success:function(result)
             {  
               $('#select_sure').popup("close");
               if(result=='error')
               {
                  mysql_error(); 
               }
               else
               {
                  $('#current_scene').html(SCENE_NAME);
                  $('#select_scene_success_id').html(SCENE_NAME);
                  setTimeout(function(){
                      $('#select_scene_success').popup("open");
                        },1000);
                  setTimeout(function(){
                      $('#select_scene_success').popup("close");
                        },3000);
               }
             }
          });
    }
    else
    {
       $('#select_sure').popup("close");   
    }
 }
</script>
</head>
<div data-role="page" id="pageone" ><!--主面板-->
<div data-role="popup" id="mysql_error" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>异常，数据库没有正常工作。</p>
</div>
<!--********************************更改情景成功弹窗************************************************-->
<div data-role="popup" id="select_scene_success" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 350px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <h>已成功切换到:</h><h id="select_scene_success_id" style="color:red"></h>
</div>
<!--********************确认情景模式选择弹窗**************************-->
<div data-role="popup" id="select_sure" data-transition="true" data-dismissible="false" style="max-width:170px" class="ui-content">
<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
    <h>即将切换到</h><h id="popup_current_scene" style="color:red"></h><h>模式</h><br/>
    <button data-inline="true" onclick="sure_select_scene(1);">确定</button><button data-inline="true" data-theme="b" onclick="sure_select_scene(0)">取消</button>
</div>
<!--*****************************主面板标题栏**********************************************************-->
 <div data-role="header" style="background:#dd393f"  data-position="fixed" data-tap-toggle="false" data-fullscreen="true" >
    <a href="" data-rel="back" style="background:#dd393f" class="ui-btn-left" data-icon="arrow-l" data-iconpos="notext"></a>  
    <h1 style="color:white">情景模式选择</h1>
  </div>
<!--********************************************************************************************************-->
 <br>
<!--*********************************************内容部分***********************************************-->
<div data-role="fieldcontain" class="ui-content" id="add">
<br>
<br>
<span style="color:blue">当前情景模式:<span id="current_scene" style="color:red"></span><br>
<?php
error_reporting(0);
    $con=mysqli_connect("localhost","root","huang110");
    mysqli_query($con,"set name 'utf-8'");
    $current_scene="null";
    if(!$con)
    {
       echo "<script type='text/javascript'>mysql_error();</script>";  
    }
    else 
    {
       mysqli_select_db($con,"LOT");
       $result=mysqli_query($con,"select * from current_scene");
       $i=mysqli_affected_rows($con);
       if($i==1)
       {
          while($row=mysqli_fetch_array($result,MYSQLI_NUM))
          {
          	$current_scene=$row[0];
          }
       }
       $result=mysqli_query($con,"select * from scene_mode_name");
        while($row=mysqli_fetch_array($result,MYSQLI_NUM))
       {
          echo "<button onclick=f('$row[0]');>$row[0]</button>";
       }
       echo "<script type='text/javascript'>Init('$current_scene')</script>"; 
       mysqli_close($con);
    }
?>        
</div> 
</body>
</html>
