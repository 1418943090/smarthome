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
           alert(result);
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
    <a href="Setting_Center.php" data-ajax='false' style="background:#dd393f" class="ui-btn-left" data-icon="arrow-l" data-iconpos="notext"></a>  
    <h1 style="color:white">设置</h1>
    </div>
    <input type="text" name="set_name" id="set_name" placeholder="给你的设置命个名吧:"/>
    <button style="width:30%" onclick="show();">选择设备</button>
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
</div>
</div>
</body>
</html>
