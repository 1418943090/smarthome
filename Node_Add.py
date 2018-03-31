

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
    client.subscribe('Equipment') #client2 publi
    client.subscribe('Sensor')
   # client.subscribe("position")
def Add_Equipment(cur,connecting,s):
    cue.execute("use LOT")
    ID=s.split(":")[0]
    Net_Type=s.split(":")[1]
    cur.execute("insert into equ(id,net_type) values('%s','%s')"%(ID,Net_Type))
    connecting.commit()
def Add_Sensor(cur,connecting,s):
    cur.execute("use DATA")
    ID=s.split(":")[0]
    Net_Type=s.split(":")[1]
    Sensor_Type=s.split(":")[2]
    cur.execute("insert into sensor(id,sensor_type,net_type) values('%s','%s','%s') "%(ID,Sensor_Type,Net_Type))
    connecting.commit()
    
def on_message(client, userdata, msg):
    s=msg.payload.decode("utf8")
    global cur
    global connecting
    if msg.topic=='Equipment':
        Add_Equipment(cur,connecting,s)
    if msg.topic=='Sensor':
        Add_Sensor(cur,connecting,s)
    #print(time.strftime('%Y-%m-%d %H:%M:%S',time.localtime(time.time())))
    #a=eval(s)#将字符串串转为字典类型
    #print(a["name"])
def fun():
    client = mqtt.Client()
    global cur
    cur=mysql_con()
   
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
    #date[0]  year
    #date[1]  month
    #date[2]  days
    #date[3]  hour
    #date[4]  minute
    #date[5]  second
