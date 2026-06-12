FROM nginx:stable-alpine

RUN set -ex \
    && apk add --no-cache bash certbot curl openssl \
    && mkdir -p /var/www/certbot /etc/letsencrypt \
    && { \
        echo '#!/usr/bin/env sh'; \
        echo 'set -e'; \
        echo 'certbot renew --webroot -w /var/www/certbot --quiet --deploy-hook "nginx -s reload"'; \
    } > /etc/periodic/daily/certbot-renew \
    && chmod +x /etc/periodic/daily/certbot-renew

COPY docker/nginx-entrypoint.sh /usr/local/bin/nginx-entrypoint.sh

RUN chmod +x /usr/local/bin/nginx-entrypoint.sh

EXPOSE 80 443

ENTRYPOINT ["/usr/local/bin/nginx-entrypoint.sh"]
CMD ["nginx", "-g", "daemon off;"]
