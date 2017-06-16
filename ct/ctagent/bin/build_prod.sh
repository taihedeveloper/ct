#!/bin/bash

cd ../src/app/main/
go build -o ctagent
mv ../src/app/main/ctagent ./
