FROM php:5.6-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN apt-get update \
 && apt-get install -y openssh-server vim curl wget tcptraceroute openrc \
 && echo "cd /home" >> /etc/bash.bashrc 


RUN rm -f /etc/ssh/sshd_config
RUN mkdir /var/run/sshd
# configure startup
RUN mkdir -p /tmp
ADD sshd_config /etc/ssh/

RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN echo "root:Docker!" | chpasswd 

RUN sed -i 's/#PermitRootLogin .*/PermitRootLogin yes/' /etc/ssh/sshd_config

RUN sed 's@session\s*required\s*pam_loginuid.so@session optional pam_loginuid.so@g' -i /etc/pam.d/sshd 

ENV NOTVISIBLE "in users profile"

RUN echo "export VISIBLE=now" >> /etc/profile

CMD bash -c '/usr/sbin/apache2ctl start;/usr/sbin/sshd -D'

EXPOSE 2222 80