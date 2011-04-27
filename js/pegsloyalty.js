var dayarray=new Array("Sun","Mon","Tue","Wed","Thu","Fri","Sat")
var montharray=new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec")
// table data mouse over effect //
var prevbgcolor,prevfontcolor;
var overbgcolor='#ADD2F1';  //#ADD2F1
var overfontcolor='#2D00B0'; //#2D00B0
//var overbgcolor='Black';  //#ADD2F1
//var overfontcolor='Yellow'; //#2D00B0
/////////////////////////////////
function getthedate(){
	var mydate=new Date(document.getElementById("datetimenow").value)
	var msRightNow = mydate.getTime()
	var finaltime=msRightNow+1000
	var mydatefinal=new Date(finaltime)
//	alert(mydate + ' ' + mydate1);
	var year=mydate.getYear()
	if (year < 1000)
	year+=1900
	var day=mydate.getDay()
	var month=mydate.getMonth()
	var daym=mydate.getDate()
	if (daym<10)
		daym="0"+daym
	var hours=mydate.getHours()
	var minutes=mydate.getMinutes()
	var seconds=mydate.getSeconds()
	var dn="AM"
	if (hours>=12)
		dn="PM"
	if (hours>12)
	{
		hours=hours-12
	}
	if (hours==0)
		hours=12
	if (minutes<=9)
		minutes="0"+minutes
	if (seconds<=9)
		seconds="0"+seconds
	//change font size here
	var cdate="<font color='green' face='Verdana'><b>"+dayarray[day]+", "+montharray[month]+" "+daym+", "+year+" "+hours+":"+minutes+":"+seconds+" "+dn
	+"</b></font>"
	if (document.all)
	{
		document.all.clock.innerHTML=cdate
		document.all.datetimenow.value=mydatefinal
	}
	else if (document.getElementById)
	{
		document.getElementById("clock").innerHTML=cdate
		document.getElementById("datetimenow").value=mydatefinal
	}
	else
		document.write(cdate)
}
if (!document.all&&!document.getElementById)
	getthedate()
function goforit()
{
	clearInterval(interval1);
	if (document.all||document.getElementById)
		var interval1 = setInterval("getthedate()",1000)
}

function checkEnter(myfield, e)
{
	var key;
	var keychar;
	
	if (window.event)
	   	key = window.event.keyCode;
	else if (e)
	   	key = e.which;
	else
	   	return true;
	keychar = String.fromCharCode(key);
	
	// control keys
	if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
	   	return true;
	
	// numbers
	else if ((("0123456789").indexOf(keychar) > -1))
	   	return true;
	else
	   	return false;
}	

function initialonly(myfield, e)
{
	var key;
	var keychar;
	
	if (window.event)
	   	key = window.event.keyCode;
	else if (e)
	   	key = e.which;
	else
	   	return true;
	keychar = String.fromCharCode(key);
	
	// control keys
	if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
	   	return true;
	
	// numbers
	else if ((("ABCDEFGHIJKLMNOPQRSTUVWXYZ123").indexOf(keychar) > -1))
	   	return true;
	else
	   	return false;
}	

function amountonly(myfield, e)
{
	var key;
	var keychar;
	
	if (window.event)
	   	key = window.event.keyCode;
	else if (e)
	   	key = e.which;
	else
	   	return true;
	keychar = String.fromCharCode(key);
	
	// control keys
	if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
	   	return true;
	
	// numbers
	else if ((("0123456789.").indexOf(keychar) > -1))
	   	return true;
	else
	   	return false;
}	
function amountfullonly(myfield, e)
{
	var key;
	var keychar;
	
	if (window.event)
	   	key = window.event.keyCode;
	else if (e)
	   	key = e.which;
	else
	   	return true;
	keychar = String.fromCharCode(key);
	
	// control keys
	if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
	   	return true;
	
	// numbers
	else if ((("0123456789.,").indexOf(keychar) > -1))
	   	return true;
	else
	   	return false;
}	

function timeonly(myfield, e)
{
	var key;
	var keychar;
	
	if (window.event)
	   	key = window.event.keyCode;
	else if (e)
	   	key = e.which;
	else
	   	return true;
	keychar = String.fromCharCode(key);
	
	// control keys
	if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
	   	return true;
	
	// numbers
	else if ((("0123456789:.").indexOf(keychar) > -1))
	   	return true;
	else
	   	return false;
}	

function dateonly(myfield, e)
{
	var key;
	var keychar;
	
	if (window.event)
	   	key = window.event.keyCode;
	else if (e)
	   	key = e.which;
	else
	   	return true;
	keychar = String.fromCharCode(key);
	
	// control keys
	if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
	   	return true;
	
	// numbers
	else if ((("0123456789/").indexOf(keychar) > -1))
	   	return true;
	else
	   	return false;
}	

//function chkdate(which,foc)
//{
//	now = new Date;
//	getyr=now.getFullYear();
//	locs = which.value.split('/');
//	chkdelete='';
//	mm=locs[0];
//	dd=locs[1];
//	yy=locs[2];
//	if(locs.length < 2)
//	{
//		chkdelete='yes';
//	}
//	else
//	{
//		if(dd=='')
//			chkdelete='yes';
//		if(mm.length==1)
//			mm='0' + mm;
//		else if(mm.length==2)
//			if(mm > 12 || mm < 01)
//				chkdelete='yes';
//		else if(mm.length > 2)
//			chkdelete='yes';
//
//		if(yy!=null || yy=='')
//		{
////			alert(yy);
//			if(yy.length == 0)
//				yy=getyr;
//			else if(yy.length == 1)
//				yy='200' + yy;
//			else if(yy.length == 2)
//				yy='20' + yy;
//			else if(yy.length == 3)
//				chkdelete='yes';
//			else if(yy.length > 4)
//				chkdelete='yes';
//			if(yy > getyr+1 || yy < (getyr-1))
//				chkdelete='yes';
//		}
//		else
//		{
//			yy=getyr;
//		}
//
//		if(dd.length==1)
//			dd='0' + dd;
//		else if(dd.length==2)
//		{
//			if(mm==02)
//			{
//				if(yy==2008 || yy==2012 || yy==2016 || yy==2020)
//				{
//					if(dd > 29 || dd < 01)
//						chkdelete='yes';
//				}
//				else
//				{
//					if(dd > 28 || dd < 01)
//						chkdelete='yes';
//				}
//			}
//			else if(mm == 04 || mm == 06 || mm == 09 || mm == 11)
//			{
//				if(dd > 30 || dd < 01)
//					chkdelete='yes';
//				//alert(dd);
//			}
//			else if(mm == 01 || mm == 03 || mm == 05 || mm == 07 || mm == 08 || mm == 10 || mm == 12)
//			{
//				if(dd > 31 || dd < 01)
//					chkdelete='yes';
//				//alert(dd);
//			}
//
//		}
//		else if(dd.length > 2)
//			chkdelete='yes';
//		//alert(chkdelete);
//	}
////	alert(foc);
//	if(chkdelete=='yes')
//	{
//		which.value='';
//		if(foc=='')
//			which.focus();
//		else
//		{
//			if(eval(foc+'.disabled'))
//			{
//			}
//			else
//				eval(foc+'.focus();');
//		}
//	}
//	else
//	{
//		var newvalue=mm + '/' + dd + '/' + yy;
//		var oldvalue=which.value;
//		which.value=newvalue;
//		if(oldvalue!=newvalue)
//		{
//			if(foc=='')
//				event.keyCode=9;
//			else
//				eval(foc+'.focus();');
//		}
//
//		//leaddate=delivery.lead.value.substring(0,10);
//		//checkDate(leaddate,which.value);
//	}
//}


//function chktime(which,foc)
//{
//	locs = which.value.split(':');
//	if(locs.length < 2)
//		locs = which.value.split('.');
//
//	var chkdelete='';
//	hh=locs[0];
//	mm=locs[1];
//	if(locs.length < 2)
//	{
//		chkdelete='yes';
//	}
//	else
//	{
//		if(mm=='')
//			chkdelete='yes';
//		if(hh.length==1)
//			hh='0' + hh;
//		else if(hh.length==2)
//			if(hh > 23)
//				chkdelete='yes';
//		else if(hh.length > 2)
//			chkdelete='yes';
//
//		if(mm.length==1)
//			mm='0' + mm;
//		else if(mm.length==2)
//			if(mm > 59)
//				chkdelete='yes';
//		else if(mm.length > 2)
//			chkdelete='yes';
//	}
//	finaltime=hh + ':' + mm;
//	if(finaltime.length!=5)
//		chkdelete='yes';
//
//	if(chkdelete=='yes')
//	{
//		which.value='';
//		if(foc=='')
//			which.focus();
//		else
//			eval(foc+'.focus();');
//	}
//	else
//	{
//		which.value=finaltime;
//		if(foc=='')
//			event.keyCode=9;
//		else
//			eval(foc+'.focus();');
//	}
////	alert(chkdelete);
//}

function alphanumericonly(myfield, e, dec)
{
	var key;
	var keychar;

	if (window.event)
   		key = window.event.keyCode;
	else if (e)
   		key = e.which;
	else
   		return true;
	keychar = String.fromCharCode(key);

	// control keys
	if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
   		return true;

	// numbers
	else if ((("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789- ").indexOf(keychar) > -1))
   		return true;

	else
   		return false;
}


function openWindow(url, name, width, height) 
{
	posX = (window.screen.width / 2) - (width / 2);
	posY = (window.screen.height / 2) - (height / 2);

	// posX = 0;
	// posY = 0;
	if(width==0 && height==0)
	{
		winModalWindow = window.open(url, name, 'menubar=no,status=yes,scrollbars=yes,resizable=yes,hotkeys=no,channelmode=yes');
	}
	else
	{
		winModalWindow = window.open(url, name, 'menubar=no,scrollbars=yes,resizable=yes,hotkeys=no,width=' +
			width + ',height=' + height + ',screenX=' + posX + 
			',screenY=' + posY);
	}
	winModalWindow.focus();
//	alert('1');
}

//for modal window with effect (fadein-fadeout)
//for modal window with effect (fadein-fadeout)
//for modal window with effect (fadein-fadeout)

var overlayOpacity = 0.8;	// controls transparency of shadow overlay
var startOpacity = 0;
var finishOpacity = overlayOpacity;

function changeopacityopen()
{
	document.getElementById('modalmask').style.display='block';
	document.getElementById('modalwindow').style.display='block';
	startOpacity=startOpacity+0.05;
	if(startOpacity<=0.8)
	{
		document.getElementById('modalmask').style.opacity=startOpacity;
		setTimeout ('changeopacityopen()', 10 );
	}
	else
	{
		startOpacity = 0;
	}
}
function changeopacityclose()
{
	finishOpacity=finishOpacity-0.05;
	if(finishOpacity>=0)
	{
		document.getElementById('modalmask').style.opacity=finishOpacity;
		setTimeout ('changeopacityclose()', 5 );
	}
	else
	{
		document.getElementById('modalmask').style.display='none';
		document.getElementById('modalwindow').style.display='none';
		finishOpacity = overlayOpacity;
	}
}






function smallcapsOnly(evt)
{
    var charCode = (evt.which) ? evt.which : window.event.keyCode;

    if (charCode <= 13)
    {
        return true;
    }
    else if ((evt.keycode > 32 && evt.keycode < 48) || (evt.keycode > 57 && evt.keycode < 65) ||
       (evt.keycode > 90 && evt.keycode < 97))
    {
        evt.returnValue = false;
        alert('Special characters not allowed.');
    }
    else
    {
        var keyChar = String.fromCharCode(charCode);
        var re = /[a-z0-9]/
        return re.test(keyChar);
    }
}


function alphaNumericOnly(evt) 
{ 
    var charCode = (evt.which) ? evt.which : window.event.keyCode; 
 
    if (charCode <= 13) 
    { 
        return true; 
    }
    else if ((evt.keycode > 32 && evt.keycode < 48) || (evt.keycode > 57 && evt.keycode < 65) ||
       (evt.keycode > 90 && evt.keycode < 97))
    {
        evt.returnValue = false;
        alert('Special characters not allowed.');
    } 
    else 
    { 
        var keyChar = String.fromCharCode(charCode); 
        var re = /[a-zA-Z0-9 ]/
        return re.test(keyChar); 
    } 
}

function numericOnly(evt) 
{ 
    var charCode = (evt.which) ? evt.which : window.event.keyCode; 
    if (charCode <= 13) 
    { 
        return true; 
    }
    else if (evt.keyCode < 48 && evt.keyCode > 57)
    {
        evt.returnValue = false;
    } 
    else 
    { 
        var keyChar = String.fromCharCode(charCode); 
        var re = /[0-9]/ 
        return re.test(keyChar); 
    } 
}

function validEmailChar(evt) 
{ 
    var charCode = (evt.which) ? evt.which : window.event.keyCode; 
 
    if (charCode <= 13) 
    { 
        return true; 
    }
    else if ((evt.keycode > 32 && evt.keycode < 46) || (evt.keycode == 47) || (evt.keycode > 57 && evt.keycode < 64) ||
       (evt.keycode > 90 && evt.keycode < 95) || (evt.keycode == 96) )
    {
        evt.returnValue = false;
        alert('Special characters not allowed.');
    } 
    else 
    { 
        var keyChar = String.fromCharCode(charCode); 
        var re = /[a-zA-Z0-9.@_]/ 
        return re.test(keyChar); 
    } 
}

function SearchEnter (myfield, e)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;

	if (keycode == 13)
	   {
		   document.getElementById('btnGo').click();
		   return false;
	   }
	else
	   return true;
}