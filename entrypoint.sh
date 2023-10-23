#!/bin/bash

CONFIG_DIR="/usr/local/jitsi-SAML2JWT/config"
export config='$config'
echo "ServerName $SERVER_NAME"

echo "Generating config File from template and .env file"
echo "PHP Token file"
cat $CONFIG_DIR/template/config.php | envsubst > /usr/local/jitsi-SAML2JWT/config/config.php

if [ $ENABLE_SHIBBOLETH = "true" ]
then
    echo "Apache with SP File"
    cat $CONFIG_DIR/template/jwtgenerator-sp.conf | envsubst > /etc/apache2/sites-available/jitsi-auth.conf

    if [ $ENABLE_BACKEND_JWT_REQUEST = "true" ]
    then
        cp $CONFIG_DIR/template/ports.conf /etc/apache2/ports.conf
    fi

    if [ ! -f /etc/shibboleth/sp-cert.pem ]
    then
        echo "You must provide your certificate for runnning this container. You can run tools/generate_certe_sp.sh to generate a self signed one."
        exit;
    fi

    echo $SHIBBOLETH_TEMPLATE_XML
    if [ -z $SHIBBOLETH_TEMPLATE_XML ]
    then
        echo "You must fill the SHIBBOLETH_TEMPLATE_XML variable for runnning this container. Sample Templates can be find in config/template"
        exit;    
    else
        echo "Shibboleth file configuration"
        cat $CONFIG_DIR/template/$SHIBBOLETH_TEMPLATE_XML | envsubst > /etc/shibboleth/shibboleth2.xml    
    fi
    
    #Start Shibboleth
    service shibd start

    # Redirect Shibd Log 
    {
    tail -f /var/log/shibboleth/shibd.log 		>> /proc/1/fd/1 ;
    tail -f /var/log/shibboleth/shibd_warn.log 	>> /proc/1/fd/2 ; 
    tail -f /var/log/shibboleth/transaction.log >> /proc/1/fd/1 ;
    tail -f /var/log/shibboleth/signature.log 	>> /proc/1/fd/1
    } &
else
    echo "Apache without SP File"
    cat $CONFIG_DIR/template/jwtgenerator.conf | envsubst > /etc/apache2/sites-available/jitsi-auth.conf
fi
echo "Config Generation Done"

echo "Dealing with certificate files"
if [ ! -f  /etc/apache2/apache.pem ]
then
    echo "Generate self signed certificate for apache"
    openssl req -x509 -newkey rsa:4096 -keyout key.pem -out cert.pem -days 10000 -nodes -subj '/CN='$SERVER_NAME
    cat key.pem cert.pem > /etc/apache2/apache.pem
    rm key.pem cert.pem
fi

a2ensite jitsi-auth.conf && a2enmod ssl


#Start Apache
/usr/sbin/apache2ctl -DFOREGROUND
