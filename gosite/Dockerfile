FROM ubuntu:latest
ARG DEBIAN_FRONTEND=noninteractive 
WORKDIR /var/gosite
RUN apt update
RUN apt upgrade -y
RUN apt install -y apache2
RUN apt install -y php libapache2-mod-php php-mysql
RUN apt install -y git
RUN apt install -y  php-zip nano
RUN apt install -y  python3 dos2unix
RUN apt install -y sudo 
RUN apt install -y php-curl php-mbstring
RUN rm -rf /var/www/html
RUN apt install certbot python3-certbot-apache -y
RUN apt-get install -y apache2 ssl-cert
RUN apt-get update && apt install -y docker.io
RUN apt install -y docker-compose

RUN apt install -y python3.12-venv python3-setuptools
RUN a2enmod ssl
RUN a2enmod proxy
RUN a2enmod proxy_http





COPY ./data .
RUN chmod +x /var/gosite/main.sh && dos2unix /var/gosite/main.sh

# RUN chmod +x /var/gosite/main.sh
CMD /var/gosite/main.sh