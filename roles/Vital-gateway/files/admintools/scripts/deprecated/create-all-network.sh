#!/bin/bash

if [ $# -ne 3 ]
then
   # create-all-network.sh <xen_server> <start student id> <stop student id>
   echo "USAGE: $0 <xen_server> <start student id> <stop student id>"
   exit;
fi


echo -- $1 $2 $3
xen_server=$1
let sindex=$2
let eindex=$3


for ((i=sindex; i <= eindex; i++))
do

    let net_id=$i+100
    net_name="Net-${net_id}"
    echo "creating ${net_name} on ${xen_server}"  
    ssh ${xen_server} brctl addbr ${net_name}
    ssh ${xen_server} ifconfig ${net_name} up

    net_name="Net-1${net_id}"
    echo "creating ${net_name} on ${xen_server}"  
    ssh ${xen_server} brctl addbr ${net_name}
    ssh ${xen_server} ifconfig ${net_name} up

done

