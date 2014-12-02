#!/bin/bash

if [ $# -ne 2 ]
then
   echo "USAGE: $0 <vm name> <load balancer groupid>"
   exit;
fi

psql -d vlab --command "delete from vlab_interim.lb_group where xen_server='$1' and group_id=$2 ;"
