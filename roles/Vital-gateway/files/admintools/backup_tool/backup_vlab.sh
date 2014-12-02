#!/bin/sh -x

#This script will back up Vlab core files and encrypt them.
#After the encryption is complete, the files are copied to the ISIS backup
#All tars will be stored in the same locations as this script
#
#Usage: ./backup_vlab.sh files2bk
#By: Luis E. Garcia II
#
#Note: I could have done this with perl/php/etc but wanted to do more
#bash scripting

#basic configuration
encrypt_name="Efstratios Gavas"
backup_acct="vlabscripts@128.238.66.140"
backup_dir="~/vitalbackups"
temp_dir="/home/luis/massbkup/"

if [ $# -ne 1 ]; then
	echo "Usage: ./backup_vlab.sh files2bkup";
	echo "files2bkup - a file that list files and directories to backup.";
	echo "           The file format is one file or directory per line.";	
	exit 22;
fi

#set if new file added
changed=0

#Check if a new file was added to files2bkup
if [ ! -e list.status ]; then
	num=$(wc -l files2bkup > list.status)
else
	newNum=$(head -n1 list.status | sed -r 's/\s+[a-z0-9]+//i')
	oldNum=$(wc -l files2bkup | sed -r 's/\s+[a-z0-9]+//i')
	
	if [ $oldNum -ne $newNum ]; then 
		wc -l files2bkup > list.status 
		sed -r 's/:[0-9]+//' bkfile_modt | diff files2bkup - | grep '<' | sed -r 's/<\s+//' > newfiles

		changed=1
	fi	
fi

#Let first verify if bkfile_modt exist if not generate it
if [ ! -e bkfile_modt ]; then

	echo "bkfile_modt does not exist."
	echo "Generating bkfile_modt"

	if [ -z "${1}" ]; then
		echo "Usage: ./backup_vlab.sh files2bk";
		echo "files2bk - a file that list files and directories to backup.";
		echo "           The file format is one file or directory per line.";	
		exit 22;
	fi

	#get list of files to backup

	while read line
	do
		if [ $line != "postgresql" ]; then

			stat -c '%n:%Y' $line >> bkfile_modt
			
			#Check if any files are not found, if so stop		
			if [ $? -ne 0 ]; then

				rm bkfile_modt
				echo "See error messages"
				echo "script failed. File not found."
				echo "Exiting"

				exit 1
			fi
	
			fname=$(stat -c '%n' $line)
			file=$(echo $fname | sed -r 's_(/?[a-z0-9_-]+/)+__')
	
			tar cvzPf "${file}.tar.gz" "${fname}"
			gpg -r "${encrypt_name}" -e "${file}.tar.gz"
			scp -B -C -q "${file}.tar.gz.gpg" ${backup_acct}:${backup_dir}
		else
			#postgresql = postgresql
			echo $line >> bkfile_modt
			fname=$(echo $line | sed -r 's_:[0-9]+__')
			pg_dumpall | gzip > "${fname}.gz"
			gpg -r "${encrypt_name}" -e "${file}.tar.gz"
			scp -B -C -q "${file}.tar.gz.gpg" ${backup_acct}:${backup_dir}
		fi
		
	done < $1

	unset file
	unset fname
	
	exit 0

fi
#else #[ -e bkfile_modt ]; then

#Check file to verify when files were last moditify

while read line
do
	fname=$(echo $line | sed -r 's_:[0-9]+__')
	
	if [ $fname != "postgresql" ]; then
		lastMod=$(echo $line | sed -r 's_(/?[a-z0-9_-]+/?)*[a-z0-9_-]+(\.[a-z0-9_-]+)?:__')
		current=$(stat -c '%Y' $fname)

	else
		pg_dumpall | gzip > "${temp_dir}/${fname}.gz"
		gpg -r "${encrypt_name}" -e "${file}.tar.gz"
		scp -B -C -q "${file}.tar.gz.gpg" ${backup_acct}:${backup_dir}
	fi

	#if file was changed mark it to be backed up
	if [ $lastMod -lt $current ]; then
	
		echo $line | sed -r "s/$lastMod/$(stat -c '%Y' $fname)/" >> bkfile_modt2
		file=$(echo $fname | sed -r 's_(/?[a-z0-9_-]+/)+__')
		tar czvPf "${file}.tar.gz" "${fname}"
		gpg -r "${encrypt_name}" -e "${file}.tar.gz"
		scp -B -C -q "${file}.tar.gz.gpg" ${backup_acct}:${backup_dir}
	else
		#stat -c '%n:%Y' $fname >> bkfile_modt2
		echo $line >> bkfile_modt2
	fi

done < bkfile_modt

#Any new files? tar/encrypt/scp them
if [ $changed -eq 1 ]; then
	while read line
	do
		stat -c '%n:%Y' $line >> bkfile_modt2

		if [ $? -ne 0 ]; then

			#rm newfiles
			echo "See error messages"
			echo "script failed. File not found."
			echo "Exiting"

			exit 1
		fi

		fname=$(stat -c '%n' $line)
		file=$(echo $fname | sed -r 's_(/?[a-z0-9_-]+/)+__')
	
		tar cvzPf "${file}.tar.gz" "${fname}"
		gpg -r "${encrypt_name}" -e "${file}.tar.gz"
		scp -B -C -q "${file}.tar.gz.gpg" ${backup_acct}:${backup_dir}

	done < newfiles
fi

rename -f 's/bkfile_modt2/bkfile_modt/' bkfile_modt2

