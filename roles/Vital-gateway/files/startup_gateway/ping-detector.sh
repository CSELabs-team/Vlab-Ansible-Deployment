#!/bin/bash
HOSTS="Vlab-xen1 Vlab-xen2 Vlab-xen3 Vlab-xen4"
COUNT=4

for myHost in $HOSTS
    do
        count=$(ping -c $COUNT $myHost | grep 'received' | awk -F ',' '{print $2}' | awk '{print $1}')
	if [ $count -eq 0 ]
            then
                #echo "Host : $myHost is down (ping failed) at $(date)"
		psql -d vlab -t --command "UPDATE vlab_interim.lb_group SET group_id='1111' WHERE xen_server='${myHost}'" 
            else 
	        #echo "GOOD Alive!"
		psql -d vlab -t --command "UPDATE vlab_interim.lb_group SET group_id='1' WHERE xen_server='${myHost}'"
  	fi
    done
