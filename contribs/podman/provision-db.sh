#!/bin/bash
# Creates the database and user named in the project's .env. Values are never printed.
set -euo pipefail

env_file="$APP_DIR/.env"

read_env() {
    local value
    value="$(grep -E "^$1=" "$env_file" | tail -n 1 | cut -d= -f2-)" || true
    value="${value%\"}"; value="${value#\"}"
    value="${value%\'}"; value="${value#\'}"
    printf '%s' "$value"
}

sql_string() {
    local value="${1//\\/\\\\}"
    printf '%s' "${value//\'/\'\'}"
}

if [ ! -f "$env_file" ]; then
    echo "[provision-db] ERROR: .env not found in the project" >&2
    exit 1
fi

db_name="$(read_env DB_DATABASE)"
db_user="$(read_env DB_USERNAME)"
db_pass="$(read_env DB_PASSWORD)"

if [ -z "$db_name" ] || [ -z "$db_user" ] || [ -z "$db_pass" ] || [ "$db_user" = "root" ]; then
    echo "[provision-db] ERROR: .env must set DB_DATABASE, DB_USERNAME (not root) and DB_PASSWORD" >&2
    exit 1
fi

for _ in $(seq 1 60); do
    mysqladmin --socket=/run/mysqld/mysqld.sock ping --silent 2>/dev/null && break
    sleep 1
done

db_identifier="${db_name//\`/\`\`}"
user_literal="$(sql_string "$db_user")"
pass_literal="$(sql_string "$db_pass")"

mysql --socket=/run/mysqld/mysqld.sock -uroot <<SQL
CREATE DATABASE IF NOT EXISTS \`$db_identifier\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$user_literal'@'%' IDENTIFIED BY '$pass_literal';
ALTER USER '$user_literal'@'%' IDENTIFIED BY '$pass_literal';
GRANT ALL PRIVILEGES ON \`$db_identifier\`.* TO '$user_literal'@'%';
FLUSH PRIVILEGES;
SQL

echo "[provision-db] database and user are ready"
