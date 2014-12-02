#!/bin/bash

# create-all-network.sh <xen_server> <start student id> <stop student id>
echo -- $1 $2 $3
xen_server=$1
let sindex=$2
let eindex=$3


for ((i=sindex; i <= eindex; i++))
do

    let net_id=$i+100
    net_name="Net-${net_id}"
    echo "removing ${net_name} on ${xen_server}"  
    ssh ${xen_server} ifconfig ${net_name} down
    ssh ${xen_server} brctl delbr ${net_name}

    net_name="Net-1${net_id}"
    echo "removing ${net_name} on ${xen_server}"  
    ssh ${xen_server} ifconfig ${net_name} down
    ssh ${xen_server} brctl delbr ${net_name}

done

