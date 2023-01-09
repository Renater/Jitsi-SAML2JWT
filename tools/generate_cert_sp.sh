#!/bin/bash
. ../.env

ENV=$SP_ENTITY_ID

echo "Generate certificat for $ENV Shibboleth SP"
cat <<EOT >>  sp-cert.cnf
[req]
RANDFILE=/dev/urandom
default_bits=3072
default_md=sha256
encrypt_key=no
distinguished_name=dn
# PrintableStrings only
string_mask=MASK:0002
prompt=no
x509_extensions=ext

# customize the "default_keyfile,", "CN" and "subjectAltName" lines below
default_keyfile=sp-key.pem

[dn]
CN=$ENV

[ext]
subjectAltName = DNS:$ENV, \
                 URI:$ENV/shibboleth
subjectKeyIdentifier=hash
EOT

openssl req -new -x509 -config sp-cert.cnf -text -out sp-cert.pem -days 3650

if [ ! -d "../config/cert/" ]; then
  mkdir ../config/cert/
fi

rm sp-cert.cnf
mv sp-* ../config/cert