#!/bin/bash

psql -d vlab --command "select vm_name from vlab_interim.vm_resource where current_state=10"
