#!/bin/bash

if [ $# -ne 1 ]
then
   echo "USAGE: $0 <vm name> "
   exit;
fi

vm_name=$1

echo "psql -d vlab --command \""

echo "-- $vm_name"
# this could be done in groups and not looped, but
# shows the sql per vm which is better for sanity checks
bash ./remove-single-user-reflector-sql.sh $vm_name 
bash ./remove-single-reflector-sql.sh $vm_name
bash ./remove-single-resource-sql.sh $vm_name 

echo "\""

