FROM debian:bullseye-slim

RUN apt update && apt-get -y install curl gettext-base\
    && apt-get update\
    && apt-get install -y --install-recommends apache2 libapache2-mod-shib shibboleth-sp-common shibboleth-sp-utils\
    && apt-get -y install php php-mysql php-mbstring php-gmp composer zip unzip php-zip


COPY config/attribute-map.xml /etc/shibboleth/

RUN mkdir /usr/local/jitsi-SAML2JWT
COPY ./src /usr/local/jitsi-SAML2JWT/src
COPY ./lib /usr/local/jitsi-SAML2JWT/lib
COPY ./config /usr/local/jitsi-SAML2JWT/config

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