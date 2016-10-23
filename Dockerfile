FROM busybox

RUN mkdir -p /var/www/html
COPY . /var/www/html
WORKDIR /var/www/html
VOLUME /var/www/html
