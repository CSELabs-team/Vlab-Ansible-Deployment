#!/bin/bash

if [ $# -ne 3 ]
then
   echo "USAGE: $0 <vm base username> <start index> <end index> "
   exit;
fi

vm_basename=$1
let sindex=$2
let eindex=$3

for ((i=sindex; i <= eindex; i++))
do

	bash ./remove-single-machine-sql.sh ${vm_basename}${i}

done
