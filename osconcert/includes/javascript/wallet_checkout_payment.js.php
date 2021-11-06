<script><!--
var selected;
var submitter = null;
var previousPaymentSelected='';
function submitFunction() {
   submitter = 1;
  }
function selectRowEffect(object, buttonSelect ,paymentSelected) {
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
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
function rowOverEffect(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}
function rowOutEffect(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}

//--></script>
<?php echo $payment_modules->javascript_validation(); ?>