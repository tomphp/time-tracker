#!/bin/bash

# This script gets automatically run by Cloud Foundary when starting an app instance.

cd /home/vcap/app
php/bin/php -c php/etc/php.ini php/bin/phinx migrate
