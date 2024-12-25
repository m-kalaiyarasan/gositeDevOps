FROM ubuntu:latest

ARG DEBIAN_FRONTEND=noninteractive 
RUN apt update
RUN apt upgrade -y
RUN apt install -y apache2
RUN apt install -y php libapache2-mod-php php-mysql php8.0-zip
RUN rm /var/www/html/index.html
RUN apt install -y git
COPY ./html /var/www/html

CMD /usr/sbin/apache2ctl -D FOREGROUND