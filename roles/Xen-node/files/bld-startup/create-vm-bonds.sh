#!/bin/bash

function create_bond {

  for vlan in $*
  do
    vconfig add bond0 $vlan
    #openvswitch command JL
    #ovs-vsctl set port bond0 tag=$vlan
    #JL
    ifconfig bond0.$vlan up
  done

}

function add_bridge_if {

  brdg=$1
  bond=$2

#  for foo in $*
#  do
	#bar=(`echo $foo | tr ',' ' '`)
	#brdg= ${bar[0]}
	#bond= ${bar[1]}
	echo "Creating $bond on $brdg"
	brctl addbr $brdg
	#openvswitch command JL
	#ovs-vsctl add-br $brdg
	#JL
	ifconfig $brdg up
	brctl addif $brdg $bond
	#openvswitch command JL
	#ovs-vsctl add-port $brdg $bond
	#JL
#  done
}

#create_bond 11 12 14 16 18 20 22 24 112
create_bond 12 14

#add_bridge_if Net-TEST	 bond0.11
add_bridge_if Net-CS6823 bond0.12
add_bridge_if Net-CS6373 bond0.14
#add_bridge_if inet-nat14 bond0.14
#add_bridge_if Net-CS6233 bond0.16
#add_bridge_if Net-CS6963 bond0.22
#add_bridge_if inet-nat18 bond0.18
#add_bridge_if inet-nat20 bond0.20

#JL
function openvswitch_bridge_vlan {
	bridge=$1
	vlan=$2
	echo "Creating Bridge: $bridge, Vlan tag: $vlan"
        #vconfig add bond0 $vlan
    	#ifconfig bond0.$vlan up
        #vconfig add bond0 $vlan
	ovs-vsctl add-br ovsbr0
	ovs-vsctl add-port ovsbr0 bond0
	ovs-vsctl add-br $bridge ovsbr0 $vlan
	ovs-vsctl set interface bond0 other-config:enable-vlan-splinters=true
}

#ovs-vsctl set interface bond0 other-config:enable-vlan-splinters=true
#openvswitch_bridge_vlan Net-CS6823 12
#JL
