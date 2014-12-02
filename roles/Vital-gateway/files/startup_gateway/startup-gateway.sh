#!/bin/bash

# create temp directory
mkdir /tmp/vlab
mkdir /tmp/vlab/vncpwd
chown -R www-data\: /tmp/vlab/

cd /home/vlab_scp/vmnet_conf/vlab-natdhcp/

# uncomment the below line to setup VLAN 12 to be used without internet access
./nat-start.sh 12

# uncomment the below line to setup VLAN 14 to be used with internet access
#./nat-start.sh 14 +internet

# uncomment the following 3 lines to allow ping to sftp server
SERVER_IP="128.238.66.35"
iptables -I FORWARD 1 -p icmp --icmp-type 8 -s 0/0 -d $SERVER_IP -m state --state NEW,ESTABLISHED,RELATED -j ACCEPT
iptables -I FORWARD 1 -p icmp --icmp-type 0 -s $SERVER_IP -d 0/0 -m state --state ESTABLISHED,RELATED -j ACCEPT

#iptables -t nat -A POSTROUTING -s 10.12.1.0/24 -o eth0 -j MASQUERADE
iptables -t nat -A POSTROUTING -s 192.168.35.0/24 -o eth0 -j MASQUERADE
#iptables -A POSTROUTING -t nat -o eth0 -j MASQUERADE
#brctl addbr xenbr0
#brctl addif xenbr0 eth2

# startup controller
cd /home/vlab_scp/cntl/interim/ 

#screen -d -m php cntl_interim-new.php
nohup php cntl_interim-new.php >> /var/log/controller_interim.log 2>&1 &
