<?php

/**
 * SAML 2.0 remote SP metadata for SimpleSAMLphp.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-sp-remote
 */

/*
 * Example SimpleSAMLphp SAML 2.0 SP
 */
$metadata['https://saml2sp.example.org'] = [
    'AssertionConsumerService' => 'https://saml2sp.example.org/simplesaml/module.php/saml/sp/saml2-acs.php/default-sp',
    'SingleLogoutService' => 'https://saml2sp.example.org/simplesaml/module.php/saml/sp/saml2-logout.php/default-sp',
];

/*
 * This example shows an example config that works with G Suite (Google Apps) for education.
 * What is important is that you have an attribute in your IdP that maps to the local part of the email address at
 * G Suite. In example, if your Google account is foo.com, and you have a user that has an email john@foo.com, then you
 * must set the simplesaml.nameidattribute to be the name of an attribute that for this user has the value of 'john'.
 */
$metadata['google.com'] = [
    'AssertionConsumerService' => 'https://www.google.com/a/g.feide.no/acs',
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
    'simplesaml.nameidattribute' => 'uid',
    'simplesaml.attributes' => false,
];

$metadata['https://legacy.example.edu'] = [
    'AssertionConsumerService' => 'https://legacy.example.edu/saml/acs',
    /*
     * Currently, SimpleSAMLphp defaults to the SHA-256 hashing algorithm.
     * Uncomment the following option to use SHA-1 for signatures directed
     * at this specific service provider if it does not support SHA-256 yet.
     *
     * WARNING: SHA-1 is disallowed starting January the 1st, 2014.
     * Please refer to the following document for more information:
     * http://csrc.nist.gov/publications/nistpubs/800-131A/sp800-131A.pdf
     */
    //'signature.algorithm' => 'http://www.w3.org/2000/09/xmldsig#rsa-sha1',
];



$metadata['https://rdv42.rendez-vous.renater.fr'] = array (
  'contacts' =>
  array (
  ),
  'metadata-set' => 'saml20-sp-remote',
  'AssertionConsumerService' =>
  array (
    0 =>
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
      'Location' => 'https://rdv42.rendez-vous.renater.fr/Shibboleth.sso/SAML2/POST',
      'index' => 1,
    ),
    1 =>
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST-SimpleSign',
      'Location' => 'https://rdv42.rendez-vous.renater.fr/Shibboleth.sso/SAML2/POST-SimpleSign',
      'index' => 2,
    ),
    2 =>
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Artifact',
      'Location' => 'https://rdv42.rendez-vous.renater.fr/Shibboleth.sso/SAML2/Artifact',
      'index' => 3,
    ),
    3 =>
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:PAOS',
      'Location' => 'https://rdv42.rendez-vous.renater.fr/Shibboleth.sso/SAML2/ECP',
      'index' => 4,
    ),
  ),
  'SingleLogoutService' =>
  array (
    0 =>
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:SOAP',
      'Location' => 'https://rdv42.rendez-vous.renater.fr/Shibboleth.sso/SLO/SOAP',
    ),
    1 =>
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
      'Location' => 'https://rdv42.rendez-vous.renater.fr/Shibboleth.sso/SLO/Redirect',
    ),
    2 =>
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
      'Location' => 'https://rdv42.rendez-vous.renater.fr/Shibboleth.sso/SLO/POST',
    ),
    3 =>
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Artifact',
      'Location' => 'https://rdv42.rendez-vous.renater.fr/Shibboleth.sso/SLO/Artifact',
    ),
  ),
  'keys' =>
  array (
    0 =>
    array (
      'encryption' => true,
      'signing' => true,
      'type' => 'X509Certificate',
      'X509Certificate' => 'XXXXXXXXXXXXX
',
    ),
  ),
);




$metadata['jitsi-auth'] = array (
  'contacts' =>
  array (
  ),
  'metadata-set' => 'saml20-sp-remote',
  'AssertionConsumerService' =>
  array (
    0 =>
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
      'Location' => 'https://jitsi-auth/Shibboleth.sso/SAML2/POST',
      'index' => 1,
    ),
    1 =>
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST-SimpleSign',
      'Location' => 'https://jitsi-auth/Shibboleth.sso/SAML2/POST-SimpleSign',
      'index' => 2,
    ),
    2 =>
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Artifact',
      'Location' => 'https://jitsi-auth/Shibboleth.sso/SAML2/Artifact',
      'index' => 3,
    ),
    3 =>
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:PAOS',
      'Location' => 'https://jitsi-auth/Shibboleth.sso/SAML2/ECP',
      'index' => 4,
    ),
  ),
  'SingleLogoutService' =>
  array (
    0 =>
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:SOAP',
      'Location' => 'https://jitsi-auth/Shibboleth.sso/SLO/SOAP',
    ),
    1 =>
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
      'Location' => 'https://jitsi-auth/Shibboleth.sso/SLO/Redirect',
    ),
    2 =>
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
      'Location' => 'https://jitsi-auth/Shibboleth.sso/SLO/POST',
    ),
    3 =>
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Artifact',
      'Location' => 'https://jitsi-auth/Shibboleth.sso/SLO/Artifact',
    ),
  ),
  'keys' =>
  array (
    0 =>
    array (
      'encryption' => true,
      'signing' => true,
      'type' => 'X509Certificate',
      'X509Certificate' => 'MIID/DCCAmSgAwIBAgIJALkkc8dcX0m6MA0GCSqGSIb3DQEBCwUAMBUxEzARBgNV
      BAMTCmppdHNpLWF1dGgwHhcNMjIwNzA4MTQwNzM1WhcNMzIwNzA1MTQwNzM1WjAV
      MRMwEQYDVQQDEwpqaXRzaS1hdXRoMIIBojANBgkqhkiG9w0BAQEFAAOCAY8AMIIB
      igKCAYEAzQjuy1wIICxll3r+cMv4LN3rniGVsTwZpoSFBNiL+Zr1aA/ha12p40xB
      24ziwA3NEUcl1kr30EHNuG2oVwhNPFSI0ULtEdW7Ap9RrFbl3etyTFIQ0DHuEK5Q
      XEl+7dog+ICapij0WO4GuyU2ptaPL5ZoAhcD91jRhf3FXWQHyWAimpYk/974cThV
      a4vM7KU5qAyqlJ/qLhXyVmWKR/QcZtGuqXisTtnguuOoVGVTmEQs49A7RLo5LWUC
      nxl3rGCMjdgaG/jedQTzqVv9vUBzsueu3RTusK0dMPdA5pjxnzD9atvPuPqwm2LZ
      uFh97xfZDMCNO6c1GLaBL9OZjKezQfEww36InMVB22Mq1O6puvCgYB9Ym0Xc8DFl
      kkKzYigQl60PZZ+3Q+n45YWvsyNVA4u9Z5+FHbEEH/HMDslFtlHGmfen1rmLfPts
      F40YrBwmQL+JDcExTeiWdMYbEEnE8rKfGMetiGIRh+Emr7z1Toz5LgEGuNDMp9bv
      vq1nCutNAgMBAAGjTzBNMCwGA1UdEQQlMCOCCmppdHNpLWF1dGiGFWppdHNpLWF1
      dGgvc2hpYmJvbGV0aDAdBgNVHQ4EFgQUQ9nbq2iJkluH53EHvvGRt0QP8towDQYJ
      KoZIhvcNAQELBQADggGBADvxpDJMAot+UZRTix8juAmfunnizyzzNzf1Cht6WUJg
      0O+Pg7/TKEVWDm2h7ocBJd+7yRnM3NicsYGT53XSUyuPQsBiVSCrqoTLnRLSx+j3
      5EEQw4hH/UWpvszhX+HyLyqFPS5Y+hBebarh9D1fLZ7DHaozVq2r/1ucVMaqQ7gw
      oDiCYwGw4k1JyvCw427JaV3zf9v1+hagG25ZrwfAcE+rwKT3hNTrexLMG+NyOaMx
      dAp+8WJsO/s38V0uvSKAwUnK8sM1/VpmG77enpNIcQIqDefEMm+QS75GgoOJi2uZ
      e8EFrjTw/L5H3RwAQ4TBZFv4GanFgZ5BqdM4hlB+z4Z9/PviLjIecGU1GwyOZz7I
      NSaNkx5gTFsykLFrL3PxnHkMRW9M0p/N+5AiUVIyhEx87q5Cn27j/kqPnQzwzOc0
      mm5WieMNX+Z+8wbyOQiApfWIXU9U5ZckdkVvcqUNx+YYEiUrnYX5jG+aPcQM8cM2
      I1IxJ8G8rkewfmv67rInLw==
',
    ),
  ),
);
