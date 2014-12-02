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
# auto eth0
#       iface eth0 inet manual
#       bond-master bond0

# auto bond0
#       iface bond0 inet static
#       address 192.168.35.1
#       netmask 255.255.255.0
#       dns-nameservers 8.8.8.8
#       dns-search poly.edu
#       slaves none
#       bond_mode 0
#       bond_miimon 1000
#       bond_downdelay 0
#       bond_updelay 0

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
@root sh /startup_gateway/startup-gateway.sh > /dev/null 2>&1
'
sudo apt-get update
sudo apt-get upgrade
sudo apt-get apt-get dist-upgrade
sudo apt-get install vlan bridge-utils ifenslave glusterfs-client

apt-get install -y apache2 postgresql php5 libapache2-mod-php5 php5-pgsql tcpdump libjpeg62 libjpeg8 libjpeg8-dev sendmail

echo 1 > /proc/sys/net/ipv4/ip_forward
sysctl -w net.ipv4.ip_forward=1
sudo modprobe 8021q
sudo modprobe bonding
echo "bonding" >> /etc/modules
echo "8021q" >> /etc/modules

# EDIT postgresql.conf located at /etc/postgresql/<version number>/main/postgresql.conf and
#set listen_addresses field to listen to the required IP addresses or give '*' to listen on all.

su postgres
createuser -a -d -P -U postgres root
createdb -O root vlab
psql -d vlab --command "\password postgres"
exit

#Afterwards, modify the authenication method near the bottom of the /etc/postgresql/<version num>/main/pg_hba.conf as follows:
#local   all         postgres                          ident
#local   all         postgres                          md5 //commented as per vital configurations
#local   all         root                              trust //commented as per vital configurations
#local 	 all 	     all 			       			   trust

service postgresql restart
psql -d vlab -U postgres -W
psql -d vlab

#Next, using the database scripts available from the deployment bundle, create and populate the tables:
psql -d vlab -f /home/gateway-installer/vlab-schema.sql
psql -d vlab -f /home/gateway-installer/vlab-data.sql
#Go to <bundleName>/config/db-setup  and run the below script
/home/gateway-installer/add_accounts.sh

#Next, you should set the passwords for the sftp accounts with the reset-sftp.sh
#script in the <bundleName>/config/gateway/admintools/scripts/ directory from the deployment bundle:

/home/gateway-installer/reset-pwd.sh 1 1000
#Create a file with sftp user ids and passwords (you may use script to generate the ids and passwords or enter manually) 
#The format of the <SFTP_UID/PWD_FILE> file is:
#<UID> <PWD>

#Each UID/PWD pair should be put on a new line.
#from the same location, vlab-deploy/config/gateway/admintools/scripts/ run the below script by feeding the input
#./reset-sftp.sh <SFTP_UID/PWD_FILE>

#/etc/apache2/sites-enabled/000-defualt file by added the following lines to the <VirtualHost *:80>directive:
#<Directory /var/www/>
#    Options Indexes FollowSymLinks MultiViews
#    AllowOverride None
#    Order allow,deny
#    allow from all
#</Directory>

#RewriteEngine On 
#RewriteCond %{HTTPS} off 
#RewriteRule (.*) https://%{HTTP_HOST}%{REQUEST_URI}

a2enmod ssl rewrite
a2ensite default-ssl

cp -r /home/gateway-installer/interim /var/www
cp -r /home/gateway-installer/glf /var/www

#And password protect (admin access only) /var/www/glf by adding the following lines to /etc/apache2/sites-enabled/default-ssl file:
#<Directory /var/www/glf>
#AuthType Basic
#AuthName "Restricted"
#AuthUserFile /usr/local/apache2/passwd/passwords
#Require user glfadmin
#</Directory>

#This directive assumes the admin user account name is glfadmin and the password hash is stored in the /usr/local/etc/apache2/passwd/passwords file.
#Create password hash using below command, also create the directories if path does not exists
htpasswd -b /usr/local/apache2/passwd/passwords glfadmin postgres
#The above command will prompt for the password

mkdir /var/log/vlab
chown www-data:www-data /var/log/vlab/
service apache2 restart

cat /home/gateway-installer/fstab >> /etc/fstab

# Before the controller can be setup, the network fileserver will need to be setup first. In the basic configuration, the network fileserver is on the Xen Node, which should export the /home/vlab_scp/ directory. Modify the /etc/fstab file with the following these additional lines (these additions assume the the NSF server is named vlab-new-store and the following mount points exist: /home/OS_Images, /home/vlab_scp, /home/vmdsk.
# Next you will have to copy the root ssh key onto the Xen Node following this guide. This will allow the controller scripts to connect to the Xen Node without password prompts.
# Then modify /home/vlab_scp/cntl/interim/config.php and /home/vlab_scp/cntl/interim/conf.php with the appropriate configuration information.

# lb_group // insert values manually into DB
# /home/gateway-installer/add-lb-item.sh 
# lb_mode // insert values manually into DB
# servers //insert values manually into DB

mkdir /startup_gateway

# Points To Remember
# ***** You need to remove log with name vlab_cntl.*, from /tmp to restart gateway (you start a gateway by running gateway startup script, startup-gateway.sh)

#Crontab -e or vim /etc/crontab
#*/10 * * * * /startup_gateway/stop-old.sh >> /var/log/vlab/cron-stop-old-output

# make RSA key to each xen-node and NFS server
#ssh-keygen -t rsa
#ssh-copy-id user@123.45.56.78

# SSL Cert

sudo reboot

