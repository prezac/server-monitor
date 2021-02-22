<?php
if (isset($_SERVER['HTTPS'])) {$protocol = 'https://';}else{$protocol='http://';} 
require_once "class/class.db.php";
require_once "class/db.inc";
$database = new DB();
$database->changecharset("utf8");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs" dir="ltr">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="description" content="RZPS" />
        <meta name="keywords" content="watchdog" />
        <meta name="author" content="Petr Řezáč" />
        <meta http-equiv="cache-control" content="no-cache,no-store,must-revalidate" />
        <meta http-equiv="pragma" content="no-cache" />
        <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <script src="js/jquery-2.1.4.min.js" type="text/javascript"></script>
        <script src="js/jquery-ui-1.11.4.min.js" type="text/javascript"></script>
	    <script src="js/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
	    <script src="js/moment-with-locales.js" type="text/javascript"></script>
        <link rel="stylesheet" media="all" type="text/css" href="style/ext-jquery-ui.css" />
        <link rel="stylesheet" type="text/css" href="style/style.css" />
        <link rel="stylesheet" type="text/css" href="style/jquery-ui-1.11.4.min.css"/>
