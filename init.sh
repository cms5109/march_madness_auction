#!/bin/sh
cd sql
./init.sh
cd ..
cd scripts
./update_static_arrays.sh

