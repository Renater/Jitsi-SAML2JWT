# Jitsi-SAML2JWT

Jitsi-SAML2JWT is a project to easily use SAML authentification with JWT, the new recommended Jitsi-Meet authentification mechanism. 


## Motivation

The legacy Shibboleth auth mechanism in Jitsi Jicofo will be removed by the Big Jitsi Auth Refactoring (see this [post](https://community.jitsi.org/t/intent-to-deprecate-and-remove-external-auth-mechanisms/115332)).

So to continue to use SAML with Jitsi, we need an independent system secured by SAML protocol to generate the JWT token.

## This project provides 2 things : 
 - Stand Alone JWT generator : a simple JWT generator written in PHP using authentification informations provided by an external SAML Proxy SP. 

 - A full running Docker file to deploy the token generator with a Shibboleth SAML SP because sometime it seems complex to deploy Shibboleth by hand.  

We're providing two generation modes : 
 - A simple one based only on email check. 
 - An advanced one with room validation and private room features. See [doc/advanced_token_generator.md](doc/advanced_token_generator.md) for more details.


## Authentication Principle

The authentication call flow with a SAML SP and the JWT server looks like this : 

<img src="doc/auth_call_flow.svg" width=70% height=70%>


## 🛠️ Dependencies

- [firebase/php-jwt](https://github.com/firebase/php-jwt) (`Firebase\JWT\JWT`, `Firebase\JWT\Key`)


## Configuration Env variables
Before any installation you should create the .env file with your own informations. 
```
> mv .env_ref .env
```
Then edit the .env values.

```
#Shibboleth
ENABLE_SHIBBOLETH=true
SHIBBOLETH_TEMPLATE_XML=shibboleth2_sp_direct_idp.xml
SP_ENTITY_ID=jitsi-auth
METADATA_URL=http://saml2/idp/metadata
SSO_URL=http://saml2/idp/sso
ENABLE_BACKEND_JWT_REQUEST=false

#JWT Token Generator
JITSI_DOMAIN=my.jitsi.meet
JWT_GENERATOR_KEY=my_jitsi_app_secret
JWT_APP_ID=my_jitsi_app_id
JWT_TOKEN_MODE=default
AUTH_ERROR_PAGE=static/error.html
JICOFO_ROOM_ENDPOINTS=192.168.0.1,192.168.0.2
ENABLE_VALIDITY_BY_REQUEST=true
DEFAULT_VALIDITY=0

#Web server
SERVER_NAME=jitsi-auth.meet
```

### Shibboleth SP related variables
- `ENABLE_SHIBBOLETH` enable and install the local Shibooleth Service Provider.
- `SHIBBOLETH_TEMPLATE_XML` with shibboleth2.xml template to use (direct idp, with a discovery service or a prebuild federation one).
- `SP_ENTITY_ID` with the Identity of your SAML Service Provider.
- `METADATA_URL` with the remote SAML metadata information url (idp or provided by a federation). 
- `SSO_URL` with the target SAML SSO url for user redirection (idp or a discovery service). 
- `ENABLE_BACKEND_JWT_REQUEST` enable an extra port (8443) for the getToken request not protected by the shibboleth SP. It shoud only be open to some backend server trafic. 

### JWT Token generator related variables
- `JITSI_DOMAIN` with the target Jitsi-Meet prosody virtual host. 
- `JWT_APP_SECRET` with prosody application secret known only to your token.
- `JWT_APP_ID` with prosody application identifier.
- `JWT_TOKEN_MODE` with default to use a simple token generator based only on email check or advanced to add affiliation and private features see [here](doc/advanced_token_generator.md).
- `AUTH_ERROR_PAGE` with the error page to display in case of authentication error.
- `JICOFO_ROOM_ENDPOINTS` with the list of jicofo room endpoints (IP) to check if a room is already started.
- `ENABLE_VALIDITY_BY_REQUEST` enable the possibility to set the validity of the token by request (see doc).
- `DEFAULT_VALIDITY` set the default validity of the token in seconds (0 for infinite validity).

### Web server related variables
- `SERVER_NAME` with web server name of your token generator.

## Generate certificates 

We provide a script to generate self-signed certificates for Apache and Shibboleth because SAML certificate auth needs to be shared with IDP or federation Metadata.
```
> cd tools
> bash init_certificates.sh
```

## Stand Alone JWT generator
This instalaltion script supposed you run it on a debian like operarting system (Ubuntu >= 18 or Debian >= 11).
You can run init_certificates.sh before installing the jwt server or provide your own certificat for apache in the conf/cert/apache.pem file.

```
> cd tools
> bash install_jwtgenerator.sh
```


## Docker SAML SP and JWT Generator

Build Docker Image :
```
docker image build -t jitsisaml2jwt .
```

We also provide Docker image on DockerHub : [https://hub.docker.com/r/renater/jitsisaml2jwt].

Start with docker compose : 
```
docker compose up -d
```

## SAML integration

You need to provide your sp-cert.pem and your Service Provider Metdata url (https://[SERVER_NAME]/Shibboleth.sso/SAML2/POST) informations to the remote IDP or Federation registry.


## Jitsi configuration to use JWT

With Jitsi-Meet Docker version : 
- Set `ENABLE_AUTH=1`, `AUTH_TYPE=jwt` and 
- Set `JWT_APP_SECRET`      with prosody application secret known only to your token.
- Set `JWT_APP_ID`  with prosody application identifier.
- To redirect from jitsi to login set the url of this container`TOKEN_AUTH_URL=https://[server_name]/redirectWithToken?room={room}`
- To redirect from jitsi to login with tenant set `TOKEN_AUTH_URL=https://[server_name]/redirectWithToken?room={room}&tenant='+subdir+'`

If you don't use Docker version, check with community post [jitsi-meet-tokens-chronicles](https://community.jitsi.org/t/jitsi-meet-tokens-chronicles-on-debian-buster/76756/3).

## Jitsi-SAML2JWT Request description

### Main request 

| Request                        | Description                                                                       | Return  | 
|--------------------------------|-----------------------------------------------------------------------------------|:--------|
| /redirectWithToken             | Redirect to the configured jitsi instance adding the JWT token in destination     |         |
| /redirectWithTokenEscape       | Redirect from a jitsi iframe to the configured jitsi instance adding the JWT token in destination |         |


Sample request : 
https://[server_name]/redirectWithToken?room=Test&tenant=tenant1

### BACKEND request to get or test the token
| Request                        | Description                                                                       | Return  | 
|--------------------------------|-----------------------------------------------------------------------------------|:--------|
| /getToken                      | Get a valid Token                                                                 |  string |
| /isValidToken                  | Check if a provided token is valid                                                | boolean |


Sample request : 
https://[server_name]/getToken?room=Test&validity_timestamp=1674645573


### Request available parameters:

| Key                            | Description                                                                                                                                  | Mandatory |    Type | Default value |
|--------------------------------|----------------------------------------------------------------------------------------------------------------------------------------------|:---------:|--------:|---------------|
| room                           | Restrict token to the specified room                                                                                                         |           |  string |               |
| tenant                         | Restrict token and redirect to the specified tenant                                                                                           |           |  string | empty         |
| validity_timestamp             | Set the max validity timestamp of the JWT token according to https://www.rfc-editor.org/rfc/rfc7519#section-4.1.4 (0 for unlimited Token)   |           |  int    |              |
| jitsi_meet_external_api_id    | Set the external api id to use in jitsi embedded frame                                                                                                    |           |  string | empty         |