#!/bin/bash

psql -d vlab --command "update vlab_interim.vm_resource set requested_state=11 where requested_state!=11"
