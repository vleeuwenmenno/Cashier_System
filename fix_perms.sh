#!/bin/bash
# Init
FILE="/tmp/out.$$"
GREP="/bin/grep"
#....
# Make sure only root can run our script
if [ "$(id -u)" != "0" ]; then
   echo "This script must be run as root" 1>&2
   exit 1
else
   echo Changing chmod for pdfs, deps and images to 755
   chmod 755 -R src/pdfs/
   chmod 755 -R deps/
   chmod 755 -R src/images/
   echo Changing chown for pdfs, deps and images to www-data
   chown www-data -R src/pdfs/
   chown www-data -R deps/
   chown www-data -R src/images/
   echo Finished!
fi