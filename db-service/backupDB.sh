#!/bin/bash

# IMPORTANT: this script is executed by the apply changes helper script. 
# Manual execution only work from the directory above 

# create backup of the database and store it in the folder for automatic init after image rebuilds
docker exec custom-livestream-service_db_1 sh -c 'exec mysqldump -u root -p"$MYSQL_ROOT_PASSWORD" testDB' > ./db-service/init/testDB.sql

exit
