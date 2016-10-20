FROM php:7.1-rc-apache

RUN docker-php-ext-install pdo pdo_mysql

ADD vhost.conf /etc/apache2/sites-available/tracker.conf

RUN a2dissite 000-default default-ssl
RUN a2ensite tracker
