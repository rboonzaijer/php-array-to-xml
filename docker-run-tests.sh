docker compose run --rm php7.0 php ./vendor/bin/phpunit -c tests/phpunit-7.0.xml -v && \
docker compose run --rm php7.1 php ./vendor/bin/phpunit -c tests/phpunit-7.1.xml -v && \
docker compose run --rm php7.2 php ./vendor/bin/phpunit -c tests/phpunit-7.2.xml -v && \
docker compose run --rm php7.3 php ./vendor/bin/phpunit -c tests/phpunit-7.3.xml -v && \
docker compose run --rm php7.4 php ./vendor/bin/phpunit -c tests/phpunit-7.4.xml -v && \
docker compose run --rm php8.0 php ./vendor/bin/phpunit -c tests/phpunit-8.0.xml -v && \
docker compose run --rm php8.1 php ./vendor/bin/phpunit -c tests/phpunit-8.1.xml && \
docker compose run --rm php8.2 php ./vendor/bin/phpunit -c tests/phpunit-8.2.xml && \
docker compose run --rm php8.3 php ./vendor/bin/phpunit -c tests/phpunit-8.3.xml && \
docker compose run --rm php8.4 php ./vendor/bin/phpunit -c tests/phpunit-8.4.xml && \
docker compose run --rm php8.5 php ./vendor/bin/phpunit -c tests/phpunit-8.5.xml && \
echo -e "\n\ndone.\n"
