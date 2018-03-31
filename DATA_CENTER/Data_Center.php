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
                 
                   sensor=ajaxobj['sensor_type'];
                  
                      for(i=0;i<ajaxobj['data'].length;i++)
                    {
                      lineChartData1.datasets[0].data.push(ajaxobj['data'][i]);
                    }
                
                    
                   switch(sensor)
                   {
                      case 'tem': defaults.scaleLabel="<%=value/1%>℃";break;
                      case 'hum': defaults.scaleLabel="<%=value/1%>%";break;  
                      case 'ill': defaults.scaleLabel="<%=value/1%>Lx";break;  
                      case 'SO2': defaults.scaleLabel="<%=value/1%>%";break;  
                      case 'CO':  defaults.scaleLabel="<%=value/1%>%";break;  
                      case 'PM25':  defaults.scaleLabel="<%=value/1%>%";break;  
                      case  'other': defaults.scaleLabel="<%=value/1%>%";break;  
                      default: defaults.scaleLabel="<%=value/1%>%";break; 

                   }
                   
                       var min=Math.min.apply(null,ajaxobj['data']);
                       var max=Math.max.apply(null,ajaxobj['data']);
                       
                       defaults. scaleSteps=15;//y轴刻度的个数       
                       //Number - The value jump in the hard coded scale  
                       defaults.scaleStepWidth =parseInt((max/0.7)/15); //y轴每个刻度的宽度     
                       // Y 轴的起始值  
                       defaults.scaleStartValue =parseInt((max/0.7)/15);  
                    
                  
                    myLine.Line(lineChartData1,defaults);
                  }
                }
        });
 }
  function show1(value)
  {
                 if(value=='hum')
                    {
                       var min=Math.min.apply(null,ajaxobj['data']);
                       var max=Math.max.apply(null,ajaxobj['data']);
                     
                       defaults. scaleSteps=15;//y轴刻度的个数       
                       //Number - The value jump in the hard coded scale  
                       defaults.scaleStepWidth =5; //y轴每个刻度的宽度     
                       // Y 轴的起始值  
                       defaults.scaleStartValue =(max+0.2*max)/15;  
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
           case 'tyear': lineChartData1.labels=[2015,2016,2017];
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
     <a href="../HomePane2.html"  style="background:#dd393f" class="ui-btn-left" data-icon="arrow-l" data-iconpos="notext"></a>  
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
