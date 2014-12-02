#!/bin/bash

if [ $# -ne 1 ]
then
   echo "USAGE: $0 <vm to stop>"
   exit;
fi



psql -d vlab --command "update vlab_interim.vm_resource set requested_state=11 where vm_name='$1'"
