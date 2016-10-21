#!/bin/sh -xe

pwd

composer install --no-plugins --no-scripts

php-cs-fixer fix --dry-run

vendor/bin/phpunit --test-suite unit
vendor/bin/behat -p features
vendor/bin/behat -p integration
