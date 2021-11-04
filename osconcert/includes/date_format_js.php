<script language="JavaScript">
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
	function DateAdd(timeU,byMany,dateObj) {
		var millisecond=1;
		var second=millisecond*1000;
		var minute=second*60;
		var hour=minute*60;
		var day=hour*24;
		var year=day*365;
	
		var newDate;
		var dVal=dateObj.valueOf();
		switch(timeU) {
			case "ms": newDate=new Date(dVal+millisecond*byMany); break;
			case "s": newDate=new Date(dVal+second*byMany); break;
			case "mi": newDate=new Date(dVal+minute*byMany); break;
			case "h": newDate=new Date(dVal+hour*byMany); break;
			case "d": newDate=new Date(dVal+day*byMany); break;
			case "y": newDate=new Date(dVal+year*byMany); break;
		}
		return newDate;
	}		
</script>