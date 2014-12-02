#!/bin/bash

# migrate-all.sh <from machine> <to machine>

if [ $# -ne 2 ]
then
   echo "USAGE: $0 <from machine> <to machine>"
   exit;
fi

for vm in `ssh $1 xm list | awk '{print $1}'` 
do
	if [ $vm == "Name" ]; 
	then 
		continue;
	fi

	if [ $vm == "Domain-0" ];
	then
		continue;
	fi
    
#        sh ./build-network.sh $vm $2
	bash migrate-vm.sh $vm $1 $2 
#        sh ./destroy-network.sh $vm $1

#	sql_cmd="update vlab_interim.vm_resource set xen_server='$2' where vm_name='${vm}'"
#	echo "${sql_cmd}"
#	psql -d vlab --command "${sql_cmd}" 
done

sql_cmd="update vlab_interim.vm_resource set xen_server='$2' where xen_server='$1'"
echo "${sql_cmd}"
psql -d vlab --command "${sql_cmd}" 
