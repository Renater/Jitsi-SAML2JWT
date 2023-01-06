#!/bin/bash

export config='$config'

echo "ServerName $SERVER_NAME"
echo "Generating config File from template and jitsi-SAML2JWT.env file"

echo "PHP Token file"
cat ../config/template/config.php | envsubst > ../config/config.php

if [ -z  "$SP_ENTITY_ID" ]
then
echo "Apache File"
cat ../config/template/jwtgenerator.conf | envsubst > ../config/jitsi-auth.conf
else
echo "Apache File"
cat ../config/template/jwtgenerator-sp.conf | envsubst > ../config/jitsi-auth.conf
echo "Shibboleth file"
cat ../config/template/$SHIBBOLETH_TEMPLATE_XML | envsubst > ../config/shibboleth2.xml
fi

echo "Generation Done"

#Start Shibboleth
service shibd start

# Redirecti Shibd Log 
{
tail -f /var/log/shibboleth/shibd.log 		>> /proc/1/fd/1 ;
tail -f /var/log/shibboleth/shibd_warn.log 	>> /proc/1/fd/2 ; 
tail -f /var/log/shibboleth/transaction.log >> /proc/1/fd/1 ;
tail -f /var/log/shibboleth/signature.log 	>> /proc/1/fd/1
} &

#Start Apache
/usr/sbin/apache2ctl -DFOREGROUND
