<?php // Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();

define('GV_FAQ','Geschenk-Gutschein FAQ');
define('HEADING_TITLE', 'Geschenk-Gutschein senden');
define('NAVBAR_TITLE', 'Geschenk-Gutschein senden');
define('EMAIL_SUBJECT', 'Anfrage von ' . STORE_NAME);
define('HEADING_TEXT','<br>Bitte geben Sie unten die Details der Geschenk-Gutschein, die Sie versenden m&ouml;chten. Weitere Informationen entnehmen Sie bitte unserem <a href="' . tep_href_link(FILENAME_GV_FAQ,'','NONSSL').'">'.GV_FAQ.'.</a><br>');
define('ENTRY_NAME', 'Name des Empf&auml;ngers:');
define('ENTRY_EMAIL', 'Email Adresse des Empf&auml;ngers:');
define('ENTRY_MESSAGE', 'Nachricht an Empf&auml;nger:');
define('ENTRY_AMOUNT', 'Anzahl der Gutscheine:');
define('ERROR_ENTRY_AMOUNT_CHECK', '&nbsp;&nbsp;<span class="errorText">Ung&uuml;ltige Anzahl</span>');
define('ERROR_ENTRY_EMAIL_ADDRESS_CHECK', '&nbsp;&nbsp;<span class="errorText">Ung&uuml;ltige Email Adresse</span>');
define('MAIN_MESSAGE', 'Sie haben beschlossen, einen Gutschein im Wert von %s to %s zu posten, wessen Email Adresse %s ist<br><br>Der Begleittext zum E-Mail wird so sein<br><br>Liebe/r %s<br><br>
                        Ihnen wurde ein Gutschein im Wert  %s gesendet von %s');

define('PERSONAL_MESSAGE', '%s says');
define('TEXT_SUCCESS', 'Herzlichen Gl&uuml;ckwunsch, Ihr Gutschein wurde erfolgreich gesendet');


define('EMAIL_SEPARATOR', '----------------------------------------------------------------------------------------');
define('EMAIL_GV_TEXT_HEADER', 'Gl&uuml;ckwunsch, Sie haben einen Gutschein im Wert von %s erhalten');
define('EMAIL_GV_TEXT_SUBJECT', 'Ein Geschenk von %s');
define('EMAIL_GV_FROM', 'Dieser Gutschein wurde Ihnen geschickt von %s');
define('EMAIL_GV_MESSAGE', 'Mit einer Nachricht: ');
define('EMAIL_GV_SEND_TO', 'Hi, %s');
define('EMAIL_GV_REDEEM', 'Um diesen Gutschein einzul&ouml;sen, klicken Sie bitte auf den untenstehenden Link. Bitte notieren Sie sich den Code:%s. Falls sie Probleme haben.');
define('EMAIL_GV_LINK', 'Zum Einl&ouml;sen bitte klicken ');
define('EMAIL_GV_VISIT', ' oder besuchen ');
define('EMAIL_GV_ENTER', ' und Code eingeben ');
define('EMAIL_GV_FIXED_FOOTER', 'Wenn sie Probleme mit dem Einl&ouml;sen haben klicken sie auf den atomatisierten Link oben, ' . "\n" . 
                                'Sie k&ouml;nnen den Gutschein Code auch w&auml;hrend des Checkouts eingeben.' . "\n\n");
define('EMAIL_GV_SHOP_FOOTER', '');
?>