 #!/bin/bash

. ../config/jitsi-SAML2JWT.env

# Generate self signed certificate for apache
openssl req -x509 -newkey rsa:4096 -keyout key.pem -out cert.pem -days 10000 -nodes -subj '/CN='$SERVER_NAME
cat key.pem cert.pem > apache.pem

mv apache.pem  ../config/cert
rm key.pem cert.pem