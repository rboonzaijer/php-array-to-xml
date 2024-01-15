# Development

When a change is made, each php version should be tested in isolation, because depending on the PHP version, composer is downloading different files.

The Github-ci will automatically performs the phpunit tests as a final check.

For local testing, this can be done with the prepared docker images, using docker compose.

### Running local PHPUnit tests

```
./docker-run-tests.sh
```

### Rebuild docker images

```
docker compose build
```

### Remove orphans

```
docker compose down --remove-orphans
```
