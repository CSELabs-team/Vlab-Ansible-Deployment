#!/bin/bash

if [ $# -ne 3 ]
then
   echo "USAGE: $0 <student_id> <from xen_server> <to xen_server>"
   exit;
fi

# migrate-group.sh <student_id> <from xen_server> <to xen_server>
echo -- $1 $2 $3
student_id=$1
from_server=$2
to_server=$3
machines=( rtr int-rtr bt4_student int-lin xp linux_student dvl ) 

xm_list=( `ssh $from_server xm list | awk '{print $1}'` )

echo "xm list count: ${#xm_list[*]}"

for machine in ${machines[@]} 
do
	echo "checking $machine";
	let was_migrated=0

	for vm in ${xm_list[@]} 
	do
	        if [ $vm = "Name" ];
       		then
			continue;
		fi

		if [ $vm = "Domain-0" ];
		then
			continue;
		fi

		vm_name=${machine}${student_id}
		if [ "$vm_name" = "$vm" ]; then
			bash ./migrate-vm.sh $vm_name $from_server $to_server 	
			let was_migrated=1
		fi

	done

	if [ $was_migrated = 0 ]; then
		bash ./move-vm.sh $vm_name $to_server
	fi
done
