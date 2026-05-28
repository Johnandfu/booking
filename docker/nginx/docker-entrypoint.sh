#!/bin/bash
set -e
echo "SSL Enabled: ${ENABLE_SSL}"

# 启动 cron（后台）
crond


# 基本 HTTP 配置
cp /etc/nginx/conf.d/http.conf.template /etc/nginx/conf.d/default.conf
#cp /etc/nginx/conf.d/https.conf.template /etc/nginx/conf.d/https.conf.template

# 如果启用 SSL，加载 SSL 模板
if [ "$ENABLE_SSL" = "true" ]; then
    if  [ -f "/etc/letsencrypt/live/${DOMAIN}/fullchain.pem" ]; then
        echo "SSL enabled, adding HTTPS server block"
        cp /etc/nginx/conf.d/https.conf.template  /etc/nginx/conf.d/default.conf
    fi
    
else
    # pnpm install
    # pnpm build --mode development
    echo 'pnpm install  pnpm dev'
fi


# 检查证书是否存在
if [ ! -f "/etc/letsencrypt/live/${DOMAIN}/fullchain.pem" ]; then
    
    if [ "$ENABLE_SSL" = "true" ]; then
        echo " No SSL cert found, requesting new certificate for ${DOMAIN} ..."
        certbot certonly --webroot -w /var/www/certbot -d "${DOMAIN}"  --email "${ADMIN_EMAIL}" --agree-tos --no-eff-email --noninteractive || true
    fi
    
fi

echo "Starting Nginx with SSL..."
nginx -g "daemon off;"
