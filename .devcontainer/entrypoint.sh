#!/bin/bash
set -e  # Exit immediately if a command exits with a non-zero status

# Define log file location
LOG_FILE="/var/www/html/suitecrm/entrypoint.log"
SUITECRM_DIR="/var/www/html/suitecrm"

# Required environment variables
REQUIRED_VARS=("MARIADB_ROOT_PASSWORD" "MARIADB_PORT_NUMBER" "MARIADB_DATABASE")

# Check if each required environment variable is set
for var in "${REQUIRED_VARS[@]}"; do
    if [ -z "${!var}" ]; then
        echo "Error: Required environment variable '$var' is not set." >&2
        exit 1
    fi
done

# Redirect stdout and stderr to the log file
exec > "$LOG_FILE" 2>&1 || { echo "Failed to redirect logs to $LOG_FILE"; exit 1; }

# Check if the directory exists
if [ ! -d "$SUITECRM_DIR" ]; then
    echo "The directory /var/www/html/suitecrm does not exist. Exiting."
    exit 1
fi

# Check if vendor directory exists; if not, install Composer dependencies
if [ ! -d "$SUITECRM_DIR/vendor" ]; then
    echo "Installing Composer dependencies..."
    if ! composer install --no-dev --no-interaction --working-dir="$SUITECRM_DIR"; then
        echo "Composer installation failed."
        exit 1
    fi
    echo "Composer dependencies installed."

    # Restore database backup
    echo "Restoring database backup..."
    BACKUP_FILE="$SUITECRM_DIR/.devcontainer/config/admin_developdb.sql.gz"

    # Check if the backup file exists
    if [ ! -f "$BACKUP_FILE" ]; then
        echo "The backup file admin_developdb.sql.gz doesn't exist in .devcontainer/config directory. Exiting."
        exit 1
    fi

    # Unzip and restore the database
    if ! gunzip < "$BACKUP_FILE" | mysql -u root -p"${MARIADB_ROOT_PASSWORD}" -h mariadb -P "${MARIADB_PORT_NUMBER}" "${MARIADB_DATABASE}"; then
        echo "Database restoration failed."
        exit 1
    fi
    echo "Database backup restored."
fi

# Start Apache in the foreground
echo "Starting Apache..."
exec apache2-foreground