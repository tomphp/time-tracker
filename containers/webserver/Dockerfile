FROM tomphp/time-tracker-php

ADD vhost.conf /etc/apache2/sites-available/tracker.conf

RUN a2dissite 000-default default-ssl
RUN a2ensite tracker
