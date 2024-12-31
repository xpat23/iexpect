FROM php:8.3-fpm
RUN apt-get update

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev
RUN docker-php-ext-install zip

RUN apt-get update && \
        apt-get install -y git

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p /app


RUN useradd -u 1000 -m user

RUN chown user:user /app

USER user

WORKDIR /app