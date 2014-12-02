#!/bin/bash

if [ $# -eq 0 ] ; then
#	default grace period
#	grace_period="1 days"
	grace_period="6 hours"
else
   if [ $# -eq 1 ] ; then
   	grace_period=$1
   else
      echo "USAGE: $0 <grace period>"
      exit;
   fi
fi


echo "Shutting down old machines"
date >> /tmp/old-vm-stopped.txt


#psql -d vlab --command "select vm_name, timestamp from vlab_interim.vm_resource where timestamp < (now() -'$grace_period'::interval) and current_state=10 and xen_server != 'vlab-bld-20'and vm_name not like 'web_student%' and vm_name != 'int-rtr250'" >> /tmp/old-vm-stopped.txt
psql -d vlab --command "select vm_name, timestamp from vlab_interim.vm_resource where timestamp < (now() -'$grace_period'::interval) and current_state=10 and vm_name not in (select vm_name from vlab_interim.shutdown_exclude)" >> /tmp/old-vm-stopped.txt

#psql -d vlab --command "update vlab_interim.vm_resource set requested_state=11 where timestamp < (now() -'$grace_period'::interval) and current_state=10 and xen_server != 'vlab-bld-20' and vm_name not like 'web_student%' and vm_name != 'int-rtr250'"
psql -d vlab --command "update vlab_interim.vm_resource set requested_state=11 where timestamp < (now() -'$grace_period'::interval) and current_state=10 and vm_name not in (select vm_name from vlab_interim.shutdown_exclude)"

#current_time=$(date +%s)
#
#reflector_list=`ps axo "lstart,pid,cmd" | grep vncreflector | grep -v grep`
#
#while read LINE ; do
#
#	echo "-- $LINE -- "
#
#done 
