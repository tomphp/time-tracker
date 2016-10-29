# bash -ex

git clone "$SOURCE" "$DESTINATION"
cd "$DESTINATION"

if [ "$MODE" = "production" ]; then
  composer install --no-interaction --no-ansi --no-dev --no-scripts --no-plugins --optimize-autoloader
else
  composer install --no-interaction --no-ansi
fi
