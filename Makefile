
container=WP_COMPOSER_REPO_php
image=wp_composer_repo_php

build:
	docker build --tag $(image) .

generate:
	docker run --rm -d -v ./:/var/www/app --name $(container) $(image) php update.php

run:
	docker run --rm -d -v ./:/var/www/app --name $(container) $(image) sleep infinity
	make connect

connect:
	docker exec -it --user=www-data $(container) bash

connect.root:
	docker exec -it --user=root $(container) bash

stop:
	docker stop $(container)
