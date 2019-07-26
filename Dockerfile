FROM colsazurecontainerregistrydigital.azurecr.io/serve-php:latest

ARG URLBLOB
ARG STOREPATH
ARG CONTAINERNAME
ARG CONNECTIONSTRINGBLOB
ARG MYSQL_USER      
ARG MYSQL_PASSWORD    
ARG MYSQL_CONECTION_PHP
ENV URLBLOB=${URLBLOB}
ENV STOREPATH=${STOREPATH}
ENV CONTAINERNAME=${CONTAINERNAME}
ENV CONNECTIONSTRINGBLOB=${CONNECTIONSTRINGBLOB}
ENV MYSQL_USER=${MYSQL_USER}
ENV MYSQL_PASSWORD=${MYSQL_PASSWORD}
ENV MYSQL_CONECTION_PHP=${MYSQL_CONECTION_PHP}

COPY ./ /var/www/html
COPY config-server/.htaccess /var/www/html/.htaccess
COPY config-server/ports.conf /etc/apache2/
COPY config-server/docker-php.conf /etc/apache2/conf-available/
COPY config-server/000-default.conf /etc/apache2/sites-available/
COPY config-blobstorage/Module.php /var/www//vendor/nemmo/yii2-attachments/src/Module.php
COPY config-blobstorage/FileController.php /var/www/vendor/nemmo/yii2-attachments/src/controllers/FileController.php
