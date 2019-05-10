FROM crishubprojects/serve-php-colsubsidio
COPY ./ /var/www/html/hotelescont
COPY config-server/ports.conf /etc/apache2/

COPY config-blobstorage/Module.php vendor/nemmo/yii2-attachments/src/Module.php
COPY config-blobstorage/FileController.php vendor/nemmo/yii2-attachments/src/controllers/FileController.php

