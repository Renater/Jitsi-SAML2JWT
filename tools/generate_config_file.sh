#!/bin/bash

. ../.env

export SERVER_NAME 
export SHIBBOLETH_TEMPLATE_XML 
export SP_ENTITY_ID 
export METADATA_URL 
export SSO_URL 
export JITSI_DOMAIN 
export JWT_APP_SECRET
export JWT_APP_ID 
export JWT_TOKEN_MODE 

export config='$config'


echo "ServerName $SERVER_NAME"

echo "Generating config File from template and .env file"

echo "PHP Token file"
cat ../config/template/config.php | envsubst > ../config/config.php

if [ $ENABLE_SHIBBOLETH = "true" ]
then
echo "Apache ans Hibboleth File for JWT and SAML SP"
cat ../config/template/jwtgenerator-sp.conf | envsubst > ../config/jitsi-auth.conf
echo "Shibboleth file"
cat ../config/template/$SHIBBOLETH_TEMPLATE_XML | envsubst > ../config/shibboleth2.xml
else
echo "Apache File for stand alone JWT"
cat ../config/template/jwtgenerator.conf | envsubst > ../config/jitsi-auth.conf
fi
echo "Generation Done"