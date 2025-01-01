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
RUN  apt install -y sudo 
RUN rm -rf /var/www/html
COPY ./data .
RUN chmod +x /var/gosite/main.sh && dos2unix /var/gosite/main.sh
# RUN chmod +x /var/gosite/main.sh
CMD /var/gosite/main.sh      