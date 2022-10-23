FROM php
WORKDIR /app
COPY composer.json .
RUN install --no-interaction --no-ansi
CMD 'php artisan serve'
EXPOSE 8080
