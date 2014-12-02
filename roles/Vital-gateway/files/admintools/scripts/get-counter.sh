#!/bin/bash

psql -d vlab --command "select * from vlab_interim.counter where name='$1'"
