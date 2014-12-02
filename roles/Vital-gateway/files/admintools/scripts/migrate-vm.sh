#!/bin/bash

# migrate-vm.sh <vm name> <from machine> <to machine>

if [ $# -ne 3 ]
then
   echo "USAGE: $0 <vm name> <from machine> <to machine>"
   exit;
fi



echo "Live Migration of $1 from $2 to $3"

#sh ./build-network.sh $vm $3

ssh $2 xm migrate --port=8002 --live $1 $3

#sh ./destroy-network.sh $vm $2

psql -d vlab --command "update vlab_interim.vm_resource set xen_server='$3' where vm_name='$1'"
