FROM crishubprojects/serve-php-colsubsidio
WORKDIR /var/www/html/gestor

COPY ./ /var/www/html/gestor
COPY config-server/ports.conf /etc/apache2/
COPY config-blobstorage/Module.php vendor/nemmo/yii2-attachments/src/Module.php
COPY config-blobstorage/FileController.php vendor/nemmo/yii2-attachments/src/controllers/FileController.php

