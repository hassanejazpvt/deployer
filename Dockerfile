FROM php:8.0-fpm

# Setting bash as shell
SHELL ["/bin/bash", "--login", "-c"]

# Update Dependencies
RUN apt-get update -y \
    && apt-get install vim curl zip libzip-dev -y

# Install PHP Extensions
RUN docker-php-ext-install zip pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install NVM
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.5/install.sh | bash

# Install Node v14
RUN nvm install v14

# Copy Files
COPY . /var/www/html

# Change Working Directory
WORKDIR /var/www/html

# Install Dependencies
RUN composer install --no-interaction
RUN npm install
RUN npm run dev

# Expose Port
EXPOSE 80