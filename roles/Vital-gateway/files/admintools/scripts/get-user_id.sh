#!/bin/bash


psql -d vlab --command "SELECT user_id FROM vlab_interim.user WHERE user_name='$1'"
