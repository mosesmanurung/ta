from time import sleep
import mysql.connector
import os
import sys
crit = 0
# Database connection
mydb = mysql.connector.connect(
    host="localhost",
    user="ta04",
    password="root",
    database="ta"
)
cursor = mydb.cursor(buffered=True)
cursor.execute("SELECT * FROM snortmonitor")
track = 0
with open("loopcounter.txt", 'r') as f:
    temp = f.readlines()
    if("<built-in function id>" in temp):
        track = 1
    else:
        counter = int(temp[0])

if(track == 1):
    with open("loopcounter.txt", 'w')as f:
        f.write("0")
        counter = int(0)

for row in cursor:
    id_temp = int(row[0])
    if(id_temp > counter):
        id = row[0]
        if(row[1] == "Critical"):
            crit = 1
            # time.sleep(120)
            print("Found! : ID = ", id)
            # exec(open("aide_check.py").read())
            with open("loopcounter.txt", "w") as f:
                if(str(id) != "<built-in function id>"):
                    f.write(str(id))
            mydb.close()
            cursor.close()
            print("Running aide.conf -C")
            os.system(r"sudo aide -c /etc/aide/aide.conf --limit /var --check")
            sleep(2)
            print("Running aide_check.py")
            os.system(r"python3 aide_check.py")
            sys.exit(0)
    if(id_temp == counter):
        id = id_temp
if(crit == 0):
    # exec(open("logger.py").read())
    with open("loopcounter.txt", "w") as f:
        if(str(id) != "<built-in function id>"):
            f.write(str(id))

    mydb.close()
    cursor.close()
    sleep(2)
    os.system(r"python3 logger.py")
    sys.exit(0)

cursor.close()
sleep(2)
mydb.close()
sys.exit(0)
