FROM crishubprojects/serve-php-colsubsidio

ARG URLBLOB
ARG STOREPATH
ARG CONTAINERNAME
ARG CONNECTIONSTRINGBLOB
ARG DEPLOYMENT_PORT
ARG FOLDER_PROJECT
ARG MYSQL_USER      
ARG MYSQL_PASSWORD    
ARG MYSQL_CONECTION_SPRING             
ARG MYSQL_CONECTION_PHP

COPY ./ /var/www/html/${FOLDER_PROJECT}
COPY config-server/ports.conf /etc/apache2/

COPY config-blobstorage/Module.php vendor/nemmo/yii2-attachments/src/Module.php
COPY config-blobstorage/FileController.php vendor/nemmo/yii2-attachments/src/controllers/FileController.php

