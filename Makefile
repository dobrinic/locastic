default: up

init:
	docker-compose up -d
	docker-compose exec -u www-data app composer install
	docker-compose exec -u www-data app php bin/console doctrine:migrations:migrate --no-interaction
	docker-compose exec -u www-data app yarn install
	docker-compose exec -u www-data app yarn encore dev

up:
	docker-compose up -d

down:
	docker-compose down

watch:
	docker-compose exec -u www-data app yarn encore dev --watch

composer:
	docker-compose exec -u www-data app composer install

cache:
	docker-compose exec -u www-data app php bin/console cache:clear