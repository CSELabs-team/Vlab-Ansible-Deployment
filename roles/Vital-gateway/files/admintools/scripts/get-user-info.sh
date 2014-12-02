#!/bin/bash


psql -d vlab --command "SELECT * FROM vlab_interim.user WHERE user_name='$1'"
