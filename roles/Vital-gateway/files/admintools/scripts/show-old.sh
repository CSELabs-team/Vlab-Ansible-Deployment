#!/bin/bash

psql -d vlab --command "select * from vlab_interim.vm_resource where timestamp < (now() -'2 days'::interval) and current_state=10 and xen_server != 'vlab-bld-20'"
