#!/bin/bash

psql -d vlab -t --command "select vm_name from vlab_interim.vm_resource where current_state!=requested_state"
