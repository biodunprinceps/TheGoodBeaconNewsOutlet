FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
  git \
  curl \
  libpng-dev \
  libonig-dev \
  libxml2-dev \
  libpq-dev \
  libicu-dev \
  libzip-dev \
  zip \
  unzip \
  && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
  pdo \
  pdo_pgsql \
  mbstring \
  exif \
  pcntl \
  bcmath \
  gd \
  intl \
  zip

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
  && apt-get install -y nodejs \
  && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www

# Copy existing application directory
COPY . /var/www

# Change ownership
RUN chown -R www-data:www-data /var/www

EXPOSE 8000
CMD ["tail", "-f", "/dev/null"]
