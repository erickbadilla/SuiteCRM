FROM ubuntu/apache2:2.4-22.04_beta

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN apt update -y && \
    apt install -y mariadb-client software-properties-common git unzip && \
    add-apt-repository -y ppa:ondrej/php

# Install php7.4 and set it as default. Also setting upload_max_filesize to 200M
RUN apt install -y php7.4-xdebug php7.4 php7.4-curl php7.4-gd php7.4-zip php7.4-imap php7.4-mbstring php7.4-intl php7.4-simplexml php7.4-dom php7.4-mysql
RUN update-alternatives --set php /usr/bin/php7.4

# Install compatible composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer

# Adds entrypoint  script to usr/local/bin
COPY ./.devcontainer/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Copies entire project structure to container
COPY . /var/www/html/suitecrm

# Creates Custom Directory for Custom Modules
RUN mkdir /var/www/html/suitecrm/custom

# Moves SuiteCRM default custom directory to custom.default as backup
RUN mv /var/www/html/suitecrm/custom /var/www/html/suitecrm/custom.default
# Creates Simbolink for Custom Modules
RUN ln -s /var/www/html/suitecrm/careers-suitecrm/custom /var/www/html/suitecrm/custom

# Sets permissions for CRM to work properly
RUN chown -R www-data:www-data /var/www/html/suitecrm \
    && chmod -R 755 /var/www/html/suitecrm \
    && chmod -R 775 /var/www/html/suitecrm/custom /var/www/html/suitecrm/modules /var/www/html/suitecrm/themes /var/www/html/suitecrm/data /var/www/html/suitecrm/upload \
    && chmod 775 /var/www/html/suitecrm/config_override.php 2>/dev/null

# Suite API/V8/CUSTOM configuration
COPY ./.devcontainer/config/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY ./.devcontainer/config/.htaccess /var/www/html/.htaccess
RUN a2enmod rewrite