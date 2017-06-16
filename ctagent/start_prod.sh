#!/bin/bash
CURDIR=`pwd`
CONFIG="$CURDIR/conf/ct_prod.conf"
LOGCONF="$CURDIR/conf/log4go_prod.xml"

#cd /root/sendop_v1.2/ &&\
nohup ./bin/ctagent -conf=$CONFIG -log=$LOGCONF > /dev/null 2>&1 &
echo $! > pid

