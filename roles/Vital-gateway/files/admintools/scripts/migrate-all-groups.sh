#!/bin/bash

if [ $# = 0 ]; then

  echo "$0 <from xen_server> <to xen_server> <start student id> <stop student id> [<stepping>]"
  exit 1;
fi

# migrate-all-groups.sh <from xen_server> <to xen_server> <start student id> <stop student id> [<stepping>]
echo -- $1 $2 $3 $4
from_server=$1
to_server=$2
let sindex=$3
let eindex=$4

if [ $# = 5 ]; then
	let step=$5
else
	let step=1
fi


for ((i=sindex; i <= eindex; i=$(( $i + $step )) ))
do
	bash ./migrate-group.sh ${i} ${from_server} ${to_server} 

	
done

