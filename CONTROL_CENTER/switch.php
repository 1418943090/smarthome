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