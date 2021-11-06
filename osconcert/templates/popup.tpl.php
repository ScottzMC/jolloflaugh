<!DOCTYPE html>
<?php 

// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<?php require(DIR_WS_INCLUDES . 'meta_tags.php'); ?>
<title><?php echo META_TAG_TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<meta name="description" content="<?php echo META_TAG_DESCRIPTION; ?>" />
<meta name="keywords" content="<?php echo META_TAG_KEYWORDS; ?>" />
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php if ($javascript) {require(DIR_WS_JAVASCRIPT . $javascript);} ?>
</head>
<body <?php echo $body_attributes; ?>>
<?php require(DIR_WS_CONTENT . $content . '.tpl.php'); ?>


