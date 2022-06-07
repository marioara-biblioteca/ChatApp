FROM mysql:5.6

ENV MYSQL_ROOT_PASSWORD parola
ENV MYSQL_DATABASE wordpress

COPY wordpressbd.sql /docker-entrypoint-initdb.d/wordpressbd.sql