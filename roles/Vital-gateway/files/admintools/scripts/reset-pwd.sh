#!/bin/bash

if [ $# -ne 2 ]
then
	echo "USAGE: $0 <start index> <end index>"
fi

#echo -- $1 $2 
let sindex=$1
let eindex=$2

for ((i=sindex; i <= eindex; i++))
do

psql -d vlab --command "update vlab_interim.user set user_pass='`./pwgen.sh [:alnum:] 8 8`' where user_name='student$i'"

done
