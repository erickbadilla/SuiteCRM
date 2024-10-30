FROM ubuntu/apache2:2.4-22.04_beta

# Fix missing Apache configuration
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Update and install OS dependencies
RUN apt update -y && \
    apt install -y mariadb-client software-properties-common git unzip openssh-client && \
    add-apt-repository -y ppa:ondrej/php && \
    apt install -y php7.4-xdebug php7.4 php7.4-curl php7.4-gd php7.4-zip php7.4-imap php7.4-mbstring php7.4-intl php7.4-simplexml php7.4-dom php7.4-mysql && \
    update-alternatives --set php /usr/bin/php7.4 && \
    apt clean && rm -rf /var/lib/apt/lists/*

# Set PHP configuration
RUN echo "upload_max_filesize=200M" >> /etc/php/7.4/apache2/php.ini

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer

# Configure SSH and set permissions
RUN mkdir -p /root/.ssh/ && ssh-keyscan -H bitbucket.org >> /root/.ssh/known_hosts
COPY ./.devcontainer/config/ssh-keys/id_rsa /root/.ssh/id_rsa
COPY ./.devcontainer/config/ssh-keys/id_rsa.pub /root/.ssh/id_rsa.pub
RUN chmod 700 /root/.ssh && \
    chmod 600 /root/.ssh/id_rsa && \
    chmod 644 /root/.ssh/id_rsa.pub && \
    eval "$(ssh-agent -s)" && \
    ssh-add /root/.ssh/id_rsa

# Copy entrypoint script and make executable
COPY ./.devcontainer/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Download and configure SuiteCRM
COPY . /var/www/html/suitecrm
WORKDIR /var/www/html/suitecrm
RUN git config core.fileMode false && \
    git rm careers-suitecrm && \
    git clone git@bitbucket.org:cecropiasolutions/careers-suitecrm.git careers-suitecrm && \
    cd careers-suitecrm && \
    git fetch && git checkout dev && \
    git config core.fileMode false
# Create custom directory and link it to the careers-suitecrm/custom directory and create cache directory
RUN mkdir -p cache && \
    ln -s /var/www/html/suitecrm/careers-suitecrm/custom /var/www/html/suitecrm/custom
# Set permissions
RUN chown -R www-data:www-data . && \
    chmod -R 755 . && \
    chmod -R 775 ./custom ./modules ./themes ./data ./upload && \
    chmod 775 ./config_override.php 2>/dev/null
# Enable Apache modules and configure site
COPY ./.devcontainer/config/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY ./.devcontainer/config/.htaccess /var/www/html/.htaccess
RUN a2enmod rewrite