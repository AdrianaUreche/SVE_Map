#!/bin/csh -f

awk '{if(substr($1,1,2)=="id")id=substr($1,5,length($1)-5);if(flag==1)if(substr($1,length($1)-2,3)=="\"/>"){d=d substr($1,1,length($1)-3);flag=0;printf("UPDATE blocks SET geometry = \"%s\" WHERE blockid = %d;\n",d,id);d="";id=0}else{d=d $1};if(substr($1,1,1)=="d"&&id+0>0&&id+0<262){d=substr($1,4,length($1)-3);flag=1}}' BRCMap_index.html

