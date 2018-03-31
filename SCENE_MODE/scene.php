<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html"; charset='utf-8'>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../assets/jquery.mobile-1.4.5.min.css">
<script src="../assets/jquery-2.1.4.min.js"></script>
<script src="../assets/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript">
  var scene_name;
  function mysql_error()
  {
    setTimeout(function(){
    $('#mysql_error').popup('open');
  },500); 
  }
  function Init(name,title)
  {
    var arr = eval("("+name+")");  
    $('#title').html(title);
    scene_name=title;
    var len=arr.length;
    $(".switch").each(function()
    {
       for(var i =0;i<len;i++)
       {
          if(($('#'+arr[i]).attr('name')==$(this).attr('name') ))
          {
            this.selectedIndex=1;
          }     
       }
    });
  }
  function sleep(numberMillis) 
  { 
    var now = new Date(); 
    var exitTime = now.getTime() + numberMillis; 
    while (true)
    { 
      now = new Date(); 
      if (now.getTime() > exitTime) 
      return; 
    } 
  }
  function save()
  {
     var N=Array();
     $('.switch').each(function()
     {
        if(this.selectedIndex==1)
        {
         var name=$(this).attr('name');
         N.push($(this).attr('name'));
        }
      });
    var len=N.length;
    var data={Len:len,SCENE_NAME:scene_name,name:N};
    $.ajax({
        url:'change_scene_save.php',
        type:'post',
        data:data,
    dataType:'text',
     success:function(result)
        {  
          if(result=="")
          {
            $('#change_success').popup("open");
                 setTimeout(function(){
            $('#change_success').popup("close");
               },1000);
          setTimeout(function(){
             self.location='scene_mode_set.php';
                 },1500);
          }
          else
          {
            $('#mysql_error').popup('open');
          }
     }    
  });
  }
</script>
</head>
<body>
<div data-role="page" id="pageone">
  <div data-role="popup" id="mysql_error" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>异常，数据库没有正常工作。</p>
</div>
<!--********************************更改成功弹窗************************************************-->
<div data-role="popup" id="change_success" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>修改成功。</p>
</div>
<!--********************************更改失败弹窗************************************************-->
<div data-role="popup" id="change_faild" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>修改失败。</p>
</div>
 <div data-role="header" style="background:#dd393f"  data-position="fixed" data-tap-toggle="false" data-fullscreen="true" >
     <!--<a  data-icon="back" data-ajax="false" data-rel="back" data-transition="turn">返回</a>-->
     <a href="" data-rel="back" style="background:#dd393f" class="ui-btn-left" data-icon="arrow-l" data-iconpos="notext"></a>  
     <h1 style="color:white" id="title">mode</h1>
  </div>
  <ul data-role="listview" data-theme="s" id="swipeMe"  data-inset="true">
  <br/>
  <br/>
<?php
error_reporting(0);
    $name=$_GET['name']; 
    $arr=array();
    array_push($arr,$name);
    $con=mysqli_connect("localhost","root","huang110");
    if(!$con)
    {
       echo "<script type='text/javascript'>mysql_error();</script>";
    }
    else
    {
      mysqli_query($con,"set name 'utf-8'");
    	mysqli_select_db($con,"LOT");
    	$result=mysqli_query($con,"select * from Node_Information");
    	while($row=mysqli_fetch_array($result,MYSQLI_NUM))
    	{
        echo "<li name=$row[0] mode=$row[1]> ";
        echo "<fieldset class='ui-grid-a'>";
        echo "<div class='ui-block-a'><h2 style='color:green'>$row[0]</h2></div>";
        echo "<div style='float:right'>";
        echo "<div class='ui-block-b'> ";
        echo "<select class='switch' id=$row[0] name=$row[0]  data-role='slider' >";
        echo "<option value='off'>on</option>   ";
        echo "<option value='on'>off</option> ";
        echo "</select>";
        echo "</div>";
        echo "</div>";
        echo "</fieldset>";
        echo "</li>";
    	}
      $data=mysqli_query($con,"select * from scene_mode_name where name='$name'");
      $arr=mysqli_fetch_array($data,MYSQLI_NUM);
      $title=$arr[0];
      $str=explode(',',$arr[1]);
      $str1=json_encode($str);
      echo "<script type='text/javascript'>Init('$str1','$title');</script>";
      mysqli_close($con);
    }
?>
</ul>
<button onclick="save();" data-theme='b'>保存更改</button>
</body>
</html>
