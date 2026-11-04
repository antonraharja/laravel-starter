#!/bin/bash
set -euo pipefail

mkdir -p /run/mysqld /run/php
chown mysql:mysql /run/mysqld /var/lib/mysql
chown app:app /run/php

if [ ! -d /var/lib/mysql/mysql ]; then
    echo "[entrypoint] initializing MySQL data directory"
    /usr/sbin/mysqld --initialize-insecure --user=mysql
fi

exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf
