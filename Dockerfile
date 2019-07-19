FROM crishubprojects/serve-php-colsubsidio

ENV URLBLOB=URLBLOB
ENV STOREPATH=STOREPATH
ENV CONTAINERNAME=CONTAINERNAME
ENV CONNECTIONSTRINGBLOB=CONNECTIONSTRINGBLOB
ENV FOLDER_PROJECT=FOLDER_PROJECT
ENV DEPLOYMENT_PORT=DEPLOYMENT_PORT
ENV MYSQL_USER=MYSQL_USER
ENV MYSQL_PASSWORD=MYSQL_PASSWORD
ENV MYSQL_CONECTION_SPRING=MYSQL_CONECTION_SPRING
ENV MYSQL_CONECTION_PHP=MYSQL_CONECTION_PHP

RUN mkdir -p /var/www/html/ryt/hoteles/hotelescont/runtime/cache
#RUN chown -R 777 /var/www/html/ryt/hoteles/hotelescont 
RUN chgrp root /var/www/html/ryt/hoteles/hotelescont
COPY ./ /var/www/html/ryt/hoteles/hotelescont
COPY config-server/ports.conf /etc/apache2/

COPY config-blobstorage/Module.php ryt/hoteles/hotelescont/vendor/nemmo/yii2-attachments/src/Module.php
COPY config-blobstorage/FileController.php ryt/hoteles/hotelescont/vendor/nemmo/yii2-attachments/src/controllers/FileController.php

