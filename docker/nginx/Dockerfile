FROM node:22-alpine AS web-build

WORKDIR /var/www/hyperf-cms/web

ARG MINE_NODE_ENV=production

COPY web/ ./
RUN npm install -g pnpm && pnpm install

RUN echo "MINE_NODE_ENV=$MINE_NODE_ENV" \
    && pnpm build --mode "$MINE_NODE_ENV"

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

COPY docker/nginx/https.conf.template /etc/nginx/conf.d/default.conf.template
COPY docker/nginx/nginx-entrypoint.sh /usr/local/bin/nginx-entrypoint.sh
COPY --from=web-build /var/www/hyperf-cms/web/dist /var/www/hyperf-cms/web/dist
RUN chmod +x /usr/local/bin/nginx-entrypoint.sh

EXPOSE 80 443

ENTRYPOINT ["/usr/local/bin/nginx-entrypoint.sh"]
CMD ["nginx", "-g", "daemon off;"]
