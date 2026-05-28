FROM nginx:stable-alpine3.21
ADD docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
ADD certbot-renew-cron /etc/periodic/monthly/certbot-renew

RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.aliyun.com/g' /etc/apk/repositories \
    && apk add --no-cache certbot bash curl openssl pnpm \
    && mkdir -p /etc/letsencrypt /var/www/certbot /var/log/letsencrypt \
    && chmod +x /usr/local/bin/docker-entrypoint.sh /etc/periodic/monthly/certbot-renew\
    && chown -R www:www /var/www/web \
    && chmod -R 775 /var/www/web
    #&& pnpm config set registry https://registry.npmmirror.com

WORKDIR /var/www/web

EXPOSE 80 443 2888

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
