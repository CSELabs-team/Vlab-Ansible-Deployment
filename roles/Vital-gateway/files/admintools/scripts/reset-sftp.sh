#!/bin/bash

if [ $# -ne 1 ]
then
   echo "USAGE: $0 <password file> "
   echo "        "
   echo "          The format of the password file is:"
   echo "        "
   echo "          <uid> <pwd> "
   echo "        "
   echo "          Each uid/pwd pair should be put on a new line."
   exit;
fi

pwd_file=$1

while read line
do
        # split line into array
        arr=($line)
 
        # if the length of the array is greater than 1
        if [[ ${#arr[*]} -gt 1 ]]; then
	 echo "resetting pwd sftp for ${arr[0]}"
	 psql -d vlab --command "UPDATE vlab_interim.sftp SET sftp_pass='${arr[1]}' WHERE sftp_user='${arr[0]}';" 
        fi

done < $pwd_file

