sed -r 's/(<div>Sector:  )([[:digit:]]+)/\1 <?php echo $blockname[\2] ?>/' BRCMap_index.php > BRCMap_index.temp.php

