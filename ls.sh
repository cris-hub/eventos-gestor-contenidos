az webapp create-remote-connection --subscription f08123f7-75c4-487d-875c-7d7a1150c6bf --resource-group Digital-Produccion-RyT-Hoteles -n cols-as-digprod-ryt-hoteles-content-front --verbose &
az extension add -n cols-as-digprod-ryt-hoteles-content-front
az extension update -n  cols-as-digprod-ryt-hoteles-content-front webapp
az webapp remote-connection create -g Digital-Produccion-RyT-Hoteles -n cols-as-digprod-ryt-hoteles-content-front -p 9000  --verbose &
az webapp log config cols-as-digprod-ryt-hoteles-content-front --resource-group Digital-Produccion-RyT-Hoteles --docker-container-logging filesystem

 docker run -it -p 80:80 -p 8081:2222  --env-file test.env test:test1



  docker build ./ -t colsazurecontainerregistrydigital.azurecr.io/recreacionyturismo/hoteles/content:test-19 
  docker build ./ -t colsazurecontainerregistrydigital.azurecr.io/serve-php:latest
  

  chown -R www-data:www-data /var/www  && a2enmod rewrite

   docker run -it -p 80:80 colsazurecontainerregistrydigital.azurecr.io/serve-php:latest