#!/bin/bash


psql -d vlab --command "SELECT sftp_user, sftp_pass FROM vlab_interim.sftp WHERE user_id=(SELECT user_id FROM vlab_interim.user WHERE user_name='$1')"
