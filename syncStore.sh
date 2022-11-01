#!/bin/bash

for((i=1;i<=10000000;i++));
do
curl "http://long-test.iswoole.com/api/indexSync/store"
done
