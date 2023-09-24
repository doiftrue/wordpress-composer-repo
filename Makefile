
container=COMPOSER_WP_REPO_php
image=composer_wp_repo_php

build:
	docker build --tag $(image) .

run:
	docker run --rm -d -v ./:/var/www/app --name $(container) $(image) sleep infinity
	make connect

connect:
	docker exec -it --user=www-data $(container) bash

connect.root:
	docker exec -it --user=root $(container) bash

stop:
	docker stop $(container)
