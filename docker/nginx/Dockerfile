FROM nginx:stable-alpine

ARG ENABLE_SSL=false
ARG CERTBOT_DOMAIN=
ARG CERTBOT_EMAIL=

ENV ENABLE_SSL=${ENABLE_SSL} \
    CERTBOT_DOMAIN=${CERTBOT_DOMAIN} \
    CERTBOT_EMAIL=${CERTBOT_EMAIL}

RUN set -ex \
    && apk add --no-cache bash certbot curl gettext openssl pnpm \
    && mkdir -p /etc/nginx/external /var/www/certbot /etc/letsencrypt \
    && { \
        echo '#!/usr/bin/env bash'; \
        echo 'set -e'; \
        echo 'certbot renew --webroot -w /var/www/certbot --quiet --deploy-hook "nginx -s reload"'; \
    } > /etc/periodic/monthly/certbot-renew \
    && chmod +x /etc/periodic/monthly/certbot-renew

COPY https.conf.template /etc/nginx/external/default.conf
COPY nginx-entrypoint.sh /usr/local/bin/nginx-entrypoint.sh
RUN chmod +x /usr/local/bin/nginx-entrypoint.sh

EXPOSE 80 443

ENTRYPOINT ["/usr/local/bin/nginx-entrypoint.sh"]
CMD ["nginx", "-g", "daemon off;"]
