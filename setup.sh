#!/usr/bin/env bash
while true; do
    read -p "Do you wish to start the worker to process messages after the setup? (Yy/Nn): " yn
    case $yn in
        [Yy]* ) docker compose down --remove-orphans && docker compose build --no-cache && docker compose up --pull always -d --wait && docker-compose exec -t php bin/console doctrine:migrations:migrate -n && ./start_worker.sh; break;;
        [Nn]* ) docker compose down --remove-orphans && docker compose build --no-cache && docker compose up --pull always -d --wait && docker-compose exec -t php bin/console doctrine:migrations:migrate -n;;
        * ) echo "Please answer yes or no.";;
    esac
done
