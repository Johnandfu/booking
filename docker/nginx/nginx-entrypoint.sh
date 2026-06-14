#!/usr/bin/env bash
set -e

ENABLE_SSL="${ENABLE_SSL:-false}"
CERTBOT_EMAIL="${CERTBOT_EMAIL:-admin@test.com}"
DOMAINS="$(echo "${CERTBOT_DOMAIN:-bi.test.com}" | tr ',;' '  ' | xargs)"
PRIMARY_DOMAIN="$(printf '%s' "$DOMAINS" | awk '{print $1}')"
ADMIN_DOMAIN="${ADMIN_DOMAIN:-admin.${PRIMARY_DOMAIN}}"
CERTIFICATE_DOMAINS="$(printf '%s %s' "$DOMAINS" "$ADMIN_DOMAIN" | xargs -n1 | awk '!seen[$0]++' | xargs)"
CERTBOT_CERTIFICATE_DIR="/etc/letsencrypt/live/${PRIMARY_DOMAIN}"
LOCAL_CERTIFICATE_DIR="/etc/nginx/local-ssl/${PRIMARY_DOMAIN}"
CERTIFICATE_DIR="$CERTBOT_CERTIFICATE_DIR"
CERTBOT_BOOTSTRAP_CONF="/etc/nginx/conf.d/certbot-bootstrap.conf"

export DOMAIN="$PRIMARY_DOMAIN"
export PRIMARY_DOMAIN
export NGINX_DOMAIN="$DOMAINS"
export ADMIN_DOMAIN

if [ "$ENABLE_SSL" = "true" ]; then
    if [ ! -f "${CERTBOT_CERTIFICATE_DIR}/fullchain.pem" ]; then
        echo "Requesting SSL certificate for: ${CERTIFICATE_DOMAINS}"
        cat > "$CERTBOT_BOOTSTRAP_CONF" <<EOF
server {
    listen 80;
    server_name ${CERTIFICATE_DOMAINS};

    location /.well-known/acme-challenge/ {
        root /var/www/certbot;
    }
}
EOF
        nginx -t
        nginx

        CERTBOT_DOMAINS=""
        for domain in $CERTIFICATE_DOMAINS; do
            CERTBOT_DOMAINS="$CERTBOT_DOMAINS -d $domain"
        done

        if ! certbot certonly --webroot -w /var/www/certbot $CERTBOT_DOMAINS --email "$CERTBOT_EMAIL" --agree-tos --no-eff-email --noninteractive; then
            nginx -s quit || true
            rm -f "$CERTBOT_BOOTSTRAP_CONF"
            echo "Certbot certificate request failed." >&2
            exit 1
        fi

        nginx -s quit
        rm -f "$CERTBOT_BOOTSTRAP_CONF"
    fi
else
    CERTIFICATE_DIR="$LOCAL_CERTIFICATE_DIR"
    mkdir -p "$CERTIFICATE_DIR"

    if [ ! -f "${CERTIFICATE_DIR}/fullchain.pem" ] || [ ! -f "${CERTIFICATE_DIR}/privkey.pem" ]; then
        echo "Creating self-signed fallback certificate for local non-SSL mode: ${PRIMARY_DOMAIN}"
        LOCAL_CERTIFICATE_SAN="$(printf '%s' "$CERTIFICATE_DOMAINS" | awk '{ for (i = 1; i <= NF; i++) printf "%sDNS:%s", (i == 1 ? "" : ","), $i }')"
        openssl req -x509 -nodes -newkey rsa:2048 -days 3650 \
            -subj "/CN=${PRIMARY_DOMAIN}" \
            -addext "subjectAltName=${LOCAL_CERTIFICATE_SAN}" \
            -keyout "${CERTIFICATE_DIR}/privkey.pem" \
            -out "${CERTIFICATE_DIR}/fullchain.pem"
    fi
fi

export SSL_CERTIFICATE="${CERTIFICATE_DIR}/fullchain.pem"
export SSL_CERTIFICATE_KEY="${CERTIFICATE_DIR}/privkey.pem"

envsubst '${DOMAIN} ${PRIMARY_DOMAIN} ${NGINX_DOMAIN} ${ADMIN_DOMAIN} ${SSL_CERTIFICATE} ${SSL_CERTIFICATE_KEY}' < "/etc/nginx/conf.d/default.conf.template" > "/etc/nginx/conf.d/site-https.conf"
nginx -t

crond
exec su-exec "${APP_UID:-1000}:${APP_GID:-1000}" "$@"
