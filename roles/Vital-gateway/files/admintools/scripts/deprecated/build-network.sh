#!/bin/bash

# move-vm.sh <vm name> <to machine>

echo "creating $1 networking on $2"

student_num=`psql -t -d vlab --command "select user_name from vlab_interim.vm_superview where vm_name='$1'" | awk '{print substr($1,8,3)}'`;

echo $student_num

xen_server=`psql -t -d vlab --command "select xen_server from vlab_interim.vm_superview where vm_name='$1'"`;

echo $xen_server

let net=$student_num+100
sh ./create-network.sh ${xen_server} ${net}

