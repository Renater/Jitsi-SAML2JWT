#!/bin/bash

# Install script for JWT token Generator running behind and existing SAML Proxy SP.
apt update
apt install software-properties-common -y

# Install apache
sudo apt-get -y install apache2
sudo cp ../config/cert/apache.pem /etc/apache2/
sudo cp ../config/jitsi-auth.conf /etc/apache2/sites-available/jitsi-auth.conf
rm jitsi-auth.conf
sudo a2ensite jitsi-auth.conf
sudo a2enmod ssl

# Generate apache self signed certificate
sudo systemctl start apache2.service

# Install PHP and run composer
cp -r .. /usr/local/jitsi-SAML2JWT
rm -rf /usr/local/jitsi-SAML2JWT/config/template
rm -rf /usr/local/jitsi-SAML2JWT/config/cert

cd /usr/local/jitsi-SAML2JWT/lib
sudo apt -y install php php-mysql php-mbstring php-gmp composer zip unzip php-zip
export COMPOSER_ALLOW_SUPERUSER=1; composer install