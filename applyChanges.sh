#!/bin/bash

# This is a helper script mainly for development purposes
# It stores the database before rebuilding the whole stack and restarting it
# It is used to bring changes in the source code to the application

# create database backup before rebuilt
./db-service/backupDB.sh

# stop compose stack, rebuild it and then start again
docker-compose down
docker-compose build
docker-compose up -d

exit
