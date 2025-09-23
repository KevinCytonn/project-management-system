FROM php:8.3-fpm

WORKDIR /var/www/html


RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zip curl \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs
    
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer



EXPOSE 9000

CMD ["php-fpm"]
