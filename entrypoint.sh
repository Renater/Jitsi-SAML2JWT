#!/bin/bash

#Start Shibboleth
service shibd start

#Start Apache
/usr/sbin/apache2ctl -DFOREGROUND
