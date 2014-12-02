#!/bin/bash

#if [ $# -ne 2 ]
#then
#	echo "USAGE: $0 <start index> <end index>"
#fi
#
#let sindex=$1
#let eindex=$2

let sindex=1
let eindex=1000

for ((i=sindex; i <= eindex; i++))
do

psql -d vlab --command "insert into vlab_interim.user (user_name, user_pass) values ('student$i', '`./pwgen.sh [:alnum:] 8 8`')"

psql -d vlab --command "insert into vlab_interim.sftp (user_id, sftp_user, sftp_pass) values ((select user_id from vlab_interim.user where user_name = 'student$i'), 'vlab_student$i', 'not set')"

done
