FROM crishubprojects/serve-php-colsubsidio
COPY ./ /var/www/html/gestor
COPY config-server/ports.conf /etc/apache2/
# COPY config-server/docker-php.conf /etc/apache2/conf-available/
# COPY config-server/000-default.conf /etc/apache2/sites-available/

WORKDIR /var/www/html/gestor

COPY config-blobstorage/Module.php vendor/nemmo/yii2-attachments/src/Module.php
COPY config-blobstorage/FileController.php vendor/nemmo/yii2-attachments/src/controllers/FileController.php

