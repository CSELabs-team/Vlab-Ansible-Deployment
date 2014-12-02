#!/bin/bash

echo -- $1 $2 $3
vm_name_base=$1
let sindex=$2
let eindex=$3

#for i in {161..320}
for ((i=sindex; i <= eindex; i++))
do

#  echo "DELETE FROM  vlab_interim.vm_resource WHERE vm_name = '${vm_name_base}$i';" 
   bash ./remove-single-resource-sql.sh ${vm_name_base}$i

done
