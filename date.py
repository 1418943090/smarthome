import pymysql
import random
connecting=""
cur=""
def mysql_con():
    global connecting
    connecting=pymysql.connect(
        host="localhost",
        user="root",
        password="huang110",
        db="DATA",
        charset="utf8"
        )
    cur=connecting.cursor()
    return cur
def insert_data():
    global cur
    global connecting
    cur=mysql_con()
    year=2017
    month=11
    days=9
    hour=11
    week=1
    minute=0
    data=0
    sensor='fc001'
   # cur.execute("delete from fc001") 
    while(hour<21):
       date=round(random.uniform(25,40),3)
       cur.execute("insert into %s(data,year,month,week,days,hour,minute) values('%s',%d,%d,%d,%d,%d,%d)"%(sensor,date,year,month,week,days,hour,minute))
       minute+=1
       if minute==59:
          Y=hour
          cur.execute("select avg(data) from %s where hour=%d"%(sensor,Y))
          data=cur.fetchall()
          h=0
          cur.execute("update %s set data=%f where hour=%d and minute=%d"%(sensor,data[0][0],Y,h))
          cur.execute("delete from %s where hour=%d and minute!=0"%(sensor,Y))
          print(data[0])
          minute=0
          hour+=1
    connecting.commit()
    connecting.close()
    cur.close()
    
if __name__=="__main__":
    
    insert_data()



