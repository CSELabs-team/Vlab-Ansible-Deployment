#!/bin/bash

for i in {$1..$2}
do
		echo vlab-bld-$i
		#perl list-running.pl vlab-bld-$i vlab vlabpass
		ssh vlab-bld-$i xm list

done

