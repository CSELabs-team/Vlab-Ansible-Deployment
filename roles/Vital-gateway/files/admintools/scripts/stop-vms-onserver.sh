#!/bin/bash

if [ $# -ne 1 ]
then

	echo "USAGE: $0 <xen server> "
	exit 
fi

xen_server=$1

psql -d vlab --command "update vlab_interim.vm_resource set requested_state=11 where xen_server='${xen_server}'"

