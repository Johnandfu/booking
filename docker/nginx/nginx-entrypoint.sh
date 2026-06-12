#!/usr/bin/env bash
set -e

NGINX_CONFIG_SOURCE="/etc/nginx/external/default.conf"
NGINX_CONFIG_TARGET="/etc/nginx/conf.d/default.conf"
CERTBOT_WEBROOT="/var/www/certbot"

ENABLE_SSL="${ENABLE_SSL:-false}"
CERTBOT_EMAIL="${CERTBOT_EMAIL:-admin@test.com}"
DOMAINS="$(echo "${CERTBOT_DOMAIN:-bi.test.com}" | tr ',;' '  ' | xargs)"

mkdir -p "$CERTBOT_WEBROOT" /etc/letsencrypt "$(dirname "$NGINX_CONFIG_TARGET")"

if [ "$ENABLE_SSL" = "true" ]; then
   
    if [ ! -f "/etc/letsencrypt/live/${PRIMARY_DOMAIN}/fullchain.pem" ]; then
        echo "Requesting SSL certificate for: ${DOMAINS}"
        cat > "$NGINX_CONFIG_TARGET" <<EOF
server {
    listen 80;
    server_name ${DOMAINS};

    location /.well-known/acme-challenge/ {
        root ${CERTBOT_WEBROOT};
    }
}
EOF
        nginx -t
        nginx

        CERTBOT_DOMAINS=""
        for domain in $DOMAINS; do
            CERTBOT_DOMAINS="$CERTBOT_DOMAINS -d $domain"
        done

        certbot certonly --webroot  -w "$CERTBOT_WEBROOT" $CERTBOT_DOMAINS --email "$CERTBOT_EMAIL" --agree-tos --no-eff-email --noninteractive

        nginx -s quit
    fi
fi

if [ ! -f "$NGINX_CONFIG_SOURCE" ]; then
    echo "Nginx config not found: ${NGINX_CONFIG_SOURCE}" >&2
    exit 1
fi

echo "Loading nginx config: ${NGINX_CONFIG_SOURCE}"
envsubst '${DOMAIN} ${PRIMARY_DOMAIN} ${NGINX_DOMAIN} ${CERTBOT_WEBROOT}' < "$NGINX_CONFIG_SOURCE" > "$NGINX_CONFIG_TARGET"
nginx -t

crond
exec "$@"
