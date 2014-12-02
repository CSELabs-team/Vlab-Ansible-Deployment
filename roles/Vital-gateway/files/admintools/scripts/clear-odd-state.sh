#!/bin/bash

psql -d vlab -t --command "update vlab_interim.vm_resource set requested_state=current_state where (current_state!= 10 or requested_state!=10) and (current_state!=11 or requested_state!=11)"
