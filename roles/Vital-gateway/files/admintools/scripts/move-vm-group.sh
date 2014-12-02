#!/bin/bash

if [ $# -ne 2 ]
then
   echo "USAGE: $0 <student id> <to machine>"
   exit;
fi

# move-vm-group.sh <student id> <to machine>

echo " moving student $1 vm group to $2"

student_id=$1
xen_server=$2

let net_id=($student_id+100)
net0_name="Net-${net_id}"
net1_name="Net-1${net_id}"


  psql -d vlab --command "update vlab_interim.vm_resource set xen_server='$2' where vm_name='rtr${1}'"
  psql -d vlab --command "update vlab_interim.vm_resource set xen_server='$2' where vm_name='int-rtr${1}'"
  psql -d vlab --command "update vlab_interim.vm_resource set xen_server='$2' where vm_name='bt4_student${1}'"
  psql -d vlab --command "update vlab_interim.vm_resource set xen_server='$2' where vm_name='int-lin${1}'"
  psql -d vlab --command "update vlab_interim.vm_resource set xen_server='$2' where vm_name='xp${1}'"
  psql -d vlab --command "update vlab_interim.vm_resource set xen_server='$2' where vm_name='linux_student${1}'"

  echo "creating ${net0_name} on ${xen_server}"  
  ssh ${xen_server} brctl addbr ${net0_name}
  ssh ${xen_server} ifconfig ${net0_name} up

  echo "creating ${net1_name} on ${xen_server}"  
  ssh ${xen_server} brctl addbr ${net1_name}
  ssh ${xen_server} ifconfig ${net1_name} up

