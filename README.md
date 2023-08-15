# Hello

### run
`cp ./.env.example .env`
`make init`

### or
`docker-compose up -d`
`docker-compose exec -u www-data app composer install`
`docker-compose exec -u www-data app php bin/console doctrine:migrations:migrate --no-interaction`
`docker-compose exec -u www-data app yarn install`
`docker-compose exec -u www-data app yarn encore dev`
`

### wisit the app on:
`http://localhost:8080/`