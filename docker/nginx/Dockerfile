FROM nginx:stable-alpine

ARG ENABLE_SSL=false
ARG CERTBOT_DOMAIN=
ARG ADMIN_DOMAIN=
ARG CERTBOT_EMAIL=

ENV ENABLE_SSL=${ENABLE_SSL} \
    CERTBOT_DOMAIN=${CERTBOT_DOMAIN} \
    ADMIN_DOMAIN=${ADMIN_DOMAIN} \
    CERTBOT_EMAIL=${CERTBOT_EMAIL}

RUN set -ex \
    && apk add --no-cache bash certbot curl gettext openssl \
    && mkdir -p /var/www/certbot /etc/letsencrypt /etc/nginx/local-ssl \
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
