import os
import mysql.connector
from time import sleep
import datetime
import sys

# Database connection
mydb = mysql.connector.connect(
        host="localhost",
        user="ta04",
        password="root",
        database="ta"
)

# Get Time
current_time = datetime.datetime.now()
year = current_time.year


def string_join(string_list):
        size = len(string_list)
        stringtemp = []
        for i in range(2, size-1):
                stringtemp.append(string_list[i])
        string = ' '.join(stringtemp)
        return string


with open("/var/log/snort/alert", 'r') as f:  # snort_log.txt"
        temp = f.readlines()
        Classification_temp = []
        Signature_temp = []
        Direct_temp = []
        Timestamp_temp = []
        IPS_temp = []
        Ports_temp = []
        IPD_temp = []
        Portd_temp = []
        for line in temp:
                string = line.replace('[', '').replace(']', '').split(' ')
                if(string[0] == "**"):
                        string2 = string_join(string)
                        Signature_temp.append(string2)
        for line in temp:
                string = line.replace('[', '').replace(
                ']', '').replace(':', '').replace('\n', '').split(' ')
                if("Priority" in string):
                        for index in range(len(string)):
                                if(string[index] == "Priority"):
                                        if(string[index + 1] == "0"):
                                                Classification_temp.append("Undefined")
                                        elif(string[index + 1] == "1"):
                                                Classification_temp.append("Critical")
                                        elif(string[index + 1] == "2"):
                                                Classification_temp.append("High")
                                        elif(string[index + 1] == "3"):
                                                Classification_temp.append("Medium")
                                        elif(string[index + 1] == "4"):
                                                Classification_temp.append("Low")
        for line in temp:
                string = line.replace('[', '').replace(']', '').replace('\n', '').split(' ')
                if("ICMP" not in string):
                        if("Type:" not in string):
                                if(len(string) == 4 or "->" in string):
                                        # TimeStamp
                                        temp1 = string[0].split('-')
                                        if("**" not in temp1 and len(temp1) == 2):
                                                stamp = str(year)+"-" + \
                                                temp1[0].replace('/', '-')+" "+temp1[1]
                                                Timestamp_temp.append(stamp)

                                                #IPS & Ports
                                                temp2 = string[1]
                                                ips = temp2.split(':')
                                                if(len(ips) == 2):
                                                        Ports_temp.append(ips[1])
                                                        IPS_temp.append(ips[0])
                                                else:
                                                        IPS_temp.append(ips[0])
                                                        Ports_temp.append("0")

                                                # Direct
                                                Direct_temp.append(string[2])

                                                #IPD & Portd
                                                temp3 = string[3]
                                                ipd = string[3].split(':')
                                                if(len(ipd) == 2):
                                                        Portd_temp.append(ipd[1])
                                                        IPD_temp.append(ipd[0])
                                                else:
                                                        IPD_temp.append(ipd[0])
                                                        Portd_temp.append("0")

# print(len(Classification_temp), len(Signature_temp), len(IPS_temp), len(
#     Ports_temp), len(IPD_temp), len(Portd_temp), len(Timestamp_temp))

# for i in range(0, len(Signature_temp)):
#     print(Classification_temp[i]+" | " + Signature_temp[i]+" | "+IPS_temp[i] +
#           " | "+Ports_temp[i]+" | "+IPD_temp[i]+" | "+Portd_temp[i]+" | "+Timestamp_temp[i])

table_cursor = mydb.cursor()
mycursor = mydb.cursor()
table_cursor.execute("SHOW TABLES")
table_show = []
indicator = 0
for x in table_cursor:
        string = str(x).strip("'(),")
        table_show.append(string)

if("snortmonitor" in table_show):
        indicator = 1

if(indicator == 0):
        mycursor.execute("Create table snortmonitor (id int NOT NULL AUTO_INCREMENT PRIMARY KEY, classification varchar(255), signature varchar(255), ips varchar(255), ports varchar(255),direct varchar(255), ipd varchar(255), portd varchar(255), timestamp datetime(6))")
   
table_show.clear()

table_temp = []
mycursor.execute("Select * from snortmonitor")
for x in mycursor:
        string = x[8]
        table_temp.append(str(string))

sql = "INSERT INTO snortmonitor (classification, signature, ips, ports,direct, ipd, portd, timestamp) VALUES (%s, %s, %s, %s, %s, %s, %s,%s)"

#print(len(Signature_temp))
#print(len(Timestamp_temp))
#print(Signature_temp[len(Signature_temp)])
for i in range(len(Signature_temp)):
        if(Timestamp_temp[i] not in table_temp):
                #print(Timestamp_temp[i]+" -> ",i)
                values = ('{}'.format(Classification_temp[i]), '{}'.format(Signature_temp[i]),
                        '{}'.format(IPS_temp[i]), '{}'.format(Ports_temp[i]), '{}'.format(Direct_temp[i]), '{}'.format(IPD_temp[i]), '{}'.format(Portd_temp[i]), '{}'.format(Timestamp_temp[i]))
                mycursor.execute(sql, values)
mycursor.close()
mydb.commit()
mydb.close()
sleep(2)
os.system(r"python3 loop.py")
sys.exit(0)  
