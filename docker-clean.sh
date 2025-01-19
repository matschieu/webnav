#!/bin/bash

for i in `docker ps -a | grep webnav | cut -d " " -f 1`; do docker rm $i; done
for i in `docker ps -a | grep "phpunit ." | cut -d " " -f 1`; do docker rm $i; done
