#!/usr/bin/env bash

TAGPATH="labelArr.txt"

for (( line=1; line<261; line++ )); do

    i=$(awk -v n=$line 'NR==n {printf("\"%s\"",$1)}' $TAGPATH)
    address=$(awk -v n=$line 'NR==n {print $3,$4,$5,$6}' $TAGPATH)
    echo $i $address

    #awk -v ad=$address '{if ($0 ~ /id='$i'/) { printf("\t\t%s %s %s %s\n",$1,$2,ad,$4) } else {print $0}}' index.txt> index.new
    awk '{if ($0 ~ /id='$i'/) { printf("\t\t%s %s '"$address"' %s\n",$1,$2,$4) } else {print $0}}' BRCMap_index.html> index.new
    #s=$(awk '{if ($0 ~ /id='$i'/) {print $0} else {print $0}}' index.txt)
    #echo $s

    cp index.new BRCMap_index.html
    
   
done

