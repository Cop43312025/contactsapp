FROM mysql:9.5.0

ADD ./init/01-schema.sql /docker-entrypoint-initdb.d/
ADD ./init/02-devdata.sql /docker-entrypoint-initdb.d/
EXPOSE 3306