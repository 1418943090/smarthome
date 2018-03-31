<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html"; charset='utf-8'>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../assets/jquery.mobile-1.4.5.min.css">
<script src="../assets/jquery-2.1.4.min.js"></script>
<script src="../assets/jquery.mobile-1.4.5.min.js"></script>
</head>
<script type="text/javascript">
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
	 	    var A= Array();
        var B= Array();
        var status="";
        var scene_name=$('#scene_name').val();
        if(scene_name=="")
        {
           $("#none_scene_name").popup('open');
        }
        else if(scene_name.indexOf(" ")>=0)
        {
           $("#error_scene_name").popup('open');
        }
        else
        {
           $(".switch").each(function()
        {
           if(this.selectedIndex==1)
           {
              var name=$(this).attr('name');
              A.push($(this).attr('name'));
           }
        });
        var len=A.length;
        var data={SCENE_NAME:scene_name,num:len,name:A};
         $.ajax({
             url:'add_scene_save.php',
            type:'post',
            data:data,
        dataType:'text',
         success:function(result)
                {
                  if(result=='error')
                  {
                     mysql_error();
                  }
                  else
                  {
                    switch(result)
                    {
                       case "exist":   $('#scene_name_exist').popup("open");break;
                       case "success":$('#add_success').popup("open");
                       setTimeout(function(){
                       self.location="scene_mode_set.php";
                         },1000); 
                           break;
                    };
                  }
                }
              });
       }
	 } 
  function mysql_error()
  {
    setTimeout(function(){
    $('#mysql_error').popup('open');
     },500);
  }
</script>
<body>
<div data-role="popup" id="mysql_error" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>异常，数据库没有正常工作。</p>
</div>
<!--**************未输入情景名弹窗************************************-->
<div data-role="popup" id="none_scene_name" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>&nbsp;请给你设定的场景命个名&nbsp;&nbsp;</p>
</div>
<!--*************************情景已存在**************************************-->
<div data-role="popup" id="scene_name_exist" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>&nbsp;该情景已经存在，请重新命名&nbsp;&nbsp;</p>
</div>
<!--*************************错误情景名**************************************-->
<div data-role="popup" id="error_scene_name" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>&nbsp;情景名中不能包含空格。&nbsp;&nbsp;</p>
</div>
<!--**********************添加情景成功弹窗*********************-->
<div data-role="popup" id="add_success" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>&nbsp;&nbsp;添加成功&nbsp;&nbsp;</p>
</div>
 <div data-role="header" style="background:#dd393f"  data-position="fixed" data-tap-toggle="false" data-fullscreen="true" >
     <!--<a  data-icon="back" data-ajax="false" data-rel="back" data-transition="turn">返回</a>-->
     <a href="" data-rel="back" style="background:#dd393f" class="ui-btn-left" data-icon="arrow-l" data-iconpos="notext"></a>  
    <h1 style="color:white">添加情景</h1>
  </div>
<div data-role="filedcontain" class="ui-content" >
<br/>
<br/>
<input type="text" name="scene_name" id="scene_name" placeholder="情景名"/>
<ul data-role="listview" data-theme="s" id="swipeMe"  data-inset="true">
<?php
error_reporting(0);
    $con=mysqli_connect("localhost","root","huang110");//""解析变量''不解析变量
         mysqli_query($con,"set name 'utf-8'");
         if(!$con)
         {
        echo "<script type='text/javascript'>mysql_error();</script>";
         }
         else
         {
           mysqli_select_db($con,"LOT");
           $result=mysqli_query($con,"select * from Node_Information");
           while($row=mysqli_fetch_array($result,MYSQLI_NUM))
           {
          echo "<li name=$row[0] mode=$row[1]> ";
          echo "<fieldset class='ui-grid-c'>";
          echo "<div class='ui-block-a'><h2 style='color:green'>$row[0]</h2></div>";
          echo "<div class='ui-block-b'></div>";
          echo "<div class='ui-block-c'></div>";
          echo "<div class='ui-block-d'> ";
          echo "<select class='switch' id=$row[0] name=$row[0]  data-role='slider' >";
          echo "<option value='off'>on</option>   ";
          echo "<option value='on'>off</option> ";
          echo "</select>";
          echo "</div>";
          echo "</fieldset>";
          echo "</li>";
           }
          mysqli_close($con);
         }
?>
  </ul>
<button onclick="save();" data-theme='b'>保存设定</button>
</div>
</body>
</html>