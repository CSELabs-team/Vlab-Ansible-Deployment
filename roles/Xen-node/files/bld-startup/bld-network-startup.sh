#!/bin/bash

mount -t glusterfs Vlab-gluster1:/vlab /mnt/vlab-datastore

xl sched-credit -d Domain-0 -w 2048

#Disable Netfilter on Bridges (Xen xl usage)
iptables -I FORWARD -m physdev --physdev-is-bridged -j ACCEPT
#
#Enable NAT & DHCP on Bridges (Xen xl usage)
#iptables -t nat -A POSTROUTING -o bond0 -j MASQUERADE
#

# mount /home/vmdsk
# mount /home/OS_images
# mount /home/vlab_scp

#xl testing
#vlan=66
#vconfig add bond0 $vlan
#ifconfig bond0.$vlan up
#brdg="xenbr0"
#if="eth1"
#if="bond0.$vlan"
#brctl addbr $brdg
#ifconfig $brdg up
#brctl addif $brdg $if
#
#ovsdb-server --remote=punix:/usr/local/var/run/openvswitch/db.sock --remote=db:Open_vSwitch,manager_options --pidfile --detach
#ovs-vswitchd --pidfile --detach

#JL Disable for test
#ovsdb-server --remote=punix:/usr/local/var/run/openvswitch/db.sock --remote=db:Open_vSwitch,Open_vSwitch,manager_options --pidfile --detach
#ovs-vsctl add-br br0
#ovs-vsctl add-port br0 bond0
/bld-startup/create-vm-bonds.sh
/bld-startup/create-vm-networks.sh 10 1000 # student20-student300
#ovs-vsctl set interface bond0 other-config:enable-vlan-splinters=true

#Xen-node for WebHost
#vconfig add bond0 66
#ifconfig bond0.66 up
#brctl addbr Public_Bridge
#ifconfig Public_Bridge up
#brctl addif Public_Bridge bond0.66
