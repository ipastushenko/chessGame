FROM nginx:1.9

COPY nginx.conf /etc/nginx/nginx.conf
COPY site.conf /etc/nginx/conf.d/default.conf

ADD . /var/www/html
