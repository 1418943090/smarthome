<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html"; charset='utf-8'><!--告诉浏览器我的页面内容用的什么字符格式-->
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../assets/jquery.mobile-1.4.5.min.css">
<script src="../assets/jquery-2.1.4.min.js"></script>
<script src="../assets/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript" >
	function show()
{
 if($("#equipment_choose").is(":hidden")){
       $("#equipment_choose").show();    //如果元素为隐藏,则将它显现
}else{
      $("#equipment_choose").hide();     //如果元素为显现,则将其隐藏
}
}
</script>
<style>

</style>
</head>
<body>
	<div data-role="page" id="pageone">    
	<div data-role="header" style="background:#dd393f"  data-position="fixed"  data-tap-toggle="false" >
     <!--<a  data-icon="back" data-ajax="false" data-rel="back" data-transition="turn">返回</a>-->
     <a href="Setting_Center.php" data-ajax='false' style="background:#dd393f" class="ui-btn-left" data-icon="arrow-l" data-iconpos="notext"></a>  
    <h1 style="color:white">添加设定</h1>
    </div>
    <div id='cai'>
       <input type="text" name="set_name" id="set_name" placeholder="给你的设定命个名吧:"/>
       <span>当</span> 
       <select name="sensor" id="sensor" style="width:30%" onchange="get_data()">
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
<span>传感器，数值大于<input type="number" step="0.1" id='num'/></span><span>时</span>
 <button style="width:30%" onclick="show();">设备</button>
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
<div >
	<nobr>
  <h style="float:left">阿辉官方哈偶</h>
   <div style="width:80px;float:left;"id='5'>
      <select id="1" name='1' style="width:80px;float:left"  data-icon="">
      	 <option value='on'>开启</option>
      	 <option value='off'>关闭</option>
      </select>
</div>
</nobr>
</div>

  </div>
	</div>
</body>
</html>
