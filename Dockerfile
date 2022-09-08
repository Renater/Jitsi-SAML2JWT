FROM debian:stable-slim

RUN apt update && apt-get -y install curl\
    && curl https://pkg.switch.ch/switchaai/debian/dists/buster/main/binary-all/misc/switchaai-apt-source_1.0.0_all.deb > switchaai-apt-source_1.0.0_all.deb \
    && apt-get install ./switchaai-apt-source_1.0.0_all.deb\
    && apt-get update\
    && apt-get install -y --install-recommends apache2 shibboleth\
    && apt-get -y install php php-mysql php-mbstring php-gmp composer zip unzip php-zip


COPY config/cert/sp* /etc/shibboleth/
COPY config/shibboleth2.xml /etc/shibboleth/
COPY config/attribute-map.xml /etc/shibboleth/
COPY config/jitsi-auth.conf /etc/apache2/sites-available/jitsi-auth.conf
COPY config/cert/apache.pem /etc/apache2

RUN a2ensite jitsi-auth.conf\
    && a2enmod ssl

COPY . /usr/local/jitsi-SAML2JWT/
RUN cd /usr/local/jitsi-SAML2JWT/lib\
    && composer install -n \
    && rm -rf /var/lib/apt/lists/*

EXPOSE 80 443

# redirect apache logs to docker stdout/stderr
RUN ln -sf /proc/1/fd/1 /var/log/apache2/access.log
RUN ln -sf /proc/1/fd/2 /var/log/apache2/error.log

COPY entrypoint.sh /var/
RUN chmod +x /var/entrypoint.sh

ENTRYPOINT ["/bin/bash", "/var/entrypoint.sh"]