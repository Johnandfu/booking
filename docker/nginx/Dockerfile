FROM nginx:stable-alpine

ARG ENABLE_SSL=false
ARG CERTBOT_DOMAIN=
ARG ADMIN_DOMAIN=
ARG CERTBOT_EMAIL=
ARG APP_UID=1000
ARG APP_GID=1000

ENV ENABLE_SSL=${ENABLE_SSL} \
    CERTBOT_DOMAIN=${CERTBOT_DOMAIN} \
    ADMIN_DOMAIN=${ADMIN_DOMAIN} \
    CERTBOT_EMAIL=${CERTBOT_EMAIL} \
    APP_UID=${APP_UID} \
    APP_GID=${APP_GID}

RUN set -ex \
    && apk add --no-cache bash certbot curl gettext libcap openssl su-exec \
    && addgroup -g "${APP_GID}" -S www \
    && adduser -u "${APP_UID}" -S -G www www \
    && mkdir -p /var/www/certbot /etc/letsencrypt /etc/nginx/local-ssl \
    && chown -R www:www /var/cache/nginx /var/log/nginx /etc/nginx/conf.d /var/www/certbot /etc/nginx/local-ssl \
    && touch /var/run/nginx.pid \
    && chown www:www /var/run/nginx.pid \
    && setcap 'cap_net_bind_service=+ep' /usr/sbin/nginx \
    && { \
        echo '#!/usr/bin/env bash'; \
        echo 'set -e'; \
        echo 'certbot renew --webroot -w /var/www/certbot --quiet --deploy-hook "nginx -s reload"'; \
    } > /etc/periodic/monthly/certbot-renew \
    && chmod +x /etc/periodic/monthly/certbot-renew

COPY https.conf.template /etc/nginx/conf.d/default.conf.template
COPY nginx-entrypoint.sh /usr/local/bin/nginx-entrypoint.sh
RUN chmod +x /usr/local/bin/nginx-entrypoint.sh

EXPOSE 80 443

ENTRYPOINT ["/usr/local/bin/nginx-entrypoint.sh"]
CMD ["nginx", "-g", "daemon off;"]
