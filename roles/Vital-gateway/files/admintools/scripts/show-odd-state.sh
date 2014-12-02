#!/bin/bash

psql -d vlab -t --command "select vm_name, current_state, requested_state, inprocess from vlab_interim.vm_resource where (current_state!= 10 or requested_state!=10) and (current_state!=11 or requested_state!=11)"
