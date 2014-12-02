#!/bin/bash


psql -d vlab --command "select * from vlab_interim.vm_resource where vm_name='$1'"
