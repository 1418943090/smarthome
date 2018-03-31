<?php
// function utf8_str_to_unicode($utf8_str) {
//   $unicode = 0;
//   $unicode = (ord($utf8_str[0]) & 0x1F) << 12;
//   $unicode |= (ord($utf8_str[1]) & 0x3F) << 6;
//   $unicode |= (ord($utf8_str[2]) & 0x3F);
//   return dechex($unicode);
// }
//  //   $str="aaaabcdaga";
//  //   $str=preg_replace('|[0-9a-zA-Z]|','',$str);
//  //  if($str=="")
//  //    echo "shgs";
//  // else echo "gshs";
//    //echo strpos($str,'b');
//    $utf8_str="你";
//    echo utf8_str_to_unicode($utf8_str);


//将内容进行UNICODE编码，编码后的内容格式：\u56fe\u7247 （原始：图片）  
// function unicode_encode($name)  
// {  
//     $name = iconv('UTF-8', 'UCS-2', $name);  
//     $len = strlen($name);  
//     $str = '';  
//     for ($i = 0; $i < $len - 1; $i = $i + 2)  
//     {  
//         $c = $name[$i];  
//         $c2 = $name[$i + 1];  
//         if (ord($c) > 0)  
//         {    // 两个字节的文字  
//             $str .= '\u'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);  
//         }  
//         else  
//         {  
//             $str .= $c2;  
//         }  
//     }  
//     return $str;  
// }  
  
// // 将UNICODE编码后的内容进行解码，编码后的内容格式：\u56fe\u7247 （原始：图片）  
// function unicode_decode($name)  
// {  
//     // 转换编码，将Unicode编码转换成可以浏览的utf-8编码  
//     $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';  
//     preg_match_all($pattern, $name, $matches);  
//     if (!empty($matches))  
//     {  
//         $name = '';  
//         for ($j = 0; $j < count($matches[0]); $j++)  
//         {  
//             $str = $matches[0][$j];  
//             if (strpos($str, '\\u') === 0)  
//             {  
//                 $code = base_convert(substr($str, 2, 2), 16, 10);  
//                 $code2 = base_convert(substr($str, 4), 16, 10);  
//                 $c = chr($code).chr($code2);  
//                 $c = iconv('UCS-2', 'UTF-8', $c);  
//                 $name .= $c;  
//             }  
//             else  
//             {  
//                 $name .= $str;  
//             }  
//         }  
//     }  
//     return $name;  
// }  
  
// //测试用例：  
  
// //编码  
// $name = '图啊agree片'; 
// $value=unicode_encode($name);  
// echo $value;
//   echo '<br/>';
// //解码  
// echo unicode_decode($value);
$arr3=array();
 $arr1=array("0"=>"17","1"=>"20","2"=>"36","3"=>"48");  
 $arr2=array("0"=>"17","1"=>"396","3"=>"57");  
 $arr3=array_intersect($arr1,$arr2);  

 echo empty($arr3);     
 foreach($arr3 as $key=>$value)
 {
     echo $key." ".$value.'<br/>';
 }
// $arr=array();
// echo empty($arr);
  
?>