#!/bin/csh -f

cat sweet/labelArr.txt | sed 's/ 1\/4/\:15/' | sed 's/ 3\/4/\:45/' | sed 's/ 1\/2/\:30/' | awk '{if(int($3)==$3)$3=$3.":00";ls=l=5+index("ABCDEFGHIJKL",$5);ns=n=($3+substr($3,3,2)/60)*4-9;if(l==10&&(n%6==1||n==11||n==15))ls++;if(l<=10)n--;printf("INSERT INTO blocks (blockid, time, letter, begin_row, end_row, begin_sec, end_sec) VALUES (%3d, \"%4s\", \"%s\", %d, %d, %d, %d);\n", $1,$3,$5,l,ls,n,ns)}'
