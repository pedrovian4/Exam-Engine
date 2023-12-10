FROM php:8.1-cli-alpine


RUN apk add --no-cache \
  openssl \
  bash \
  unzip \
  vim \
  libzip-dev \
  zlib-dev \
  libsodium-dev \
  icu-dev



RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /usr/src/app

COPY . /usr/src/app

RUN composer install

EXPOSE 4000

CMD ["vendor/bin/phpunit"]
