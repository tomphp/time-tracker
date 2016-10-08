# Time Tracker

To get this repo up and running you need to run the following steps:

```
docker-compose up
```

Now wait wait until docker is up and running - use `docker-compose logs` to
check the status.

In a new window, run:

```
composer install
phing migrate-db:test
phing test
```

If all goes well, all tests should be green!
