FROM php:8.3.10-apache

LABEL maintainer=Matschieu

WORKDIR /var/www/html/

COPY ./*.php .
COPY conf conf
COPY core core
COPY i18n i18n
COPY img img
COPY lib lib
COPY styles styles

HEALTHCHECK CMD curl http://localhost/index.php --fail > /dev/null

