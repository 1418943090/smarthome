
import paho.mqtt.client as mqtt
import time
def on_connect(client, userdata, flags, rc):
    print("Connected with result code "+str(rc))
    client.subscribe('Tem') #client2 publi
   # client.subscribe("position")

def on_message(client, userdata, msg):
    s=msg.payload.decode("utf8")
    print(time.strftime('%Y-%m-%d %H:%M:%S',time.localtime(time.time())))
    #a=eval(s)#将字符串串转为字典类型
    #print(a["name"])
    print(msg.topic+" "+s)
    print(s.split(":")[1])
    print(s.split(":")[3])
    
def fun():
    client = mqtt.Client()
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
