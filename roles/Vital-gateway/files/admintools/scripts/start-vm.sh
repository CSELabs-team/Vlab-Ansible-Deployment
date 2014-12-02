#!/bin/bash


psql -d vlab --command "update vlab_interim.vm_resource set requested_state=10 where vm_name='$1'"
