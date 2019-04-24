<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONSTANTS - MAP IDS TO THE APPLICATION CONSTANTS
| -------------------------------------------------------------------
*/

// TODO: review and remove unused

// Pages
$config['DB_PAGE_ERROR404'] = 11;

// Common constants
$config['UNKNOWN_TYPE_ID'] = 971;

// Relations constants
$config['RELATION_TYPE_OWNER_FOUNDER'] = 'Vlastník / Zřizovatel';
$config['RELATION_TYPE_OWNER_FOUNDER_COLOR'] = '82C223';

$config['STATUTAR_RELATION_TYPES'] = array(5, 6, 7, 8, 9, 10, 11);
$config['SPOLECNIK_RELATION_TYPES'] = array(18);

$config['MIN_BLACKLISTED'] = 10000;

$config['SPIS_NOTAVAILABLE_TEXT'] = 468;
$config['SPIS_STATUS_NOTAVAILABLE'] = 17;
$config['SPIS_STATUS_FAULTENTRY'] = 2;

// Highest supervisor type that is supported
$config['MAX_SUPPORTED_SPIS_SUPERVISOR_TYPE'] = 2;

$config['SECTION_POHLEDAVKY'] = 2;

$config['SPIS_COUNT_MINOR_DOCS'] = 98500;

// Inactive insolvence states
// List of spis statues which denote that spis is not active
$config['STATES_NOT_INSOLVENCE'] = array(1, 2, 5, 7, 14, 17);

// Emails
$config['EMAIL_ACTIVATEPAID'] = 2;
$config['EMAIL_FORGOTTENPASS'] = 3;
$config['EMAIL_FOOTER'] = 4;
$config['EMAIL_HEADER'] = 5;
$config['EMAIL_NEWLIKVIDACE'] = 9;
$config['EMAIL_NEWISIRXLS'] = 15;
$config['EMAIL_NONEWISIR'] = 23;
$config['EMAIL_NEWVATDEBTORSXLS'] = 24;
$config['EMAIL_NONEWVATDEBTORS'] = 25;
$config['EMAIL_NEWACCOUNTCHANGE'] = 102;
$config['EMAIL_NEWCLAIMS'] = 103;
$config['EMAIL_CHANGEACCOUNTSERVICEMSG'] = 106;
$config['EMAIL_DELETEDACCOUNTFREE'] = 107;
$config['EMAIL_DELETEDACCOUNTPAID'] = 108;
$config['EMAIL_REGSERVICECREATE'] = 109;
$config['EMAIL_REGSERVICEALTER'] = 110;
$config['EMAIL_NEWORSXLS'] = 111;

// Indexes
$config['USER_DISABLED'] = 1;
$config['USER_ACTIVE'] = 2;
$config['USER_DELETED'] = 3;

// Data sources
$config['DATA_SOURCE_DEFAULT'] = 1;
$config['DATA_SOURCE_GINIS'] = 2;
$config['DATA_SOURCE_FIRMAIC'] = 3;

// ISIR notifications frequencies
$config['USER_NOTIFICATION_FREQUENCY_2TIMESADAY'] = 1;
$config['USER_NOTIFICATION_FREQUENCY_1TIMEADAY'] = 2;
$config['USER_NOTIFICATION_FREQUENCY_1TIMEAWEEK'] = 3;
$config['USER_NOTIFICATION_FREQUENCY_1TIMEPER2WEEKS'] = 4;
$config['USER_NOTIFICATION_FREQUENCY_NONE'] = 5;
$config['USER_NOTIFICATION_FREQUENCY_2TIMESADAYNOWEEKEND'] = 6;
$config['USER_NOTIFICATION_FREQUENCY_1TIMEADAYNOWEEKEND'] = 7;

// ISIR notifications types
$config['USER_NOTIFICATION_FILTERING_ALL'] = 1;
$config['USER_NOTIFICATION_FILTERING_STATUSCHANGESONLY'] = 2;

// Notification settings localization - OR (same applies for CZ and SK)
$config['NOTIFICATION_OR_TYPES'] = array(
    'name' => 'Změna názvu',
    'address' => 'Změna sídla',
    'law_form' => 'Změna právní formy',
    'owner' => 'Změna vlastníka (společníka, zakladatele)',
    'statutar' => 'Změna osob ve statutárním orgánu',
    'zastupovani' => 'Změna způsobu zastupování',
    'transformation' => 'Přeměna společnosti',
    'execution' => 'Exekuce na podíl',
    'insolvency' => 'Vstup do insolvence',
    'likvidace' => 'Likvidace',
    'predmet_podnikani' => 'Změna předmětu podnikání',
    'druh_podilu' => 'Změna druhu podílu',
    'zastavni_pravo' => 'Zástavní právo',
    'other' => 'Ostatní'
);

/* End of file database.php */
/* Location: ./application/config/database.php */
