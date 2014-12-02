#!/bin/bash

# create-network.sh <Xen Server> <VLAN ID>

  xen_server=$1
  net=$2

  net_name="Net-${net}"

  echo "removing ${net_name} on ${xen_server}"  
  ssh ${xen_server} ifconfig ${net_name} down 
  ssh ${xen_server} ifconfig bond0.${net} down 
  ssh ${xen_server} brctl delif ${net_name} bond0.${net}
  ssh ${xen_server} vconfig rem bond0.${net}
  ssh ${xen_server} brctl delbr ${net_name}
