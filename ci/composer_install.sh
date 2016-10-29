#!/bin/sh -ex

startDir=`pwd`

git clone "$SOURCE" "$DESTINATION"

! tar xzf "$CACHE_FILE" "$DESTINATION"

cd "$DESTINATION"

if [ "$MODE" = "production" ]; then
  composer install --no-interaction --no-progress --no-suggest --no-dev --no-scripts --no-plugins --optimize-autoloader
else
  composer install --no-interaction --no-progress --no-suggest
fi

tar czf "${startDir}/${CACHE_FILE}" vendor/
