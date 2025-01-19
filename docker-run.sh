#!/bin/bash

docker build -f Dockerfile-run -t webnav .
docker run --rm -p 8080:80 webnav

