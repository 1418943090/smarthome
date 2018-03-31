<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html"; charset='utf-8'><!--告诉浏览器我的页面内容用的什么字符格式-->
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../assets/jquery.mobile-1.4.5.min.css">
<script src="../assets/jquery-2.1.4.min.js"></script>
<script src="../assets/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript" >
	var name,date;
	var equ_name=[];
  var count=0;
	$(document).on("pageinit",function(){
    $('.switch').on("change",function(){
      var tag = this.selectedIndex;
     var status="off";
     if(tag==1)
         status="on";
     name = $(this).attr('name');
     var data="name="+name+"&status="+status;//数组对象创建 
        $.ajax({
                         url:'smart_switch.php',
                         type:'post',
                         data:data,
                    dataType:'text',
                         success:function(result)
                         {
                        
                          
                         	if(result=="equ_null"&&status=='on')
                         	{
                             $('#equ_null').popup('open');
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
                          if(result=='error')
                          {
                            mysql_error();
                          }
                          else if(result=='success')
                          {}
                          else {
                           
                             var ajaxobj=eval("("+result+")");
                             if(ajaxobj['cai']=="LOVE")
                             {
                                 setting_clash_popup(ajaxobj,name,0);
                               }
                             else
                             {
                                 smart_clash_popup(ajaxobj,name,0);
                           }
                          }
                         }
                });
    });
    $("li").on("swiperight",function(){
       $('#pane1').panel("open");
        name=$(this).attr("name");
 
       var data={name:name};
        $.ajax({
            url:'pane_information.php',
            data:data,
            type:'post',
            dataType:'text',
            success:function(data)
            {
                    if(data=='error')
                 {
                        mysql_error();
                 }  
                 else
                 {
                      var ajaxobj=eval("("+data+")");
                     // alert(ajaxobj['sensor']);
                      $('#pane_sensor').html(ajaxobj['sensor']);
                      if(ajaxobj['compare']=='less')
                        {
                        	$('#pane_compare').html("小于");
                        }               
                        else{
                        	$('#pane_compare').html("大于");
                        }   

                        $('#pane_num').val(ajaxobj['value']);

                        if(ajaxobj['operate']=='on')
                        {
                        	$('#pane_operate').html("开启");
                        }
                        else{
                        	$('#pane_operate').html("关闭");
                        }
                var boo=0;
                      $("input[class='equipment2']:checkbox").each(function()
                      {
                           for(var i=0;i<ajaxobj['equ'].length;i++)
                         { 
                             if($(this).attr('name')==ajaxobj['equ'][i])
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
       });
     });
	});
function setting_clash_popup(ajaxobj,name,yu)
{
    var length=ajaxobj['inf'].length;
                                   var str="";
                                   $("#lingyucai").empty();
                                   for(var i=0;i<length;i++)
                                   {
                                     str="<p><font color='red'>错误:</font>"+i+"设备:<font color='green'><h>"+ajaxobj['inf'][i]['equ']+"</font></h>已经在<font color='blue'>"+ajaxobj['inf'][i]['setting_name']+"</font></h>中设置为定时启动模式。</p> ";
                                     document.getElementById("lingyucai").innerHTML+=str;  
                                  }
                                     $('#setting_equ_clash').popup('open');
                                     if(yu==0)
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
}
function smart_clash_popup(ajaxobj,name,yu)
{
    var length=ajaxobj['inf'].length;
                                   var str="";
                                   $("#lingyucai1").empty();
                                   for(var i=0;i<length;i++)
                                   {
                                     var C=i+1;
                                     str="<p>"+C+":设备:<font color='green'><h>"+ajaxobj['inf'][i]['equ']+"</font></h>已经在<font color='blue'>"+ajaxobj['inf'][i]['smart_name']+"</font></h>中设置为智能启动模式。</p> ";
                                     document.getElementById("lingyucai1").innerHTML+=str;  
                                  }
                                     $('#smart_equ_clash').popup('open');
                                   
                                     if(yu==0)
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
                             
}
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
	function f(name)
  {
  	$('#popup_sensor').popup('close');
  	$('#pane_sensor').html(name);
  }
function f1(name)
  {
  	$('#popup_compare').popup('close');
  	$('#pane_compare').html(name);
  }
  function f2(name)
  { 
  	$('#popup_operate').popup('close');
  	$('#pane_operate').html(name);
  }

	function popup_sensor()
	{
		$('#popup_sensor').popup('open');
	}
	function popup_compare()
	{
		$('#popup_compare').popup('open');
	}
	function popup_operate()
	{
		$('#popup_operate').popup('open');
	}
//*******************************************
	function first_step()
	{
   $('#error1').html("");
   $('#name').val("");
   $('#first_step').popup('open');
	}
//**************************************
   function second_step()
   {
   	 name=$('#name').val();
   	if(name=='')
   	 {
    $('#error1').html("错误：请命个名");
   	}
    else if(name.indexOf(" ")>=0)
    {
      $('#error1').html("错误:名字不能含有空格。");
    }
  else
  {
  	 var DATA={name:name};
     $.ajax(
     {
        url:'name_check.php',
        data:DATA,
        type:'post',
        dataType:'text',
        success:function(result)
        {
        	if(result=="nameexist")
        	{
            $('#error1').html("错误:该名字已存在");
          }
          else if(result=="")
          {
            $('#first_step').popup('close');
            setTimeout(function(){
            $('#second_step').popup('open');
            },800);  
            }
            else
	        {
	        	mysql_error();
	        }	
        } 
     });
    }
   }
  //**************************************************
    function third_step()
   {
    $('#second_step').popup('close');
       setTimeout(function(){
    $('#third_step').popup('open');
       },800);
   }
//************************************************
  function forth_step()
  {
    date=$('#num').val();
    if(date=='')
    {
       $('#error2').html("错误：请输入阈值"); 
    }
    else
    {
       $('#third_step').popup('close');
         setTimeout(function(){
       $('#forth_step').popup('open');
         },800);
    }
  }
  //*******************************
  function finish()
  {
    count++;
    var equ_check=0;
     $("input[class='equipment']:checkbox").each(function(){
             if(this.checked==true)
             {
                     equ_name.push($(this).attr('name'));
                     equ_check=1;
             }  
         });  
     if(equ_check==0)
     {
      $('#error3').html("错误：请至少选择一个设备。")
     }
     else
     {
       if(count==1)
     	 {
        save_add();
       }
     }
  }
  //*******************************
  function save_add()
  {

     var sensor=$('#sensor').val();
     var compare=$('#compare').val();
     var operate=$('#operate').val();
     var DATA={name:name,sensor:sensor,compare:compare,date:date,operate:operate,equ_name:equ_name};
     $.ajax(
     {
        url:'save_add.php',
        data:DATA,
        type:'post',
        dataType:'text',
        success:function(result)
        {
        	if(result=="")
        	{
                $('#forth_step').popup('close');
              setTimeout(function(){
                $('#add_success').popup('open');
           },800);
                setTimeout(function(){
                $('#add_success').popup('close');
           },1800);

                setTimeout(function(){
                location.reload([true]);
           },2500);
           }
           else
           {
           	mysql_error();
           }
           count=0;
        }
     });
  }
function save_change()
{
  var sensor=$('#pane_sensor').html();
  var num=$('#pane_num').val();
  var operate=$('#pane_operate').html();
  var compare=$('#pane_compare').html();
  if(compare=="小于")
  	compare='less';
  else compare='more';
  if(operate=='开启')
    operate='on';
  else operate='off';
   var equ_check=0;
   equ_name=[];
     $("input[class='equipment2']:checkbox").each(function(){
             if(this.checked==true)
             {
                     equ_name.push($(this).attr('name'));
                     equ_check=1;
             }  
         });  
     if(equ_check==0)
     {
       $('#no_equ').popup('open');
     }
    else
    {
         var DATA={name:name,sensor:sensor,compare:compare,date:num,operate:operate,equ_name:equ_name};
        
     $.ajax(
     {
        url:'change_setting.php',
        data:DATA,
        type:'post',
        dataType:'text',
        success:function(result)
        {
       
        	  if(result=="")
        	  {
               $('#change_success').popup('open');
               setTimeout(function(){

                $('#change_success').popup('close');
               },1500);
            }
           if(result=='error')
            {
            	mysql_error();
            }
            else { 
                    var ajaxobj=eval("("+result+")");
                    if(ajaxobj['cai']=="LOVE")
                    {
                      setting_clash_popup(ajaxobj,name,1);
                    }
                    else
                    {
                     smart_clash_popup(ajaxobj,name,1);
                  }
            }
        }
     });
    }
}
function del_sure()
{
var DATA={name:name};
   $.ajax(
    {
        url:'del_setting.php',
        data:DATA,
        type:'post',
        dataType:'text',
        success:function(result)
        {
            if(result=='ok')
            {
            	 location.reload([true]); 
            }
            else
            {
               mysql_error();
            }
        }
    });
}
function del_cancel()
{
  $('#delete').popup('close');
}
function del()
{
   $('#delete').popup('open');
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
   <div data-role="page" id="pageone">
   	<div data-role="popup" id="delete"  data-transition="turn" data-position-to="window" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>你确定要删除该设定吗</p>
     <button data-inline="true" onclick="del_sure();">确定</button><button data-inline="true" data-theme="b" onclick="del_cancel();">取消</button>
</div>
   	 <!-- ********************************数据库连接失败弹窗************************************************-->
<div data-role="popup" id="mysql_error" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>异常，数据库没有正常工作。</p>
</div>
<div data-role="popup" id="setting_equ_clash" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
    <div id='lingyucai'></div>
</div>
     <!-- ******************************************************************************-->
<div data-role="popup" id="smart_equ_clash" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p><font color='red'>错误:</font>设备不能重复被设置为智能启动模式！</p>
     <div id="lingyucai1"></div>
    
</div>
     <!-- ******************************************************************************-->
<div data-role="popup" id="equ_null" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>可能由于您删除了一些设备，导致设置失效，请重新设置。</p>
</div>
   	<div data-role="panel" id="pane1">
    <h1>智能中心:</h1>
    <h>当传感器</h>
    <label  id="pane_sensor" style="color:blue" onclick="popup_sensor();"></label>
    <span>数值</span><span><label  style="color:blue" id='pane_compare' onclick="popup_compare();">大于</label> </span>
    <input type="number" step="0.1" id='pane_num'/>
    <label  id="pane_operate" style="color:blue" onclick="popup_operate();">开启</label>
    <div style="margin:auto;" id="equipment_choose2">
     <fieldset data-role="controlgroup" data-type="horizontal">
        <?php
        error_reporting(0);
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
                 	$id=$row[0].'2';
                    echo "<input type='checkbox' name=$row[0] id=$id value=$row[0] class='equipment2' />";
                    echo "<label for=$id>$row[0]</label>";
                 }
                 mysqli_close($con);
               }
        ?>
      </fieldset>
</div>
<button onclick="del()" data-theme='b'>删除设定</button>
<button onclick="save_change();">保存更改</button>
   	</div>
   	 <div data-role="popup" id="popup_sensor" data-transition="turn" data-position-to="window" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>  
<?php
   $con=mysqli_connect("localhost","root","huang110");
   if(!$con)
   {
   }
   else
   {
       mysqli_query($con,"set name 'utf8'");
       mysqli_select_db($con,"DATA");
       $result=mysqli_query($con,"select * from Now_Data");
       while($row=mysqli_fetch_array($result,MYSQLI_NUM))
       {
         //echo   "<option value=$row[1]>$row[1]</option>";
       	echo "<button name=$row[1] onclick=f('$row[1]');>$row[1]</button>";
       }
   }
?>
      </div>
       <div data-role="popup" id="popup_operate" data-transition="turn" data-position-to="window" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <button onclick="f2('关闭');">关闭</button>
      <button onclick="f2('开启');">开启</button>
      </div>
      	 <div data-role="popup" id="popup_compare" data-transition="turn" data-position-to="window" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <button onclick="f1('大于');">大于</button>
      <button onclick="f1('小于');">小于</button>
      </div>
         <div data-role="popup" id="no_equ" data-transition="turn" data-position-to="window" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
    <h>请至少选择一个设备</h>
      </div>
    <!--******************************************************************-->
      <div data-role="popup" id="change_success" data-transition="turn" data-position-to="window" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
    <h>修改成功。</h>
      </div>
    <!--******************************************************************-->
   	<!--******************************************************************-->
   	 <div data-role="popup" id="add_success" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>添加成功</p>
</div>
    <div data-role="popup" id="no_name" data-transition="turn" data-position-to="window" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
      <h>请命个名。</h>
      </div>
   	 	<!--*****************************************************************************-->
       <div data-role="popup" id="first_step" data-transition="turn" data-position-to="window" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
    
    <h><font size="5">第一步:</font>命名 </h>
   <input type="text" name="name" id='name' />
       <br/>
    <ul data-role="listview" data-theme="s" id="swipeMe" >
      <li>
      	<fieldset class='ui-grid-a'>
      		<div class="ui-block-a">
               <h id='error1' style='color:red'></h>
      	    </div>
      		<div class='ui-block-b'>
      			<label align="right" style="color:blue" onclick="second_step();">下一步</label>
      		</div>
      	</fieldset>
      </li>
    </ul>
      </div>
<!--*****************************************************************************-->
   	<!--*****************************************************************************-->
       <div data-role="popup" id="second_step" data-transition="turn" data-position-to="window" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
    
    <h><font size="5">第二步:</font>选择传感器 </h>
   
     <select name="sensor" id="sensor">
<?php
   $con=mysqli_connect("localhost","root","huang110");
   if(!$con)
   {
   }
   else
   {
       mysqli_query($con,"set name 'utf8'");
       mysqli_select_db($con,"DATA");
       $result=mysqli_query($con,"select * from Now_Data");
       while($row=mysqli_fetch_array($result,MYSQLI_NUM))
       {
         echo   "<option value=$row[1]>$row[1]</option>";
       }
   }
?>
    </select>  
   <label  align="right"  id="lab1" style="color:blue" onclick="third_step();">下一步</label>
      </div>
<!--*****************************************************************************-->
	<!--*****************************************************************************-->
       <div data-role="popup" id="third_step" data-transition="turn" data-position-to="window" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
    
    <h><font size="5">第三步:</font><font size='4'>设定阈值</font> </h><br/>
     <h>当传感器数值</h>
     <select  id='compare' name='compare'>
     	<option value="less">小于</option>
     	<option value="more">大于</option>
     </select>
     <input type="number" step="0.1" id='num'/>
   <br/>
    <ul data-role="listview" data-theme="s" id="swipeMe" >
      <li>
      	<fieldset class='ui-grid-a'>
      		<div class="ui-block-a">
               <h id='error2' style='color:red'></h>
      	    </div>
      		<div class='ui-block-b'>
      			<label align="right" style="color:blue" onclick="forth_step();">下一步</label>
      		</div>
      	</fieldset>
      </li>
     
    </ul>
      </div>
<!--*****************************************************************************-->
   <div data-role="popup" id="forth_step" data-transition="turn" data-position-to="window" data-dismissible="false" class=" ui-content" style="max-width: 320px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
    
    <h><font size="5">第四步:</font><font size='4'>操作设定</font> </h><br/>
    
     <select  id='operate' name='operate'>
     	<option value="on">开启</option>
     	<option value="off">关闭</option>
     </select>
    
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
   <br/>
    <ul data-role="listview" data-theme="s" id="swipeMe" >
      <li>
      	<fieldset class='ui-grid-a'>
      		<div class="ui-block-a">
               <h id='error3' style='color:red'></h>
      	    </div>
      		<div class='ui-block-b'>
      			<label align="right" style="color:blue" onclick="finish();">完成</label>
      		</div>
      	</fieldset>
      </li>
     
    </ul>
      </div>
<!--*****************************************************************************-->

        <div data-role="header" style="background:#dd393f"  data-position="fixed" data-tap-toggle="false" >
     <!--<a  data-icon="back" data-ajax="false" data-rel="back" data-transition="turn">返回</a>-->
        <a href="../HomePane2.html"  style="background:#dd393f" class="ui-btn-left" data-icon="arrow-l" data-iconpos="notext"></a>  
        <h1 style="color:white">智能中心</h1>
         </div>
     
      <div data-role="filedcontain" class="ui-content">
        <ul data-role="listview" data-theme="s" id="swipeMe">
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
        $result=mysqli_query($con," select * from smart");
        while($row = mysqli_fetch_array($result,MYSQLI_NUM))
        {
          echo "<li name=$row[0]>";
          echo "<fieldset class='ui-grid-a'>";
          echo "<div class='ui-block-a'><h2 style='color:green' name=$row[0]>$row[0]</h2></div>";
          echo "<div class='ui-block-b'> ";
          echo "<div style='float:right'>";
          echo "<select class='switch' id=$row[0] name=$row[0]  data-role='slider' >";
          echo "<option value='off'>on</option>   ";
          echo "<option value='on'>off</option> ";
          echo "</select>";
          echo "</div>";
          echo "</div>";
          echo "</fieldset>";
          echo "</li>";
         }
          mysqli_close($con);
         }
?>
</ul>
      <br>
      <button onclick="first_step();" data-theme='b'>添加</button>
   </div>
</body>
</html>
