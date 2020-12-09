FROM php:7.2-fpm-alpine
WORKDIR /codility-management

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /codility-management

RUN docker-php-ext-enable sodium
RUN composer install --ignore-platform-reqs
RUN chmod -R 0777 "/codility-management/storage"
RUN chmod +x "/codility-management/docker/docker-entrypoint-staging.sh"
RUN docker-php-ext-install pdo pdo_mysql
EXPOSE 8000

ENTRYPOINT [ "/bin/sh","/codility-management/docker/docker-entrypoint-staging.sh" ]