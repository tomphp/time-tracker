FROM tomoram/time-tracker-php

ADD apache/vhost.conf /etc/apache2/sites-available/tracker.conf

RUN a2dissite 000-default default-ssl
RUN a2ensite tracker

RUN mkdir -p /var/www/html
COPY . /var/www/html
