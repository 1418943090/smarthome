<?php
$name=$_POST['name'];
$status=$_POST['status'];
$style=$_POST['style'];
$con=mysqli_connect("localhost","root","huang110");
      
      if(!$con)
      {
        echo  "error";
      }
      else
      {
        mysqli_query($con,"set name 'utf8'");
        mysqli_select_db($con,"LOT");
        $Result1=mysqli_query($con,"select * from Node_Information where name='$name'");
        $i=mysqli_affected_rows($con);
        if($i==1)
        {
           echo "exist";
        }
        else
        {
         mysqli_query($con,"insert into Node_Information(name,style,status) values('$name','$style','$status')");
        $result=mysqli_query($con,"select * from scene_mode_name");
        while($row=mysqli_fetch_array($result,MYSQLI_NUM))
        {
               mysqli_query($con,"insert into $row[0](node_name,status) values('$name','off');");
        }
        mysqli_close($con);
        }
      }
?>
<?php
 $name=$_POST['name'];
 $newname=$_POST['newname'];
$con=mysqli_connect("localhost","root","huang110");
      mysqli_query($con,"set name 'utf8'");
function equname_change($con,$name,$newname)
{
  $result=mysqli_query($con,"select * from Node_Information where name='$newname'");
        $i=mysqli_affected_rows($con);
        if($i==1)
        {
          echo "exist";
          return 'false';
        }
        else
        {
         mysqli_query($con,"update Node_Information set name='$newname' where name='$name'");
         return 'true';
         //mysqli_query($con,"update User_Define_ON set name='$newname' where name='$name'");
         //mysqli_query($con,"update User_Define_OFF set name='$newname' where name='$name'");
        }
}
function mode_equname_change($con,$name,$newname)
{
   $result=mysqli_query($con,"select * from scene_mode_name");
        while($row=mysqli_fetch_array($result,MYSQLI_NUM))
        {
           mysqli_query($con,"update $row[0] set node_name='$newname' where node_name='$name'");
        }
}
function timing_equname_change($con,$name,$newname)
{
   mysqli_select_db($con,"SETTING");
   $result=mysqli_query($con,"select * from timing");
   while($row=mysqli_fetch_array($result,MYSQLI_NUM))
   {
     $array=explode(',',$row[0]);
     foreach ($array as $key => $value) {
       if($value==$name)
       {
         $array[$key]=$newname;
       }
     }
     $string=implode(',',$array);
     mysqli_query($con,"update timing set equ_name='$string' where equ_name='$row[0]'");
   }

}
      if(!$con)
      {
        echo "error";
      }
      else
      {
        mysqli_select_db($con,"LOT");
        if(equname_change($con,$name,$newname)=='true')
        {
           mode_equname_change($con,$name,$newname);
           timing_equname_change($con,$name,$newname);
           echo 'true';
        }
      }
         mysqli_close($con);
?>
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
                           if(result=="on")
                           {
                              $(".switch").each(function(){
                                   this.selectedIndex=1;
                                  $(this).slider("refresh");
                               });
                              close_popup('open_all');
                           }
                           else if(result=="off")
                           {
                              $(".switch").each(function(){
                                  this.selectedIndex=0;
                              $(this).slider("refresh");
                               });
                              close_popup('close_all');
                           }
                           else
                           {
                            if(i==1)
                            {
                              $('#open_all').popup("close");
                              setTimeout(function(){
                                $('#mysql_error').popup("open");
                                   },1000);
                             }
                              if(i==0)
                             {
                            $('#close_all').popup("close");
                              setTimeout(function(){
                                $('#mysql_error').popup("open");
                                   },1000);
                            }
                           }
                        }
                });
              //location.reload([true]);
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
                      $(".switch").each(function(){
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
$(document).on("pageinit",function(){ 
//--------------滑动事件-------------------
     $("li").on("swiperight",function(){
     var name=$(this).attr("name");
     $('#Equipment_Name').html($("h2[name="+name+"]").html());
      var style= $(this).attr('mode');
      $('#Equipment_Mode').html(style);
     var s=document.getElementById(name).selectedIndex;
     if(s==1)
          $("#Equipment_Status").html("on");
     else 
          $("#Equipment_Status").html("off")

          $("#ChangePane").panel("open");
});
//-------------开关操作功能实现---------
$('.switch').on("change",function(){
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
                                if(tag==1)
                                {
                                   $(".switch").each(function(){
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
                                   $(".switch").each(function(){
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
function sleep(numberMillis) { 
var now = new Date(); 
var exitTime = now.getTime() + numberMillis; 
while (true) { 
now = new Date(); 
if (now.getTime() > exitTime) 
return; 
} 
}
//添加设备功能实现
  function add_equipment(){
       var equipment_name = $('#equipment_name').val();
      
       if(equipment_name=="")
        {
           $('#add_equipment').popup("close");
           setTimeout(function(){
         $('#equipment_name_null').popup("open");
        },1000);
        }
        else
        {
       var style= $('#mode').val();
       var symbol=0;
       var data="name="+equipment_name+"&status="+"off"+"&style="+style;   
          $.ajax({
                         url:'Add_Equipment.php',
                        type:'post',
                        data:data,
                    dataType:'text',
                        success:function(result)
                        {
                          //alert(result);
                         switch(result)
                         {
                          case "exist": f1();break;
                          case "error": mysql_error();break;
                         }
                        }
                });
      location.reload([true]);  //重新加载页面 待改进！！！！！
    }
}
function f1()
{
   $('#add_equipment').popup("close");
         setTimeout(function(){
         $('#equipment_exist').popup("open");
        },1000);
}
function f3()
{
  $('#change_name').popup("close");

         setTimeout(function(){
         $('#equipment_exist').popup("open");
        },1000);
}
function f5()
{
  $('#change_name').popup("close");

         setTimeout(function(){
         $('#system_error').popup("open");
        },1000);
}
function f4()
{
  $('#change_name').popup("close");
         setTimeout(function(){
         $('#change_success').popup("open");
        },1000);

          setTimeout(function(){
        $('#change_success').popup("close");
         },3000);
}
function mysql_error()
  {
    setTimeout(function(){
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
                              setTimeout(function(){
                                $('#mysql_error').popup("open");
                                   },1000);
                           }
                         else
                         {
                           location.reload([true]);  
                         }
                        }
                });
}
//更改设备名
function change_equipment_name(){
   var newname=$('#newname').val();
   var name=$('#Equipment_Name').html();
    if(newname=="")
    {
      $('#change_name').popup("close");
       setTimeout(function(){
         $('#change_name_null').popup("open");
        },1000);
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
                             f3();
                          else if(result=='true'){
                           $("h2[name="+name+"]").html(newname);
                           $("h2[name="+name+"]").attr('name',newname);
                            // $("h2[name="+name+"]").html(newname);
                           $('#Equipment_Name').html(newname); 
                            f4();
                            }
                            else{
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
//**************************
</script>
</head>
<body onload="Init();">
<div data-role="page" id="pageone" ><!--主面板-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~更改设备名弹窗~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~`-->
<div data-role="popup" id="change_name" data-transition="turn" data-position-to="window" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
    <input type="text" id="newname" placeholder="新设备名:"/>
   &nbsp;&nbsp;&nbsp;
    <button data-inline="true" onclick="change_equipment_name();">确定</button>
    &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
    <button data-inline="true" data-theme="b" onclick="close_popup('change_name');">取消</button>
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
<!--********************************设备名为空弹窗************************************************-->
<div data-role="popup" id="equipment_name_null" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>添加失败，请输入设备名。</p>
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
<!--********************************设备已经存在弹窗************************************************-->
<div data-role="popup" id="equipment_exist" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>添加失败，该设备已存在。</p>
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
<!--*****************************************是否自定义开启设备弹窗****************************************************-->
<!-- <div data-role="popup" id="user_define_open" data-transition="turn" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>该操作将<b style="color:red">开启</b>所有白名单的设备</p>
     <p>是否继续...</p>
     <button data-inline="true" onclick="off_or_on_all(2);">确定</button><button data-inline="true" data-theme="b" onclick="close_popup('user_define_open');">取消</button>
</div>
*****************************************是否自定义关闭设备弹窗****************************************************
<div data-role="popup" id="user_define_close" data-transition="turn" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>该操作将<b style="color:red">关闭</b>所有白名单的设备</p>
     <p>是否继续...</p>
     <button data-inline="true" onclick="off_or_on_all(3);">确定</button><button data-inline="true" data-theme="b" onclick="close_popup('user_define_close');">取消</button>
</div> -->
<!--**************************************设备信息面板**********************************************************************-->
 <div data-role="panel" id="ChangePane"> 
    <h2>设备信息</h2>
    <span style="color:blue">设备名:</span><span id="Equipment_Name"></span><br>
    <span style="color:blue">设备状态:</span><span id="Equipment_Status"></span><br>
    <span style="color:blue">设备类型:</span><span id="Equipment_Mode"></span><br>
    <a href="#change_name"  data-rel="popup" data-role="button">更改设备名</a>
   <a href="#delete_sure" data-rel="popup" data-role="button" data-theme="b" >删除设备</a>
  </div> 
<!--********************************没有输入设备名报错弹窗********************************************************************-->
<div data-role="popup" id="none_name" data-transition="turn" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>&nbsp;&nbsp;请输入设备名&nbsp;&nbsp;</p>
</div>
<!--*************************************添加设备对话框***************************************************************************-->
<div data-role="popup" id="add_equipment" data-position-to="window" data-overlay-theme="a" data-theme="a" data-dismissible="true"  style="max-width:400px;" class="ui-corner-all" data-position="turn" >
<div data-role="header" data-theme='b' class="ui-corner-bottom ui-content">
<h1> &nbsp; &nbsp; &nbsp;添加设备 &nbsp; &nbsp; &nbsp;</h1>
</div>
<div data-role="content" data-theme="a"  class="ui-corner-bottom ui-content">
 <input type="text" name="equipment_name" id="equipment_name" placeholder="设备名:" style='width:400px;'/>
       <fieldset class="ui-grid-c">
          <div class="ui-block-b">
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
       </fieldset>
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
          echo "<li name=$row[0] mode=$row[1]> ";
          echo "<fieldset class='ui-grid-c'>";
          echo "<div class='ui-block-a'><h2 style='color:green' name=$row[0]>$row[0]</h2></div>";
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
<li >
    <fieldset class="ui-grid-a">
     <div class="ui-block-a"><a href="#open_all" data-rel="popup" data-role="button" data-icon="check">一键开启</a></div>
      <div class="ui-block-b"><a href="#close_all" data-rel="popup" data-role="button" data-icon="delete" data-theme="b" >一键关闭</a></div>
    </fieldset>
</li>
<!-- <li >
    <fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href= "#user_define_open" data-rel="popup" data-role="button" data-icon="check">自定义开启</a></div>
    <div class="ui-block-b"><a href="#user_define_close" data-rel="popup" data-role="button" data-icon="delete" data-theme="b">自定义关闭</a></div>
    </fieldset>
</li> -->
</ul>
<br>
<a href="#add_equipment" data-rel="popup"  data-role="button"  data-icon="plus" data-theme="b">添加设备</a>
  <!--<div data-role="footer" data-position="fixed" data-fullscreen="true">
    <h1>控制中心</h1>
  </div>--> 
</div> 
</body>
</html>
<?php
error_reporting(0);
 $name=$_POST['name'];
$boo=0;
$con=mysqli_connect("localhost","rot","huang110");
      mysqli_query($con,"set name 'utf8'");
      if(!$con)
      {
        echo "error";
      }
      else
      {
        mysqli_select_db($con,"LOT");
        mysqli_query($con,"delete from Node_Information where name='$name'");
        $result=mysqli_query($con,"select * from scene_mode_name");
        while($row=mysqli_fetch_array($result,MYSQLI_NUM))
        {
           mysqli_query($con,"delete from $row[0] where node_name='$name'");
        }
        mysqli_select_db($con,"SETTING");
        $result=mysqli_query($con,"select * from timing");
        while($row=mysqli_fetch_array($result,MYSQLI_NUM))
        {
             $array=explode(',',$row[0]);
             foreach($array as $key=>$value)
             {
               if($value==$name)
               {
                unset($array[$key]);
                 $boo=1;
               }
             }
             if($boo==1)
             {
              echo"aga";
              $boo=0;
              $string=implode(',',$array);
              echo $string;
              mysqli_query($con,"update timing set equ_name='$string' where equ_name='$row[0]'");
             }
        }
        echo "成功删除设备:'$name'";
        mysqli_close($con);
      }
?>
<?php
 error_reporting(0);
 $a=array();

 $con=mysqli_connect("localhost","root","huang110");
     
      if(!$con)
      {
         echo "error";
      }
      else
      {
        mysqli_query($con,"set name 'utf8'");
        mysqli_select_db($con,"LOT");
        $result=mysqli_query($con," select * from Node_Information ");
        while($row = mysqli_fetch_array($result,MYSQLI_NUM))
         {
          $b=array("$row[0]"=>$row[2]);
          $a=$a+$b; 
         }
         echo json_encode($a);
         mysqli_close($con);
      }
?>
<?php
$type=$_POST['type'];
$filename = '../mqtt.txt';
$A=array();
$fh = fopen($filename, "a");
function fun($con,$status,$filename)
{
   $result=mysqli_query($con," select * from Node_Information ");
        while($row = mysqli_fetch_array($result,MYSQLI_NUM))
         {
          mysqli_query($con,"update  Node_Information set status='$status' where name='$row[0]'");
         }
         $data="{$status}:all";
         fwrite( $GLOBALS['fh'], $data."\r\n");
         fclose($GLOBALS['fh']);//使用全局变量 $G['']
         mysqli_close($con);
}
$con=mysqli_connect("localhost","root","huang110");
      mysqli_query($con,"set name 'utf8'");
      if(!$con)
      {
        echo "error";
      }
      else
      {
         mysqli_select_db($con,"LOT");

        if($type==0) //2 open 先关掉所有的
          {
            $status="off";
            //echo 'off_all';
          }
        if($type==1) //3 close 打开所有的
          {
            $status="on";
            //echo 'on_all';
          }
        if($type==0||$type==1)
       fun($con,$status,$filename);
       echo $status;
//---------------------------------------------------------------------------------------------------------------------------
      //  if($type==2||$type==3) 
      //  {
      //        echo $type;
      //        if($type==2)
      //          $table="User_Define_ON";
      //        if($type==3)
      //          $table="User_Define_OFF";

      //    $result=mysqli_query($con," select * from Node_Information ");
      //    while($row = mysqli_fetch_array($result,MYSQLI_NUM))
      //    {
      //          $Result1=mysqli_query($con,"select * from $table where name='$row[0]'");
      //          $i=mysqli_affected_rows($con);
      //          if($i==1)
      //            $b=array("$row[0]"=>"yes");//************
      //          echo $i;
      //          if($row[2]=="on"&&$i==0)
      //            {
      //             mysqli_query($con,"update  Node_Information set status='off' where name='$row[0]'");
      //             $data="{$row[0]}:off";
      //             fwrite($fh, $data."\r\n");
      //            }
      //          if($row[2]=="off"&&$i==1)
      //            {
      //             mysqli_query($con,"update  Node_Information set status='on' where name='$row[0]'");
      //             $data="{$row[0]}:on";
      //             fwrite($fh, $data."\r\n");
      //            } 

      //    }
      //    fclose($fh);
      //    mysqli_close($con);
      // }
//-------------------------------------------------------------------------------------------------------------------- 
      }
?>

<?php 
// 建立客户端的socet连接 
$name=$_POST['name'];
$status=$_POST['status'];


$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP); 
  //连接服务器端socket 
$connection = socket_connect($socket, '192.168.137.1', 8090); 
echo "huang"; 
//要发送到服务端的信息。
$send_data = "{$name}:{$status}";
//客户端去连接服务端并接受服务端返回的数据，如果返回的数据保护not connect就提示不能连接。
        // 将客户的信息写到通道中，传给服务器端 
        if (!socket_write($socket, "$send_data")) { 
            echo "Write failed\n"; 
        } 
?>
<?php
error_reporting(0);
$name=$_POST['name'];
$status=$_POST['status'];
$filename = "../mqtt.txt";

     $con=mysqli_connect("localhost","root","huang110");
     if(!$con)
     {
      echo "error";
     }
     else
     {
     mysqli_query($con,"set name utf-8");
     mysqli_select_db($con,"LOT");
     mysqli_query($con,"update Node_Information set status='$status' where name='$name'");
     mysqli_close($con);
     $data="{$name}:{$status}";
     $fh = fopen($filename, "a");
     fwrite($fh, $data."\r\n");
     fclose($fh);
    }
?>
<?php
error_reporting(0);
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
      switch($type)
     {
       case  'thour':  type_hour($year,$month,$days,$sensor); break;
       case   'tday':   type_day($year,$month,$days,$sensor); break;
       case  'tweek':  type_week($year,$month,$days,$sensor); break;
       case 'tmonth': type_month($year,$month,$days,$sensor); break;
       case  'tyear':  type_year($year,$month,$days,$sensor); break;
     }
      echo json_encode($data);
      mysqli_close($con);
   }
     
?>
<!doctype html>
<html>
  <head>
    <script src="../assets/Chart.js"></script>
    <!-- <link rel="stylesheet" href="assets/jquery.mobile-1.4.5.min.css">
        <script src="assets/jquery.mobile-1.4.5.min.js"></script>
        
<script src="assets/jquery-2.1.4.min.js"></script> -->
<link rel="stylesheet" href="../assets/jquery.mobile-1.3.2.min.css" />
<script src="../assets/jquery-1.9.1.min.js"></script>
<script src="../assets/jquery.mobile-1.3.2.min.js"></script>
<link rel="stylesheet" type="text/css" href="../assets/mobipick.css" />
<script type="text/javascript" src="../assets/xdate.js"></script>
<script type="text/javascript" src="../assets/xdate.i18n.js"></script>
<script type="text/javascript" src="../assets/mobipick.js"></script>


<meta name = "viewport" content = "initial-scale = 1, user-scalable = no,width=device-width">
<meta charset="UTF-8">

<script type="text/javascript">
Date.prototype.Format = function (fmt) { //author: meizz 
    var o = {
        "M+": this.getMonth() + 1, //月份 
        "d+": this.getDate(), //日 
        "h+": this.getHours(), //小时 
        "m+": this.getMinutes(), //分 
        "s+": this.getSeconds(), //秒 
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
        "S": this.getMilliseconds() //毫秒 
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
}
    $(function(){
    var picker = $("#date");
    picker.mobipick();
    var date=new Date().Format('yyyy-MM-dd');

    $(picker).attr("value", date);
    picker.on("change", function() {
        // formatted date, like yyyy-mm-dd              
        var date = $(this).val();
        var A=date.split('-');
        var year=A[0];
        var month=A[1];
        var day=A[2];
        // JavaScript Date object
        var dateObject = $(this).mobipick("option",date);
    });
});

   var myLine ;
   var lineChartData1;
   var defaults;

   window.onload=function()
   {
       myLine = new Chart(document.getElementById("canvas").getContext("2d"));
       Init();
   }
    function Init()
{
 defaults = {            
    //Boolean - If we show the scale above the chart data             
    scaleOverlay : false,  
    //Boolean - If we want to override with a hard coded scale  
    scaleOverride : true,  //是否用硬编码重写y轴网格线 
    //** Required if scaleOverride is true **  
    //Number - The number of steps in a hard coded scale  
    scaleSteps : 15,//y轴刻度的个数       
    //Number - The value jump in the hard coded scale  
    scaleStepWidth : 2,  //y轴每个刻度的宽度     
    // Y 轴的起始值  
    scaleStartValue : 15,  
    // Y/X轴的颜色  
    scaleLineColor : "rgba(0,0,0,.1)",      
    // X,Y轴的宽度  
    scaleLineWidth : 1,  
    // 刻度是否显示标签, 即Y轴上是否显示文字  
    scaleShowLabels : true,    
    // Y轴上的刻度,即文字  
   // scaleLabel : "<%=value%>",  
    scaleLabel: "<%=value/1%>℃",  
    // 字体  
    scaleFontFamily : "'Arial'",   
    // 文字大小  
    scaleFontSize : 12,    
    // 文字样式  
    scaleFontStyle : "normal",  
    // 文字颜色  
    scaleFontColor : "#666",        
    // 是否显示网格  
    scaleShowGridLines : true,  
    // 网格颜色  
    scaleGridLineColor : "rgba(0,0,0,.05)",    
    // 网格宽度  
    scaleGridLineWidth : 3,   
    // 是否使用贝塞尔曲线? 即:线条是否弯曲  
    bezierCurve : true,     
    // 是否显示点数  
    pointDot : true,   
    // 圆点的大小  
    pointDotRadius : 2,    
    // 圆点的笔触宽度, 即:圆点外层白色大小  
    pointDotStrokeWidth : 2,   
    // 数据集行程  
    datasetStroke : true,  
    // 线条的宽度, 即:数据集  
    datasetStrokeWidth : 2,  
    // 是否填充数据集  
    datasetFill : true,  
    // 是否执行动画  
    animation : true,  
    // 动画的时间  
    animationSteps : 120,  
    // 动画的特效  
    animationEasing : "easeOutQuart",  
    // 动画完成时的执行函数  
    onAnimationComplete : null  
}  
       var lineChartData = {
            labels : [0,' ','','','','',6,'','','','','',12,'','','','','',18,'','','','',23],
            datasets : [
                {
                    fillColor : "rgba(151,187,205,0.5)",
                    strokeColor : "rgba(151,187,205,1)",
                    pointColor : "rgba(151,187,205,1)",
                    pointStrokeColor : "#fff",
                    data : []
                }
            ]
            }
              lineChartData1 = {
            labels : [0,'','','','','',6,'','','','','',12,'','','','','',18,'','','','',23],
            datasets : [
                {
                    fillColor : "rgba(151,187,205,0.5)",
                    strokeColor : "rgba(151,187,205,1)",
                    pointColor : "rgba(151,187,205,1)",
                    pointStrokeColor : "#fff",
                    data : []
                }
            ]
            }
            get_data();
    }
function get_data()
{
   var string=$('#date').val();
   var A=string.split('-');
   var sensor=$('#sensor').val();
   var type=$('#type').val();
   var data={sensor:sensor,type:type,year:A[0],month:A[1],days:A[2]};
   $.ajax({
                url:'chart_data.php',
                type:'post',
                data:data,
                dataType:'text',

                success:function( data){
                  
                  if(data=='error')
                  {
                     $('#mysql_error').popup("open");
                  }
                 else {
                   var ajaxobj=eval("("+data+")");
                     lineChartData1.datasets[0].data=[];
                 
                  
                  //alert(ajaxobj.length);
                      for(i=0;i<ajaxobj.length;i++)
                    {
                      lineChartData1.datasets[0].data.push(ajaxobj[i]);
                    }
                   // alert(lineChartData1.datasets[0].data);
                    if(sensor=='hum')
                    {
                       var min=Math.min.apply(null,ajaxobj);
                       var max=Math.max.apply(null,ajaxobj);
                       defaults.scaleLabel="<%=value/1%>%";
                       defaults. scaleSteps=15;//y轴刻度的个数       
                       //Number - The value jump in the hard coded scale  
                       defaults.scaleStepWidth =5; //y轴每个刻度的宽度     
                       // Y 轴的起始值  
                       defaults.scaleStartValue =15;  
                    }
                    if(sensor=='tem')
                    {
                      defaults.scaleLabel="<%=value/1%>℃";  
                    }

                    myLine.Line(lineChartData1,defaults);
                  }
                }
        });
 }
  function show1(value)
  {
                 if(value=='hum')
                    {
                       var min=Math.min.apply(null,ajaxobj);
                       var max=Math.max.apply(null,ajaxobj);
                       defaults.scaleLabel="<%=value/1%>%";
                       defaults. scaleSteps=15;//y轴刻度的个数       
                       //Number - The value jump in the hard coded scale  
                       defaults.scaleStepWidth =5; //y轴每个刻度的宽度     
                       // Y 轴的起始值  
                       defaults.scaleStartValue =15;  
                    }
                    if(value=='tem')
                    {
                      defaults.scaleLabel="<%=value/1%>℃";  
                    }
                    myLine.Line(lineChartData1,defaults);
                    get_data();
  }
    function show(value)
    {
        switch(value)
        {
           case 'thour':lineChartData1.labels=[0,'','','','','',6,'','','','','',12,'','','','','',18,'','','','',23];  //lineChartData1.datasets[0].data=[22,24,25,26,27,28,25,26,25,26,25,25,25,25,27,28,29,25,24,23,22,23,24,25];
           break;
           case 'tday': lineChartData1.labels=[0,'','','','','',6,'','','','','',12,'','','','','',18,'','','','',23,'','','','','','','',31];
                       //lineChartData1.datasets[0].data=[22,24,25,26,27,28,25,26,25,26,25,25,25,25,27,28,29,25,24,23,22,23,24,25,25,15,48,65,45,12,45,15];
                       break;
           case 'tweek':lineChartData1.labels=['一','二','三','四','五'];
                       //lineChartData1.datasets[0].data=[30,30,30,30,30];
                       break;
           case 'tmonth':lineChartData1.labels=[1,2,3,4,5,6,7,8,9,10,11,12];
                        //lineChartData1.datasets[0].data=[29,32,34,23,23,34,23,30,31,34,32,36];
                        break;
          // case 'year':
        }
         myLine.Line(lineChartData1,defaults);
         get_data();
    }

</script>
  </head>
  <body>
  <div data-role="page">
    <div data-role="popup" id="mysql_error" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>异常，数据库没有正常工作。</p>
</div>
  <!-- <div data-role="page" id="datapage">
       <div data-role="header" style="background:#dd393f"  data-position="fixed" data-tap-toggle="false" data-fullscreen="true" >
     <a  data-icon="back" data-ajax="false" data-rel="back" data-transition="turn">返回</a>
     <a href="" data-rel="back" style="background:#dd393f" class="ui-btn-left" data-icon="arrow-l" data-iconpos="notext"></a>  
    <h1 style="color:white">图表中心</h1>
  </div> -->
  <div data-role="header" style="background:#dd393f"  data-position="fixed" data-tap-toggle="false" data-fullscreen="true" >
     <!--<a  data-icon="back" data-ajax="false" data-rel="back" data-transition="turn">返回</a>-->
     <a href="" data-rel="back" style="background:#dd393f" class="ui-btn-left" data-icon="arrow-l" data-iconpos="notext"></a>  
    <h1 style="color:white">数据中心</h1>
  </div>

  <br><br><br>
    <canvas id="canvas" height="300" width="360"></canvas>
  <!-- <script>
// window.onload = function(){
         
//             var current = 0;
//             var h=document.body.clientHeight;
//             var w=document.body.clientWidth;
//             //$('#canvas').attr('height',h);
//             //$('#canvas').attr('width',w);
//             //var current=90;
//             //document.getElementById('canvas').style.transform = 'rotate('+current+'deg)';
//            //alert($('#canvas').attr('height'));
//          document.getElementById('canvas').onclick = function(){
             
//                current = (current+90)%360;
             
//                 this.style.transform = 'rotate('+current+'deg)';
//             }
//         };
  </script> -->
      <ul data-role="listview" >
       <li >
            <fieldset class='ui-grid-a'> 
              <div class='ui-block-a'><input type="text" id="date"  /> </div>   
              <div class='ui-block-b'> 
              <select name="sensor" id="sensor" width="100" onchange="get_data()">
      <option value="tem">温度</option>
      <option value="hum">湿度</option>
      <option value="lit">光照强度</option>
      <option value="pm2.5">PM2.5</option>
    </select>
               </div>   
            </fieldset>
       </li>
    </ul>  
    <!-- <input type="text" id="date" width="50%"/> -->
         <!-- <input class="qfdate" type="text" name="aaaa" value="select date">  -->
       
    <select name="type" id="type" width="100" onchange="show(this.value)">
      <option value="thour">按时显示</option>
      <option value="tday">按天显示</option>
      <option value="tweek">按周显示</option>
      <option value="tmonth">按月显示</option>
      <option value="tyear">按年显示</option>
    </select>
<button  data-theme='b' onclick="get_data();">刷新</button>
</div>
  </body>
</html>
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
    $Result=mysqli_query($con,"select * from Now_Data where id='$id'");
    $i=mysqli_affected_rows($con);
    if($i==1)
    {
      echo 'exist';
    }
    else{
         mysqli_query($con,"insert into Now_Data(id,name,sensor_type,net_type) values('$id','$name','$sensor_type','$net_type')");
         mysqli_query($con,"create table $id(
                                 id varchar(20),
                                 data varchar(20),
                                 year int(5),
                                 month int(5),index index_a(month),
                                 week int(2),index index_b(week),
                                 days int(2),index index_c(days),
                                 hour int(2),index index_d(hour),
                                 minute int(2),index index_e(minute)
                                  );");
    }
   mysqli_close($con);
  }
  
 


?>
<?php
error_reporting(0);
$name=$_POST['name'];

   $con=mysqli_connect("localhost","root","huang110");
   if(!$con) 
   {
      echo "error";
   }
    else
   {
      mysqli_query($con,"set name 'utf8'");
      mysqli_select_db($con,"DATA");
      mysqli_query($con,"delete from Now_Data where name='$name'");
      mysqli_query($con,"drop table '$name' ");
      mysqli_close($con);
   }
       
?>
<?php
error_reporting(0);
   $a=array();
   $con=mysqli_connect("localhost","root","huang110");
   if(!$con)
   {
      echo "error";
   }
   else
   {
     mysqli_query($con,"set name 'utf8'");
     mysqli_select_db($con,"DATA");
     
     $result=mysqli_query($con,"select * from Now_Data");
     while($row=mysqli_fetch_array($result,MYSQLI_NUM))
     {
       
         $b=array("$row[0]"=>$row[2]);
          $a=$a+$b; 
     }
         echo json_encode($a);
         mysqli_close($con);
   }

?>
<!DOCTYPE html>
<html>
<head>
  <title>数据中心</title>
  <meta http-equiv="Content-Type" content="text/html"  charset='utf-8'>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="../assets/jquery.mobile-1.4.5.min.css">
  <script src="../assets/jquery-2.1.4.min.js"></script>
  <script src="../assets/jquery.mobile-1.4.5.min.js"></script>
    <script type="text/javascript">
          var name="";

        $(document).on("pageinit",function(){
            $('li').on("taphold",function(){
           name=$(this).attr('name');
       $('#delete_sensor').popup('open');
              
         });
           setInterval("getdata()","60000");
        });
      function delete_sensor()
      {
         var data={name:name};
         $.ajax({
         url:"delete_sensor.php",
         data:data,
         type:"post",
         dataType:'text',
         success:function(result){
            if(result=='error')
            {
               $('#delete_sensor').popup('close');
               setTimeout(function(){
                 $('#mysql_error').popup('open');
               },500);
            }
            else
            {
              location.reload([true]);
            }
         }
         });
      }
      function add_sensor_2()
      {
        var id=$('#sensor_id').val();
            var name=$('#sensor_name').val();
           var sensor_type=$('#sensor_type').val();//传感器类型
           var net_type=$('#net_type').val();//网络类型
           var data={id:id,name:name,sensor_type:sensor_type,net_type:net_type};

          $.ajax({
              url:"add_sensor.php",
              data:data,
              type:'post',
              dataType:'text',
              success:function(result){
                  if(result=='error')
                  {
                      $('#add_sensor').popup('close');
                        setTimeout(function(){
                 $('#mysql_error').popup('open');
                },500);
                  }
                  else if(result=='exist')
                 {
                  $('#add_sensor').popup('close');
                        setTimeout(function(){
                 $('#sensor_id_exist').popup('open');
                },500);
                 }
                 else
                 {
                        $('#add_sensor').popup('close');
                        setTimeout(function(){
                 $('#add_success').popup('open');
                },500);
                        setTimeout(function(){
                 $('#add_success').popup('close');
                },1500);  
                        setTimeout(function(){
                 location.reload([true]);
                },2000);  
                 }
              }
          });
      }
      function add_sensor()
      {
           var id=$('#sensor_id').val();
           if(id=="")
           {
               $('#add_sensor').popup('close');
               setTimeout(function(){
                 $('#no_id').popup("open");
               },500);
           }
           else
           {
               var name=$('#sensor_name').val();
               if(name=="")
               {
                 $('#add_sensor').popup('close');
               setTimeout(function(){
                 $('#no_name').popup("open");
               },500);

               }
               else{
                     add_sensor_2();   
               }
           }
      }
      function add_sensor_pane()
      {
        $('#add_sensor').popup('open');
      }
      function close_popup()
      {
        $('#delete_sensor').popup('close');
      }
          function getdata()
          {
             $.ajax({
               url:'getdata.php',
               post:'get',
               dataType:'text',
               success:function(result)
               {
                if(result=='error')
                {
                    $('#mysql_error').popup('open');  
                }
               else
                {
                  var ajaxobj=eval("("+result+")");//
                  $(".sensor").each(function(){    
                     var id=$(this).attr('id');
                     $(this).html(ajaxobj[id]);
                   });
               }
              }
             });
          }
    </script>
</head>
<body >
     <div data-role="page" id="pageone">
      <!--*************************************************-->
      <div data-role="popup" id="add_sensor" data-position-to="window" data-overlay-theme="a" data-theme="a" data-dismissible="true"  style="max-width:400px;" class="ui-corner-all" data-position="turn" >
<div data-role="header" data-theme='b' class="ui-corner-bottom ui-content">
<h1> &nbsp; &nbsp; &nbsp;添加传感器 &nbsp; &nbsp; &nbsp;</h1>
</div>
<div data-role="content" data-theme="a"  class="ui-corner-bottom ui-content">
  <input type="text" name="sensor_id" id="sensor_id" placeholder="传感器ID:" style='width:400px;'/>
 <input type="text" name="sensor_name" id="sensor_name" placeholder="传感器名:" style='width:400px;'/>
 <fieldset class="ui-grid-c">
          <div class="ui-block-b">
                <h4>数据类型</h4>
          </div> 
          <div claa="ui-grid-c">
               <select id="sensor_type">
                 <option value="tem">温度</option>
                 <option value="hum">湿度</option>
                 <option value="ill">光照强度</option>
                 <option value="SO2">二氧化硫溶度</option>
                 <option value="CO">一氧化碳溶度</option>
                 <option value="PM2.5">PM2.5值</option>
                 <option value="other">Other</option>
               </select>
          </div>
       </fieldset>
       <fieldset class="ui-grid-c">
          <div class="ui-block-b">
                <h4>传感器类型</h4>
          </div> 
          <div claa="ui-grid-c">
               <select id="net_type">
                 <option value="wifi">WiFi</option>
                 <option value="zigbee">ZigBee</option>
                 <option value="bluetooth">BlueTooth</option>
                 <option value="other">Other</option>
               </select>
          </div>
       </fieldset>
   <a href="#" data-role="button" data-inline="false"  data-theme="b" onclick="add_sensor();">添加</a>
</div>
</div>
<div data-role="popup" id="no_id" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>请输入传感器ID。</p>
</div>
<div data-role="popup" id="no_name" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>请输入传感器名字。</p>
</div>
<div data-role="popup" id="add_success" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>添加成功。</p>
</div>
<div data-role="popup" id="sensor_id_exist" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>传感器ID已存在，请检查是否输入错误。</p>
</div>
<!--******************************************************************************-->
      <div data-role="popup" id="delete_sensor" data-transition="turn" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>你确定要删除该传感器吗？</p>
     <button data-inline="true" onclick="delete_sensor();">确定</button><button data-inline="true" data-theme="b" onclick="close_popup();">取消</button>
</div>
<div data-role="popup" id="mysql_error" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>异常，数据库没有正常工作。</p>
</div>
 <div data-role="header" style="background:#dd393f"  data-position="fixed" data-tap-toggle="false" data-fullscreen="true" >
     <!--<a  data-icon="back" data-ajax="false" data-rel="back" data-transition="turn">返回</a>-->
     <a href="" data-rel="back" style="background:#dd393f" class="ui-btn-left" data-icon="arrow-l" data-iconpos="notext"></a>  
    <h1 style="color:white" id="title">数据中心</h1>

  </div>
    <br/>
    <br/>
    <br/>
         <div data-role="fieldcontain" class="ui-content" id="">

             <ul data-role="listview" data-theme="s" >
       <?php
            $name="";
            $value="";
            $con=mysqli_connect("localhost","root","huang110");
           if(!$con)
            {}
           else
          {
              mysqli_query($con,"set name 'utf8'");
              mysqli_select_db($con,"DATA");
              $result=mysqli_query($con,"select * from Now_Data");
              while($row=mysqli_fetch_array($result,MYSQLI_NUM))
              {
                 echo "<li name=$row[1]>";
                 echo "<fieldset class='ui-grid-d'>";
                 echo "<div class='ui-block-a'></div>";
                 echo "<div class='ui-block-b'>";
                // switch($row[1])
                // {
                //     case 'tem':$name="温度";$value=$row[2].'℃';break;
                //     case 'hum':$name="湿度";$value=$row[2].'%';break;
                //     case 'ill':$name="光照强度";$value=$row[2];break;
                //     case 'pm2.5':$name="PM2.5";$value=$row[2];break;
                // }
                echo " <h style='color:blue'><b>$row[1]</b></h>";
                echo "</div>";
                echo "<div class='ui-block-c'></div>";
                echo "  <div class='ui-block-d'>";
          
                echo " <h class='sensor' id=$row[0] name=$row[1]>$row[2]</h>";
                echo " </fieldset>" ;
                echo "</li>";
              }
            }
                ?>
             </ul> 
         </div>
         <button onclick="add_sensor_pane();">添加</button>
     </div>
</body>
</html>
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

   function sleep(numberMillis) {
   var now = new Date();
   var exitTime = now.getTime() + numberMillis;
   while (true) {
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
        //var data,status;
        var scene_name=$('#scene_name').val();
        if(scene_name=="")
           {
            $("#none_scene_name").popup('open');
          }
        else
        {
        $(".switch").each(function()
        {
            //name.push($(this).attr('name'));
            if(this.selectedIndex==0)
              status="off";
            else status="on";
            var name=$(this).attr('name');
            A.push($(this).attr('name'));
            B.push(status);
                   
        });
        var len=A.length;
        var data={SCENE_NAME:scene_name,num:len,name:A,sta:B};
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
                           case "success":$('#add_success').popup("open"); break;
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
<?php
  error_reporting(0);
  $num=$_POST['num'];
  $name=$_POST['name'];
  $status=$_POST['sta'];
  $scene_name=$_POST['SCENE_NAME'];
  $con=mysqli_connect("localhost","root","huang110");
  mysqli_query($con,"set name 'utf-8'");
  if(!$con)
  {
    echo "error";
  }
  else
  {
     mysqli_select_db($con,"LOT");
     
     mysqli_query($con,"select * from scene_mode_name where name='$scene_name' ");
    $i= mysqli_affected_rows($con);
    if($i==1)
    {
       echo "exist";
    }
    else
    {
     mysqli_query($con,"insert into scene_mode_name(name) values('$scene_name');");
     mysqli_query($con,"create table $scene_name(node_name varchar(20),status varchar(5));");
     for($i=0;$i<$num;$i++)
     {
   
        mysqli_query($con,"insert into $scene_name(node_name,status) values('$name[$i]','$status[$i]');");
     }
     echo "success";
    }
     mysqli_close($con);
  }
?>
<?php
error_reporting(0);
  $scene_name=$_POST['SCENE_NAME'];
  $name=$_POST['name'];
  $status=$_POST['status'];
  $len=$_POST['Len'];
  $con=mysqli_connect("localhost","root","huang110");
  mysqli_query($con,"set name 'utf-8'");
  if(!$con)
  {
       echo "error";
  } 
  else
  {
    mysqli_select_db($con,"LOT");
    for($i=0;$i<$len;$i++)
    {
      mysqli_query($con,"update $scene_name set status='$status[$i]' where node_name='$name[$i]'");
    }
   mysqli_close($con);
  }
?>
<?php
error_reporting(0);
     $name=$_POST['name'];
     $con=mysqli_connect("localhost","root","huang110");
          mysqli_query($con,"set name 'utf-8'");
          if(!$con)
          {
              echo "error";
          }
          else
          {
             mysqli_select_db($con,"LOT");
             mysqli_query($con,"delete from current_scene");
             mysqli_query($con,"insert into current_scene(name) values('$name') ");
             mysqli_close($con);
          }   
?>
<?php
error_reporting(0);
  $name=$_POST['name'];
  $con=mysqli_connect("localhost","rot","huang110");
     if(!$con)
     {
      echo "error";
     }
     else
     {
        mysqli_query($con,"set name 'utf-8'");
        mysqli_select_db($con,"LOT");
        mysqli_query($con,"drop table $name");
        mysqli_query($con,"delete from scene_mode_name where name='$name'");
        $result=mysqli_query($con,"select * from current_scene");
        while($row=mysqli_fetch_array($result,MYSQLI_NUM))
        {
          if($row[0]==$name)
          {
            mysqli_query($con,"update current_scene set name=null where name='$name'");
          }

        }
        mysqli_close($con);
     }

?>
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
    function Init(name)
    {

        var arr = eval("("+name+")");  
         $('#title').html(arr[0]);
         scene_name=arr[0];
        var len=arr.length;
        $(".switch").each(function()
        {
           for(var i =1;i<len;i++)
          {
              if( ($('#'+arr[i]).attr('name')==$(this).attr('name') ))
              {
                 this.selectedIndex=1;
                //$(this).slider("refresh");     //FUCK 
              }     
          }
        });
    }
    function sleep(numberMillis) { 
var now = new Date(); 
var exitTime = now.getTime() + numberMillis; 
while (true) { 
now = new Date(); 
if (now.getTime() > exitTime) 
return; 
} 
}
    function save()
    {
        var N=Array();
        var S=Array();
        $('.switch').each(function()
      {
        if(this.selectedIndex==0)
              status="off";
            else status="on";
            var name=$(this).attr('name');
            N.push($(this).attr('name'));
            S.push(status);
      });
        var len=N.length;
    var data={Len:len,SCENE_NAME:scene_name,name:N,status:S};
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
      $result=mysqli_query($con,"select * from $name");
      while($row=mysqli_fetch_array($result,MYSQLI_NUM))
      {
        if($row[1]=="on")
                array_push($arr,$row[0]);
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
      }
        $str=json_encode($arr);
        echo "<script type='text/javascript'>Init('$str');</script>";
        mysqli_close($con);
    }
?>
</ul>
<button onclick="save();" data-theme='b'>保存更改</button>
</body>

</html>
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
        if(current_scene=="null")
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

                        else{
                        
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
    <h>即将切换到</h><h id="popup_current_scene" style="color:red"></h><h>模式</h>
    <button data-inline="true" onclick="sure_select_scene(1);">确定</button><button data-inline="true" data-theme="b" onclick="sure_select_scene(0)">取消</button>
</div>
<!--*****************************主面板标题栏**********************************************************-->
 <div data-role="header" style="background:#dd393f"  data-position="fixed" data-tap-toggle="false" data-fullscreen="true" >
     <!--<a  data-icon="back" data-ajax="false" data-rel="back" data-transition="turn">返回</a>-->
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
 <!--<div data-role="footer" data-position="fixed" data-fullscreen="true"
   <h1>控制中心</h1>
  </div>--> 
</div> 
</body>
</html>
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
    $(document).on("pageinit",function(){
  var i=1;

 $('a').on("taphold",function(){
      i=0;
    name=$(this).attr('name');
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
          success:function(result){
           if(result=='error')
           {
             $('#delete_scene').popup("close");
              mysql_error();
           }
            else{
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
     <a name='false' href="" data-rel="back" style="background:#dd393f" class="ui-btn-left" data-icon="arrow-l" data-iconpos="notext"></a>  
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
        //echo "<ul id='swipeme' data-role='listview'>";
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
<?php
error_reporting(0);
  $scene=$_POST['scene_name'];
  $filename='../mqtt.txt';
  $fh=fopen($filename,"a");
  $con=mysqli_connect("localhost","root","huang110");
      
       if(!$con)
       {
           echo "error";
       }
       else
       {
           mysqli_query($con,"set name 'utf-8'");
           mysqli_select_db($con,"LOT");
           mysqli_query($con,"delete from current_scene");
           mysqli_query($con,"insert into current_scene(name) values('$scene') ");
           $result=mysqli_query($con,"select * from $scene");
           while($row=mysqli_fetch_array($result,MYSQLI_NUM))
           {
                $result_N=mysqli_query($con,"select * from Node_Information where name='$row[0]'");
                $row_N=mysqli_fetch_array($result_N,MYSQLI_NUM);
                if($row[1]!=$row_N[2])
                {
                  //echo $row_N[0]." ".$row_N[2]."<br>";
                  mysqli_query($con,"update  Node_Information set status='$row[1]' where name='$row[0]'");
                  $data="{$row[0]}:{$row[1]}";
                  fwrite($fh,$data."\r\n");
                }
           }
       fclose($fh);
       mysqli_close($con);
       }
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html"; charset='utf-8'>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="../assets/jquery.mobile-1.4.5.min.css">
<script src="../assets/jquery-2.1.4.min.js"></script>
<script src="../assets/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript">
var a=[];
$(document).on("pageinit",function(){ 
  $('#week_choose').hide();
  $("input[name=map]").click(function(){
       var name=$("input[name=map]:checked").attr("id");
       if(name=="define")
       {
           $('#week_choose').show();
       }
       else
       {
           $('#week_choose').hide();
       }
  });
});

function show()
{
 if($("#equipment_choose").is(":hidden")){
       $("#equipment_choose").show();    //如果元素为隐藏,则将它显现
}else{
      $("#equipment_choose").hide();     //如果元素为显现,则将其隐藏
}

}
function f1()
{
$('#week_choose').hide();
$('#equipment_choose').hide();
$.ajax({
      url:'select_equipment.php',
      type:'get',
     dataType:'text',
       success:function(result)
       {
             var ajaxobj=eval("("+result+")");
             var num=ajaxobj['num'];
             for(i=0;i<num;i++)
             {
                $("select").append("<option  value="+ajaxobj['equipment'][i]+">"+ajaxobj['equipment'][i]+"</option>");
             }

       }  
  });
}
function f2(name)
{
          setTimeout(function(){
         $('#'+name).popup("open");
         },500);

          if(name=='setting_success')
          {
          setTimeout(function(){
        $('#'+name).popup("close");
         },3000);
          setTimeout(function()
          {
window.location.href="Setting_Center.php";
          },4000);
       
        }
}
function first()
{
  if(!$('#set_name').val())
  {
    f2('no_setname');
  }
  else
  {
    var set_name=$('#set_name').val();
    var data={name:set_name};
    $.ajax({
       url:'set_name_check.php',
       data:data,
       type:'post',
       dataType:"text",
       success:function(result)
       {
           if(result=="true")
           {
            second();
           }
           else if(result=="false")
           {
              f2('set_name_exist');
           }

       }

 
    });
  }

}
function second()
{
   var i=0;
   var days_check=0;
   var equ_check=0;
   days=[]; 
   var equ_name=[];
   var set_name=$('#set_name').val();
   var type=$("input[name=map]:checked").attr("id");
    if(type=="define")
    {
         $("input[class='week']:checkbox").each(function(){
             if(this.checked==true)
             {
                     days.push($(this).attr('name'));
                     days_check=1;
             }  

         });     
    }
    $("input[class='equipment']:checkbox").each(function(){
             if(this.checked==true)
             {
                     equ_name.push($(this).attr('name'));
                     equ_check=1;
             }  
         });  
   
    
    if(days_check==0&&type=='define')
    {
       f2('days_none');
       
    }
    else if(equ_check==0)
    {
          f2('equ_none');
    }
    else
    {
    var myDate=new Date();
    var date=myDate.getDate();
    var start_time=$('#start_time').val();
    var start_hour=start_time.split(":")[0];
    var start_minute=start_time.split(":")[1];
    var end_time=$('#end_time').val();
    var end_hour=end_time.split(":")[0];
    var end_minute=end_time.split(":")[1];
    var data={name:set_name,equ_name:equ_name,type:type,s_hour:start_hour,s_minute:start_minute,e_hour:end_hour,e_minute:end_minute,week:days,date:date};
    $.ajax({
       url:'save_setting.php',
       type:'post',
       data:data,
       success:function(result)
       {
           //alert(result);
           if(result=='true')
           {
                 f2('setting_success');
           }
       }
    });
   }

}
</script>
</head>
<body onload="f1();">
<div data-role="page" id="pageone" >


<!-- ***********************************************************-->


   <!--*********************************************************************-->
  <div data-role="popup" id="no_setname" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>请给你的设置命个名吧。</p>
</div>
<!--**************************************************-->
 <div data-role="popup" id="set_name_exist" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>这个名字你已经用过了，换个名字吧。</p>
</div>
<!--**************************************************-->
<div data-role="popup" id="setting_success" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>设置成功。</p>
</div>
<!--**************************************************-->
<div data-role="popup" id="equ_none" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>请至少选择一个设备</p>
</div>
<!-- *************************** -->
<div data-role="popup" id="days_none" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>在自定义模式下，请至少选择一天使设置生效。</p>
</div>

  <div data-role="header" style="background:#dd393f"  data-position="fixed"  data-tap-toggle="false" >
     <!--<a  data-icon="back" data-ajax="false" data-rel="back" data-transition="turn">返回</a>-->
     <a href="Setting_Center.php" data-ajax='false' style="background:#dd393f" class="ui-btn-left" data-icon="arrow-l" data-iconpos="notext"></a>  
    <h1 style="color:white">设置</h1>
    </div>
     <input type="text" name="set_name" id="set_name" placeholder="给你的设置命个名吧:"/>

      <button style="width:30%" onclick="show();">选择设备</button>
     <!--  <select id="equipment">  
       <option selected="selected">请选择</option>    
      </select> -->
<!-- ******************************************************************* -->
      <div style="margin:auto;" id="equipment_choose">
      <fieldset data-role="controlgroup" data-type="horizontal">
        <?php
          $con=mysqli_connect("localhost","root","huang110");
               if(!$con)
               {
               }
               else
               {
                 mysqli_query($con,"set name 'utf-8'");
                 mysqli_select_db($con,"LOT");
                 $result=mysqli_query($con,"select * from Node_Information");
                 while($row=mysqli_fetch_array($result,MYSQLI_NUM))
                 {
                    echo "<input type='checkbox' name=$row[0] id=$row[0] value=$row[0] class='equipment' />";
                    echo "<label for=$row[0]>$row[0]</label>";
                 }
                 mysqli_close($con);
               }
        ?>
      </fieldset>
      </div>
     <!--  **************************************************************** -->
      <label for="start_time"><b>起始时间</b></label>
      <input type="time" id="start_time" value="08:00"/>
      <label ><strong>停止时间</strong></label>
      <input type="time" id="end_time" value="23:00" />
      <label><strong>有效期</strong></label>
     <fieldset data-role="controlgroup" data-type="horizontal">  
            <input type="radio" name="map" id="everyday" value="Map" checked="checked" />  
            <label for="everyday">每天</label>  
            <input type="radio" name="map" id="today" value="Satellite" /> <label for="today">当天</label>
            <input type="radio" name="map" id="define" value="Hybrid"  />  
            <label for="define">自定义</label>  
     </fieldset> 
     <div style="margin:auto;" id="week_choose">
         <fieldset data-role="controlgroup" data-type="horizontal">  
            <input type="checkbox" name="monday" id="week1" value="week1" class="week"  />  
            <label for="week1">一</label>  
            <input type="checkbox" name="tuesday" id="week2" value="week2" class="week" />  
            <label for="week2">二</label>
            <input type="checkbox" name="wednesday" id="week3" value="week3" class="week" />  
            <label for="week3">三</label>    
            <input type="checkbox" name="thursday" id="week4" value="week4" class="week" />  
            <label for="week4">四</label>  
            <input type="checkbox" name="friday" id="week5" value="week5" class="week" />  
            <label for="week5">五</label>
            <input type="checkbox" name="saturday" id="week6" value="week6" class="week" />  
            <label for="week6">六</label>
            <input type="checkbox" name="sunday" id="week7" value="wee7"  class="week"/>  
            <label for="week7">日</label>  
        </fieldset> 
     </div>
<button onclick="first();">保存</button>
      <!-- <div style="margin:auto;width:60%">  
  <div style="float:left;width:40%"><button onclick="fun();">保存</button></div>  
  <div style="float:right;width:40%"><button onclick="add();">添加</button></div>  
  </div> -->
  </div>
</div>
</body>
</html>
<?php
  error_reporting(0);
  $name=$_POST['name'];
  $con=mysqli_connect("localhost","rot","huang110");
  if(!$con)
  {
     echo "error";
  }
  else
  {
    mysqli_query($con,"set name 'utf-8'");
    mysqli_select_db($con,"SETTING");
    mysqli_query($con,"delete from set_name where name='$name'");
    mysqli_query($con,"delete from timing where name='$name'");
    echo 'true';
    mysqli_close($con);
  }
?>
<?php
error_reporting(0);
 $a=array();
 $con=mysqli_connect("localhost","root","huang110");
     
      if(!$con)
      {
         echo "error";
      }
      else
      {
         mysqli_query($con,"set name 'utf8'");
        mysqli_select_db($con,"SETTING");
        $result=mysqli_query($con," select * from set_name ");
        while($row = mysqli_fetch_array($result,MYSQLI_NUM))
         {
          $b=array("$row[0]"=>$row[1]);
          $a=$a+$b; 
         }
         echo json_encode($a);
        mysqli_close($con);
         }
        

?>
<?php
error_reporting(0);
  $name=$_POST['name'];
  $con=mysqli_connect("localhost","root","huang110");
  if(!$con)
  {
    echo "error";
  }
  else
  {
      mysqli_query($con,"set name 'utf-8'");
       mysqli_select_db($con,"SETTING");
       $result=mysqli_query($con,"select * from timing where name='$name'");
       while($row = mysqli_fetch_array($result,MYSQLI_NUM))
       {
          $equ_name=explode(',',$row[0]);
          $shour=$row[2];
          $sminute=$row[3];
          $ehour=$row[4];
          $eminute=$row[5];
          $type=$row[6];
          $days=explode(',',$row[7]);
          $set_name=$row[8];
      }
        $a=array(
       "equ_name"=>$equ_name,
      "shour"=>$shour,
      "sminute"=>$sminute,
       "ehour"=>$ehour,
       "eminute"=>$eminute,
       "type"=>$type,
       "days"=>$days,
       "set_name"=>$set_name
   );
      echo json_encode($a);  
          mysqli_close($con);
  }
 

 
?>
<?php
error_reporting(0);
$type=$_POST['type'];
$s_hour=$_POST['s_hour'];
$s_minute=$_POST['s_minute'];
$e_hour=$_POST['e_hour'];
$e_minute=$_POST['e_minute'];
//$week=$_POST['week'];
$equ_name=$_POST['equ_name'];
$name=$_POST['name'];
$date=$_POST['date'];
$string=implode(',',$equ_name);
$con=mysqli_connect("localhost","rot","huang110");
      mysqli_query($con,"set name 'utf-8'");
      if(!$con)
      {
        echo "error";
      }
      else
      {
          mysqli_select_db($con,"SETTING");
          mysqli_query($con,"select * from set_name where name='$name'");
          $q=mysqli_affected_rows($con);
          if($type=='define')
          {
            $week=$_POST['week'];
            $days=implode(',',$week);   
            if(!$q)  
            mysqli_query($con,"insert into timing(equ_name,shour,sminute,ehour,eminute,type,days,name) values('$string','$s_hour','$s_minute','$e_hour','$e_minute','2','$days','$name')");
            else{
              mysqli_query($con,"update  timing  set equ_name='$string',shour='$s_hour',sminute='$s_minute',ehour='$e_hour',eminute='$e_minute',type='2',days='$days' where name='$name'");
            }
          }
          else
          {
            $i=0;
            if($type=='today')
            {
               $i=1;
            }
             if(!$q)
             mysqli_query($con,"insert into timing(equ_name,date,shour,sminute,ehour,eminute,type,days,name) values('$string','$date','$s_hour','$s_minute','$e_hour','$e_minute','$i','','$name')");
             else
            {
             mysqli_query($con,"update timing  set equ_name='$string',date='$date',shour='$s_hour',sminute='$s_minute',ehour='$e_hour',eminute='$e_minute',type='$i' where name='$name' ");
              }
           }
           if(!$q)
         mysqli_query($con,"insert into set_name(name) values('$name')");
         mysqli_close($con);
         echo 'true';
      }
?>
<?php
 $con=mysqli_connect("localhost","root","huang110");
      mysqli_query($con,"set name 'utf-8'");
      if(!$con)
      {
      }
      else
      {
        $data=Array();
        mysqli_select_db($con,"LOT");
        $num=0;
        $result=mysqli_query($con," select * from Node_Information ");
        while($row = mysqli_fetch_array($result,MYSQLI_NUM))
        {
           array_push($data,$row[0]);
             $num++;
        }
        $DATA=array(
        'num'=>$num,
        'equipment'=>$data
        );
        echo json_encode($DATA);

          mysqli_close($con);
      }
?>
<?php
$name=$_POST['name'];
   $con=mysqli_connect("localhost","root","huang110");
   if(!$con)
   {

   }
   else
   {
     mysqli_query($con,"set name 'utf-8'");
     mysqli_select_db($con,"SETTING");
     mysqli_query($con,"select * from set_name where name='$name'");
     $i=mysqli_affected_rows($con);
     if($i==0)
     {
      echo "true";
     }
     else
     {
      echo "false";
     }
     mysqli_close($con);
   }

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html"; charset='utf-8'>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="../assets/jquery.mobile-1.4.5.min.css">
<script src="../assets/jquery-2.1.4.min.js"></script>
<script src="../assets/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript">
  $(document).on("pageinit",function(){
      $("li").on("swiperight",function(){

          $('#set_information').panel("open");
          var name=$(this).attr("name");
          updata(name);
      });
      //-------------开关操作功能实现---------
$('.switch').on("change",function(){
     var tag = this.selectedIndex;
     var status="off";
     if(tag==1)
         status="on";
     var name = $(this).attr('name');
     var data="name="+name+"&status="+status;//数组对象创建 
     //alert(data); 
          $.ajax({
                         url:'setting_switch.php',
                         type:'post',
                         data:data,
                    dataType:'text',
                         success:function(result)
                         {
                            if(result=='error')
                           {
                                mysql_error();
                                if(tag==1)
                                {
                                   $(".switch").each(function(){
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
                                   $(".switch").each(function(){
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
  });
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
                      $(".switch").each(function(){
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
function updata(name)
{
      var data={name:name};
      $.ajax({
          url:'pane1_information.php',
          data:data,
          type:'post',
          dataType:'text',
          success:function(data)
          {
                 if(data=='error')
                 {
                        mysql_error();
                 }  
                  else{
                 // alert(data);
                   var ajaxobj=eval("("+data+")");
                   //alert(ajaxobj);
                   if(ajaxobj['type']!='2')
                 {
                    $('#week_choose').hide();
                 }
                 else{
                  $('#week_choose').show();
                 }
                    
  $("input[name=map]").click(function(){
       var name=$("input[name=map]:checked").attr("id");
       if(name=="define")
       {
           $('#week_choose').show();
       }
       else
       {
           $('#week_choose').hide();
       }
  });
                  $('#set_name').val(ajaxobj['set_name']);
                  $('#set_name').attr('disabled','disabled');
                  $('#start_time').attr('value',ajaxobj['shour']+":"+ajaxobj['sminute']);
                  $('#end_time').attr('value',ajaxobj['ehour']+":"+ajaxobj['eminute']);
 //***************************************************************************                 
                   var boo=0;
                      $("input[class='equipment']:checkbox").each(function()
                      {
                           for(var i=0;i<ajaxobj['equ_name'].length;i++)
                         { 
                             if($(this).attr('name')==ajaxobj['equ_name'][i])
                             {
                             boo=1;
                             }  
                         }    
                           if(boo==1)
                        {
                            $(this).prop('checked',true).checkboxradio("refresh");
                        }
                           else
                        {
                           $(this).prop('checked',false).checkboxradio("refresh");
                        }
                           boo=0;
                    }); 
//***********************************************************************************
                   //alert(ajaxobj['type']);
                       switch(ajaxobj['type'])
                       {
                         case '0': $("#everyday").prop("checked",true).checkboxradio("refresh");break;
                          case '1': $("#today").prop("checked",true).checkboxradio("refresh");;break;
                          case '2': $("#define").prop("checked",true).checkboxradio("refresh");break;
                       } 
                  $("input[type='radio']").checkboxradio("refresh");
//*****************************************************************************                  
                  if(ajaxobj['type']==2)
                  {
                  $("input[class='week']:checkbox").each(function()
                  {
                                 
                      for(var i=0;i<ajaxobj['days'].length;i++)
                      {
                            var d=$(this).attr('name');
                            var D=ajaxobj['days'][i];
                            if(d==D)
                            {
                                  boo=1;
                            }     
                      }
                         if(boo==1)
                        {
                            $(this).prop('checked',true).checkboxradio("refresh");
                        }
                           else
                        {
                           $(this).prop('checked',false).checkboxradio("refresh");
                        }
                           boo=0;
 
                  });
                }
              }
             }
         });
}
  function show()
{
 if($("#equipment_choose").is(":hidden")){
       $("#equipment_choose").show();    //如果元素为隐藏,则将它显现
}else{
      $("#equipment_choose").hide();     //如果元素为显现,则将其隐藏
}
}
function close_popup(name)
{
  //$('#save_change_sure').popup("close");
  $('#'+name).popup("close");
}
function delete_test()
{
   var name=$('#set_name').val();
   var data={name:name};
   $.ajax({
        url:'delete_test.php',
        data:data,
        type:'post',
        dataType:'text',
       success:function(result)
       {
             if(result=='error')
             {
              $('#delete_sure').popup("close");
              mysql_error();
             }
             if(result=='true')
             {
                 f3();
             }
       }
   });
}
function f3()
{
   setTimeout(function(){
         $('#delete_sure').popup("close");
         },500);
          setTimeout(function(){
         $('#delete_success').popup("open");
         },1000);
            setTimeout(function(){
         $('#delete_success').popup("close");
         },2000);
            setTimeout(function(){
        location.reload([true]);
            },3000);
}
function f2(name)
{
        setTimeout(function(){
         $('#save_change_sure').popup("close");
         },500);
          setTimeout(function(){
         $('#'+name).popup("open");
         },1000);
          if(name=='save_success')
          {
          setTimeout(function(){
        $('#'+name).popup("close");
         },3000);
        }
}
function save_change_sure()
{
   var i=0;
   var days_check=0;
   var equ_check=0;
   var days=[]; 
   var equ_name=[];
   var set_name=$('#set_name').val();
   var type=$("input[name=map]:checked").attr("id");
    if(type=="define")
    {
         $("input[class='week']:checkbox").each(function(){
             if(this.checked==true)
             {
                     days.push($(this).attr('name'));
                     days_check=1;
             }  
         });     
    }
    $("input[class='equipment']:checkbox").each(function(){
             if(this.checked==true)
             {
                     equ_name.push($(this).attr('name'));
                     equ_check=1;
             }  
         });  
   
    var start_time=$('#start_time').val();
    if(start_time=="")
    {
         f2('s_time_none');
    }
    else  if(days_check==0&&type=='define')
    {
       f2('days_none');
    }
    else if(equ_check==0)
    {
          f2('equ_none');
    }
    else
    {
    var start_time=$('#start_time').val();
    var start_hour=start_time.split(":")[0];
    var start_minute=start_time.split(":")[1];
    var end_time=$('#end_time').val();
    var end_hour;
    var end_minute;
    if(end_time!="")
    {
     end_hour=end_time.split(":")[0];
     end_minute=end_time.split(":")[1];
    }
    else
    {
       end_hour="";
       end_minute="";
    }
    var myDate=new Date();
    var date=myDate.getDate();
    var data={name:set_name,equ_name:equ_name,type:type,s_hour:start_hour,s_minute:start_minute,e_hour:end_hour,e_minute:end_minute,week:days,date:date};
     $.ajax({
        url:'save_setting.php',
       type:'post',
       data:data,
       dataType:'text',
       success:function(result)
       {
           if(result=='error')
           {
              $('#save_change_sure').popup('close');
               mysql_error();
           }
           if(result=='true')
           {
                 f2('save_success');
                
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
</head>
<body onload="Init();">
<div data-role="page" id="pageone" >
 <!-- ********************************数据库连接失败弹窗************************************************-->
<div data-role="popup" id="mysql_error" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>异常，数据库没有正常工作。</p>
</div>
  <!--**************************************************-->
<div data-role="popup" id="save_success" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>保存成功。</p>
</div>
  <!--**************************************************-->
<div data-role="popup" id="s_time_none" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>请选择定时任务开始时间。</p>
</div>
  <!--**************************************************-->
<div data-role="popup" id="equ_none" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>请至少选择一个设备</p>
</div>
  <!--**************************************************-->
<div data-role="popup" id="delete_success" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>删除成功</p>
</div>
<!-- *************************** -->
<div data-role="popup" id="days_none" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>在自定义模式下，请至少选择一天使设置生效。</p>
</div>
   <!--********************************确认是否保存更改弹窗************************************************-->
 <div data-role="popup" id="save_change_sure" data-transition="turn" data-position-to="window" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>你确定要保存更改吗</p>
     <button data-inline="true" onclick="save_change_sure();">确定</button><button data-inline="true" data-theme="b" onclick="close_popup('save_change_sure');">取消</button>
</div>
  <!--********************************确认是否删除任务弹窗************************************************-->
 <div data-role="popup" id="delete_sure" data-transition="turn" data-position-to="window" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>你确定要删除该任务吗</p>
     <button data-inline="true" onclick="delete_test();">确定</button><button data-inline="true" data-theme="b" onclick="close_popup('delete_sure');">取消</button>
</div>
  <div data-role="panel" id="set_information">
    <h2>设置信息:</h2>
  
    <input type='text' name='set_name' id="set_name" value=""/>

    <button style="width:60%" onclick="show();">生效设备</button>
<div style="margin:auto;" id="equipment_choose">
      <fieldset data-role="controlgroup" data-type="horizontal">
        <?php
        error_reporting(0);
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
                    echo "<input type='checkbox' name=$row[0] id=$row[0] value=$row[0] class='equipment' />";
                    echo "<label for=$row[0]>$row[0]</label>";
                 }
                 mysqli_close($con);
               }
        ?>
      </fieldset>
      </div>
 <label for="start_time"><b>起始时间</b></label>
      <input type="time" id="start_time" name="start_time" value="08:00"/>
      <label ><strong>停止时间</strong></label>
      <input type="time" id="end_time" value="23:00" />
      <label><strong>有效期</strong></label>
     <fieldset data-role="controlgroup" data-type="horizontal">  
            <input type="radio" name="map"  id="everyday" value="Map" />  
            <label for="everyday">每天</label>  
            <input type="radio" name="map" id="today" value="Satellite" /> 
            <label for="today">当天</label>
            <input type="radio" name="map" id="define" value="Hybrid"  />  
            <label for="define">自定义</label>  
     </fieldset> 
     <div style="margin:auto;" id="week_choose">
         <fieldset data-role="controlgroup" data-type="horizontal">  
            <input type="checkbox" name="monday" id="week1" value="week1" class="week"  />  
            <label for="week1">一</label>  
            <input type="checkbox" name="tuesday" id="week2" value="week2" class="week" />  
            <label for="week2">二</label>
            <input type="checkbox" name="wednesday" id="week3" value="week3" class="week" />  
            <label for="week3">三</label>    
            <input type="checkbox" name="thursday" id="week4" value="week4" class="week" />  
            <label for="week4">四</label>  
            <input type="checkbox" name="friday" id="week5" value="week5" class="week" />  
            <label for="week5">五</label>
            <input type="checkbox" name="saturday" id="week6" value="week6" class="week" />  
            <label for="week6">六</label>
            <input type="checkbox" name="sunday" id="week7" value="wee7"  class="week"/>  
            <label for="week7">日</label>  
        </fieldset> 
     </div>
  <a href="#save_change_sure" data-rel="popup" data-role="button" data-theme="a" >保存更改</a>
 <a href="#delete_sure" data-rel="popup" data-role="button" data-theme="b" >删除任务</a>
  </div>
  <div data-role="header" style="background:#dd393f" data-position="fixed" data-tap-toggle="false"  >
     <!--<a  data-icon="back" data-ajax="false" data-rel="back" data-transition="turn">返回</a>-->
     <a href="../HomePane2.html"  data-ajax="false" style="background:#dd393f" class="ui-btn-left" data-icon="arrow-l" data-iconpos="notext"></a>  
    <h1 style="color:white">定时开启/关闭</h1>
   </div>
      <br>
      <div data-role="filedcontain" class="ui-content">
        <ul data-role="listview" data-theme="s" id="swipeMe"  >
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
        mysqli_select_db($con,"SETTING");
        $result=mysqli_query($con," select * from set_name ");
        while($row = mysqli_fetch_array($result,MYSQLI_NUM))
        {
          echo "<li name=$row[0]>";
          echo "<fieldset class='ui-grid-c'>";
          echo "<div class='ui-block-a'><h2 style='color:green' name=$row[0]>$row[0]</h2></div>";
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
      <br>
      <a href="add_setting.php" data-ajax="false" data-role="button" data-theme="b">添加</a>
  </div>
</body>
</html>

import time,os,pymysql
connecting=""
#*************************
def mysql_con():
  global connecting
  connecting=pymysql.connect(
      host="localhost",
      user="root",
      password="huang110",
      charset="utf8"
    )
  cur=connecting.cursor()
  return cur
#*******************************
fp=open("../mqtt.txt",'a')
#***********************
def status_check(name):
    cur.execute("use LOT")
    cur.execute("select * from Node_Information where name=\"%s\""%name)
    data=cur.fetchall()
    if data[0][2]=='on':
        return 'on'
    else :
        return 'off'
#***************************
def s_time_check(date,row):
    global cur
    global connecting
    print(row[6])
    print(date[3]," ",row[2]," ",date[4]," ",row[3])
    s_h=int(row[2])
    s_m=int(row[3])
    
    if date[3]==s_h and date[4]==s_m:
      if row[4]=="" and row[6]==1:#如果没有输入关闭时间 又是 当天模式 则到达开启时间后关闭该定时任务
        cur.execute("use SETTING")
        cur.execute("update set_name set status='off' where name=\"%s\""%row[8])
        connecting.commit()          
      return 'ontrue'
    else:
       return 'onfalse'
#**************************************************
def e_time_check(date,row):
    global cur
    global connecting
    print(row[6])
    print(date[3]," ",row[4]," ",date[4]," ",row[5])
    e_h=int(row[4])
    e_m=int(row[5])
    if date[3]==e_h and date[4]==e_m:
      if row[6]==1:#到了关闭时间 当天有效模式 则把定时任务关闭
        cur.execute("use SETTING")
        cur.execute("update set_name set status='off' where name=\"%s\""%row[8])
        connecting.commit()
      return 'offtrue'
    else:
      return 'offfalse'
#************************************************   
def f_everyday(row,date):#每天生效模式
    
    global fp
    global cur
    global connecting
    bool=1
    A=row[0].split(',')
    for i in A:
       if status_check(i)=='off' and s_time_check(date,row)=='ontrue':#设备是关的 且开始时间到了
           code=i+":"+"on"                               #则打开设备
           print(code)
           fp.writelines("%s\n"%code)#将指令写入文档中
           fp.flush()
           cur.execute("use LOT")
           cur.execute("update Node_Information set status='on' where name=\"%s\" "%i)#更新设备状态
           connecting.commit()
       if status_check(i)=='on' and e_time_check(date,row)=='offtrue':#设备是开的 且停止时间到了
           code=i+":"+"off"
           print(code)
           fp.writelines("%s\n"%code)
           fp.flush()
           cur.execute("use LOT")
           cur.execute("update Node_Information set status='off' where name=\"%s\" "%i)
           connecting.commit()        
#**********************************************************
def f_today(row,date):#当天生效模式
    day=int(row[1])
    if day==date[2]:
      f_everyday(row,date)
      
#*********************************************************
def f_define(row,date):#自定义模式
     A=row[7].split(',')
     for i in A:
       if (i=='monday' and date[6]==1) or (i=='tuesday' and date[6]==2) or (i=='wednesday'and date[6]==3) or (i=='thursday' and date[6]==4) or (i=='friday' and date[6]==5) or (i=='saturday' and date[6]==6)or (i=='sunday' and date[6]==0):
         f_everyday(row,date)
    
#************************************************************  
def gettime():
    date=time.localtime()
    return date
#**********************************************************
while 1:
  cur=mysql_con()
  cur.execute("use SETTING");
  cur.execute("select * from timing")
  data=cur.fetchall()
  for row in data:
      date=gettime()
      cur.execute("use SETTING");
      cur.execute("select * from set_name where name=\"%s\""%row[8])
      status=cur.fetchall()

      if (status[0][1]=='on'):
          if(row[6]==0):
             f_everyday(row,date)
          if(row[6]==1):
             f_today(row,date)
          if(row[6]==2):
             f_define(row,date)
      time.sleep(2)

  time.sleep(5)  
# date[0] year
# date[1] mon
# date[2] day
# date[3] hour
# date[4] min
# date[6] week
fp.close()
cur.close()
connecting.close()
<?php

   $filename='../mqtt.txt';
   $fh=fopen($filename,"a");
   //设置为亚洲时区
   if(date_default_timezone_get() != "1Asia/Shanghai") 
    {
      date_default_timezone_set("Asia/Shanghai");
    }
 function gettime()
 {
    $showtime=date("Y-m-d H:i:s",time());
    $time=strtotime($showtime);
    $arr['y']=date('Y',$time);//年份
    $arr['m']=date('m',$time);//月份
    $arr['d']=date('d',$time);//日期
    $arr['h']=date('H',$time);//小时
    $arr['i']=date('i',$time);//分钟
    $arr['s']=date('s',$time);//秒
    $arr['w']=date(date('w'));//星期几 数字表示
    return $arr;
 }
//*******************************************************
 function status_check($name)
 {
     $con=$GLOBALS['con'];
     mysqli_select_db($con,"LOT");
     $result=mysqli_query($con,"select * from Node_Information where name='$name'");
     $row=mysqli_fetch_array($result,MYSQLI_NUM);
     return $row[2];
 }
 //******************************************************
 function f_everyday($row,$arr)
 {
  if(($shour==$arr['h'])&&($sminute==$arr['i']))
  {
       $equ_name=explode(',',$row[0]);
       foreach($equ_name as $key=>$value)
       {
          if(status_check($value)=='off')
          {
             $data="{$value}:on";
             fwrite( $GLOBALS['fh'], $data."\r\n");
          }
       }
       echo "ad";
  }
 }  
 //*****************************************************
 function f_today($row,$arr)
 {
    echo $row[6];
 }
 //***************************************************
 function f_define($row,$arr)
 {
   echo $row[6];

 }
 //***************************************************************
   $con=mysqli_connect("localhost","root","huang110");

   if(!$con)
   {

   }
   else
   {
     mysqli_query($con,"set name 'utf-8'");
     mysqli_select_db($con,"SETTING");
     $result=mysqli_query($con,"select * from timing");
  while(1)
  {
      while($row=mysqli_fetch_array($result,MYSQLI_NUM))
      {

        $arr=gettime();
       $result1=mysqli_query($con,"select * from set_name where name='$row[8]'");
       $status=mysqli_fetch_array($result1,MYSQLI_NUM);
       if($status='on')
       {
           switch($row[6])
           {
              case 0: f_everyday($row,$arr);break;
              case 1: f_today($row,$arr);break;
              case 2: f_define($row,$arr);break;
           }
        } 
      }
      sleep(60);
     }
   }
?>
<?php
error_reporting(0);
  $name=$_POST['name'];
  $status=$_POST['status'];
  $con=mysqli_connect("localhost","root","huang110");
  if(!$con)
  {
    echo "error";
  }
  else
  {
    mysqli_query($con,"set name 'utf-8'");
    mysqli_select_db($con,"SETTING"); 
    mysqli_query($con,"update set_name set status='$status' where name='$name'");
    mysqli_close($con);
  }



?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html"  charset='utf-8'>
<link rel="stylesheet" href="assets/jquery.mobile-1.4.5.min.css">
<script src="assets/jquery-2.1.4.min.js"></script>
<script src="assets/jquery.mobile-1.4.5.min.js"></script>
</head>
<body>
   <div data-role="page" data-add-back-btn="true" style="background:url(assets/images/timg.jpg) 50% 0 no-repeat;background-size:cover">
       <!--   <div class="ui-bar-e" data-role="header" style="background:white" data-position="fixed" >
          <h1 style="color:black">功能面板</h1> 
        </div>    -->
        <div data-role="content">
           <fieldset class="ui-grid-a">
             <div class="ui-block-a">
                <a href="CONTROL_CENTER/Control_Center.php" data-ajax="false"><img src="assets/images/11.ico" width="80%" height="80%" /></a>
             </div>
             <div class="ui-block-b">
               <a  href="NOW_DATA/Now_Data.php" data-ajax="false">   <img src="assets/images/22.png" width="80%" height="80%" /></a>
             </div>

              <div class="ui-block-a">
               &nbsp;&nbsp;<a style="color:black">控制中心</a>
             </div>
             <div class="ui-block-b">
                <a style="color:black">&nbsp;&nbsp; 实时数据</a>
             </div>
             
              <div class="ui-block-a">
               <a href="SCENE_MODE/scene_mode_select.php" data-ajax="false">   <img src="assets/images/33.png" width="80%" height="80%" /></a>
             </div>
             <div class="ui-block-b">
                <a href="SCENE_MODE/scene_mode_set.php" data-ajax="false">  <img src="assets/images/44.png" width="80%" height="80%" /></a>
             </div>
             
             <div class="ui-block-a">
               &nbsp;<a style="color:black">情景模式选择</a>
             </div>
             <div class="ui-block-b">
                <a style="color:black">&nbsp;情景模式设定</a>
             </div>

             <div class="ui-block-a">
               <a href="SETTING_CENTER/Setting_Center.php" data-ajax="false">   <img src="assets/images/55.png" width="80%" height="80%" /></a>
             </div>
             <div class="ui-block-b">
               <a href="DATA_CENTER/Data_Center.html" data-ajax="false">  <img src="assets/images/66.png" width="80%" height="80%" /></a>
             </div>

             <div class="ui-block-a">
               &nbsp;&nbsp;<a style="color:black">定时中心</a>
             </div>
             <div class="ui-block-b">
                <a style="color:black">&nbsp;&nbsp;数据中心</a>
             </div>
          </fieldset>

        </div> 
</body>
</html>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html"; charset='utf-8'>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="assets/jquery.mobile-1.4.5.min.css">
<script src="assets/jquery-2.1.4.min.js"></script>
<script src="assets/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript" >
    function login_click()
    {
        var account=$("#account").val();
        if(account=="")
       {
           $("#none_account").popup('open');
       }
        else
       { 
          var password = $("#password").val();
          if(password=="")
          {
           $("#none_password").popup('open');
          }
          else 
          {
             var data="account="+account+"&password="+password; 
             $.ajax
               ({
                   url:'login.php',
                  type:'post',
                  data:data,
              dataType:'text', 
               success:function(data)
                { 
                  if(data=="true")
                    self.location="HomePane2.html";//跳转到HomePane.html页面
                  else 
                    $("#error").popup('open');  
                }
               });
          }
       }
    } 
</script>
</head>
<body>
   <div data-role="page">
<!--      没有输入账号弹窗          -->
<div data-role="popup" id="none_account" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>&nbsp;&nbsp;请输入账号&nbsp;&nbsp;</p>
</div>
<!--     没有输入密码弹窗           -->
<div data-role="popup" id="none_password" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>&nbsp;&nbsp;请输入密码&nbsp;&nbsp;</p>
</div>
<!--密码或账号输入错误-->
<div data-role="popup" id="error" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>&nbsp;&nbsp;账号或密码输入错误&nbsp;&nbsp;</p>
</div>
<!-- 弹窗-->
        <div data-role="header" data-position="fixed">
          <h1>用户登录</h1>
        </div>  
        <div data-role="content">
        <img src="assets/images/3.png" style="width:50%; margin-left: 25%;"/>
        <form action="#" method="post"><br/><br/>
             <input type="text" name="account" id="account" placeholder="账号:"/>
             <input type="password" name="password" id="password" placeholder="密码:"/ >
 <a data-role="button" data-theme="b" id="login" onclick="login_click();">登录</a> 
        </form>
        </div>
  </div> 
</body>
</html>