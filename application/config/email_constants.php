<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//TODO: CHANGE THIS TO NEW EMAIL CREDENTIALS

// Email where contact emails are sent
$config['CONTACT_EMAIL'] = 'michal.jakubeczy@gmail.com'; // 'info@rejstriky.info'; // jakubeczy@prowia.cz

// Email where emails to user are sent from
$config['SENDER_EMAIL'] = 'info@isir.info'; // info@prowia.cz

// Password for email sender account
$config['SENDER_EMAIL_PASSWORD'] = 'xxx'; // info@prowia.cz

// Email where emails to user are sent from, used as secondary one to distribute the load
$config['SENDER_EMAIL_SECONDARY'] = 'notifikace@isir.info'; // info@prowia.cz

// Password for secondary email sender account
$config['SENDER_EMAIL_SECONDARY_PASSWORD'] = 'xxx:'; // info@prowia.cz

// Email where emails to user are sent from, used as secondary one to distribute the load
$config['SENDER_EMAIL_TERTIARY'] = 'notifikace2@isir.info'; // info@prowia.cz

// Password for secondary email sender account
$config['SENDER_EMAIL_TERTIARY_PASSWORD'] = 'xxx:'; // info@prowia.cz

// Email where emails to user are sent from, used as special one to distribute the load
// This one should be used rarely only for customers who experience problems with delivery (e.g. paid on seznam.cz)
$config['SENDER_EMAIL_SPECIAL'] = 'notifikace@prowia.cz'; // info@prowia.cz

// Password for secondary email sender account
$config['SENDER_EMAIL_SPECIAL_PASSWORD'] = 'xxx:'; // info@prowia.cz

// Sender name
$config['SENDER_NAME'] = 'Rejstříky.info'; 

// Protocol to send emails
$config['SENDER_PROTOCOL'] = 'smtp'; 

// Host for sending emails
$config['SENDER_HOST'] = 'smtp.websupport.sk'; 

// Port for sending emails
$config['SENDER_PORT'] = 25; 

// Clients who receive their emails sent through our special sender email
$config['SENDER_EMAIL_SPECIAL_USERS'] = array(); // add 2311
