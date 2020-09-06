#!/bin/csh -f

foreach file (*.php)
 diff -q $file ../$file
end
