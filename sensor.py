

import paho.mqtt.client as mqtt
import time
import os,pymysql
connecting=""
cur=""
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
def gettime():
    date=time.localtime()
    return date
def on_connect(client, userdata, flags, rc):
    print("Connected with result code "+str(rc))
    client.subscribe('Tem') #client2 publi
   # client.subscribe("position")
def on_message(client, userdata, msg):
    ID="001"
    s=msg.payload.decode("utf8")
    date=gettime()
    global cur
    global connecting
    #print(time.strftime('%Y-%m-%d %H:%M:%S',time.localtime(time.time())))
    #a=eval(s)#将字符串串转为字典类型
    #print(a["name"])
    print(msg.topic+" "+s)
    print(s.split(":")[1])
    print(s.split(":")[3])
    tem=s.split(":")[1]
    hum=s.split(":")[3]
    cur.execute("insert into tem(id,data,year,month,week,days,hour,minute) values(\"%s\",\"%s\",'%d','%d','%d','%d','%d','%d')"%(ID,tem,date[0],date[1],date[6],date[2],date[3],date[4]))
    cur.execute("insert into hum(id,data,year,month,week,days,hour,minute) values(\"%s\",\"%s\",'%d','%d','%d','%d','%d','%d')"%(ID,hum,date[0],date[1],date[6],date[2],date[3],date[4])) 
    cur.execute("update Now_Data set data='%s' where name ='tem'"%tem)
    cur.execute("update Now_Data set data='%s' where name ='hum'"%hum)
    connecting.commit()
def fun():
    client = mqtt.Client()
    global cur
    cur=mysql_con()
    cur.execute("use DATA")
    #连接mqtt服务器
    client.username_pw_set("admin", "password") # 必须设置，否则会返回「Connected with result code 4」
    client.on_connect = on_connect
    client.on_message = on_message

    HOST = "192.168.137.218"
    client.connect(HOST, 1883, 60)
    client.loop_start()
def f():
    print("shshsh")
if __name__=="__main__":   
    print("main")   
    fun()
    f()
