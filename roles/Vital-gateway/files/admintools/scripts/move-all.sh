#!/bin/bash

# migrate-all.sh <from machine> <to machine>

if [ $# -ne 2 ]
then
   echo "USAGE: $0 <from machine> <to machine>"
   exit;
fi

sql_cmd="update vlab_interim.vm_resource set xen_server='$2' where xen_server='$1'"
echo "${sql_cmd}"
psql -d vlab --command "${sql_cmd}" 
