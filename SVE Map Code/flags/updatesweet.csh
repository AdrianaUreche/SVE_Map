#!/bin/csh -f

cat sweet/labelArr.txt | sed 's/ 1\/4/\:15/' | sed 's/ 3\/4/\:45/' | sed 's/ 1\/2/\:30/' | awk '{if(int($3)==$3)$3=$3.":00";ls=l=5+index("ABCDEFGHIJKL",$5);ns=n=($3+substr($3,3,2)/60)*4-9;if(l==10&&(n%6==1||n==11||n==15))ls++;if(l<=11)n--;printf("UPDATE blocks SET begin_row=%d, end_row=%d, begin_sec=%d, end_sec=%d WHERE blockid = %d;\n", l,ls,n,ns, $1)}'
