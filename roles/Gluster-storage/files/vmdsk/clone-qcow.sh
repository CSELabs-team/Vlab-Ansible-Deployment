#!/bin/bash

if [ $# -ne 4 ]
then
   echo "USAGE: $0 <vm to clone> <base vmname> <start index> <end index> "
   exit;
fi

clone_vm=$1
vm_basename=$2
let sindex=$3
let eindex=$4

for ((i=sindex; i <= eindex; i++))
do

  echo "cloning ${clone_vm} to ${vm_basename}${i}.qcow"
  cp ${clone_vm} ${vm_basename}${i}.qcow

done
