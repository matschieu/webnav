#!/bin/bash

docker build -f Dockerfile-test -t webnav-test .
docker run webnav-test

