#!/bin/bash

if [ $# -ne 2 ]
then
   echo "USAGE: $0 <vm name> <to machine>"
   exit;
fi

# move-vm.sh <vm name> <to machine>

echo "DB updated for $1 to $2"


psql -d vlab --command "update vlab_interim.vm_resource set xen_server='$2' where vm_name='$1'"
