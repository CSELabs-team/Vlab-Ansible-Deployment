#!/bin/bash

if [ $# -ne 1 ]
then
   echo "USAGE: $0 <vlab id>"
   exit;
fi


student_name=$1

psql -d vlab -t --command "select vm_id, vm_name, vnc_port+10000, requested_state, current_state from vlab_interim.vm_resource where vm_id in (select vm_id from vlab_interim.reflector where reflector_id in (select reflector_id from vlab_interim.user_reflector where user_id = (select user_id from vlab_interim.user where user_name = '$student_name')))"
