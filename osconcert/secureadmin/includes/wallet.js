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

function rowOverEffect(object) {
  if (object.className == 'dataTableRow') object.className = 'dataTableRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'dataTableRowOver') object.className = 'dataTableRow';
}

function selectRowEffect2(object, buttonSelect,paymentSelected) {

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
   if (previousPaymentSelected==paymentSelected) return;
  
  if (previousPaymentSelected!='' && document.getElementById(previousPaymentSelected)){
  	document.getElementById(previousPaymentSelected).style.display="none";
  }
  if (document.getElementById(paymentSelected)){
  	document.getElementById(paymentSelected).style.display="";
  }
  previousPaymentSelected=paymentSelected;
}

function rowOverEffect2(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect2(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
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