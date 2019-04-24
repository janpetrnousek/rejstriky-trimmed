<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Proxy data to retrieve data from OR or another registers
$config['PROXY_USERNAME'] = 'xxx';
$config['PROXY_PASSWORD'] = 'xxx';

// Application information for assets versioning
$config['APPLICATION_NAME'] = 'Rejstříky.info';
$config['APPLICATION_VERSION'] = '2.0';

// Search constants
$config['SEARCH_SCREENING'] = 1;
$config['SEARCH_DATA'] = 2;
$config['SEARCH_RELATIONS'] = 3;

// Ordering watches constants 
$config['ORDER_NAME'] = 1;
$config['ORDER_INSERT'] = 2;

// Search validation constants
$config['SEARCH_NAME_LENGTH'] = 200;
$config['SEARCH_IC_LENGTH'] = 20;
$config['SEARCH_RC_LENGTH'] = 20;
$config['SEARCH_ADDRESS_LENGTH'] = 200;
$config['SEARCH_SPISMARK_LENGTH'] = 100;

// URL details
$config['DETAIL_OBCHODNI'] = 'o';
$config['DETAIL_ISIR'] = 'i';

// Limit length for home page new subjects
$config['HOME_NEWSUBJECTS_TITLE'] = 18;
$config['HOME_NEWSUBJECTS_ADDRESS'] = 22;

// Limit length for detail and screening titles
$config['DETAIL_TITLE'] = 50;
$config['SCREENING_TITLE'] = 40;

// Size of a page
$config['PAGE_SIZE'] = 15;

// Spis prefix
$config['SPIS_PREFIX'] = 'INS';

// Active/non active spis constants
$config['SPIS_NOT_ACTIVE'] = 1;
$config['SPIS_ACTIVE'] = 2;

// Session constants
$config['USER_LOGGED_SESSION'] = 'logged_id';
$config['CMS_LOGGED_SESSION'] = 'cms_logged_id';
$config['REGISTRATION_SESSION'] = 'registration_session';

// Watch types contants
$config['WATCH_SOURCE_JUSTDATA'] = 'justdata'; // this is very same as servis, but it changes captions
$config['WATCH_SOURCE_SERVIS'] = 'servis';
$config['WATCH_SOURCE_MONITORING'] = 'monitoring';

// Password validation criteria
$config['PASSWORD_VALIDATION_CRITERIA'] = 'trim|required|min_length[8]|matches[password2]';

// Alrogithm used to hash and verify passwords
$config['HASH_PASS_ALG'] = PASSWORD_ARGON2I;

// Constant representing a start datetime for the new and untouched record's date property
$config['START_YEAR'] = '1970-01-01 00:00:00';

// User accounts
$config['USER_ACCOUNTS'] = array(
    array('subjects' => 50, 'monthprice' => 100),
    array('subjects' => 100, 'monthprice' => 150),
    array('subjects' => 150, 'monthprice' => 220),
    array('subjects' => 250, 'monthprice' => 300),
    array('subjects' => 400, 'monthprice' => 350),
    array('subjects' => 800, 'monthprice' => 400),
    array('subjects' => 1400, 'monthprice' => 450),
    array('subjects' => 2000, 'monthprice' => 520),
    array('subjects' => 4000, 'monthprice' => 570)
);

// Account selected by default
$config['USER_ACCOUNTS_DEFAULT_INDEX'] = 2;

// Discounts
$config['DISCOUNT_LAWYER'] = 5;
$config['DISCOUNT_YEAR'] = 6;

// Length of trial period in days
$config['TRIAL_LENGTH_DAYS'] = 20;

// Number of days we send the invoice before the payment day
$config['SEND_INVOICE_BEFORE_DAYS'] = 14;

// Names of the various system notifcations
$config['SPIS_NOTIFICATION_NAME'] = 'Upozorňování na zahájená insolvenční řízení a změny stavů řízení u sledovaných osob';
$config['LIKVIDACE_NOTIFICATION_NAME'] = 'Upozorňování na vstup do likvidace u sledovaných obch. společností';
$config['VATDEBTORS_NOTIFICATION_NAME'] = 'Upozorňování na neplatiče DPH';
$config['ACCOUNTS_NOTIFICATION_NAME'] = 'Upozorňování na změny bankovních účtů';
$config['CLAIMS_NOTIFICATION_NAME'] = 'Upozorňování na přihlášené pohledávky';
$config['OR_NOTIFICATION_NAME'] = 'Upozorňování na změny v obchodním rejstříku';
$config['ORSK_NOTIFICATION_NAME'] = 'Upozorňování na změny ve slovenském obchodním rejstříku';
$config['IFILTER_NOTIFICATION_NAME'] = 'Upozorňování na podobné záznamy';

// Session data which when set collect emails to SESS_COLLECTED_EMAILS item
$config['SESS_COLLECT_EMAILS'] = "COLLECT_EMAILS";
$config['SESS_COLLECT_EMAILS_ON'] = "TRUE";
$config['SESS_COLLECTED_EMAILS'] = "COLLECTED_EMAILS";

// Rejstrikovy servis - create company
$config['REGSERVIS_CREATE_SROBASIC'] = 'S.R.O. BASIC';
$config['REGSERVIS_CREATE_SROEXTRA'] = 'S.R.O. EXTRA';
$config['REGSERVIS_CREATE_OSCVTOSRO'] = 'z OSVČ na S.R.O.';
$config['REGSERVIS_CREATE_ASBASIC'] = 'A.S. BASIC';
$config['REGSERVIS_CREATE_OTHER'] = 'jiný požadavek';

// Rejstrikovy servis - alter company
$config['REGSERVIS_ALTER_ADDRESS'] = 'ZMĚNA SÍDLA';
$config['REGSERVIS_ALTER_MEMBER'] = 'ZMĚNA ČLENA ORGÁNU';
$config['REGSERVIS_ALTER_PROKURA'] = 'ZŘÍZENÍ PROKURY';
$config['REGSERVIS_ALTER_AGREEMENT'] = 'ZMĚNA SPOLEČENSKÉ SMLOUVY';
$config['REGSERVIS_ALTER_OTHER'] = 'jiný požadavek';

// Hidden node for not registered user
$config['RELATIONS_HIDDENNODE'] = 'HIDDENNODE';


/* End of file database.php */
/* Location: ./application/config/database.php */
