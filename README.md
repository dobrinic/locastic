## Hello

#### Open terminal in that location and paste following line to download project.
```shell
git clone https://github.com/dobrinic/locastic.git
```

#### run
```shell
cp ./.env.example .env
make init
```

#### or
```shell
docker-compose up -d
docker-compose exec -u www-data app composer install
docker-compose exec -u www-data app php bin/console doctrine:migrations:migrate --no-interaction
docker-compose exec -u www-data app yarn install
docker-compose exec -u www-data app yarn encore dev
```

#### wisit the app on:
```shell
http://localhost:8080/
```