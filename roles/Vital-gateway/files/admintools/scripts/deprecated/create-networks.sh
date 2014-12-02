#!/bin/bash

echo -- $1 $2 
xen_server=$1
let sindex=$2
let eindex=$3


#for i in {161..320}
for ((i=sindex; i <= eindex; i++))
do

  let num=$i
  let net=$i+100
  let xen_id=($num%9)+11

#  if [ $xen_id -eq 13 ]; then
#  	let xen_id=12
#  fi

#  net_name="Net-${net}"
#  xen_server="vlab-bld-${xen_id}"
#
#  echo "creating ${net_name} on ${xen_server}"  
#  ssh ${xen_server} brctl addbr ${net_name}
#  ssh ${xen_server} vconfig add bond0 ${net}
#  ssh ${xen_server} brctl addif ${net_name} bond0.${net}
#  ssh ${xen_server} ifconfig bond0.${net} up
#  ssh ${xen_server} ifconfig ${net_name} up
   sh ./create-network.sh ${xen_server} ${net}

  echo "moving bt4 to ${xen_server}"
  psql -d vlab --command "UPDATE vlab_interim.vm_resource set xen_server='${xen_server}' WHERE vm_name='bt4_student${num}';"

done

