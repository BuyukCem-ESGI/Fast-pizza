## install

### init .env file
```
cd api
cp .env.dist .env
```
### Docker
```
docker-compose up -- build
```
### Load fixture
```
docker-compose exec php \
bin/console hautelook:fixtures:load
```

## Voir les requètes qui seront jouer avec force
```
docker-compose exec php bin/console doctrine:schema:update --dump-sql
```

## Executer les requètes en DB
```
docker-compose exec php bin/console doctrine:schema:update --force
```
