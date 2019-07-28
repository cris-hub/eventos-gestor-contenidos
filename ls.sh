az webapp create-remote-connection --subscription f08123f7-75c4-487d-875c-7d7a1150c6bf --resource-group Digital-Produccion-RyT-Hoteles -n cols-as-digprod-ryt-hoteles-content-front --verbose &

 docker run -it -p 80:80  --env-file test.env
test:test1


  docker build ./ -t colsazurecontainerregistrydigital.azurecr.io/recreacionyturismo/hoteles/content:test-19 
  docker build ./ -t colsazurecontainerregistrydigital.azurecr.io/serve-php:latest
  

  chown -R www-data:www-data /var/www  && a2enmod rewrite

   docker run -it -p 80:80 colsazurecontainerregistrydigital.azurecr.io/serve-php:latest