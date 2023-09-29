
container=WP_COMPOSER_REPO_php
image=wp_composer_repo_php

# scripts

update_repo:
	-docker stop $(container) && sleep 1
	docker run --rm -it --name $(container) -v ./:/var/www/app $(image) php run.php update

check_urls:
	-docker stop $(container) && sleep 1
	docker run --rm -it --name $(container) -v ./:/var/www/app $(image) php run.php check

# docker

build:
	docker build --tag $(image) .

run_container:
	docker run --rm -d --name $(container) -v ./:/var/www/app $(image) sleep infinity
	make connect

connect:
	docker exec -it --user=www-data $(container) bash

connect.root:
	docker exec -it --user=root $(container) bash

stop:
	docker stop $(container)
