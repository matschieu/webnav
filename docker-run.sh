#!/bin/bash

docker build -f Dockerfile-run -t webnav .
docker run -p 8080:80 webnav

