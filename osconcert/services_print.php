<?php
/*
    twzFileWatch :: example script
    
    http://tweezy.net.au/filewatch.html
*/
// Set flag that this is a parent file
	define( '_FEXEC', 1 );
require('includes/application_top.php');
date_default_timezone_set('Asia/Ho_Chi_Minh');

$filename = "services_print.php";
//let's not show the modification date
$new_date = strtotime("11 December 2012"); // set the required date timestamp here
    touch($filename,$new_date);

# 1. Set variables to suit your requirements..
# --------------------------------------------
$SiteName       = STORE_OWNER.' '.STORE_NAME;
$CheckFolder    = './';
$RecurseLevel   = 12;
$EmailFrom      = 'webmaster@osconcert.com';
$EmailTo        = STORE_OWNER_EMAIL_ADDRESS;


# 2. The class file must be attached..
# ------------------------------------
require 'includes/classes/twzFileWatch.class.php';


# 3. Instantiate the class..
# --------------------------
$fw = new twzFilewatch($SiteName, $CheckFolder, $RecurseLevel, $EmailTo);


# 4. Set the required options..
# -----------------------------

# set the location of the save-file (location must be writable)
$fw->saveFile(LOG_PATH.'/logs/twzFW.txt');

# for testing in a browser, set $testing=true 
# so the result will be echoed and no email will be sent.
$testing=true;
if($testing)
    {
    $fw->doSendEmail(false);
    $fw->reportAlways(true);
	$fw->reportBase(false);
    $fw->minInterval('5 seconds');
	$fw->excludeFolders( array('./images/tickets') );
	//$fw->excludeFolders( array('./DEMOadmin/') );
	$fw->excludeFiles( array('twzFW.txt', 'error_log', 'test1.txt', 'mail.txt', 'pseudo_cron_timestamp.txt') );
	$fw->SuppressNewList(true);
	$fw->useChecksums(true);
    }


# 5. Do it!..
# -------------------------
$fw->checkFiles();


?>