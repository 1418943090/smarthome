<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html"; charset='utf-8'>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../assets/jquery.mobile-1.4.5.min.css">
<script src="../assets/jquery-2.1.4.min.js"></script>
<script src="../assets/jquery.mobile-1.4.5.min.js"></script>
<script src="../assets/jquery.swipeButton-1.2.1.min.js"></script>
<script type="text/javascript">
  var name;
  $(document).on("pageinit",function()
  {
    var i=1;
    $('a').on("taphold",function(){
    i=0;
    name=$(this).attr('name');
    if(name!='false')
      $('#delete_scene').popup("open");//********************
     });
    if(i==1)
    {
      $('a').on("tap",function(){
      if($(this).attr('name')!='false'&&i==1)
      {
         name=$(this).attr('name');
         window.location.href="scene.php?name="+name;
      }
     });
   }
  });
  function mysql_error()
  {
    setTimeout(function(){
    $('#mysql_error').popup('open');
  },500);
  }
  function delete_scene()
  {
     var data={name:name};
     $.ajax({
         url:'delete_scene.php',
        type:'post',
        data:data,
    datatype:'text',
     success:function(result)
           {
             if(result=='error')
             {
                $('#delete_scene').popup("close");
                mysql_error();
             }
             else
             {
               location.reload([true]);
             }
          } 
      });
  }
  function close_popup()
  {
    $('#delete_scene').popup("close");
  }
</script>
</HEAD>
<div data-role="page" id="pageone" ><!--主面板-->
  <div data-role="popup" id="mysql_error" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>异常，数据库没有正常工作。</p>
</div>
<!--*****************************主面板标题栏**********************************************************-->
<div data-role="popup" id="delete_scene" data-transition="turn" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>你确定要删除该场景吗</p>
     <button data-inline="true" onclick="delete_scene();">确定</button><button data-inline="true" data-theme="b" onclick="close_popup();">取消</button>
</div>
 <div data-role="header" style="background:#dd393f"  data-position="fixed" data-tap-toggle="false" data-fullscreen="true" >
     <!--<a  data-icon="back" data-ajax="false" data-rel="back" data-transition="turn">返回</a>-->
     <a name='false' href="../HomePane2.html"  style="background:#dd393f" class="ui-btn-left" data-icon="arrow-l" data-iconpos="notext"></a> 
     <h1 style="color:white">情景模式</h1>
  </div>
<!--********************************************************************************************************-->
  <br>
<!--*********************************************内容部分***********************************************-->
<div data-role="fieldcontain" class="ui-content" id="add">
<br/>
<br/>
<?php
error_reporting(0);
   $con=mysqli_connect("localhost","root","huang110");
   mysqli_query($con,"set name 'utf-8'");
   if(!$con)
   {
      echo "<script type='text/javascript'>mysql_error();</script>";
   }
   else
   {
      mysqli_select_db($con,"LOT");
      $result=mysqli_query($con,'select * from scene_mode_name');
      while($row = mysqli_fetch_array($result,MYSQLI_NUM))
      {
        echo "<a href='#' data-role='button' name=$row[0]> $row[0]</a>";
      }
      mysqli_close($con);
   }
?>
<form>
    <a  name='false' href="add_scene_pane.php" data-role="button" data-ajax="false" data-theme="b" >添加情景</a>
</form>         
<br>
</div> 
</body>
</html>