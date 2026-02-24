FROM docker.io/library/node:22-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
COPY postcss.config.js tailwind.config.js vite.config.js ./
COPY resources ./resources

RUN npm ci
RUN npm run build

FROM docker.io/hyperf/hyperf:8.4-alpine-v3.22-swoole-v6

WORKDIR /opt/www

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader

COPY . .
COPY --from=frontend /app/public/build ./public/build

RUN mkdir -p runtime storage/logs database \
    && chown -R root:root runtime storage database

RUN chmod +x /opt/www/entrypoint.sh

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV HTTP_SERVER_HOST=0.0.0.0
ENV HTTP_SERVER_PORT=9501

EXPOSE 9501

CMD ["/opt/www/entrypoint.sh"]
