#!/bin/bash

if [ $# -ne 2 ]; then

   echo "$0 <vm name> <user name>";
   exit 1;
fi

vm_name=$1
user_name=$2

for ((i=sindex; i <= eindex; i++))
do

  echo "psql -d vlab --command \""
  echo -- $1 $2 
  echo "INSERT INTO vlab_interim.user_reflector (user_id, reflector_id) SELECT user_id, reflector_id from vlab_interim.user, vlab_interim.reflector WHERE user_name = '${user_name}' and vm_id = (SELECT vm_id from vlab_interim.vm_resource WHERE vm_name ='${vm_name}');"
  echo "\""

done
