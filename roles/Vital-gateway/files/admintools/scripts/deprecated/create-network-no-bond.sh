#!/bin/bash

# create-network.sh <Xen Server> <VLAN ID>

  xen_server=$1
  net=$2

  net_name="Net-${net}"

  echo "creating ${net_name} on ${xen_server}"  
  ssh ${xen_server} brctl addbr ${net_name}
#  ssh ${xen_server} vconfig add bond0 ${net}
#  ssh ${xen_server} brctl addif ${net_name} bond0.${net}
#  ssh ${xen_server} ifconfig bond0.${net} up
  ssh ${xen_server} ifconfig ${net_name} up

