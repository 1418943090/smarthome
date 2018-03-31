#encoding:utf-8 
import time,os,pymysql,sys
connecting=""
#*************************
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
#*******************************
fp=open("../mqtt.txt",'a')
#***********************
def status_check(name):
    cur.execute("use LOT")
    cur.execute("select * from Node_Information where name=\"%s\""%name)
    data=cur.fetchall()
    if data[0][2]=='on':
        return 'on'
    else :
        return 'off'
#***************************
def s_time_check(date,row):
  if row[2]!="":
    global cur
    global connecting
    print("****************************************")
    if(row[6]==0):
      print("每天")
    if(row[6]==1):
      print("当天")
    if(row[6]==2):
      print("自定义")
    print(row[8])
    print("当前时间:",date[3],":",date[4])
    print("开启时间:",row[2],":",row[3])
    s_h=int(row[2])
    s_m=int(row[3])
    if date[3]==s_h and date[4]==s_m:
      if row[4]=="" and row[6]==1:#如果没有输入关闭时间 又是 当天模式 则到达开启时间后关闭该定时任务
        cur.execute("use SETTING")
        cur.execute("update set_name set status='off' where name=\"%s\""%row[8])
        connecting.commit()          
      return 'ontrue'
    else:
       return 'onfalse'
  else:
    return 'onfalse';
#**************************************************
def e_time_check(date,row):
  if row[4]!="":
    global cur
    global connecting
    print("***************************************")
    if(row[6]==0):
       print("每天")
    if(row[6]==1):
       print("当天")
    if(row[6]==2):
       print("自定义")
    print(row[8])
    print("当前时间:",date[3],":",date[4])
    print("关闭时间:",row[4],":",row[5])
    e_h=int(row[4])
    e_m=int(row[5])
    if date[3]==e_h and date[4]==e_m:
      if row[6]==1:#到了关闭时间 当天有效模式 则把定时任务关闭
        cur.execute("use SETTING")
        cur.execute("update set_name set status='off' where name=\"%s\""%row[8])
        connecting.commit()
      return 'offtrue'
    else:
      return 'offfalse'
  else:
    return 'offfalse'
#************************************************   
def f_everyday(row,date):#每天生效模式
    global fp
    global cur
    global connecting
    bool=1
    A=row[0].split(',')
    for i in A:
       if status_check(i)=='off' and s_time_check(date,row)=='ontrue':#设备是关的 且开始时间到了
          
           cur.execute("use LOT")
           cur.execute("update Node_Information set status='on' where name=\"%s\" "%i)#更新设备状态
           cur.execute("select ID from Node_Information where name=\"%s\""%i)
           ID=cur.fetchall()
           code=ID[0][0]+":"+"on"                               #则打开设备
           print(code)
           fp.writelines("%s\n"%code)#将指令写入文档中
           fp.flush()
           connecting.commit()
       if status_check(i)=='on' and e_time_check(date,row)=='offtrue':#设备是开的 且停止时间到了
           cur.execute("use LOT")
           cur.execute("update Node_Information set status='off' where name=\"%s\" "%i)
           cur.execute("select ID from Node_Information where name=\"%s\""%i)
           ID=cur.fetchall()
           code=ID[0][0]+":"+"off"                               #则打开设备
           print(code)
           fp.writelines("%s\n"%code)#将指令写入文档中
           fp.flush()
           connecting.commit() 
#**********************************************************
def f_today(row,date):#当天生效模式
    #day=int(row[1])
    #if day==date[2]:
      f_everyday(row,date)
     
#*********************************************************
def f_define(row,date):#自定义模式
     A=row[7].split(',')
     for i in A:
       if (i=='monday' and date[6]==1) or (i=='tuesday' and date[6]==2) or (i=='wednesday'and date[6]==3) or (i=='thursday' and date[6]==4) or (i=='friday' and date[6]==5) or (i=='saturday' and date[6]==6)or (i=='sunday' and date[6]==0):
         f_everyday(row,date)
#************************************************************  
def gettime():
    date=time.localtime()
    return date
#**********************************************************
while 1:
  cur=mysql_con()
  cur.execute("use SETTING");
  cur.execute("select * from timing")
  data=cur.fetchall()
  for row in data:
      date=gettime()
      cur.execute("use SETTING");
      cur.execute("select * from set_name where name=\"%s\""%row[8])
      status=cur.fetchall()
      if (status[0][1]=='on'):
          if(row[6]==0):
             f_everyday(row,date)
          if(row[6]==1):
             f_today(row,date)
          if(row[6]==2):
             f_define(row,date)
      time.sleep(2)
  time.sleep(5)  
# date[0] year
# date[1] mon
# date[2] day
# date[3] hour
# date[4] min
# date[6] week
fp.close()
cur.close()
connecting.close()
