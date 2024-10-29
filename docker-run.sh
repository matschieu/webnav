#!/bin/bash

docker build . -t webnav
docker run -d -p 8080:80 webnav

