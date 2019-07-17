FROM crishubprojects/serve-php-colsubsidio

ENV URLBLOB
ENV STOREPATH
ENV CONTAINERNAME
ENV CONNECTIONSTRINGBLOB
ENV FOLDER_PROJECT
ENV DEPLOYMENT_PORT
ENV MYSQL_USER      
ENV MYSQL_PASSWORD    
ENV MYSQL_CONECTION_SPRING             
ENV MYSQL_CONECTION_PHP

COPY ./ /var/www/html/${FOLDER_PROJECT}
COPY config-server/ports.conf /etc/apache2/

COPY config-blobstorage/Module.php ${FOLDER_PROJECT}/vendor/nemmo/yii2-attachments/src/Module.php
COPY config-blobstorage/FileController.php ${FOLDER_PROJECT}/vendor/nemmo/yii2-attachments/src/controllers/FileController.php

