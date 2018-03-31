<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html"; charset='utf-8'><!--告诉浏览器我的页面内容用的什么字符格式-->
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../assets/jquery.mobile-1.4.5.min.css">
<script src="../assets/jquery-2.1.4.min.js"></script>
<script src="../assets/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript" >
//----------------------一键开启和一键关闭功能实现
function off_or_on_all(i)
{     
     var data="type="+i;
     $.ajax({  
           url:'off_or_on_all.php',
           type:'post',
           data:data,
           dataType:'text',
           success:function(result)
           {    
              if(result=="on")//如果是开启指令
              {
                $(".switch").each(function()//将所有开关状态置为开启状态
                {
                  this.selectedIndex=1;
                  $(this).slider("refresh");
                });
                close_popup('open_all');//关闭弹窗
              }
              else if(result=="off")//如果是关闭指令
              {
                $(".switch").each(function()//将所有指令置为关闭状态
                {
                  this.selectedIndex=0;
                  $(this).slider("refresh");
                });
                close_popup('close_all');//关闭弹窗
              }
              else
              {
                if(i==1)//操作失败关闭弹窗，开启报错弹窗
                {
                  $('#open_all').popup("close");
                  setTimeout(function()
                  {
                    $('#mysql_error').popup("open");
                  },1000);
                }
                if(i==0)
                {
                  $('#close_all').popup("close");
                  setTimeout(function()
                  {
                    $('#mysql_error').popup("open");
                  },1000);
                }
              }
            }
          });
}
//-------------------页面开关状态初始化操作---------------------
function Init()
{
  $.ajax({
       url:'Init.php',
      type:'get',
  dataType:'text', 
  success:function(data)
          {
            if(data=="error")
            {
              mysql_error();
            }
            else
            {                                
              var ajaxobj=eval("("+data+")");//将服务端传来的的数据编码成数组类型
              $(".switch").each(function()
              {
                var name = $(this).attr("name");
                if(ajaxobj[name]=='on') 
                {
                  this.selectedIndex=1;
                  $(this).slider("refresh");
                }
              });
            }
          }
        });
}
//--------------页面加载时操作-------------
$(document).on("pageinit",function()
{ 
//--------------滑动事件-------------------
   $("li").on("swiperight",function(){
   var name=$(this).attr("name");
   $('#Equipment_Name').html($("h2[name="+name+"]").html());
   $('#Equipment_ID').html($(this).attr('id'));
   var style= $(this).attr('mode');
   $('#Equipment_Mode').html(style);
   var s=document.getElementById(name).selectedIndex;
   if(s==1)
      $("#Equipment_Status").html("on");
   else 
      $("#Equipment_Status").html("off");
   $("#ChangePane").panel("open");
});
//-------------开关操作功能实现---------
$('.switch').on("change",function()
{
     var tag = this.selectedIndex;
     var status="off";
     if(tag==1)
         status="on";
     var name = $(this).attr('name');
     var data="name="+name+"&status="+status;//数组对象创建   
         $.ajax({
               url:'switch_file.php',
              type:'post',
              data:data,
          dataType:'text',
           success:function(result)
                   { 
                     if(result=='error')
                     {
                       mysql_error();
                       if(tag==1)//如果操作失败，开关状态恢复起始状态
                       {
                         $(".switch").each(function()
                         {
                           var name1 = $(this).attr("name");
                           if(name1==name) 
                           {
                             this.selectedIndex=0;
                             $(this).slider("refresh");
                           }
                         });
                        }
                        else
                        {
                          $(".switch").each(function()//如果操作失败，开关状态恢复起始状态
                          {
                            var name1 = $(this).attr("name");
                            if(name1==name) 
                            {
                              this.selectedIndex=1;
                              $(this).slider("refresh");
                            }
                          });
                        }
                      }
                   }
            });
      });
//----------------------------------------------------
});
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
//添加设备功能实现
function add_equipment()
{
  var equipment_id=$('#equipment_id').val();
  $('#error').html("");
  if(equipment_id=="")
  {
    $('#error').html("错误:请输入设备ID");
  }
  else
  {
    var equipment_name = $('#equipment_name').val();
    if(equipment_name=="")
    {
      $('#error').html("错误:请输入设备名");
    }
    else if(equipment_name.indexOf(" ")>=0)
    {
      $('#error').html("错误:名字不能包含有空格。");
    }
    else
    {     
      var symbol=0;
      var data="name="+equipment_name+"&status="+"off"+"&ID="+equipment_id;   
      $.ajax({
          url:'Add_Equipment.php',
         type:'post',
         data:data,
     dataType:'text',
      success:function(result)
              {
                switch(result)
                {
                  case "nameexist":   $('#error').html("错误:设备名已存在。");break;
                  case "idexist":  $('#error').html("错误:设备ID已存在。");break;
                  case "noexist": $('#error').html("错误:设备ID不存在，请检查后重新输入。");break;
                  case "error": mysql_error();break;
                  default: success();break;
                }
              }
            });
    }
 }
}
function success()
{
  $('#add_equipment').popup('close');
  setTimeout(function()
  {
    $('#add_success').popup('open');
  },800);
  setTimeout(function()
  {
    $('#add_success').popup('close');
  },1800);
   setTimeout(function()
  {
   location.reload([true]); 
  },2300);
}
function f5()
{
  $('#change_name').popup("close");
  setTimeout(function()
  {
    $('#system_error').popup("open");
  },1000);
}
function f4()
{
  $('#change_name').popup("close");
  setTimeout(function()
  {
    $('#change_success').popup("open");
  },1000);
  setTimeout(function()
  {
    $('#change_success').popup("close");
  },3000);
}
function mysql_error()
{
  setTimeout(function()
  {
    $('#mysql_error').popup('open');
  },500);
}
//删除设备功能实现
function delete_equipment()
{
   $('#delete_sure').popup("close");
   var name=$('#Equipment_Name').html();
   var data="name="+name;
   $.ajax({
       url:'Delete_Equipment.php',
      type:'post',
      data:data,
  dataType:'text',
   success:function(result)
            {
              if(result=='error')
              {
                setTimeout(function()
                {
                  $('#mysql_error').popup("open");
                },1000);
              }
              else if(result=="")
              {
                location.reload([true]);
              } 
              else
              {
                setTimeout(function()
                {                 
                   $('#warring').popup("open");
                },500);
                                
              }
            }
         });
}
function close_warring()
{
  $('#warring').popup("close");
  setTimeout(function()
  {
    location.reload([true]);  
  },1000);
}
//更改设备名
function change_equipment_name(){
   var newname=$('#newname').val();
   var name=$('#Equipment_Name').html();
    if(newname=="")
    {
    $('#error1').html("错误:请输入更改后设备名。");
    }
    else if(newname.indexOf(" ")>=0)
    {
      $('#error1').html("错误:名字不能包含有空格。");
    }
    else
    {
      var data="name="+name+"&newname="+newname;
      $.ajax({
          url:'Change_Equipment_Name.php',
         type:'post',
         data:data,
     dataType:'text',
      success:function(result)
              {           
                if(result=="exist")
                {
                  $('#error1').html("错误:设备名已存在。") 
                }
                else if(result=='true')
                {
                  $("h2[name="+name+"]").html(newname);
                  $("h2[name="+name+"]").attr('name',newname);
                  // $("h2[name="+name+"]").html(newname);
                  $('#Equipment_Name').html(newname); 
                  f4();
                }
                else
                {
                  f5();
                }
             }
         });
    }
}
//***********关闭弹窗**********************
function close_popup(pop_id)
{
  $("#"+pop_id).popup("close");
}
function Add_Equipment_Popup_Open()
{
   $('#error').html("");
   $('#equipment_name').val("");
   $('#equipment_id').val("");
   $('#add_equipment').popup('open');
}
function change_name_pane_open()
{
   $('#error1').html("");
   $('#change_name').popup('open');
}
//**************************
</script>
</head>
<body onload="Init();">
<div data-role="page" id="pageone" ><!--主面板-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~更改设备名弹窗~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~`-->
<div data-role="popup" id="change_name" data-transition="turn" data-position-to="window" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <h id='error1' style="color:red"></h>
     <input type="text" id="newname" placeholder="新设备名:"/>
     &nbsp;&nbsp;&nbsp;
     <button data-inline="true" onclick="change_equipment_name();">确定</button>
     &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
    <button data-inline="true" data-theme="b" onclick="close_popup('change_name');">取消</button>
</div>
<!--***********************************************************-->
<div data-role="popup" id="warring" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <p><font color="red" size="3">警告</font>:由于您删除了该设备导致您之前的一些设置失效，系统已帮您关闭了那些无效的设置。</p>
     <button onclick="close_warring();">我知道了</button>
</div>
<!--***********************************************************-->
<div data-role="popup" id="add_success" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>添加成功</p>
</div>
<!--***********************************************************-->
<div data-role="popup" id="system_error" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>系统错误，请重启后重试。</p>
</div>
<!--********************************更改设备名为空弹窗************************************************-->
<div data-role="popup" id="change_name_null" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>修改失败，请输入修改后设备名。</p>
</div>
<!--********************************设备名更改成功弹窗************************************************-->
<div data-role="popup" id="change_success" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>修改成功。</p>
</div>
<!--********************************数据库连接失败弹窗************************************************-->
<div data-role="popup" id="mysql_error" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>异常，数据库没有正常工作。</p>
</div>
<!-- data-position-to="window"  弹窗居中显示-->
<!--********************************确认是否删除设备弹窗************************************************-->
 <div data-role="popup" id="delete_sure"  data-transition="turn" data-position-to="window" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>你确定要删除该设备吗</p>
     <button data-inline="true" onclick="delete_equipment();">确定</button><button data-inline="true" data-theme="b" onclick="close_popup('delete_sure');">取消</button>
</div>
<!--****************************************是否关闭所有设备弹窗*******************************************************-->
<div data-role="popup" id="close_all" data-position-to="window" data-transition="turn" data-dismissible="false"  class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>该操作将<b style="color:red">关闭</b>所有设备</p>
     <p>是否继续....</p>
     <button data-inline="true" onclick="off_or_on_all(0);">确定</button><button data-inline="true" data-theme="b" onclick="close_popup('close_all');">取消</button>
</div>
<!--*****************************************是否开启所有设备弹窗****************************************************-->
<div data-role="popup" id="open_all" data-position-to="window" data-transition="turn" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>该操作将<b style="color:red">开启</b>所有设备</p>
     <p>是否继续....</p>
     <button data-inline="true" onclick="off_or_on_all(1);">确定</button><button data-inline="true" data-theme="b" onclick="close_popup('open_all');">取消</button>
</div>
<div data-role="panel" id="ChangePane"> 
     <h2>设备信息</h2>
     <span style="color:blue">设备名:</span><span id="Equipment_Name"></span><br>
     <span style="color:blue">设备ID:</span><span id="Equipment_ID"></span><br>
     <span style="color:blue">设备状态:</span><span id="Equipment_Status"></span><br>
     <span style="color:blue">设备类型:</span><span id="Equipment_Mode"></span><br>
     <button data-theme='a' onclick="change_name_pane_open();">更改设备名</button>
     <a href="#delete_sure" data-rel="popup" data-role="button" data-theme="b" >删除设备</a>
</div> 
<!--*************************************添加设备对话框***************************************************************************-->
<div data-role="popup" id="add_equipment" data-position-to="window" data-overlay-theme="a" data-theme="a" data-dismissible="true"  style="max-width:400px;" class="ui-corner-all" data-position="turn" >
<div data-role="header" data-theme='b' class="ui-corner-bottom ui-content">
<h1> &nbsp; &nbsp; &nbsp;添加设备 &nbsp; &nbsp; &nbsp;</h1>
</div>
<div data-role="content" data-theme="a"  class="ui-corner-bottom ui-content">
<h id='error' style='color:red'></h>
<input type="text" name="equipment_id" id="equipment_id" placeholder="设备ID:" style='width:400px;'/>
<input type="text" name="equipment_name" id="equipment_name" placeholder="设备名:" style='width:400px;'/>
<fieldset class="ui-grid-c">
      <!--     <div class="ui-block-b">
                <h4>设备类型</h4>
          </div> 
          <div claa="ui-grid-c">
               <select id="mode">
                 <option value="wifi">WiFi</option>
                 <option value="zigbee">ZigBee</option>
                 <option value="bluetooth">BlueTooth</option>
                 <option value="other">Other</option>
               </select>
          </div>
       </fieldset> -->
<a href="#" data-role="button" data-inline="false"  data-theme="b" onclick="add_equipment();">添加</a>
</div>
</div>
<!--添加设备对话框-->
<!--*****************************主面板标题栏**********************************************************-->
<div data-role="header" style="background:#dd393f"  data-position="fixed" data-tap-toggle="false" data-fullscreen="true" >
     <!--<a  data-icon="back" data-ajax="false" data-rel="back" data-transition="turn">返回</a>-->
    <a href="../HomePane2.html"  style="background:#dd393f" class="ui-btn-left" data-icon="arrow-l" data-iconpos="notext"></a>  
    <h1 style="color:white">控制中心</h1>
 </div>
<!--********************************************************************************************************-->
<br>
<!--*********************************************内容部分***********************************************-->
<div data-role="fieldcontain" class="ui-content" id="add">
<br>
<!--第一列-->
<ul data-role="listview" data-theme="s" id="swipeMe" data-filter="true" data-filter-placeholder="Search equipment" data-inset="true">
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
      $result=mysqli_query($con," select * from Node_Information ");
      while($row = mysqli_fetch_array($result,MYSQLI_NUM))
      {   
        echo "<li name=$row[0] mode=$row[1] id=$row[3]> ";
        echo "<fieldset class='ui-grid-a'>";
        echo "<div class='ui-block-a'><h2 style='color:green' id=$row[3] name=$row[0]>$row[0]</h2></div>";
        echo "<div class='ui-block-b'> ";
        echo "<div style='float:right'>";
        echo "<select class='switch' id=$row[0] name=$row[0]  data-role='slider' >";
        echo "<option value='off'>on</option>   ";
        echo "<option value='on'>off</option> ";          echo "</select>";
        echo "</div>";
        echo "</div>";
        echo "</fieldset>";
        echo "</li>";
     }
        mysqli_close($con);
   }
?>
<li >
    <fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="#open_all" data-rel="popup" data-role="button" data-icon="check">一键开启</a></div>
    <div class="ui-block-b"><a href="#close_all" data-rel="popup" data-role="button" data-icon="delete" data-theme="b" >一键关闭</a></div>
    </fieldset>
</li>
</ul>
<br>
<button  data-theme='b' onclick="Add_Equipment_Popup_Open();">添加设备</button>
</div> 
</body>
</html>
