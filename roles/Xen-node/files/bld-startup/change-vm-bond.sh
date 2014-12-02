#!/bin/bash

  old_brdg=$1
  new_brdg=$2
  bond=$3

	echo "removing $bond on $old_brdg"
	ifconfig $old_brdg down
	brctl delbr $old_brdg 
	echo "Creating $bond on $new_brdg"
	brctl addbr $new_brdg 
	ifconfig $new_brdg up
	ifconfig $bond up
	brctl addif $new_brdg $bond 


