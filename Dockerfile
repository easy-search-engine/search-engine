FROM webdevops/php-apache-dev:7.1

ENV WEB_DOCUMENT_ROOT="/var/www/html/search-engine/public/"

WORKDIR /var/www/html/search-engine/

EXPOSE 80 443
