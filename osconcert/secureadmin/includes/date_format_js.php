<?php 
	// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
?>
<script language="javascript" type="text/javascript">
 	function doRound(x, places) {
	  	return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
	} 
	
	function getTaxRate(parameterVal) {
	  if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
		return tax_rates[parameterVal];
	  } else {
		return 0;
	  }
	}
 
	function updateGross(grossValue,taxRate,total) {
	  var qty="";
	   if (taxRate > 0) {
		grossValue = grossValue * ((taxRate / 100) + 1);
	  }
	ret_value=doRound(grossValue, 4);
	if(total){ 
		ret_value=dollarAmount(qty*ret_value);
	}
	return ret_value;
	}
	function dollarAmount(Num) {
		var sym_left="<?php echo $currencies->currencies[DEFAULT_CURRENCY]['symbol_left'];?>";
		var sym_right="<?php echo $currencies->currencies[DEFAULT_CURRENCY]['symbol_right'];?>";
		
		if (typeof(Num)=="number") {
			Num=doRound(Num,2);
			Num=""+Num;
		} else {
			Num=""+doRound(parseFloat(Num),2);
		}
		
		dec = Num.indexOf(".");
		end = ((dec > -1) ? "" + Num.substring(dec,Num.length) : ".00");
		Num = "" + parseInt(Num);
	
		var temp1 = "";
		var temp2 = "";
		
		if (end.length == 2) end += "0";
		if (end.length == 1) end += "00";
		if (end == "") end += ".00";
	
		var count = 0;
		for (var k = Num.length-1; k >= 0; k--) {
		var oneChar = Num.charAt(k);
		if (count == 3 && oneChar!="-") {
		temp1 += ",";
		temp1 += oneChar;
		count = 1;
		continue;
		}
		else {
		temp1 += oneChar;
		count ++;
		   }
		}
		for (var k = temp1.length-1; k >= 0; k--) {
		var oneChar = temp1.charAt(k);
		temp2 += oneChar;
		}
		temp2 = sym_left + temp2 + end + sym_right;
		return temp2;
	  } 		
				function getDatePart(source,type){
					var result="";
					if (source!=""){
						splt=source.split(" ");
						if (type=="D")
							result=splt[0];
						else
							result=splt[1];
					}
					return result;
				}
				function convert_to_sdate(sdate){
					format="<?php echo EVENTS_DATE_FORMAT;?>";
					splt=sdate.split("-");
					switch(format){
						case "d-m-Y":
							rdate=splt[2]+"-"+splt[1]+"-"+splt[0];
							break;
						case "m-d-Y":
							rdate=splt[1]+"-"+splt[2]+"-"+splt[0];
							break;
						case "Y-m-d":
							rdate=sdate;
							break;
					}
					return rdate;
				}
				
				function convert_to_stime(stime){
					splt=stime.split(":");
					hour=parseInt(splt[0],10);
					minutes=parseInt(splt[1],10);
					if (hour>12 || (hour==12 && minutes>0)){
						if (hour>12) hour=hour-12;
						rtime=" PM";
					}else  rtime=" AM";					
					if (hour<10) hour="0"+hour;
					if (minutes<10) minutes="0"+minutes;
					rtime=((hour==00)?12:hour)+":"+minutes+rtime;
					return rtime;
				}
			
			
			// REQUIRES: isDate()
	function dateAdd(p_Interval, p_Number, p_Date){	
				if(isNaN(p_Number)){return false;}
				//convert date elements
				if (book_type=="H"){
					splt=p_Date.split(":");
					m=parseInt(splt[1],10);
					if (tempCheck==0) {
					  m--;
					  tempCheck=1;					  
					}
						var dt=new Date(1976,1,1,parseInt(splt[0],10),m);
				} else {
					splt=p_Date.split("-");
					var dt=new Date(parseInt(splt[0],10),parseInt(splt[1],10)-1,parseInt(splt[2],10));
				}
								
				p_Number = new Number(p_Number);
				switch(p_Interval.toLowerCase()){
					case "yyyy": {// year
						dt.setFullYear(dt.getFullYear() + p_Number);
						break;
					}
					case "q": {		// quarter
						dt.setMonth(dt.getMonth() + (p_Number*3));
						break;
					}
					case "m": {		// month
						dt.setMonth(dt.getMonth() + p_Number);
						break;
					}
					case "y":		// day of year
					case "d":		// day
					case "w": {		// weekday	
						dt.setDate(dt.getDate() + p_Number);
						break;
					}
					case "ww": {	// week of year
						dt.setDate(dt.getDate() + (p_Number*7));
						break;
					}
					case "h": {		// hour
						dt.setHours(dt.getHours() + p_Number);
						break;
					}
					case "n": {		// minute
						dt.setMinutes(dt.getMinutes() + p_Number);
						break;
					}
					case "s": {		// second
						dt.setSeconds(dt.getSeconds() + p_Number);
						break;
					}
					case "ms": {		// second
						dt.setMilliseconds(dt.getMilliseconds() + p_Number);
						break;
					}
					default: {
						return "invalid interval: '" + p_Interval + "'";
					}
				}
				
				if (book_type=="H") {
					hour=dt.getHours();
					minute=dt.getMinutes();
					if (hour<10) hour="0"+hour;
					if (minute<10) minute="0"+minute;
					return hour+":"+minute;
				} else {
					day=dt.getDate();
					month=dt.getMonth()+1;
					if (day<10) day="0"+day;
					if (month<10) month="0"+month;
					return dt.getFullYear()+"-"+month+"-"+day;
			}
	}

	function get_diff_minutes(stime,etime){
		splt1=stime.split(":");
		splt2=etime.split(":");
		smin=parseInt(splt1[0],10)*60+parseInt(splt1[1],10);
		emin=parseInt(splt2[0],10)*60+parseInt(splt2[1],10);
		return emin-smin;
	}
	
	function price(){
		var frm=document.move_booking_confirm;
		var service_costs="<?php echo $costs;?>";
		var old_order_price="<?php echo $product_price;?>";
		if(frm.resource){
			resource_ids=frm.resource.options[frm.resource.selectedIndex].value;
			resource_ids=resource_ids.split("-");
			var resource_id=resource_ids[0];
			service_costs=resource_ids[1];
			resource_tax=resource_ids[2];
		}
		if(book_type=='H'){
			start_times=frm.start_date.value+' '+frm.start_time.value;
		}else {
			start_times=frm.start_times.value;
			start_tm=start_times.split(" ");
			start_times=start_tm[0]+" "+start_tm[2];
		}
		end_times=frm.end_times.value;
		var minute=tep_time_diff(start_times,end_times);
		var hours=Math.round((minute)/60);
		book_time=hours/time_length;
		if(book_type=='D'){
			days=(hours/24);
			book_time=days/time_length;
		}
		var qty=1;
		if(frm.txt_quantity){
			qty=frm.txt_quantity.value;	
		}
		frm.resource_id.value=resource_id;
		frm.qty.value=qty;
		var option_price="";
		var price=0;
		var tot="";
		if(service_costs){	
		for(i=0;i<option_array.length;i++){
  			  attrb_splt=option_array[i].split("#");
			  var at_id="id["+parseInt(attrb_splt[0])+"]";
			  opt_id=document.getElementById(at_id).value;
			  pre_id="";
			  if(pre_id!=attrb_splt[0]){
			  	if(opt_id==attrb_splt[2]){
 					price=price+parseInt(attrb_splt[4]);	
				}	
				pre_id=attrb_splt[0];	
			 }
		}
		 var tot=((qty*book_time)*service_costs)+price;
		 document.getElementById("tx_total").innerHTML="<?php echo TEXT_TOTAL;?> : " + dollarAmount(tot);
		 if(old_order_price<tot){
		 	var balance=Math.round(tot-old_order_price);
		 	document.getElementById("txt_balance_refund").innerHTML="<?php echo TEXT_BALANCE?> : "+dollarAmount(balance);
		 }else {
		 	var refund=Math.round(old_order_price-tot);
		 	document.getElementById("txt_balance_refund").innerHTML="<?PHP echo TEXT_REFUND;?> : "+dollarAmount(refund);
		 }
	  }
	}
	function date_format(sourceDate,srcFormat,resultFormat,bolConvertObject){
			var dt;
			if (srcFormat=="")	srcFormat="<?php echo EVENTS_DATE_FORMAT;?>";
			if (resultFormat=="")	resultFormat="<?php echo EVENTS_DATE_FORMAT;?>";
			if (typeof(sourceDate)=="string"){
				if (sourceDate=="") return false;
				if (!bolConvertObject && srcFormat!="" && resultFormat==srcFormat) return sourceDate;
				var splt=sourceDate.split("-");
				if (splt.length!=3) return false;
				switch(srcFormat){
					case "d-m-Y":
						sourceDate=new Date(parseInt(splt[2],10),parseInt(splt[1],10)-1,parseInt(splt[0],10));
						break;
					case "m-d-Y":
						sourceDate=new Date(parseInt(splt[2],10),parseInt(splt[0],10)-1,parseInt(splt[1],10));
						break;

					default:
						sourceDate=new Date(parseInt(splt[0],10),parseInt(splt[1],10)-1,parseInt(splt[2],10));
				}
			}
			
			if (!sourceDate) return false;
			if (bolConvertObject) return sourceDate;
			var day=sourceDate.getDate();
			var month=sourceDate.getMonth()+1;
			if (day<10) day="0"+day;
			var year=sourceDate.getFullYear();
			if (month<10) month="0"+month;
			switch(resultFormat){
				case "d-m-Y":
					var textdate=day+"-"+month+"-"+year;
					break;
				case "m-d-Y":
					var textdate=month+"-"+day+"-"+year;
					break;
				default:
					var textdate=year+"-"+month+"-"+day;
					break;
			}
			return textdate;
		}
		function get_diff_minutes(stime,etime){
					splt1=stime.split(":");
					splt2=etime.split(":");
					smin=parseInt(splt1[0],10)*60+parseInt(splt1[1],10);
					emin=parseInt(splt2[0],10)*60+parseInt(splt2[1],10); 
					return emin-smin;
				}				

				function add_options(textArr,valueArr,optionElement){
					// clear the previous details
					while(optionElement.options.length>0){
						optionElement.remove(optionElement.options.length - 1);
					}
					//create the new details
					for (icnt=0;icnt<textArr.length;icnt++){
						  var option_obj = document.createElement('option');
						  if(option_obj){						  	
						  	option_obj.text = textArr[icnt];
						  	option_obj.value = valueArr[icnt];
						  	try {
								optionElement.add(option_obj, null); // standards compliant; doesn't work in IE
	 					    }
						   catch(ex) {
							optionElement.add(option_obj); // IE only
						   }
						}
					}
				}
</script>