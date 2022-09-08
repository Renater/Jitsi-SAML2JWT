#!/bin/bash

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
