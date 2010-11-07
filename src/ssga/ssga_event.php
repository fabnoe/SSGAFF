<?php
require_once 'autoload.php';

use UnitedPrototype\GoogleAnalytics;

// Request vars
if (isset($_GET['accountId'])) { $accountId = $_GET['accountId']; } else { throw new UnexpectedValueException('accountId for page not set.');}
if (isset($_GET['domainName'])) { $domainName = $_GET['domainName']; } else { throw new UnexpectedValueException('domainName for page not set.');}
if (isset($_GET['category'])) { $category = $_GET['category']; } else { throw new UnexpectedValueException('category for page not set.');}
if (isset($_GET['action'])) { $action = $_GET['action']; } else { throw new UnexpectedValueException('action for page not set.');}
if (isset($_GET['label'])) { $label = $_GET['label']; } else { throw new UnexpectedValueException('label for page not set.');}
if (isset($_GET['userstatus'])) { $userstatus = $_GET['userstatus']; } else { throw new UnexpectedValueException('userstatus for page not set.');}

// Config
$config = new GoogleAnalytics\Config();
$config->setAnonymizeIpAddresses(true);

// Initilize GA Tracker
$tracker = new GoogleAnalytics\Tracker($accountId, $domainName, $config);

// Assemble Visitor information
$visitor = new GoogleAnalytics\Visitor();
$visitor->setIpAddress($_SERVER['REMOTE_ADDRESS']);
$visitor->setUserAgent($_SERVER['HTTP_USER_AGENT']);
$visitor->setLocale(current(explode(',', current(explode(';', $_SERVER['HTTP_ACCEPT_LANGUAGE'])))));

// Assemble Session information
$session = new GoogleAnalytics\Session();

// Assemble Event information
$event = new GoogleAnalytics\Event();
$event->setCategory('permission');
$event->setAction('read_permission');
$event->setLabel('allowed');

// Assemble Custom var
$customVariable = new GoogleAnalytics\CustomVariable();
$customVariable->setIndex(1);
$customVariable->setName('userstatus');
$customVariable->setValue($userstatus);
$customVariable->setScope(GoogleAnalytics\CustomVariable:: SCOPE_VISITOR);

// Track event
$tracker->addCustomVariable($customVariable);
$tracker->trackEvent($event, $session, $visitor);

// Return image
header("Content-Type: image/gif");
header("Cache-Control: " .
           "private, no-cache, no-cache=Set-Cookie, proxy-revalidate");
header("Pragma: no-cache");
header("Expires: Wed, 17 Sep 1975 21:32:10 GMT");
echo join( array(
chr(0x47), chr(0x49), chr(0x46), chr(0x38), chr(0x39), chr(0x61),
chr(0x01), chr(0x00), chr(0x01), chr(0x00), chr(0x80), chr(0xff),
chr(0x00), chr(0xff), chr(0xff), chr(0xff), chr(0x00), chr(0x00),
chr(0x00), chr(0x2c), chr(0x00), chr(0x00), chr(0x00), chr(0x00),
chr(0x01), chr(0x00), chr(0x01), chr(0x00), chr(0x00), chr(0x02),
chr(0x02), chr(0x44), chr(0x01), chr(0x00), chr(0x3b)
));