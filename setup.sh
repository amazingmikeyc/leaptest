#!/usr/bin/env bash

docker-compose up -d

until [ "$(docker inspect --format='{{.State.Health.Status}}' "leaptest_web")" == "healthy" ]; do
  echo "Waiting for for web server to be ready..."
  sleep 2
done

#get composer things
echo "Installing Composer packages..."
docker exec leaptest_web composer install

#get npm things
echo "Installing NPM packages..."
docker exec leaptest_web npm install

echo "Building front end"
docker exec leaptest_web npm run build

until [ "$(docker inspect --format='{{.State.Health.Status}}' "leaptest_database")" == "healthy" ]; do
  echo "Waiting for for database to be ready..."
  sleep 2
done

#run migrations
echo "Running migrations..."
docker exec leaptest_web artisan migrate
