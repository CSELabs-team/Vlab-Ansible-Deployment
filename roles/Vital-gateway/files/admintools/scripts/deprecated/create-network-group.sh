#!/bin/bash

echo -- $1 $2 $3 
let vlan_id=$1
let sindex=$2
let eindex=$3


#for i in {161..320}
for ((i=sindex; i <= eindex; i++))
do

  let num=$i
  let xen_id=($num%9)+11

  if [ $xen_id -eq 13 ]; then
  	let xen_id=12
  fi

  net=${vlan_id}
  net_name="Net-${vlan_id}"
  xen_server="vlab-bld-${xen_id}"

  echo "creating ${net_name} on ${xen_server}"  
  ssh ${xen_server} brctl addbr ${net_name}
  ssh ${xen_server} vconfig add bond0 ${net}
  ssh ${xen_server} brctl addif ${net_name} bond0.${net}
  ssh ${xen_server} ifconfig bond0.${net} up
  ssh ${xen_server} ifconfig ${net_name} up

done

