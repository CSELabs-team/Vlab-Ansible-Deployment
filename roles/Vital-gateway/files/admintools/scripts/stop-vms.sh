#!/bin/bash

# start-vms.sh <basename> <start index> <end index>

echo -- $1 $2 $3

let sindex=$2
let eindex=$3

for ((i=sindex; i <= eindex; i++))
do

   echo "Starting ${1}${i}"

   psql -d vlab --command "update vlab_interim.vm_resource set requested_state=11 where vm_name='${1}${i}'"

done
