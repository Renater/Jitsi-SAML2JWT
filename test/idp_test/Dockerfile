FROM debian:buster-slim

RUN apt update\
    && apt-get install -y --install-recommends apache2 curl\
    && apt-get -y install php libapache2-mod-php php-mysql php-dom php-curl


RUN mkdir -p /var \
    && curl -SL  https://github.com/simplesamlphp/simplesamlphp/releases/download/v1.18.8/simplesamlphp-1.18.8.tar.gz \
    | tar -xzC /var && mv /var/simplesamlphp-1.18.8 /var/simplesamlphp

COPY config/* /var/simplesamlphp/
RUN touch /var/simplesamlphp/modules/exampleauth/enable

COPY config/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY config/ports.conf /etc/apache2/ports.conf

RUN a2ensite 000-default.conf
RUN a2enmod ssl


RUN cd /var/simplesamlphp/cert \
    && openssl req -x509 -newkey rsa:2048 -nodes -keyout server.pem -out server.crt -days 3650 -subj '/CN=idp.rendez-vous.renater.fr' \
    && chmod 666 server*

EXPOSE 9443

# redirect apache logs to docker stdout/stderr
RUN ln -sf /proc/1/fd/1 /var/log/apache2/access.log
RUN ln -sf /proc/1/fd/2 /var/log/apache2/error.log

#COPY entrypoint.sh /var/
COPY entrypoint.sh /var/
RUN chmod +x /var/entrypoint.sh

ENTRYPOINT ["/bin/bash", "/var/entrypoint.sh"]