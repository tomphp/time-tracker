#!/bin/sh -xe

SCRIPT_DIR=`dirname $0`
PROJECT_DIR=`dirname $SCRIPT_DIR`

pushd $PROJECT_DIR

pwd

composer install --no-plugins --no-scripts

php-cs-fixer fix --dry-run

vendor/bin/phpunit --test-suite unit
vendor/bin/behat -p features
vendor/bin/behat -p integration

popd
