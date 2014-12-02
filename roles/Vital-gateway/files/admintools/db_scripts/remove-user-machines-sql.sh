#!/bin/bash

if [ $# -ne 3 ]
then
   echo "USAGE: $0 <user base username> <start index> <end index> "
   exit;
fi

user_basename=$1
let sindex=$2
let eindex=$3

for ((i=sindex; i <= eindex; i++))
do

vmlist=`psql -d vlab -t --command "select vm_name from vlab_interim.vm_resource where vm_id in (select vm_id from vlab_interim.reflector where reflector_id in (select reflector_id from vlab_interim.user_reflector where user_id = (select user_id from vlab_interim.user where user_name = '${user_basename}$i')))"`

#echo "psql -d vlab --command \""
for vm_name in ${vmlist[@]}
do
	bash ./remove-single-machine-sql.sh $vm_name 

done
#echo "\""

done
