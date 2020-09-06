BEGIN{
	lets[17]="Esplanade"
	lets[18]="Adi"
        lets[19]="Blue"
        lets[20]="Chris"
        lets[21]="Danny"
        lets[22]="Elizabeth"
        lets[23]="Freya"
        lets[24]="Glee"
        lets[25]="H-Bar"
        lets[26]="Iocane"
	lets[27]="Jenny Lane"
        lets[28]="Liz"


	for(i=2;i<10;i+=.5){
		min = ":00"
		if (i-int(i)==.5)min=":30"
		printf("INSERT INTO achievements (achid, name, description, flufftext, impact, max_num) VALUES (%d, \"Master of Time, %d%s\", \"First to occupy the %d%s sector\", \"\", 2, 1);\n",(i*2)-3,int(i),min,int(i),min);
	}
	for(i=17;i<=28;i++){
                printf("INSERT INTO achievements (achid, name, description, flufftext, impact, max_num) VALUES (%d, \"Master of Space, %s\", \"First to occupy radial road %s\", \"\", 2, 1);\n",i,lets[i],substr(lets[i],0,1));
        }
}
