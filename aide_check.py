import mysql.connector

# Database connection
mydb = mysql.connector.connect(
    host="localhost",
    user="ta04",
    password="root",
    database="ta"
)


def string_join(list_string):
    size = len(list_string)
    temp = []
    for i in range(size):
        if(i > 0):
            if(i != size-1):
                temp.append(list_string[i])
    string = '/'.join(temp)
    return string


with open("aide_check.txt", 'r') as f:
    stamp = f.readlines()[0].split(':')
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
    tempname = string_join(stringsplit1)
    tempcount = 1
    # Check If Folder Has Already Mentioned
    if(tempname not in foldername_added):
        foldername_added.append(tempname)
        for j in addentries:
            stringsplit2 = j.split('/')
            tempname2 = string_join(stringsplit2)
            if(tempname == tempname2):
                tempcount += 1
        arrtemp_added.append(tempcount)

for i in removedentries:
    stringsplit1 = i.split('/')
    tempname = string_join(stringsplit1)
    tempcount = 1
    # Check If Folder Has Already Mentioned
    if(tempname not in foldername_removed):
        foldername_removed.append(tempname)
        for j in addentries:
            stringsplit2 = j.split('/')
            tempname2 = string_join(stringsplit2)
            if(tempname == tempname2):
                tempcount += 1
        arrtemp_removed.append(tempcount)

for i in changeentries:
    stringsplit1 = i.split('/')
    tempname = string_join(stringsplit1)
    tempcount = 1
    # Check If Folder Has Already Mentioned
    if(tempname not in foldername_changed):
        foldername_changed.append(tempname)
        for j in addentries:
            stringsplit2 = j.split('/')
            tempname2 = string_join(stringsplit2)
            if(tempname == tempname2):
                tempcount += 1
        arrtemp_changed.append(tempcount)

temp_name = []  # Object
temp_value1 = []  # Added
temp_value2 = []  # Removed
temp_value3 = []  # Modified / Changed


for i in range(len(foldername_added)):
    strname = foldername_added[i]
    temp_name.append(strname)
    temp_value1.append(arrtemp_added[i])
    if((strname in foldername_removed) == True):
        for j in range(len(foldername_removed)):
            if(strname == foldername_removed[j]):
                temp_value2.append(arrtemp_removed[j])
                break
    else:
        temp_value2.append(0)

    if((strname in foldername_changed) == True):
        for j in range(len(foldername_changed)):
            if(strname == foldername_changed[j]):
                temp_value3.append(arrtemp_changed[j])
                break
    else:
        temp_value3.append(0)

for i in range(len(foldername_removed)):
    strname = foldername_removed[i]
    if((strname in foldername_added) == False):
        temp_name.append(strname)
        temp_value1.append(0)
        temp_value2.append(arrtemp_removed[i])
        if((strname in arrtemp_changed) == True):
            temp_value3.append(arrtemp_changed[i])
        else:
            temp_value3.append(0)
    else:
        continue

for i in range(len(arrtemp_changed)):
    strname = foldername_changed[i]
    if((strname in foldername_added) == False):
        if((strname in foldername_removed) == False):
            temp_name.append(strname)
            temp_value1.append(0)
            temp_value2.append(0)
            temp_value3.append(arrtemp_changed[i])
        else:
            continue
    else:
        continue

mycursor = mydb.cursor(buffered=True)

mycursor.execute("SHOW TABLES")
table_show = []
indicator = 0
for x in mycursor:
    string = str(x).strip("'(),")
    table_show.append(string)
    break

if("aidemonitor" in table_show):
    indicator = 1

if (indicator == 0):
    mycursor.execute(
        "CREATE TABLE aidemonitor (id INT AUTO_INCREMENT PRIMARY KEY, objectname varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `added` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `modified` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `removed` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,`timestamp` datetime NOT NULL)")
# else:
#     mycursor.execute("DROP TABLE aidemonitor")
#     mycursor.execute(
#         "CREATE TABLE aidemonitor (id INT AUTO_INCREMENT PRIMARY KEY, objectname varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `added` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `modified` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `removed` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,`timestamp` datetime NOT NULL)")

table_show.clear()

sql = ("INSERT INTO aidemonitor(objectname,added,modified,removed,timestamp) VALUES (%s,%s,%s,%s,%s)")

for i in range(1, len(temp_name)):
    values = ('{}'.format(temp_name[i]), '{}'.format(temp_value1[i]),
              '{}'.format(temp_value3[i]), '{}'.format(temp_value2[i]), '{}'.format(timestamp))
    mycursor.execute(sql, values)

mydb.commit()
