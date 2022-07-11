from socket import NI_MAXHOST
import mysql.connector
import os
from time import sleep
import sys

# os.system("sudo aide -c /etc/aide/aide.conf -C")
# Database connection
mydb = mysql.connector.connect(
    host="localhost",
    user="ta04",
    password="root",
    database="ta"
)


class struct:
    def __init__(self, name, namemin, addentries, removedentries, changeentries):
        self.name = name
        self.namemin = namemin
        self.addentries = addentries
        self.removedentries = removedentries
        self.changeentries = changeentries


Toples = []


def top_insert(string, stringmin, operator):
    if(operator == "[+]"):
        Toples.append(struct(string, stringmin.replace('\n', ''), 1, 0, 0))
    elif(operator == "[x]"):
        Toples.append(struct(string, stringmin.replace('\n', ''), 0, 1, 0))
    elif(operator == "[-]"):
        Toples.append(struct(string, stringmin.replace('\n', ''), 0, 0, 1))


def mid_insert(string, stringmin, operator):
    arr = len(Toples)
    j = 0
    for i in range(0, arr):
        if(Toples[i].name == string):
            temp1 = Toples[i].namemin
            temp2 = temp1 + ","+stringmin.replace('\n', '')
            if(operator == "[+]"):
                Toples[i].addentries += 1
            elif(operator == "[x]"):
                Toples[i].removedentries += 1
            elif(operator == "[-]"):
                Toples[i].changeentries += 1
            Toples[i].namemin = temp2
        else:
            j += 1
    if(j == arr):
        if(operator == "[+]"):
            Toples.append(struct(string, stringmin.replace('\n', ''), 1, 0, 0))
        elif(operator == "[x]"):
            Toples.append(struct(string, stringmin.replace('\n', ''), 0, 1, 0))
        elif(operator == "[-]"):
            Toples.append(struct(string, stringmin.replace('\n', ''), 0, 0, 1))


def string_join(list_string, operator):
    size = len(list_string)
    temp = []
    for i in range(size):
        if(i > 0):
            if(i != size-1):
                temp.append(list_string[i])
    string = '/'.join(temp)
    last_string = operator+list_string[size-1]
    if(len(Toples) == 0):
        top_insert(string, last_string, operator)
    else:
        mid_insert(string, last_string, operator)
    return string


def string_join2(list_string):
    size = len(list_string)
    temp = []
    for i in range(size):
        if(i > 0):
            if(i != size-1):
                temp.append(list_string[i])
    string = '/'.join(temp)
    return string


with open("aide_check.txt", 'r') as f:
    t_stamp = f.readlines()
    for i in range (0,len(t_stamp)):
        stamp = t_stamp[i].split(':')
        if("Start timestamp" in stamp):
            strtemp = stamp[3].split(' ')
            timestamp = stamp[1]+":"+stamp[2]+":"+strtemp[0]


with open("aide_check.txt", 'r') as f:
    f.readline()
    added_counter = 0
    removed_counter = 0
    changed_counter = 0
    addentries = []
    removedentries = []
    changeentries = []
    for line in f:
        string1 = line.strip(':').split(':')
        if(string1[0] == "Added entries"):
            added_counter = 1
        if(added_counter == 1):
            if(len(string1) == 2):
                addentries.append(string1[1])
            if(string1[0] == "Removed entries"):
                added_counter = 99
                removed_counter = 1
        if(removed_counter == 1):
            if(len(string1) == 2):
                removedentries.append(string1[1])
            if(len(string1) == 3):
                temp = string1[1]+":"+string1[2]
                removedentries.append(temp)
            if(string1[0] == "Changed entries"):
                removed_counter = 99
                changed_counter = 1
        if(changed_counter == 1):
            if(len(string1) == 2):
                changeentries.append(string1[1])
            if(len(string1) == 3):
                changeentries.append(string1[2])
            if(string1[0] == "Detailed information about changes"):
                changed_counter = 99


arrtemp_added = []
arrtemp_removed = []
arrtemp_changed = []

foldername_added = []
foldername_removed = []
foldername_changed = []

for i in addentries:
    stringsplit1 = i.split('/')
    tempname = string_join(stringsplit1, "[+]")
    tempcount = 1
    # Check If Folder Has Already Mentioned
    if(tempname not in foldername_added):
        foldername_added.append(tempname)
        for j in addentries:
            stringsplit2 = j.split('/')
            tempname2 = string_join2(stringsplit2)
            if(tempname == tempname2):
                tempcount += 1
        arrtemp_added.append(tempcount)

for i in removedentries:
    stringsplit1 = i.split('/')
    tempname = string_join(stringsplit1, "[x]")
    tempcount = 1
    # Check If Folder Has Already Mentioned
    if(tempname not in foldername_removed):
        foldername_removed.append(tempname)
        for j in addentries:
            stringsplit2 = j.split('/')
            tempname2 = string_join2(stringsplit2)
            if(tempname == tempname2):
                tempcount += 1
        arrtemp_removed.append(tempcount)

for i in changeentries:
    stringsplit1 = i.split('/')
    tempname = string_join(stringsplit1, "[-]")
    tempcount = 1
    # Check If Folder Has Already Mentioned
    if(tempname not in foldername_changed):
        foldername_changed.append(tempname)
        for j in addentries:
            stringsplit2 = j.split('/')
            tempname2 = string_join2(stringsplit2)
            if(tempname == tempname2):
                tempcount += 1
        arrtemp_changed.append(tempcount)

temp_name = []  # Object


for i in range(len(foldername_added)):
    strname = foldername_added[i]
    temp_name.append(strname)


for i in range(len(foldername_removed)):
    strname = foldername_removed[i]
    if((strname in foldername_added) == False):
        temp_name.append(strname)
    else:
        continue

for i in range(len(arrtemp_changed)):
    strname = foldername_changed[i]
    if((strname in foldername_added) == False):
        if((strname in foldername_removed) == False):
            temp_name.append(strname)
        else:
            continue
    else:
        continue

mycursor = mydb.cursor()

mycursor.execute("SHOW TABLES")
table_show = []
indicator = 0
for x in mycursor:
    string = str(x).strip("'(),")
    table_show.append(string)

if("aidemonitor" in table_show):
    indicator = 1

if (indicator == 0):
    mycursor.execute(
        "CREATE TABLE aidemonitor (id INT AUTO_INCREMENT PRIMARY KEY, objectname varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `added` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `modified` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `removed` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,`timestamp` datetime NOT NULL,`files` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL)")
else:
    mycursor.execute("DROP TABLE aidemonitor")
    mycursor.execute(
        "CREATE TABLE aidemonitor (id INT AUTO_INCREMENT PRIMARY KEY, objectname varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `added` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `modified` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `removed` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,`timestamp` datetime NOT NULL,`files` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL)")

table_show.clear()

sql = ("INSERT INTO aidemonitor(objectname,added,modified,removed,timestamp,files) VALUES (%s,%s,%s,%s,%s,%s)")


def finder(t_name):
    counter = 0
    for i in range(len(Toples)):
        if(t_name == Toples[i].name):
            return i
        else:
            counter += 1
    if(counter == len(Toples)):
        print("Not Found")


for i in range(1, len(temp_name)):
    files = finder(temp_name[i])
    values = ('{}'.format(temp_name[i]), '{}'.format(Toples[files].addentries),
              '{}'.format(Toples[files].changeentries), '{}'.format(
                  Toples[files].removedentries), '{}'.format(timestamp), '{}'.format(Toples[files].namemin))
    mycursor.execute(sql, values)
mycursor.close()
mydb.commit()
# exec(open("loop.py").read())
mydb.close()
sleep(2)
os.system(r"python3 loop.py")
sys.exit(0)

