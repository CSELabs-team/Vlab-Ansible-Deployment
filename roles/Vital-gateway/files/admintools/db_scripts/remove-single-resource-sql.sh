#!/bin/bash

vm_name=$1

  echo "DELETE FROM  vlab_interim.vm_resource WHERE vm_name = '${vm_name}';" 
