<?php 
 	/*
		  
	
	osConcert eCommerce
	http://www.openfreeway.org
	Copyright (c) 2007 ZacWare
	
	Released under the GNU General Public License
	*/
	
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
###############################################################
# PlaceHolders Only:  NOT FOR TRANSLATING!!
###############################################################
//invoice
define("TEXT_INV_DEAR","Text_Dear");
define("TEXT_INV_PRODUCTS","Text_Tickets");
define("TEXT_INV_DD","Text_Delivery_Details");
define("TEXT_INV_PD","Text_Payment_Details");
define("TEXT_INV_THANKS","Text_Thanks");
define("TEXT_INV_THANKS_PURCHASE","Text_Thanks_Purchase");
define("TEXT_INV_THANKS_PURCHASE_SENT","Text_Thanks_Purchase_Sent");//for PRS
define("TEXT_INV_WITH_THANKS","Text_With_Thanks");
define("TEXT_INV_ADDRESS","Text_Address");
define("TEXT_INV_EMAIL","Text_Email");
define("TEXT_INV_TELEPHONE","Text_Telephone");
define("TEXT_INV_PM","Text_Payment_Method");
define("TEXT_INV_ON","Text_Order_Number");
//receipt

//eof
define("TEXT_FN","First_Name");
define("TEXT_LN","Last_Name");
define("TEXT_DF","Date_of_Birth");
define("TEXT_EM","E-mail_Address");
define("TEXT_TN","Telephone_Number");
define("TEXT_FX","Mobile_Number");
define("TEXT_SA","Street_Address");
define("TEXT_SU","Address_2");
define("TEXT_PC","Post_Code");
define("TEXT_CT","City");
define("TEXT_ST","State/Province");
define("TEXT_CY","Country");
define("TEXT_ON","Order_Number");
define("TEXT_OC","Order_Comments");
define("TEXT_OD","Order_Date");
define("TEXT_BT","Billed_to");
define("TEXT_BA","Billing_Address");
define("TEXT_PY","Payment_Method");
define("TEXT_DD","Direct Deposit");
define("TEXT_SB","Sub-Total");
define("TEXT_TX","Tax_Amount");
define("TEXT_TL","Total");
define("TEXT_IL","Detailed_Invoice_Link");
define("TEXT_RA","Refund_Amount");
define("TEXT_RL","Renewal_Reminder_Link");
define("TEXT_RM","Remainder_Fee_Link");
define("TEXT_SM","Store_Name");
define("TEXT_SN","Store_Owner");
define("TEXT_SE","Store_Email");
define("TEXT_LE","Login_Email");
define("TEXT_LP","Login_Password");
define("TEXT_GR","Email_Greeting");
define("TEXT_AL","Admin_Link");
define("STORE_AD","Store_Address,Phone");
define("PAYMT_DT","Payment_Detail");
define("ORDR_SA","Delivery_Address");
define("ORDR_BA","Billing_Address");
define("TEXT_AU","Username");
define("TEXT_AP","Password");
define("TEXT_FD","Payment_Invoice_Id");
define("TEXT_FB","Payment_Date");
define("TEXT_FI","Payment_Info_Link");
define("TEST_MAIL_FN","admin");
define("TEST_MAIL_LN","yoga");
define("TEST_MAIL_DF","07/03/2000");
define("TEST_MAIL_EM","admin@osconcert.com");
define("TEST_MAIL_SA","Level 20");
define("TEST_MAIL_SU","300 Queen Street");
define("TEST_MAIL_PC","4000");
define("TEST_MAIL_CT","Brisbane");
define("TEST_MAIL_ST","Queensland");
define("TEST_MAIL_CY","Australia");
define("TEST_MAIL_TN","27 45667");
define("TEST_MAIL_ON","1x");
define("TEST_MAIL_OC","This is the testing mail");
define("TEST_MAIL_OD","07/03/2000");
define("TEST_MAIL_BT","admin");
define("TEST_MAIL_BA","Level 20, 300 Queen St");
define("TEST_MAIL_PY","Cheque/MoneyOrder");
define("TEST_MAIL_SB","20");
define("TEST_MAIL_TX","10");
define("TEST_MAIL_TL","22");
define("TEST_MAIL_EF","22");
define("TEST_MAIL_IL","Detailed Invoice Link");
define("TEST_MAIL_SD","07/03/2000");
define("TEST_MAIL_IC","Your comments will appear here");
define("TEST_MAIL_IF","admin@osconcert.com");
define("TEST_MAIL_FORMAT_SS","%s, %s - %s %s, %s");
define("TEST_MAIL_SM","STORE_NAME");
define("TEST_MAIL_SE","STORE_OWNER_EMAIL_ADDRES");
define("TEST_MAIL_LE","test@osconcert.com");
define("TEST_MAIL_LP","testpassword");
define("TEST_MAIL_GR"," . STORE_OWNER . ',");
define("TEST_MAIL_SN","STORE_OWNER");
define("TEST_MAIL_AD","1x");
define("TEST_MAIL_AU","test@osconcert.com");
define("TEST_MAIL_AP","testpassword");
define("TEST_MAIL_FD","1x");
define("TEST_MAIL_FB","strftime(DATE_FORMAT_LONG");
define("TEST_MAIL_FI","");
define("TEXT_EVENT_MAIL_FROM","<b>From</b> : %s (%s)");
define("TEXT_EVENT_MAIL_TO","<b>To</b> : %s (%s)");
define("TEXT_EVENT_MAIL_REPLY_TO","<b>Reply to</b> : %s");
define("TEXT_EVENT_MAIL_SUBJECT","<b>Subject</b> : %s");
define("TEST_STORE_AD","STORE_NAME_ADDRES");
define("TEST_PAYMT_DT"," . STORE_NAME . ' Sent To: ' . STORE_ADDRESS .  ' Your order will not ship until we receive payment");
define("PAYMT_PRODUCT_P","<b>Your tickets will not be released until we receive payment.</b>");
define("PAYMT_PRODUCT_R","Your items have been processed and will be delivered shortly");
define("PAYMT_PRODUCT_C","Your items have been delivered");
define("PAYMT_MIXED_P","Make Payable to: %s Post To: %s. <br><b>Your product will not ship until we receive payment.<br>Your registration will lapse if payment is not received within %s days.</b>");
define("PAYMT_MIXED_R","");
define("PAYMT_MIXED_C","");
define("TEXT_NO","Order_Number");
define("TEXT_OP","Date_Purchased");
define("TEXT_OL","Order_Invoice_Link");
define("TEXT_OM","Order_Comments");
define("TEXT_PO","Products_Ordered");
define("TEXT_OT","Order_Totals");
define("TEXT_PM","Payment_Method");
define("LOG_IP_TEXT","To ensure this transaction is more secure, we have logged your unique computer address: ");
define("LOG_IP","Ip_Address");
define("TEXT_PF","Payment_Footer");
define("ORDR_NO","Order_Number");
define("ORDR_OP","Date_Purchased");
define("ORDR_OL","Order_Invoice_Link");
define("ORDR_OM","Order_Comments");
define("ORDR_PO","Products_Ordered");
define("ORDR_OT","Order_Totals");
define("ORDR_PM","Payment_Method");
define("ORDR_PS","Payment_Status");
define("ORDR_DD","Direct Deposit");
define("ORDR_PF","Payment_Footer");
define("CUST_CF","First_Name");
define("CUST_CL","Last_Name");
define("CUST_AU","Username");
define("CUST_CM","Company");
define("CUST_CT","Street_Address");
define("CUST_CS","Address_2");
define("CUST_CC","City");
define("CUST_CP","Post_Code");
define("CUST_CE","State");
define("CUST_CU","Country");
define("CUST_CO","Telephone");
define("CUST_CA","Email_Address");
define("DELI_NA","Delivery_Name");
define("DELI_DE","Delivery_Email");
define("DELI_CM","Delivery_Company");
define("DELI_CT","Delivery_Street_Address");
define("DELI_CS","Delivery_Address_2");
define("DELI_CC","Delivery_City");
define("DELI_CP","Delivery_Post_Code");
define("DELI_CE","Delivery_State");
define("DELI_CU","Delivery_Country");
define("BILL_NA","Billing_Name");
define("BILL_BE","Billing_Email");
define("BILL_CM","Billing_Company");
define("BILL_CT","Billing_Street_Address");
define("BILL_CS","Billing_Address_2");
define("BILL_CC","Billing_City");
define("BILL_CP","Billing_Post_Code");
define("BILL_CE","Billing_State");
define("BILL_CU","Billing_Country");
define("TEST_ORDR_NO","1x");
define("TEST_ORDR_OP","strftime(DATE_FORMAT_LONG");
define("TEST_ORDR_OL",tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO ,'order_id=1x'));
define("TEST_ORDR_OM","Test Order");
define("TEST_ORDR_OT","Total: 20$");
define("TEST_ORDR_PO","Test Product");
define("TEST_ORDR_PM","Credit Card");

define("TEST_ORDR_PF","Amount Paid");
define("TEST_ORDR_CF","STORE_OWNE");
define("TEST_ORDR_CL","");
define("TEST_ORDR_CM"," STORE_NAM");
define("TEST_ORDR_CT","Level 20, 300 Queen Street");
define("TEST_ORDR_CS","Address_2");
define("TEST_ORDR_CC","Brisbane");
define("TEST_ORDR_CP","Post Code");
define("TEST_ORDR_CE","Queensland");
define("TEST_ORDR_CU","Australia");
define("TEST_ORDR_CO","04523456");
define("TEST_ORDR_CA","STORE_OWNER_EMAIL_ADDRES");
define("TEXT_WAD","Amount_added");
define("TEXT_WCB","Current_balance");
define("TEXT_PT","Payment_Type");

define("TEXT_TN1","Name Text Box");
define("TEXT_TN2","Email Text Box");
define("TEXT_TN3","Comments Text Box");
define("TEXT_BT1","Continue Button");
define("TEXT_BT2","Reset Button");
define("TEXT_SL","Store_Link");
define("TEXT_SP","Store_Logo");

define("TEXT_PDF","Detailed_PDF_Format_Link");
define("TEST_MAIL_PDF","Detailed PDF Format Link");
define("TEXT_US",'User_Name');

//user creation
define("TEXT_YA","Your_Account_Has_Been_Created");
define("TEXT_FH","Text_For_Help");
define("TEXT_NT","Text_Note");
define("TEXT_LOGIN_PL","Text_Login_Email");//ALREADY_DEFINED
define("TEXT_USER","Text_Username");
define("TEXT_PASS","Text_Password");

//OSU 
define("MAIL_TEXT_1",'Message_1');
define("MAIL_TEXT_2",'Message_2');
define("MAIL_TEXT_NS",'Text_New_Status');
define("MAIL_TEXT_TC",'Text_Comments');
define("MAIL_TEXT_ON",'Text_Order_Number');
define("MAIL_TEXT_PD",'Text_Payment_Date');
define("MAIL_TEXT_DP",'Text_Date_Purchased');
define("MAIL_TEXT_AC",'Text_Account');
define("MAIL_TEXT_PW",'Text_Password');
define("MAIL_TEXT_DO",'Date_Ordered');

define("ACCOUNT_TEXT_1",'Account_Message_1');
?>