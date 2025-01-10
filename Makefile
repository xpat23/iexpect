
build:
	docker-compose up --build -d
up:
	docker-compose up -d

down:
	docker-compose down

ssh:
	docker-compose exec php bash

install:
	docker-compose run --rm --no-deps php composer install

composer-dump:
	docker-compose run --rm --no-deps php composer dump-autoload

ps:
	docker-compose ps

env:
	cp .env.dist .env


test:
	docker-compose run --rm --no-deps php  php iexpect tests/