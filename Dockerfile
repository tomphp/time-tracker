FROM php:7-cli

# Install ZLib (for composer)
RUN apt-get update \
    && apt-get install -y zlib1g-dev \
    && docker-php-ext-install zip

# Install PHP Curl extension (for composer)
RUN apt-get update \
    && apt-get install -y libcurl4-openssl-dev \
    && docker-php-ext-install curl

# Install git (for composer)
RUN apt-get update \
    && apt-get install -y git

RUN docker-php-ext-install pdo && docker-php-ext-install mysql

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === 'e115a8dc7871f15d853148a7fbac7da27d6c0030b848d9b3dc09e2a0388afed865e6a3d6b3c0fad45c48e2b5fc1196ae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

RUN mkdir -p /var/www/html

COPY . /var/www/html

RUN useradd -ms /bin/bash composer
RUN chown -R composer: /var/www/html

USER composer
WORKDIR /var/www/html

RUN /usr/local/bin/composer install --no-plugins --no-scripts

VOLUME /var/www/html
