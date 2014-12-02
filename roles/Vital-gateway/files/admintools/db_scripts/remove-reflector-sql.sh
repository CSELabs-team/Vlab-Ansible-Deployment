#!/bin/bash

echo -- $1 $2 $3
vm_name_base=$1
let sindex=$2
let eindex=$3

for ((i=sindex; i <= eindex; i++))
do

#  echo "DELETE FROM vlab_interim.reflector WHERE vm_id = (SELECT vm_id from vlab_interim.vm_resource WHERE vm_name = '${vm_name_base}$i');" 
	bash ./remove-single-reflector-sql.sh ${vm_name_base}$i

done
