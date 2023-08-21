#!/bin/bash

#Start Apache

echo "ServerName $SERVER_NAME">> /etc/apache2/apache2.conf

AUTH_SP_CERT=`openssl x509 -in /sp-cert.pem | tail -n +2 | head -n -1`

echo "AUTH_SP_CERT" $AUTH_SP_CERT

echo "Dealing with certificate files"
if [ ! -f  /etc/apache2/apache.pem ]
then
    echo "Generate self signed certificate for apache"
    openssl req -x509 -newkey rsa:4096 -keyout key.pem -out cert.pem -days 10000 -nodes -subj '/CN='$SERVER_NAME
    cat key.pem cert.pem > /etc/apache2/apache.pem
    rm key.pem cert.pem
fi


/usr/sbin/apache2ctl -DFOREGROUND
