FROM php:8.3-fpm-alpine

# Install runtime system dependencies (non-dev)
RUN apk add --no-cache \
    git \
    curl \
    libpng \
    libxml2 \
    zip \
    bash \
    unzip \
    oniguruma \
    icu \
    freetype \
    libjpeg-turbo \
    libzip \
    supervisor \
    openssh-server \
    openssh-client \
    nginx \
    apache2-utils \
    dcron \
    nodejs \
    npm

# Install build dependencies, compile PHP extensions (including soap), then remove build deps
RUN apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        autoconf \
        gcc \
        g++ \
        make \
        libxml2-dev \
        libpng-dev \
        freetype-dev \
        libjpeg-turbo-dev \
        oniguruma-dev \
        icu-dev \
        libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql mbstring exif pcntl bcmath gd intl zip soap \
    && pecl install redis mongodb \
    && docker-php-ext-enable redis mongodb \
    && apk del .build-deps

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure SSH
RUN mkdir /var/run/sshd \
    && echo 'root:root' | chpasswd \
    && sed -i 's/#PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config \
    && ssh-keygen -A

# Configure PHP-FPM
RUN sed -i 's/listen = 127.0.0.1:9000/listen = 9000/' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/;listen.owner = www-data/listen.owner = www-data/' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/;listen.group = www-data/listen.group = www-data/' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/;listen.mode = 0660/listen.mode = 0660/' /usr/local/etc/php-fpm.d/www.conf

# Set working directory
WORKDIR /var/www/html

# Configure nginx user and directories
RUN adduser -D -s /bin/sh nginx nginx || true \
    && mkdir -p /var/log/nginx /var/lib/nginx/tmp /var/cache/nginx \
    && chown -R nginx:nginx /var/log/nginx /var/lib/nginx /var/cache/nginx \
    && chmod -R 755 /var/log/nginx /var/lib/nginx /var/cache/nginx

# Copy application files
COPY . /var/www/html

# Copy configurations
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf /etc/nginx/conf.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/start.sh /start.sh

# Create .htpasswd file
RUN touch /etc/nginx/.htpasswd

# Make start script executable
RUN chmod +x /start.sh

# Create required directories and set permissions before composer install
RUN mkdir -p /var/www/html/bootstrap/cache /var/www/html/storage/app /var/www/html/storage/framework/cache /var/www/html/storage/framework/sessions /var/www/html/storage/framework/views /var/www/html/storage/logs \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

RUN curl -fsSL https://bun.sh/install | bash && \
    mv /root/.bun/bin/bun /usr/local/bin/bun || true

RUN cp .env.example .env || true
# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist || true

# Generate Laravel application key and cache (if .env exists)
RUN if [ -f .env ]; then php artisan key:generate --force; fi \
    && if [ -f .env ]; then php artisan config:cache; fi \
    && if [ -f .env ]; then php artisan route:cache; fi \
    && if [ -f .env ]; then php artisan view:cache; fi

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# Create log directories
RUN mkdir -p /var/log/supervisor /var/log/nginx

# Expose ports
EXPOSE 80 443 22 9000

# Start supervisor which will manage all services
CMD ["/bin/sh", "/start.sh"]
