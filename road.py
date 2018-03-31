import os
import paho.mqtt.client as mqtt
import time
import pymysql
#**********************************
connecting=pymysql.connect(
        host="localhost",
        user="root",
        password="huang110",
        db="LOT",
        charset="utf8"
        )
cur=connecting.cursor()
#*******************************
client=mqtt.Client()
f=open('mqtt.txt')
def on_connect(client, userdata, flags, rc):
    print("Connected with result code "+str(rc))
def mqtt_connect():
    client.username_pw_set("admin", "password") # 必须设置，否则会返回「Connected with result code 4」
    client.on_connect = on_connect
    host_name = "192.168.137.218"
    client.connect(host_name, 1883, 60)
    client.loop_start()   
def Mqtt_Publish(s):
    client.publish('node_information',s)
#************************************************
#def Uart_Send():
def Get_Style(ID):
    global cur
    print(name)
    cur.execute("select style from node_information where ID=\'%s\'"%ID)
    data=cur.fetchone()
    style=data[0]
    print(style)
    return style
#********************************
def fun():
  s=f.readline()
  while(s!=""):
    s=f.readline()
  while True:
     
     s=f.readline()
     if(s!=""):
       s=s.strip('\n')
       print(s)
       ID=s.split(':')[0]
       statsu=s.split(':')[1]
       style=Get_Style(name)
       if(style=='wifi'):
          Mqtt_Publish(s)
       if(style=='zigbee'):
          Uart_Send()
#***************************************
if __name__ == '__main__':
	mqtt_connect()
	fun()
cur.close()
connecting.close()

        
