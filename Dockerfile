FROM colsazurecontainerregistrydigital.azurecr.io/serve-php:latest

ARG URLBLOB
ARG STOREPATH
ARG CONTAINERNAME
ARG CONNECTIONSTRINGBLOB

ARG MYSQL_USER      
ARG MYSQL_PASSWORD    
ARG MYSQL_CONECTION_PHP

ARG FOLDER_PROJECT
ARG APACHE_LOG_DIR
ENV URLBLOB=${URLBLOB}
ENV STOREPATH=${STOREPATH}
ENV CONTAINERNAME=${CONTAINERNAME}
ENV CONNECTIONSTRINGBLOB=${CONNECTIONSTRINGBLOB}

ENV MYSQL_USER=${MYSQL_USER}
ENV MYSQL_PASSWORD=${MYSQL_PASSWORD}
ENV MYSQL_CONECTION_PHP=${MYSQL_CONECTION_PHP}

ENV FOLDER_PROJECT=${FOLDER_PROJECT}
ENV PATH_MAIN=/var/www/html/ryt/hoteles/contenido
ARG APACHE_LOG_DIR=/var/log



COPY ./ ${PATH_MAIN}
# COPY config-server/.htaccess /var/www/html/.htaccess
# COPY config-server/ports.conf /etc/apache2/
# COPY config-server/docker-php.conf /etc/apache2/conf-available/docker-php.conf
# COPY config-server/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY config-blobstorage/Module.php ${PATH_MAIN}/vendor/nemmo/yii2-attachments/src/Module.php
COPY config-blobstorage/FileController.php ${PATH_MAIN}/vendor/nemmo/yii2-attachments/src/controllers/FileController.php

RUN chgrp www-data ${PATH_MAIN}/web/assets
RUN chmod -R 777 ${PATH_MAIN}/runtime
RUN chmod 777 ${PATH_MAIN}
RUN chmod g+w ${PATH_MAIN}/web/assets/

EXPOSE 2222 80