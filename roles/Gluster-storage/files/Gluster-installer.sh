#!/bin/bash
# Please running under home as root Ubuntu 14.04.1
# Hareware RAID10 GPT partition table

# Manually edit
# EDIT /etc/hosts
# 192.168.35.1 Vlab-vital
# 192.168.35.11 Vlab-gluster1
# 192.168.35.22 Vlab-gluster2
# 192.168.35.33 Vlab-xen1
# 192.168.35.44 Vlab-xen2
# 192.168.35.55 Vlab-xen3
# 192.168.35.66 Vlab-xen4
# 192.168.35.99 Vlab-backup

# EDIT /etc/networks/interface
#auto eth2
#   iface eth2 inet manual
#   bond-master bond0

#auto eth3
#   iface eth3 inet manual
#   bond-master bond0

#auto eth4
#   iface eth4 inet manual
#   bond-master bond0

#auto eth5
#   iface eth5 inet manual
#   bond-master bond0

# auto bond0
#   iface bond0 inet static
#   address 192.168.35.22
#   gateway 192.168.35.1
#   netmask 255.255.255.0
#   dns-nameservers 8.8.8.8
#   dns-search poly.edu
#   slaves none
#   bond_mode 0
#   bond_miimon 1000
#   bond_downdelay 0
#   bond_updelay 0

sudo apt-get update
sudo apt-get upgrade
sudo apt-get apt-get dist-upgrade
sudo apt-get install vlan bridge-utils ifenslave glusterfs-server
sudo modprobe 8021q
sudo modprobe bonding
echo "bonding" >> /etc/modules
echo "8021q" >> /etc/modules

# In both machines run the command gluster peer probe
gluster peer probe Vlab-gluster1
gluster peer probe Vlab-gluster2
gluster volume create vlab transport tcp Vlab-gluster1:/mnt/data Vlab-gluster2:/mnt/data

mkdir -p /mnt/data/OS_images
mkdir -p /mnt/data/vmdsk
mkdir -p /mnt/data/vlab_scp
mkdir -p /mnt/data/local_blade/OS_images

rsync -av /mnt/data/OS_images to /mnt/data/local_blade/OS_images

