
docker-run:
	docker build -f Dockerfile-run -t webnav .
	docker run --rm -p 8080:80 webnav
docker-test:
	docker build -f Dockerfile-test -t webnav-test .
	docker run --rm webnav-test
docker-clean:
	for i in `docker ps -a | grep webnav | cut -d " " -f 1`; do docker rm $i; done
	for i in `docker ps -a | grep "phpunit ." | cut -d " " -f 1`; do docker rm $i; done
	docker system prune -f --volumes
