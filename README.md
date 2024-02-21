### Running project

Open the terminal window in desired location and paste following lines to download the project and create environment file:
```shell
git clone https://github.com/dobrinic/locastic.git
cd locastic/
cp ./.env.example .env
```

If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)

If your system has GNU make installed you can use Makefile with command `make init` to build the project and use other shorthand methods.

If you don't have GNU make installed please run:
```shell
docker-compose up -d
docker-compose exec -u www-data app composer install
docker-compose exec -u www-data app php bin/console doctrine:migrations:migrate --no-interaction
docker-compose exec -u www-data app yarn install
docker-compose exec -u www-data app yarn encore dev
```

Visit the app on [localhost:8080](http://localhost:8080/)