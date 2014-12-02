#!/bin/bash


if [ $# -ne 4 ] 
then
   echo "USAGE: $0 <class number> <start index> <end index> <sql output file>"
   exit;
fi

#echo -- $1 $2 $3 $4 $5
#vm_name_base=$1
#let vnc_base=$2
#let sindex=$3
#let eindex=$4
#outfile=$5
course=$1
let sindex=$2
let eindex=$3
outfile="`pwd`/$4"


vm_name_bases=`psql -d vlab -t --command "select vm_name_base from vlab_interim.vnc_base where course='${course}'"`

#echo "psql -d vlab --command \"" >> $outfile
for vm_name_base in ${vm_name_bases[@]}
do

cd /home/egavas/backup/root/db_scripts/

vnc_base=`psql -d vlab -t --command "select vnc_base from vlab_interim.vnc_base where vm_name_base = '${vm_name_base}'"`

#echo "psql -d vlab --command \"" >> $outfile
#for ((i=sindex; i <= eindex; i++))
#do

	echo "adding $vm_name_base for $sindex to $eindex"

#	echo "#create-resource-sql $vm_name_base $vnc_base $sindex $eindex" >> $outfile
	bash ./create-resource-sql.sh $vm_name_base $vnc_base $sindex $eindex >> $outfile
#	echo "#create-reflectore-sql $vm_name_base $vnc_base $sindex $eindex" >> $outfile
	bash ./create-reflector-sql.sh $vm_name_base $sindex $eindex >> $outfile
#	echo "#create-user-reflector-sql $vm_name_base $vnc_base $sindex $eindex" >> $outfile
	bash ./create-user-reflector-sql.sh $vm_name_base $sindex $eindex >> $outfile

#done
#echo "\"" >> $outfile

#cd /home/vlab_scp/vm_conf/manual_configs/

#bash create-student-conf-new.sh $vm_name_base $vnc_base $sindex $eindex 

done

