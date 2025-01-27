FROM ubuntu/apache2:2.4-22.04_beta

# Use non-interactive frontend to avoid prompts during installation
ENV DEBIAN_FRONTEND=noninteractive

# Add Ondřej Surý’s PHP PPA key and repository
RUN apt-get update -y -o Acquire::AllowInsecureRepositories=true -o Acquire::AllowDowngradeToInsecureRepositories=true && \
    apt-get install -y --no-install-recommends software-properties-common gnupg && \
    gpg --keyserver keyserver.ubuntu.com --recv-keys E5267A6C && \
    gpg --export --armor E5267A6C | tee /etc/apt/trusted.gpg.d/ondrej_php.gpg > /dev/null && \
    add-apt-repository -y ppa:ondrej/php && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Update and install OS dependencies
RUN apt-get update -y && \
    apt-get install -y --no-install-recommends \
    mariadb-client \
    git \
    unzip \
    openssh-client \
    php7.4-xdebug \
    php7.4 \
    php7.4-curl \
    php7.4-gd \
    php7.4-zip \
    php7.4-imap \
    php7.4-mbstring \
    php7.4-intl \
    php7.4-simplexml \
    php7.4-dom \
    php7.4-mysql && \
    update-alternatives --set php /usr/bin/php7.4 && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Set  Apache configuration
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

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
WORKDIR /var/www/html/suitecrm
# Copies the entire project to the container
COPY . .
# Copy config files to root of the project
COPY ./.devcontainer/config/config_override.php ./.devcontainer/config/config.php ./

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
    find . -type d -exec chmod 755 {} + && \
    find . -type f -exec chmod 755 {} + && \
    find ./custom ./modules ./themes ./data ./upload -type d -exec chmod 775 {} + && \
    find ./custom ./modules ./themes ./data ./upload -type f -exec chmod 775 {} + && \
    chmod 775 ./config_override.php 2>/dev/null

# Generate SSL keys for OAuth2 if private key does not exist
RUN if [ ! -f /var/www/html/suitecrm/Api/V8/OAuth2/private.key ]; then \
    openssl genrsa -out /var/www/html/suitecrm/Api/V8/OAuth2/private.key 2048 && \
    openssl rsa -in /var/www/html/suitecrm/Api/V8/OAuth2/private.key -pubout -out /var/www/html/suitecrm/Api/V8/OAuth2/public.key; \
    fi && \
    # Set ownership and permissions for SSL keys
    if [ -f /var/www/html/suitecrm/Api/V8/OAuth2/private.key ]; then \
    chown www-data:www-data /var/www/html/suitecrm/Api/V8/OAuth2/*.key && \
    chmod 600 /var/www/html/suitecrm/Api/V8/OAuth2/*.key; \
    fi

# Enable Apache modules and configure site
COPY ./.devcontainer/config/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY ./.devcontainer/config/.htaccess /var/www/html/.htaccess
RUN a2enmod rewrite

# Adds xDebug configuration
RUN echo "zend_extension=xdebug.so" >> /etc/php/7.4/mods-available/xdebug.ini && \
    echo "xdebug.mode=debug" >> /etc/php/7.4/mods-available/xdebug.ini && \
    echo "xdebug.start_with_request=trigger" >> /etc/php/7.4/mods-available/xdebug.ini && \
    echo "xdebug.client_port=9003" >> /etc/php/7.4/mods-available/xdebug.ini && \
    echo "xdebug.discover_client_host=true" >> /etc/php/7.4/mods-available/xdebug.ini && \
    echo "xdebug.idekey=VSCODE" >> /etc/php/7.4/mods-available/xdebug.ini
    