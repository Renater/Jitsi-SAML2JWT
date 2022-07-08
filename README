# Jitsi-SAML2JWT

Jitsi-SAML2JWT is a project to easily use SAML authentification with JWT the new recommended Jitsi-Meet authentification mecanisms. 


## Motivation

The legacy Shibboleth auth mecanisme in Jitsi Jicofo will be removed by the Big Jitsi Auth Refactoring (see this [post](https://community.jitsi.org/t/intent-to-deprecate-and-remove-external-auth-mechanisms/115332) ). So to continue to use SAML with Jitsi, we need a independent system securised by SAML protocol to generate the JWT token.

This project provides 2 things : 
 - a simple JWT generator written in PHP using authentification informations provided by an external SAML Proxy SP.
 - a full running Docker file to deploy the token generator and a Shibboleth SAML SP (because sometime it seems complex to deploy Shibboleth by hand :) ).  

## Configuration
Before any installation you should create the jitsi-SAML2JWT.env file with your own informations. 
> mv config/jitsi-SAML2JWT.env_ref conf/jitsi-SAML2JWT.env
`̀ ̀

Then you should run generate_config_file.sh in tools directory.
`̀ ̀
> cd tools
> sh generate_config_file.sh
`̀ ̀


### Stand Alone JWT generator 

You only need to file sections JWT Token Generator and Web server.

`̀ ̀
#JWT Token Generator
JITSI_DOMAIN=my.jitsi.meet
JWT_GENERATOR_KEY=my_jitsi_app_secret
JWT_APP_ID=my_jitsi_app_id

#Web server
SERVER_NAME=jitsi-auth.meet
`̀ ̀

- `JITSI_DOMAIN` with the target Jitsi-Meet prosody virtual host. 
- `JWT_GENERATOR_KEY` with prosody application secret known only to your token.
- `JWT_APP_ID` with prosody application identifier.
- `SERVER_NAME` with web server name of your token generator.

### SAML SP and JWT Generator

You need to file all sections.

`̀ ̀
#Shibboleth
SHIBBOLETH_TEMPLATE_XML=shibboleth2_sp_direct_idp.xml
SP_ENTITY_ID=jitsi-auth
METADATA_URL=http://saml2/idp/metadata
SSO_URL=http://saml2/idp/sso

#JWT Token Generator
JITSI_DOMAIN=my.jitsi.meet
JWT_GENERATOR_KEY=my_jitsi_app_secret
JWT_APP_ID=my_jitsi_app_id

#Web server
SERVER_NAME=jitsi-auth.meet
`̀ ̀

- `SHIBBOLETH_TEMPLATE_XML` with shibboleth2.xml template to use (direct idp, with a discovery service, a prebuild federation one).
- `SP_ENTITY_ID` with the Identity of your SAML Service Provider.
- `METADATA_URL` with the remote SAML metadata information url (idp or provided by a federation). 
- `SSO_URL` with the target SAML SSO url for user redirection (idp or a discovery service). 

- `JITSI_DOMAIN` with the target Jitsi-Meet prosody virtual host. 
- `JWT_APP_SECRET` with prosody application secret known only to your token.
- `JWT_APP_ID` with prosody application identifier.
- `SERVER_NAME` with web server name of your token generator.

## Installation

You must first complete the jitsi-SAML2JWT.env file and run generate_config_file.sh.

### Generate certificates 

We provide a script to generate selfsigned certificat for Apache and Shibboleth.
`̀ ̀
> cd tools
> sh init_certificates.sh
`̀ ̀


If you want, you can use your own certificate by setting it in conf/cert directory (apache.pem for apache and s-cert.pem and sp-cert.key for Shibboleth).

### Stand Alone JWT generator
This instalaltion script supposed you run it on a debian like operarting system (Ubuntu >= 18 or Debian >= 11 ).

`̀ ̀
> cd tools
> sh install_jwtgenerator.sh
`̀ ̀

### SAML SP and JWT Generator

Build Docker Image :
`̀ ̀
docker image build -t shib2jwt .
`̀ ̀

Start the docker container : 
`̀ ̀
docker run --rm -d  -p 443:443/tcp -p 80:80/tcp shib2jwt:latest
`̀ ̀

You also need to provide your sp-cert.pem and your Service Provider Metdata url (https://[SERVER_NAME]/Shibboleth.sso/SAML2/POST)  informations to the remote IDP or Federation registry.


## Jitsi configuration to use JWT

With Jitsi-Meet Docker version : 
- Set `ENABLE_AUTH=1`, `AUTH_TYPE=jwt` and 
- Set `JWT_APP_SECRET`      with prosody application secret known only to your token.
- Set `JWT_APP_ID`  with prosody application identifier.
- To redirect from jitsi to login set the url of this container`TOKEN_AUTH_URL=https://[server_name]/generateToekn?room={room}`

If you don't use Docker version check with community post (jitsi-meet-tokens-chronicles)[https://community.jitsi.org/t/jitsi-meet-tokens-chronicles-on-debian-buster/76756/3].