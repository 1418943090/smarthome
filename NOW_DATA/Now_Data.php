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
  var id="";
  var name="";
  $(document).on("pageinit",function()
  {
     $('li').on("taphold",function()
     {
        id=$(this).attr('iid');
        name=$(this).attr('name');
        $('#option').popup('open');
       //***************************************    
     });
    setInterval("getdata()","5000");
  });
  function delete_sensor_popup()
  {
     $('#option').popup('close');
     setTimeout(function(){
       $('#delete_sensor').popup('open');
        },500);
  }
  function show_information()
  {
     $('#option').popup('close');
     var data={name:name};
     $.ajax
     ({
         url:"get_sensor_information.php",
        type:"post",
        data:data,
    dataType:'text',
      success:function(result)
      {
         if(result=='error')
         {
            setTimeout(function(){
               $('#mysql_error').popup('open');  
                },1000);
         }
         else
         {
            $('#qqsensor_id').html(result);
            setTimeout(function(){
            $('#sensor_information').popup('open');  
                },1000);
         }
      }
      });
  }
function save_change_sensorname()
{ 
  var newname=$('#new_sensorname').val();
  if(newname=="")
  {
     $('#error1').html('错误:请输入新的传感器名字。');
  }
  else if(newname.indexOf(" ")>=0)
       {
         $('#error1').html('错误:名字不能含有空格。');
       }
  else {
         var data={id:id,newname:newname};
         $.ajax({
           url:"save_change_sensorname.php",
          type:"post",
          data:data,
      dataType:'text',
       success:function(result)
              {
                if(result=='error')
                {
                  setTimeout(function(){
                  $('#mysql_error').popup('open');  
                  },1000);
                }
                else if(result=="exist")
                {
                   $('#error1').html('错误:传感器名字已存在，请重新输入。');
                }
                else if(result=='success')
                {
                  $('#change_sensorname_popup').popup('close');
                     setTimeout(function(){
                  $('#change_success').popup('open');  
                  },1000);
                  $('#'+name+'h').html(newname);
                     setTimeout(function(){
                  $('#change_success').popup('close');  
                  },2000);
                  setTimeout(function(){
                          location.reload([true]);
                     },3000);
               }
             }
           });
      }
}
function change_sensorname_popup()
{
  $('#option').popup('close');
  $('#error1').html('');
  setTimeout(function(){
  $('#change_sensorname_popup').popup('open');
          },500);
  var data={name:name};
   $.ajax({
        url:"get_sensor_information.php",
       type:"post",
       data:data,
   dataType:'text',
    success:function(result)
            {
               $('#llsensor_id').html(result);
            }
        });
}
 function clear_sensor_data()
 {
   $('#clear_sensor_data').popup('close');
   var data={id:id};
   $.ajax({
       url:'clear_sensor_data.php',
      data:data,
      type:'post',
  dataType:'text',
   success:function(result)
          {
            if(result=='error')
            {
              setTimeout(function(){
              $('#mysql_error').popup('open');  
                 },1000);
            }
            else
            {
               setTimeout(function(){
               $('#clear_success').popup('open');  
               },1000);
               setTimeout(function(){
                  $('#clear_success').popup('close');  
               },2500);
               setTimeout(function(){
                  },3000);
            }
          }
        });
  }
function clear_data_popup()
{
   $('#option').popup('close');
   setTimeout(function(){
   $('#clear_sensor_data').popup('open');
          },500);
}
function option(type)
{
  switch(type)
  {
    case 0:show_information();break;
    case 1:change_sensorname_popup();break;
    case 2:delete_sensor_popup();break;
    case 3:clear_data_popup();break;
  }
}
function delete_sensor()
{
  var data={id:id};
  $.ajax({
      url:"delete_sensor.php",
     data:data,
     type:"post",
 dataType:'text',
  success:function(result)
         {
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
   success:function(result)
          {
            if(result=='error')
            {
              setTimeout(function(){
              $('#mysql_error').popup('open');
                },500);
            }
            else if(result=='idexist')
                 {
                   $('#error').html("错误:设备ID已存在，无法重复添加。");
                 }
                 else if(result=='namexist')
                 {
                    $('#error').html("错误:该传感器名已存在了，请重新命名。");
                 }
                 else if(result=='noexist')
                 {
                   $('#error').html("错误:该设备ID不存在，请检查后重新输入。");
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
    $('#error').html("错误:请输入传感器ID。");
  }
  else
  {
    var name=$('#sensor_name').val();
    if(name=="")
    {
       $('#error').html("错误:请输入传感器名字。");
    }
    else 
    if(name.indexOf(" ")>=0)
    {
       $('#error').html("错误:名字不能含有空格。");
    }
    else
    {
       add_sensor_2();   
    }
  }
}
function add_sensor_pane()
{
   $('#error').html("");
   $('#sensor_name').val("");
   $('#sensor_id').val("");
   $('#add_sensor').popup('open');
}
function close_popup(id)
{
   $('#'+id).popup('close');
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
              $(".sensor").each(function()
              {    
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
  <h id='error' style="color:red"></h>
  <input type="text" name="sensor_id" id="sensor_id" placeholder="传感器ID:" style='width:400px;'/>
  <input type="text" name="sensor_name" id="sensor_name" placeholder="传感器名:" style='width:400px;'/>
  <a href="#" data-role="button" data-inline="false"  data-theme="b" onclick="add_sensor();">添加</a>
</div>
</div>
<!--************************************-->
<div data-role="popup" id="option" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
      <button onclick="option(0);">查看设备ID号</button>
      <button onclick="option(1);">修改传感器名</button>
      <button onclick="option(2);">删除该传感器</button>
      <button onclick="option(3);">清除该传感器数据</button>
</div>
<!--*******************************************************************-->
<div data-role="popup" id="change_sensorname_popup" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
      <span><font color="blue">传感器ID:</font><span id="llsensor_id"></span></span>
      <h id='error1' style="color:red"></h>
      <input type="text" name="new_sensorname" id="new_sensorname" placeholder="新传感器名:"/>
      <button data-theme="b" onclick="save_change_sensorname();">保存</button>
</div>
<!--*********************************************-->
<div data-role="popup" id="sensor_information" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <span><font color="blue">传感器ID:</font><span id="qqsensor_id"></span></span>
</div>
<!--*********************************************-->
<div data-role="popup" id="clear_success" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>清除数据成功。</p>
</div>
<div data-role="popup" id="add_success" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>添加成功。</p>
</div>
<div data-role="popup" id="change_success" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>修改成功。</p>
</div>
<!--******************************************************************************-->
      <div data-role="popup" id="clear_sensor_data" data-transition="turn" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>你确定要清除该传感器数据吗？</p>
     &nbsp;&nbsp;<button data-inline="true" onclick="clear_sensor_data();">确定</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button data-inline="true" data-theme="b" onclick="close_popup('clear_sensor_data');">取消</button>
</div>
<!--******************************************************************************-->
      <div data-role="popup" id="delete_sensor" data-transition="turn" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>你确定要删除该传感器吗？</p>
     <button data-inline="true" onclick="delete_sensor();">确定</button>&nbsp;&nbsp;&nbsp;&nbsp;<button data-inline="true" data-theme="b" onclick="close_popup('delete_sensor');">取消</button>
</div>
<div data-role="popup" id="mysql_error" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>异常，数据库没有正常工作。</p>
</div>
 <div data-role="header" style="background:#dd393f"  data-position="fixed" data-tap-toggle="false" data-fullscreen="true" >
     <!--<a  data-icon="back" data-ajax="false" data-rel="back" data-transition="turn">返回</a>-->
     <a href="../HomePane2.html"  style="background:#dd393f" class="ui-btn-left" data-icon="arrow-l" data-iconpos="notext"></a>  
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
               echo "<li name=$row[1] iid=$row[0]>";
               echo "<fieldset class='ui-grid-d'>";
               echo "<div class='ui-block-a'></div>";
               echo "<div class='ui-block-b'>";
               $H=$row[1].'h';
               echo " <b><h style='color:blue'><span id=$H >$row[1]</span></h></b>";
               echo "</div>";
               echo "<div class='ui-block-c'></div>";
               echo "  <div class='ui-block-d'>";
               $type=$row[3];
               switch($type)
               {
                  case 'tem': $val=$row[2].'℃';break;
                  case 'hum': $val=$row[2].'%';break;
                  case 'ill': $val=$row[2].'lx';break;
                  default: $val=$row[2];break;

               }
               echo " <h class='sensor' id=$row[0] name=$row[1]>$val</h>";
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