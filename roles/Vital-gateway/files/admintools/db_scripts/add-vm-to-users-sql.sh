#!/bin/bash

if [ $# -ne 4 ]
then
   echo "USAGE: $0 <vm basename> <base username> <start index> <end index> "
   exit;
fi

vm_basename=$1
user_basename=$2
let sindex=$3
let eindex=$4

for ((i=sindex; i <= eindex; i++))
do

  echo "psql -d vlab --command \""
  echo -- $1 $2 
  echo "INSERT INTO vlab_interim.user_reflector (user_id, reflector_id) SELECT user_id, reflector_id from vlab_interim.user, vlab_interim.reflector WHERE user_name = '${user_basename}${i}' and vm_id = (SELECT vm_id from vlab_interim.vm_resource WHERE vm_name ='${vm_basename}${i}');"
  echo "\""

done
