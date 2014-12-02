#!/bin/bash

# Please running under home as root Ubuntu 14.04.1 + Xen 4.4.1
# Hareware RAID1 GPT partition table

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
# auto eth0
#        iface eth0 inet manual
#        bond-master bond0

# auto eth1
#        iface eth1 inet manual
#        bond-master bond0

# auto bond0
#        iface bond0 inet static
#        address 192.168.35.33
#        netmask 255.255.255.0
#        gateway 192.168.35.1
#        dns-nameservers 8.8.8.8
#        dns-search poly.edu
#        slaves none
#        bond_mode 0
#        bond_miimon 1000
#        bond_downdelay 0
#        bond_updelay 0

# EDIT /etc/fstab
： '
Vlab-gluster1:/vlab/OS_images      /home/OS_images glusterfs defaults,_netdev 0 0
Vlab-gluster1:/vlab/OS_images      /home/local_blade/OS_images glusterfs defaults,_netdev 0 0
Vlab-gluster1:/vlab/vlab_scp       /home/vlab_scp  glusterfs defaults,_netdev 0 0
Vlab-gluster1:/vlab/vmdsk          /home/vmdsk     glusterfs defaults,_netdev 0 0
Vlab-backup:/mnt/backup/            /home/backup    glusterfs defaults,_netdev 0 0
'
# EDIT /etc/rc.local
: '
@reboot root sh /bld-startup/bld-network-startup.sh
'

sudo apt-get update
sudo apt-get upgrade
sudo apt-get apt-get dist-upgrade
sudo apt-get install bridge-utils vlan ifenslave nfs-common xen-hypervisor-amd64 glusterfs-client
sudo modprobe 8021q
sudo modprobe bonding
echo "bonding" >> /etc/modules
echo "8021q" >> /etc/modules

cd /
mkdir /bld-startup
mkdir -p /home/local_blade/OS_images

#EDIT /etc/default/grub with
: '
GRUB_CMDLINE_XEN="dom0_mem=32G,max:32G dom0_max_vcpus=12 dom0_vcpus_pin iommu=1 loglvl=all guest_loglvl=all com1=9600,8n1,0x3e8,5 console=com1,vga"
GRUB_CMDLINE_LINUX="loglevel=9 console=ttyS0 earlyprintk=xen"
GRUB_TERMINAL="console serial"
'
#Upload files to /bld-startup/*
sudo reboot

