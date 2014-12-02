#!/bin/bash

# fix-network.sh <machine>

for vm in `psql -t -d vlab --command "select vm_name from vlab_interim.vm_resource where xen_server='$1'"`
do
        sh ./build-network.sh $vm $1
done

