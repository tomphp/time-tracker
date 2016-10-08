FROM busybox

RUN mkdir -p /var/www/html

ADD behat.yml /var/www/html/behat.yml
ADD build.xml /var/www/html/build.xml
ADD config /var/www/html/config
ADD db /var/www/html/db
ADD phpunit.xml /var/www/html/phpunit.xml
ADD phinx.yml /var/www/html/phinx.yml
ADD public /var/www/html/public
ADD src /var/www/html/src
ADD test /var/www/html/test
ADD vendor /var/www/html/vendor

VOLUME /var/www/html
