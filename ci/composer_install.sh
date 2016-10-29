# bash -ex

cacheFile=time-tracker-vendor.tar.gz
bucket=ci-composer-cache
resource="/${bucket}/${cacheFile}"
contentType="application/x-compressed-tar"
dateValue=`date -R`
stringToSign="PUT\n\n${contentType}\n${dateValue}\n${resource}"
s3Key=$S3_KEY
s3Secret=$S3_SECRET
signature=`echo -en ${stringToSign} | openssl sha1 -hmac ${s3Secret} -binary | base64`

stringToSign="GET\n\ntext/plain\n${dateValue}\n${resource}"

curl -H "Date: ${dateValue}" \
     -H "Content-Type: ${contentType}" \
     -H "Authorization: AWS ${s3Key}:${signature}" \
     "https://s3-us-west-2.amazonaws.com/${resource}" \
     -o "$cacheFile"

if [ -e "$cacheFile" ]; then
  tar xzf "$cacheFile"
fi

git clone "$SOURCE" "$DESTINATION"
cd "$DESTINATION"

if [ "$MODE" = "production" ]; then
  composer install --no-interaction --no-ansi --no-dev --no-scripts --no-plugins --optimize-autoloader
else
  composer install --no-interaction --no-ansi
fi

# Backup to S3
tar czf "$cacheFile" vendor/

curl -X PUT -T "${cacheFile}" \
  -H "Host: ${bucket}.s3.amazonaws.com" \
  -H "Date: ${dateValue}" \
  -H "Content-Type: ${contentType}" \
  -H "Authorization: AWS ${s3Key}:${signature}" \
  https://${bucket}.s3.amazonaws.com/${cacheFile}

rm -f "$cacheFile"
