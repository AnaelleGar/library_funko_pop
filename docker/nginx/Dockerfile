FROM nginx:alpine

WORKDIR /var/www

CMD ["nginx"]

COPY ./docker/nginx/default.conf /etc/nginx/sites-available/default.conf
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/nginx/conf.d.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
