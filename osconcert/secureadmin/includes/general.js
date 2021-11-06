var selected;   
var previousPaymentSelected='';
function SetFocus() {
	
  if (document.forms.length > 0) {
    var field = document.forms[0];
    for (i=0; i<field.length; i++) {
      if ( (field.elements[i].type != "image") &&
           (field.elements[i].type != "hidden") &&
           (field.elements[i].type != "reset") &&
           (field.elements[i].type != "submit") ) {

        document.forms[0].elements[i].focus();

        if ( (field.elements[i].type == "text") ||
             (field.elements[i].type == "password") )
          document.forms[0].elements[i].select();

        break;
      }
    }
  }
}

function rowOverEffect2(object) {
	
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect2(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}

function rowOverEffect(object) {
 
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
  else if (object.className == 'dataTableRowOdd') object.className = 'dataTableRowOddOver';
  else if (object.className == 'dataTableRowEven') object.className = 'dataTableRowEvenOver';
  else if (object.className == 'tableMenuItem') object.className = 'tableMenuItemOver';
  else if(object.className == 'subdataTableRowOdd') object.className = 'subdataTableRowOddOver';
  else if (object.className == 'subdataTableRowEven') object.className = 'subdataTableRowEvenOver';
  else  if (object.className == 'dataTableRow') object.className = 'dataTableRowOver';
}

function rowOutEffect(object) {
	if (object.className == 'moduleRowOver') object.className = 'moduleRow';
   else if (object.className == 'dataTableRowOddOver') object.className = 'dataTableRowOdd';
  else if (object.className == 'dataTableRowEvenOver') object.className = 'dataTableRowEven';
  else if (object.className == 'tableMenuItemOver') object.className = 'tableMenuItem';
   else if (object.className == 'subdataTableRowOddOver') object.className = 'subdataTableRowOdd';
  else if (object.className == 'subdataTableRowEvenOver') object.className = 'subdataTableRowEven';
  else if (object.className == 'moduleRowOver') object.className = 'moduleRow';
  else if (object.className == 'dataTableRowOver') object.className = 'dataTableRow';
}


function selectRowEffect2(object, buttonSelect,paymentSelected) {
  var frm=document.edit_order;
  if(document.move_booking_confirm) frm=document.move_booking_confirm;
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultRow');
    } else {
      selected = document.all['defaultRow'];
    }
  }

  if (selected) selected.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected = object;
// one button is not an array
  if (frm.payment[0]) {
    frm.payment[buttonSelect].checked=true;
	payment_value=frm.payment[buttonSelect].value;
	} else {
    frm.payment.checked=true;
  }
   if (previousPaymentSelected==paymentSelected) return;
  
  if (previousPaymentSelected!='' && document.getElementById(previousPaymentSelected)){
  	document.getElementById(previousPaymentSelected).style.display="none";
  }
  if (document.getElementById(paymentSelected)){
  	document.getElementById(paymentSelected).style.display="";
  }
  previousPaymentSelected=paymentSelected;
}

function select_RowEffect2(object, buttonSelect,paymentSelected) { 
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultRow');
    } else {
      selected = document.all['defaultRow'];
    }
  }

  if (selected) selected.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected = object;

// one button is not an array
  if (document.checkout_payment.payment[0]) {
    document.checkout_payment.payment[buttonSelect].checked=true;
	payment_value=document.checkout_payment.payment[buttonSelect].value;
	} else {
    document.checkout_payment.payment.checked=true;
  }
  
 // alert (document.checkout_payment.payment);
   if (previousPaymentSelected==paymentSelected) return;
  if (previousPaymentSelected!='' && document.getElementById(previousPaymentSelected)){
  	document.getElementById(previousPaymentSelected).style.display="none";
  }
  if (document.getElementById(paymentSelected)){
  	document.getElementById(paymentSelected).style.display="";
  }
  previousPaymentSelected=paymentSelected;
}


// wallet upload payment
function selectRowEffect(object, buttonSelect ,paymentSelected) {
	
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
    }
  }

  if (selected) selected.className = 'defaultSelected';
  object.className = 'defaultSelected';
  selected = object;

// one button is not an array
  if (document.checkout_payment.payment[0]) {
    if (document.checkout_payment.payment[buttonSelect]) {document.checkout_payment.payment[buttonSelect].checked=true;
		payment_value=document.checkout_payment.payment[buttonSelect].value;
	}
  } else {
    document.checkout_payment.payment.checked=true;
  }
  if (previousPaymentSelected==paymentSelected) return;
  
  if (previousPaymentSelected!='' && document.getElementById(previousPaymentSelected)){
  	document.getElementById(previousPaymentSelected).style.display="none";
  }
  if (document.getElementById(paymentSelected)){
  	document.getElementById(paymentSelected).style.display="";
  }
  previousPaymentSelected=paymentSelected;
}



function selectRowEffect3(object, buttonSelect) {
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultRow');
    } else {
      selected = document.all['defaultRow'];
    }
  }

  if (selected) selected.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected = object;

// one button is not an array
  if (document.pending_waitlist_payments_confirm.payment[0]) {
    document.pending_waitlist_payments_confirm.payment[buttonSelect].checked=true;
  } else {
    document.pending_waitlist_payments_confirm.payment.checked=true;
  }
}

function rowOverEffect3(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect3(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}

	function isValidDate(dateStr) {

		var datePat = /^(\d{4})(\/|-)(\d{1,2})\2(\d{1,2})$/; // requires 4 digit year
		var matchArray = dateStr.match(datePat); // is the format ok?
		if (matchArray == null) {
		return false;
		}
		month = matchArray[3]; // parse date into variables
		day = matchArray[4];
		year = matchArray[1];
		if (month < 1 || month > 12) { // check month range
		return false;
		}
		if (day < 1 || day > 31) {
		return false;
		}
		if ((month==4 || month==6 || month==9 || month==11) && day==31) {
		return false;
		}
		if (month == 2) { // check for february 29th
		var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
		if (day>29 || (day==29 && !isleap)) {
		return false;
		   }
		}
		return true;
	}
	
	function FormatNumber(num,noPlace,commaDelimiter,Decimal)
	{       
	var sVal='';
	var minus='';
	var pos=0;
	var numWhole;
	var numDecimal;
	var temp="00000000000000000";
	try 
	{

		if (num.lastIndexOf("-") == 0) { minus='-'; }

		pos=num.lastIndexOf(Decimal);
		if (pos>0){
			numWhole=num.substring(0,pos);
			numDecimal=num.substring(pos+1);
			if (numDecimal.length<=noPlace){
				numDecimal=numDecimal+temp.substring(0,noPlace-numDecimal.length);
			} else {
				if (numDecimal.charCodeAt(noPlace)>=53){
					numDecimal=parseInt(numDecimal.substring(0,noPlace))+1;
				} else {
					numDecimal=parseInt(numDecimal.substring(0,noPlace));
				}
			}
			numDecimal=""+numDecimal;
		} else {
			numWhole=num;
			numDecimal=temp.substring(0,noPlace);
		}
		numWhole = FormatClean(numWhole);
		numDecimal = FormatClean(numDecimal);
		
		numWhole = parseInt(numWhole);

		
		var samount = new String(numWhole);
		 
		for (var i = 0; i < Math.floor((samount.length-(1+i))/3); i++)
		{
			samount = samount.substring(0,samount.length-(4*i+3)) + commaDelimiter + samount.substring(samount.length-(4*i+3));
		}
	}
	catch (exception) { alert("Format Number"); }

	return minus + samount+Decimal+numDecimal;
	}
	
	function FormatClean(num)
	{
	var sVal='';
	var nVal = num.length;
	var sChar='';
	
	try
	{
		for(i=0;i<nVal;i++)
		{
			sChar = num.charAt(i);
			nChar = sChar.charCodeAt(0);
			if ((nChar >=48) && (nChar <=57))  { sVal += num.charAt(i);   }
		}
	}
	catch (exception) { alert("Format Clean"); }
	return sVal;
	}
	
	function check_mime_type(control,extArray){
        var file=control.value;
		while (file.indexOf("\\") != -1)
		file = file.slice(file.indexOf("\\") + 1);
		ext = file.slice(file.indexOf(".")).toLowerCase();
		for (var i = 0; i < extArray.length; i++) {
		if (extArray[i] == ext) { return true;}
		}
		return false;
	}
	
 function pwdValidation(type,strng){ 
	switch (type){ 
			case '1':
				if(str_trim(strng)=='')
					return false;
				break;
			case '2':
				 var alphanum = /^[a-zA-Z0-9_]+$/;
				 return alphanum.test(strng);
				 break;
			case '3':
					//var symbol = /^([0-9a-zA-Z]\*\+\-\*)*?/g;
					var symbol = /^([0-9a-zA-Z_\.\+\-\=\*\/\@\#\$\!\`\~\%\^\&\*\(\)\_\|\>\<\,\?\:\;\"\'\{\[\}\]\\])+$/;
					//alert(symbol.test(strng));
					var ans = symbol.test(strng);
					if (ans == true){
					return true;
					}
					else 
					return false;
					// return symbol.test(strng);	
					 
				 break;
		/*	case '4':
					//var symbol = /^([0-9a-zA-Z]\*\+\-\*)*?/g;
					var symbol = /^([0-9a-zA-Z_\.\+\-\*\/])+$/;
					//alert(symbol.test(strng));
					var ans = symbol.test(strng);
					if (ans == true){
					return true;
					}
					else 
					return false;
					// return symbol.test(strng);	
				 break;*/
	}			
	return true;
}
function str_trim(str){  

	if(!str || typeof str != 'string')  
		return '';  
	return str.replace(/^[\s]+/,'').replace(/[\s]+$/,'').replace(/[\s]{2,}/,' ');
} 

   function getKeyCode(e){
    	if (window.event)
		return window.event.keyCode;
		else if (e)
		return e.which;
		else
		return null;
   }
  function keyRestrict(e, allowNeg = false) {
       
		var key='', keychar='';
		key = getKeyCode(e);
       
        if (key == null) return true;
		keychar = String.fromCharCode(key);
		keychar = keychar.toLowerCase();


        if (key == 45 && allowNeg === true) return true;
		
        if(key>47 && key<58 || key==0 || key==8)
			return true;
		else
			return false;
   }    
