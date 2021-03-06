#!/bin/sh

. ../config/jitsi-SAML2JWT.env

export SERVER_NAME 
export SHIBBOLETH_TEMPLATE_XML 
export SP_ENTITY_ID 
export METADATA_URL 
export SSO_URL 
export JITSI_DOMAIN 
export JWT_APP_SECRET
export JWT_APP_ID 
export config='$config'


echo "ServerName $SERVER_NAME"

echo "Generating config File from template and jitsi-SAML2JWT.env file"

echo "Apache File"
cat ../config/template/jwtgenerator.conf | envsubst > ../config/jitsi-auth.conf


echo "PHP Token file"
cat ../config/template/config.php | envsubst > ../config/config.php

echo "Shibboleth file"
cat ../config/template/$SHIBBOLETH_TEMPLATE_XML | envsubst > ../config/shibboleth2.xml

echo "Generation Done"