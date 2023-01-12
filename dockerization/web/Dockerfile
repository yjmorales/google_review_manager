FROM php:7.4-apache

### Install OS tools
RUN apt-get update \
    && apt-get install --yes curl \
    libmcrypt-dev \
    libreadline-dev \
    libzip-dev \
    nano \
    git \
    vim \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libxpm-dev \
    libfreetype6-dev \
    python3-distutils \
    python3-apt \
    python3-pip \
    beanstalkd \
    libicu-dev \
    libxslt-dev \
\
### Install PHP extras
    && pecl install mcrypt \
    && apt-get update && apt-get install -y zlib1g-dev libmcrypt-dev \
    && docker-php-ext-install -j$(nproc) zip mysqli pdo pdo_mysql gd \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl xsl \
\
### Changing Ownership of document root directory to apache.
    && chown -R www-data:www-data /var/www/html

### Coping vhost template within apache sites.
COPY dockerization/web/000-default.conf /etc/apache2/sites-enabled/000-default.conf

### Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/bin/composer \
    && chmod +x /usr/bin/composer \
\
### Install Node
    && curl -sL https://deb.nodesource.com/setup_14.x | bash \
    && apt-get install --yes nodejs \
\
### Install Yarn
    && curl -o- -L https://yarnpkg.com/install.sh | bash \
    && export PATH="$HOME/.yarn/bin:$HOME/.config/yarn/global/node_modules/.bin:$PATH" \
\
### Install gulp
    && npm install gulp bower -g \
    && npm install \
\
### Install and starting `supervisord`
    && pip3 install supervisor \
    && echo_supervisord_conf > /etc/supervisord.conf \
    && supervisord -c /etc/supervisord.conf

### Starting `beanstalk` service
    # && service beanstalkd start

### Coping supervisord workers config files
COPY dockerization/web/supervidord_workers.conf /etc/supervisord.d/conf.d/workers.conf

### Exposing port 80 to the exterior.
EXPOSE 80