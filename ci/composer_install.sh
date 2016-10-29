#!/bin/sh -ex

startDir=`pwd`

git clone "$SOURCE" "$DESTINATION"

cd "$DESTINATION"

! tar xzf vendor-cache.tgz

if [ "$MODE" = "production" ]; then
  composer install --no-interaction --no-progress --no-suggest --no-dev --no-scripts --no-plugins --optimize-autoloader
else
  composer install --no-interaction --no-progress --no-suggest
fi

tar czf vendor-cache.tgz vendor/
