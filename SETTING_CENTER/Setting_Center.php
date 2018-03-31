<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html"; charset='utf-8'>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="../assets/jquery.mobile-1.4.5.min.css">
<script src="../assets/jquery-2.1.4.min.js"></script>
<script src="../assets/jquery.mobile-1.4.5.min.js"></script>
<style>
tr {
    border-bottom: 1px solid lightgray;
    word-break:break-all;
}
</style>
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
          $.ajax({
                         url:'setting_switch.php',
                         type:'post',
                         data:data,
                    dataType:'text',
                         success:function(result)
                         {
                            if(result=="success")
                            {
                               
                            }
                            else if(result=='equ_null'&&status=='on')
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
                          else if(result=='error')
                           {
                                mysql_error();
                                if(tag==1)
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
                                   $(".switch").each(function()
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
                           else 
                           {
                             var ajaxobj=eval("("+result+")");
                             if(ajaxobj['cai']!="LOVE")
                               equ_clash(ajaxobj,name,tag);
                             else setting_smart_check(ajaxobj,name);
                           }
                         }
                 });
      });
  });
  function setting_smart_check(ajaxobj,name)
  {
     var length  = ajaxobj['inf'].length;
     var str="";
     $("#lingyucai").empty();
     for(var i=0;i<length;i++)
     {
          str="<p><font color='red'>错误:</font>"+i+"设备:<font color='green'><h>"+ajaxobj['inf'][i]['equ']+"</font></h>已经在<font color='blue'>"+ajaxobj['inf'][i]['smart_name']+"</font></h>中设置为智能启动模式。</p> ";
document.getElementById("lingyucai").innerHTML+=str;  
     }
      $('#setting_smart_clash').popup('open');
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
  function equ_clash(ajaxobj,name,tag)
  {
     $('#equ_clash').popup('open');
                           
                             var length=ajaxobj.length;
                             var i=0;
                             var tb = document.getElementById('table');
                             var rowNum=tb.rows.length;
                             for (i=1;i<rowNum;i++)
                              {
                                  tb.deleteRow(i);
                                  rowNum=rowNum-1;
                                  i=i-1;
                              }

                                 for(i=0;i<length;i++)
                               {
                                 var time=ajaxobj[i]['hour']+":"+ajaxobj[i]['minute'];
                                 var type="";
                                 if(ajaxobj[i]['type']==0)
                                 type="每天";
                                 if(ajaxobj[i]['type']==1)
                                 type="当天";
                                 if(ajaxobj[i]['type']==2)
                                   type="自定义";
                                 var trhtml="<tr><td>"+ajaxobj[i]['test']+"</td><td>"+time+"</td><td>"+type+"</td><td>"+ajaxobj[i]['equ']+"</td></tr>";
                                 $('#table').append(trhtml);
                                 }
                                if(tag==1)
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
                                   $(".switch").each(function()
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
                   var ajaxobj=eval("("+data+")");
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
                  $('#start_time').prop('value',ajaxobj['shour']+":"+ajaxobj['sminute']);
                  $('#end_time').prop('value',ajaxobj['ehour']+":"+ajaxobj['eminute']);
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
     var start_hour=start_time.split(":")[0];
    var start_minute=start_time.split(":")[1];
    var end_time=$('#end_time').val();
    if(start_time==""&&end_time=="")
    {
         f2('s_time_none');
    }
    else if(start_time==end_time)
    {
           f2('error_time');
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
           else {
               var ajaxobj=eval("("+result+")");
               if(ajaxobj['cai']!="LOVE")
               {
                   save_change_error(ajaxobj);
               }
               else {
                   smart_equ_clash(ajaxobj);
               }
         
           }
       }
    });
   }
}
function smart_equ_clash(ajaxobj)
{ $('#save_change_sure').popup('close');
   var length  = ajaxobj['inf'].length;
     
     var str="";
     $("#lingyucai").empty();
     for(var i=0;i<length;i++)
     {
          str="<p><font color='red'>错误:</font>"+i+"设备:<font color='green'><h>"+ajaxobj['inf'][i]['equ']+"</font></h>已经在<font color='blue'>"+ajaxobj['inf'][i]['smart_name']+"</font></h>中设置为智能启动模式。</p> ";

document.getElementById("lingyucai").innerHTML+=str;  
     }
      setTimeout(function(){
    $('#setting_smart_clash').popup('open');
  },500);
     
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
function save_change_error(ajaxobj)
{
     $('#save_change_sure').popup('close');
            setTimeout(function(){
            $('#equ_clash').popup('open');
            },1000);
         
            var length=ajaxobj.length;
            var i=0;
            var tb = document.getElementById('table');
            var rowNum=tb.rows.length;
            for (i=1;i<rowNum;i++)
           {
             tb.deleteRow(i);
             rowNum=rowNum-1;
             i=i-1;
           }
            for(i=0;i<length;i++)
           {
            var time=ajaxobj[i]['hour']+":"+ajaxobj[i]['minute'];
            var type="";
            if(ajaxobj[i]['type']==0)
                 type="每天";
            if(ajaxobj[i]['type']==1)
                type="当天";
            if(ajaxobj[i]['type']==2)
                type="自定义";    
            var trhtml="<tr><td>"+ajaxobj[i]['test']+"</td><td>"+time+"</td><td>"+type+"</td><td>"+ajaxobj[i]['equ']+"</td></tr>";
            $('#table').append(trhtml);
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
      <!-- ******************************************************************************-->
<div data-role="popup" id="equ_null" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>可能由于您删除了一些设备，导致设置失效，请重新设置。</p>
</div>
      <!-- ******************************************************************************-->
<div data-role="popup" id="setting_smart_clash" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <div id='lingyucai'></div>
    <!--  <p><font color='red'>错误:</font>设备:<font color='green'><h id='clash_equ'></font></h>已经在<font color='blue'><h id='clash_smart_name'></font></h>
     中设置为智能启动模式。</p> -->
</div>
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
<div data-role="popup" id="time_error" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>请输入完整的时间。</p>
</div>
  <!--**************************************************-->
<div data-role="popup" id="s_time_none" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>请至少填写一个任务时间。</p>
</div>
  <!--**************************************************-->
<div data-role="popup" id="error_time" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     <p>非法设置，无法在同一时间开启-关闭设备。</p>
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
<div data-role="popup" id="equ_clash" data-transition="flow" data-dismissible="false" class=" ui-content" style="max-width: 280px">
     <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">close</a>
     
     <div data-role="content">
      <p>无效设定,尝试在同一时间对设备进行,<font color="red">开启:关闭</font>操作。</p>
      <table border="1" id="table">
        <caption>错误报告</caption>
         <thead>
            <th>冲突任务</th>
            <th>&nbsp;时间&nbsp;</th>
            <th>模式</th>
            <th>&nbsp;&nbsp;设备&nbsp;&nbsp;</th>
         </thead>
      </table>
     </div>
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
	<div data-role="header" style="background:#dd393f" data-position="fixed" data-tap-toggle="false">
     <!--<a  data-icon="back" data-ajax="false" data-rel="back" data-transition="turn">返回</a>-->
     <a href="../HomePane2.html"  data-ajax="false" style="background:#dd393f" class="ui-btn-left" data-icon="arrow-l" data-iconpos="notext"></a>  
    <h1 style="color:white">定时开启/关闭</h1>
   </div>
      <br>
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
        mysqli_select_db($con,"SETTING");
        $result=mysqli_query($con," select * from set_name ");
        while($row = mysqli_fetch_array($result,MYSQLI_NUM))
        {
          echo "<li name=$row[0]>";
          echo "<fieldset class='ui-grid-a'>";
          echo "<div class='ui-block-a'><h2 style='color:green' name=$row[0]>$row[0]</h2></div>";
         // echo "<div class='ui-block-b'></div>";
         // echo "<div class='ui-block-c'></div>";
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
      <a href="add_setting.php" data-ajax="false" data-role="button" data-theme="b">添加</a>
	</div>
</body>
</html>
