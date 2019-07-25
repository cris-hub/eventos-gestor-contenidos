FROM crishubprojects/serve-php-colsubsidio


WORKDIR /var/www/html


RUN apt-get update && apt-get install -y openssh-server 
RUN mkdir /var/run/sshd
RUN echo "root:screencast" | chpasswd 
USER root
RUN sed -i 's/#PermitRootLogin .*/PermitRootLogin yes/' /etc/ssh/sshd_config
# SSH login fix. Otherwise user is kicked off after login
RUN sed 's@session\s*required\s*pam_loginuid.so@session optional pam_loginuid.so@g' -i /etc/pam.d/sshd 
ENV NOTVISIBLE "in users profile"
RUN echo "export VISIBLE=now" >> /etc/profile
CMD ["/usr/sbin/sshd", "-D"]     
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
EXPOSE 80 2222
# ARG URLBLOB
# ARG STOREPATH
# ARG CONTAINERNAME
# ARG CONNECTIONSTRINGBLOB
# ARG DEPLOYMENT_PORT
# ARG FOLDER_PROJECT
# ARG MYSQL_USER      
# ARG MYSQL_PASSWORD    
# ARG MYSQL_CONECTION_SPRING             
# ARG MYSQL_CONECTION_PHP
ENV URLBLOB=${URLBLOB}
ENV STOREPATH=${STOREPATH}
ENV CONTAINERNAME=${CONTAINERNAME}
ENV CONNECTIONSTRINGBLOB=${CONNECTIONSTRINGBLOB}
ENV FOLDER_PROJECT=${FOLDER_PROJECT}
ENV DEPLOYMENT_PORT=${DEPLOYMENT_PORT}
ENV MYSQL_USER=${MYSQL_USER}
ENV MYSQL_PASSWORD=${MYSQL_PASSWORD}
ENV MYSQL_CONECTION_SPRING=${MYSQL_CONECTION_SPRING}
ENV MYSQL_CONECTION_PHP=${MYSQL_CONECTION_PHP}


RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
ENTRYPOINT [ "executable" ] ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
COPY ./ /var/www/html/${FOLDER_PROJECT}
COPY config-server/.htaccess /var/www/html/.htaccess

COPY config-server/ports.conf /etc/apache2/
COPY config-server/docker-php.conf /etc/apache2/conf-available/
COPY config-server/000-default.conf /etc/apache2/sites-available/

COPY config-blobstorage/Module.php ${FOLDER_PROJECT}/vendor/nemmo/yii2-attachments/src/Module.php
COPY config-blobstorage/FileController.php ${FOLDER_PROJECT}/vendor/nemmo/yii2-attachments/src/controllers/FileController.php
