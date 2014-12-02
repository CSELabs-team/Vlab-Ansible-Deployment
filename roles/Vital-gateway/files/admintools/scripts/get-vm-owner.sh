#!/bin/bash

if [ $# -ne 1 ]
then
   echo "USAGE: $0 <vm name>"
   exit;
fi

vm_name=$1

#vmlist=`psql -d vlab -t --command "select vm_name from vlab_interim.vm_resource where vm_id in (select vm_id from vlab_interim.reflector where reflector_id in (select reflector_id from vlab_interim.user_reflector where user_id = (select user_id from vlab_interim.user where user_name = '${user_basename}$i')))"`

ownerlist=`psql -d vlab -t --command "select user_name from vlab_interim.user where user_id in (select user_id from vlab_interim.user_reflector where reflector_id in (select reflector_id from vlab_interim.reflector where vm_id = (select vm_id from vlab_interim.vm_resource where vm_name = '${vm_name}')))"`

for owner in ${ownerlist[@]}
do
        echo "$vm_name : $owner"
done
