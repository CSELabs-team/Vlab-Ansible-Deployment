#!/bin/bash

psql -d vlab -t --command "update vlab_interim.vm_resource set requested_state=current_state, inprocess=0"
