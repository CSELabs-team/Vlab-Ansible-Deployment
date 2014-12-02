#!/bin/bash


psql -d vlab --command "select * from vlab_interim.user where user_name='$1'"
