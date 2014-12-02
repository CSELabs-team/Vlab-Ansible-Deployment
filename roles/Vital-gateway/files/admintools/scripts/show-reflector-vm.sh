#!/bin/bash

reflector_id=$1

psql -d vlab -t --command "select vm_id, vm_name from vlab_interim.vm_resource where vm_id in (select vm_id from vlab_interim.reflector where reflector_id = '$reflector_id')"
