#!/bin/bash

if [ $# -ne 2 ]
then
   echo "USAGE: $0 <vm name> <load balancer groupid>"
   exit;
fi

psql -d vlab --command "insert into vlab_interim.lb_group (group_id, xen_server) values ($2, '$1');"
