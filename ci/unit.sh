#!/bin/sh -xe

START_DIR=`pwd`
SCRIPT_DIR=`dirname $0`
PROJECT_DIR=`dirname $SCRIPT_DIR`

cd $PROJECT_DIR

pwd

composer install --no-plugins --no-scripts

vendor/bin/php-cs-fixer fix --dry-run

vendor/bin/phpunit --testsuite unit
vendor/bin/behat -p features
vendor/bin/behat -p integration

cd $START_DIR
