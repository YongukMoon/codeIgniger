/*--------------------------------------------------------------------------------
format = '' , 'YYY-MM-DD' 형식으로 날짜 생성
포맷형식 예) 'yyyy-mm-dd' 'mm-dd-yyyy' 'yyyy/mm/dd' 'yyyymmdd' ...
--------------------------------------------------------------------------------*/



// 달력의 스타일 시트 정의
function fnCalStyle(){
	var strCSS = "";
	strCSS += "<style type=text/css>";
	strCSS += ".fc_today {font-family: tahoma; font-size: 11px; text-align: left; color:#000000; padding-left:5px;}";
	strCSS += ".fc_close {font-family: tahoma; cursor:pointer; font-size: 10px; text-align: center; color:#000000;}";
	strCSS += ".CalenderBox {background: #ffffff; border: 5px solid #000000; font-family: tahoma; font-size: 9px; padding:5px;}";
	strCSS += ".fc_pointer {font-family: 돋움;cursor:pointer; font-size: 9pt; text-align: center;}";
	strCSS += ".fc_date {font-family: tahoma; border: 1px solid #F1F1F1;  cursor:pointer; font-size: 9px; text-align: center;}";
	strCSS += ".fc_dateHover, TD.fc_date:hover {font-family: tahoma;cursor:pointer;border: 1px solid #DFDFDF;background:#dfdfdf;font-size: 9px; text-align: center;}";
	strCSS += ".fc_day {font-family: 돋움; font-size: 11px; text-align: center; color:#FFFFFF; background-color:#000000;}";
	strCSS += ".inBox {font-family:tahoma;font-size:12px;color:RoyalBlue;text-align:center;border:1px solid #EFEFEF; width:180px;height20px;}";
	strCSS += ".inSel {font-family:tahoma;font-size:11px;color:#333333;}";
	strCSS += ".WEEK_D {color:#000000; 	font-family:돋움;font-size: 12px; text-align: center;}";
	strCSS += ".WEEK_S {color:Crimson; 	font-family:돋움;font-size: 12px; text-align: center;}";
	strCSS += ".WEEK_F {color:RoyalBlue;font-family:돋움;font-size: 12px; text-align: center;}";
	strCSS += ".fc_head { background: #FFFFFF; color: #000000; font-weight:bold; text-align: left;  font-size: 11px;font-family: tahoma;}";
	strCSS += ".cal_btn {height:20px;width:50px;COLOR:#333333;FONT-FAMILY:돋움;PADDING-LEFT:1px;PADDING-TOP:3px;FONT-SIZE:11px;BACKGROUND-COLOR:#F1F1F1;BORDER:#DFDFDF 1px solid;}";
	strCSS += "</style>";
	document.write(strCSS);
}
	
	//var fc_ie = false;
    //if (document.all) fc_ie = true;
    
   	var calendars 	= Array();
    var fc_months 	= Array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12');
	var strTimes 	= Array('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24');
	var strMinutes 	= Array('00','05','10','15','20','25','30','35','40','45','50','55');
    var fc_openCal;
	var fc_calCount = 0;
	
    //this.date.setDate(d);
    
	/* 달력의 날자를 클릭시 일어나는 이벤트 */
	function getCalendar(fieldId) {
		return calendars[fieldId];
    }
    
	/* 달력 호출시 처음 여기 */
	function displayCalendarFor(obj,fieldId) {
	   	var formElement = fc_getObj(fieldId);		 	/* fieldId 이름으로 오브젝트를 찾음 */
		displayCalendar(formElement);
    }
   
    function displayCalendar(formElement) {
		if (!formElement.id) {							/* 오브젝트의 아이디가 있는지 검사후 없으면 0 을 넣는다 */
			formElement.id = fc_calCount++;
		}
		
		var cal = calendars[formElement.id];			/* calendars[] 배열변수에 오브젝으의 아이디 값을 넣는다. */
		if (typeof(cal) == 'undefined') {				/* cal 값이 정의되지 않은 값이면(처음 달력을 열었을경우) 달력을 셋팅*/ 
			cal = new floobleCalendar();				/* floobleCalendar 정의한 변수를 가져다 쓴다. */
			cal.setElement(formElement);
			calendars[formElement.id] = cal;
		}
	
		if (cal.shown) cal.hide();						/* 달력이 떠있으면 달력을 숨긴다 */
		else cal.show();								/* 달력이 떠있지 않으면 달력을 보여준다 */

    }
	
	/* display3FieldCalendar(fc_getObj('form_name'), fc_getObj('form_name'), fc_getObj('form_name')); */
	/* 3게의 필드를 사용시  [05] - [06] - [2008] */
    function display3FieldCalendar(me, de, ye) {
        if (!me.id) { me.id = fc_calCount++; }
        if (!de.id) { de.id = fc_calCount++; }
        if (!ye.id) { ye.id = fc_calCount++; }
        var id = me.id + '-' + de.id + '-' + ye.id;
        var cal = calendars[id];
        if (typeof(cal) == 'undefined') {
            cal = new floobleCalendar();
            cal.setElements(me, de, ye);
            calendars[id] = cal;
        }
        
		if (cal.shown) cal.hide();						/* 달력이 떠있으면 달력을 숨긴다 */
		else cal.show();								/* 달력이 떠있지 않으면 달력을 보여준다 */
    }

    /* Class Stuff */
    function floobleCalendar() {

        this.setElement 		= fc_setElement;
        this.setElements 		= fc_setElements;
        this.parseDate 			= fc_parseDate;
        this.show 				= fc_show;
        this.hide 				= fc_hide;
        this.moveMonth 			= fc_moveMonth;
        this.setDate 			= fc_setDate;
        this.formatDate 		= fc_formatDate;
        this.setDateFields 		= fc_setDateFields;
        this.parseDateFields 	= fc_parseDateFields;
        this.generateHTML 		= fc_generateHTML;
		this.inputs 			= fc_inputs;
		this.shown 				= false;
    }
    
    function fc_setElement(formElement) {
		this.element 	= formElement;
        this.format 	= this.element.getAttribute("format");
        this.value 		= this.element.value;
        this.id 		= this.element.id;
        this.mode = 1;
		
		if(this.format != "YYYY-MM-DD-HH-II-SS") {
			this.strTable = "S";
			this.strTableSize = "200";
		}else {
			this.strTable = "M";
			this.strTableSize = "400";
		}
    }

	function fc_setElements(monthElement, dayElement, yearElement) {
		this.strTable = "S";
		this.mElement = monthElement;
		this.dElement = dayElement;
		this.yElement = yearElement;
		
		this.id = this.mElement.id + '-' + this.dElement.id + '-' + this.yElement.id;
		this.element = this.mElement;
		if (fc_absoluteOffsetLeft(this.dElement) < fc_absoluteOffsetLeft(this.element)) {
			this.element = this.dElement;
		}
		if (fc_absoluteOffsetLeft(this.yElement) < fc_absoluteOffsetLeft(this.element)) {
			this.element = this.yElement;
		}
		if (fc_absoluteOffsetTop(this.mElement) > fc_absoluteOffsetTop(this.element)) {
			this.element = this.mElement;
		}
		if (fc_absoluteOffsetTop(this.dElement) > fc_absoluteOffsetTop(this.element)) {
			this.element = this.dElement;
		}
		if (fc_absoluteOffsetTop(this.yElement) > fc_absoluteOffsetTop(this.element)) {
			this.element = this.yElement;
		}
		
		this.mode = 2;
	}
    
    function fc_parseDate() {
        if (this.element.value) {
            this.date = new Date();
            var out = '';
            var token = '';
            var lastCh, ch;
            var start = 0;
            lastCh = this.format.substring(0, 1);
            for (i = 0; i < this.format.length; i++) {
                ch = this.format.substring(i, i+1);
                if (ch == lastCh) { 
                    token += ch;
                } else {
                    fc_parseToken(this.date, token, this.element.value, start);
                    start += token.length;
                    token = ch;
                }
                lastCh = ch;
            }
            fc_parseToken(this.date, token, this.element.value, start);
        } else {
            this.date = new Date();
        }
        if ('' + this.date.getMonth() == 'NaN') {
            this.date = new Date();
        }
    }
	
	function fc_show() {
		var str;
		
		if (typeof(fc_openCal) != 'undefined') { fc_openCal.hide(); }
		
		if (this.mode == 1) {
			this.parseDate();
		} else {
			this.parseDateFields();
		}
		
       
	   this.showDate = new Date(this.date.getTime());
        if (typeof(this.div) != 'undefined') {
            this.div.innerHTML = this.generateHTML();
        }
		
        if (typeof(this.div) == 'undefined') {
            this.div = document.createElement('DIV');
            this.div.id = 'CalenderBox';
			this.div.className = 'CalenderBox';
		    this.div.style.position = 'absolute';
            this.div.style.display = 'none';
            this.div.style.zIndex = '999';
			this.div.style.left = fc_absoluteOffsetLeft(this.element) + 'px';
            this.div.style.top = fc_absoluteOffsetTop(this.element) + this.element.offsetHeight + 2 +'px';
			
			
			/* 아이프레임 생성*/
			
			//this.ifr = document.getElemenyById("CalenderBox").appendChild(createElement('iframe'));
			//this.ifr.setAttribute('frameBorder',0);
			//	this.ifr.id = "iFrameBox";
			//this.ifr.innerHTML = this.generateHTML();
			
			
			this.div.innerHTML = this.generateHTML();
			document.body.appendChild(this.div);
			//document.write(this.generateHTML());
			
			//alert(this.div.id);
			
        }
		
        this.div.style.display = 'block';
        this.shown = true;
        fc_openCal = this;
    }
	
	
	function fc_hide() {
        if (this.div != false) {
            this.div.style.display = 'none';
        }
        this.shown = false;
        fc_openCal = undefined;
    }
    
    function fc_moveMonth(amount) {
		this.showDate.setDate(1);
        var m = this.showDate.getMonth();
        var y = fc_getYear(this.showDate);
        if (amount == 1)  {
            if (m == 11)  {
                this.showDate.setMonth(0);
                this.showDate.setYear(y + 1);
            } else {
                this.showDate.setMonth(m + 1);
            }
        } else if (amount == -1)  {
            if (m == 0)  {
                this.showDate.setMonth(11);
                this.showDate.setYear(y - 1);
            } else {
                this.showDate.setMonth(m - 1);
            }
        } else if (amount == 12) {
            this.showDate.setYear(y + 1);
        } else if (amount == -12) {
            this.showDate.setYear(y - 1);
		} else if (amount > 12 || amount < -12) {
			this.showDate.setYear(y + amount/12);
		} else if ((amount > 1 && amount<12) || (amount<-1 && amount>-12)) {
			var n = m+parseInt(amount);
			this.showDate.setMonth(n);
        }
        this.div.innerHTML = this.generateHTML();
    }
	
	/* 달력의 날자를 클릭했을경우 해당 오브젝트에 값을 넣고 달력을 닫는다.*/
	function fc_setDate(d, m, y) {
        this.date.setYear(y);
        this.date.setMonth(m);
        this.date.setDate(d);

		if(this.strTable == "M"){
			var tts = document.getElementById("submittext").value;
			var arraytts = tts.split("-");
		}

		if (this.mode == 1) {
			if(this.strTable == "M") document.getElementById("submittext").value = this.formatDate();
			this.element.value = this.formatDate();
        } else {
			if(this.strTable == "M") document.getElementById("submittext").value = this.formatDate();
			this.setDateFields();
        }

		if(this.strTable == "S") this.hide();
    }
    
	function fc_formatDate() {
        var out = '';
        var token = '';
        var lastCh, ch;
        lastCh = this.format.substring(0, 1);
        for (i = 0; i < this.format.length; i++) {
            ch = this.format.substring(i, i+1);
			if (ch == lastCh) { 
                token += ch;
            } else {
                out += fc_formatToken(this.date, token);
                token = ch;
            }
            lastCh = ch;
        }
		out += fc_formatToken(this.date, token);
        return out;
    }
	
    function fc_setDateFields() {
        fc_setFieldValue(this.mElement, fc_zeroPad(this.date.getMonth() + 1));
        fc_setFieldValue(this.dElement, fc_zeroPad(this.date.getDate()));
        fc_setFieldValue(this.yElement, this.date.getFullYear());
    }
	
	function fc_parseDateFields() {
        this.date = new Date();
        if (this.mElement.value) this.date.setMonth(fc_getFieldValue(this.mElement) - 1);
        if (this.dElement.value) this.date.setDate(fc_getFieldValue(this.dElement));
        if (this.yElement.value) this.date.setFullYear(fc_getFieldValue(this.yElement));
        if ('' + this.date.getMonth() == 'NaN') {
            this.date = new Date();
        }
    }

	function fc_getYearSelect(year) {
		var str = "";
		var date = new Date();
		var fyear = date.getFullYear()+1;
		for(var y=fyear; y>=1900; y--) {
			var val = (y-year)*12;
			var selected = (year==y) ? " selected": "";
			str += "<option value='"+val+"'"+selected+">"+y+"</option>";
		}
		return str;
	}

	function fc_getMonthSelect(month) {
		var str = "";
		for(var m=1; m<=12; m++) {
			var val = m-month;
			var selected = (month==m) ? " selected": "";
			str += "<option value='"+val+"'"+selected+">"+m+"</option>";
		}
		return str;
	}
    
	function fc_generateHTML() {
		var MouseEvent = ' onMouseover="this.className = \'fc_dateHover\';" onMouseout="this.className=\'fc_date\';" ';
		var ToDay = this.showDate.getFullYear() + '.' + (this.showDate.getMonth()+1) + '.'+ this.showDate.getDate();
		 
		 
		html = '\
		<TABLE border="0" width="' + this.strTableSize +'" height="180" valign="top">\
			<TR>\
				<TD width="200">\
				<TABLE border="0" width="' + this.strTableSize + '" height="180" cellpadding="0" cellspacing="0">\
					<TR bgcolor="#FFFFFF">\
						<TD width="200">\
						<TABLE border="0" width="200" height="180" style="padding:10px;">\
							<TR height="20">\
								<TD COLSPAN="7" CLASS="WEEK_D"><select name="year" style="font-size:8pt;" onchange=getCalendar("' + this.id + '").moveMonth(this.value);>' + fc_getYearSelect(fc_getYear(this.showDate)) + '</select>년 <select name="month" style="font-size:8pt;" onchange=getCalendar("' + this.id + '").moveMonth(this.value);>' + fc_getMonthSelect(fc_months[this.showDate.getMonth()]) + '</select> 월 </TD>\
							</TR>\
							<TR height="20">\
								<TD WIDTH="14%" class="WEEK_S">일</TD>\
								<TD WIDTH="14%" CLASS="WEEK_D">월</TD>\
								<TD WIDTH="14%" CLASS="WEEK_D">화</TD>\
								<TD WIDTH="14%" CLASS="WEEK_D">수</TD>\
								<TD WIDTH="14%" CLASS="WEEK_D">목</TD>\
								<TD WIDTH="14%" CLASS="WEEK_D">금</TD>\
								<TD WIDTH="14%" CLASS="WEEK_F">토</TD>\
							</TR>\
							<TR>\
		';

		
		var dow = 0;
        var i, style,strColor;
        var totald = fc_monthLength(this.showDate);
        var intCnt = fc_firstDOW(this.showDate) + 1;

		if(intCnt < 7){
			for (i = 0; i < intCnt; i++) {
				dow++;
				html += '<TD CLASS="fc_date">&nbsp;</TD>';
			}
		}

	   for (i = 1; i <= totald; i++) {
			if (dow == 0) { html += '<TR>'; }

			if(dow == 0){
				if (this.showDate.getMonth() == this.date.getMonth() && this.showDate.getYear() == this.date.getYear() && this.date.getDate() == i) { 
					style = ' style="font-weight: bold;color:#009900;background:#F1F1F1;"';
				} else {
					style = ' style="color:Crimson;"';
				}				
			}else if(dow == 6){
				if (this.showDate.getMonth() == this.date.getMonth() && this.showDate.getYear() == this.date.getYear() && this.date.getDate() == i) { 
					style = ' style="font-weight: bold;color:#009900;background:#F1F1F1;"';
				} else {
					style = ' style="color:RoyalBlue;"';	
				}
			}else{
				
				if (this.showDate.getMonth() == this.date.getMonth() && this.showDate.getYear() == this.date.getYear() && this.date.getDate() == i) { 
					style = ' style="font-weight: bold;color:#009900;background:#F1F1F1;"';
				} else {
					style = '';
				}
			}
			
			html += '<TD CLASS="fc_date" ' + MouseEvent + ' onClick="getCalendar(\'' + this.id + '\').setDate(' + i + ', ' + this.showDate.getMonth() + ', ' + this.showDate.getFullYear() + ');" ' + style + '>' + i + '</TD>';
			
			dow++;

			if (dow == 7) {
                html += '</TR>';
                dow = 0;
            }
        }
		
        if (dow != 0) {
            for (i = dow; i < 7; i++) {
                html += '<TD CLASS="fc_date">&nbsp;</TD>';
            }
        }
		
		html += '\
							</TR>\
						</TABLE>\
						</TD>\
		';
		
		if(this.strTable == "M"){
			
			html += '\
						<TD valign="top" align="center">\
						<TABLE border="0" width="195" height="180" cellpadding="0" cellspacing="0">\
							<TR>\
								<TD width=195 height=40 align="center">\
									<input type="text" name="submittext" id="submittext" class="inBox" value="0000-00-00-00-00-00" readonly>\
								</TD>\
							</TR>\
							<TR>\
								<TD width=195 class="WEEK_D" height="40" align="center">\
									<select name="f_times" id="times" class="inSel" onChange="GetCheangeDate(this);">\
									<option value="시">시</option>\
			';
									for(k = 0; k < strTimes.length; k++){
			html += '					<option value="' + strTimes[k] +'">' + strTimes[k] + '</option>';
									}
			html += '				</select> 시&nbsp;';
			html += '				<select name="f_minutes" id="minutes" class="inSel" onChange="GetCheangeDate(this);">';
			html += '				<option value="분">분</option>';
									for(k = 0; k < strMinutes.length; k++){
			html += '					<option value="' + strMinutes[k] +'">' + strMinutes[k] + '</option>';					
									}
			html += '				</select> 분&nbsp;';
			html += '				<select name="f_second" id="second" class="inSel" onChange="GetCheangeDate(this);">';
			html += '				<option value="초">초</option>';
									for(k = 0; k <= 60; k++){
			html += '					<option value="' + fc_zeroPad(k) +'">' + fc_zeroPad(k) + '</option>';					
									}
			html += '				</select> 초&nbsp;';
			
			html += '\				</TD>\
								</TR>\
								<TR>\
									<TD width=180 height=80 valign=bottom align=right style=padding-right:5px;>\
									<input class=cal_btn type=button value="확인" onClick="getCalendar(\'' + this.id + '\').inputs();">\
									</TD>\
								</TR>\
							</TABLE>\
							<TD>\
						</TR>\
			';
		}
		html += '\
						<TR>\
							<TD class="fc_today" colspan="2">ToDay : '+ ToDay + '</TD>\
						</TR>\
						<TR>\
							<TD height=22 CLASS="fc_close" onClick="getCalendar(\'' + this.id + '\').hide();" COLSPAN="2">CLOSE</TD>\
						</TR>\
					</TABLE>\
					</TD>\
				</TR>\
			</TABLE>\
		';

	   
	   return html;
    }
   
   	function fc_inputs(){
		this.objtext 		= document.getElementById("submittext");
		this.element.value 	= this.objtext.value;
		this.hide();
	}
	

    /* Utils */
	function fc_absoluteOffsetTop(obj) {
		
		var top = obj.offsetTop;
		var parent = obj.offsetParent;
		while (parent != document.body) {
			top += parent.offsetTop;
			parent = parent.offsetParent;
		}
		return top;
		
	}
     
	function fc_absoluteOffsetLeft(obj) {
		var left = obj.offsetLeft;
		var parent = obj.offsetParent;
		while (parent != document.body) {
			left += parent.offsetLeft;
			parent = parent.offsetParent;
		}
		return left;
	}

	
     
	function fc_firstDOW(date) {
		var dow = date.getDay();
		var day = date.getDate();
		if (day % 7 == 0) return dow;
		return (7 + dow - (day % 7)) % 7; 
	}
     
	function fc_getYear(date) {
		var y = date.getYear();
		if (y > 1900) return y;
		return 1900 + y;
	}
     
	function fc_monthLength(date) {
		var month = date.getMonth();
		var totald = 30;
		if (month == 0 || month == 2 || month == 4 || month == 6 || month == 7 || month == 9 || month == 11) totald = 31;
		if (month == 1) {
			var year = date.getYear();
			if (year % 4 == 0 && (year % 400 == 0 || year % 100 != 0))
				 totald = 29;
			else
				totald = 28;
		}
		return totald;
	}
     
	function GetCheangeDate(obj){
		var obj_text 	= document.getElementById("submittext");
		var ArrayText 	= obj_text.value.split("-");
		var strYMD = ArrayText[0] + "-" + ArrayText[1] + "-" + ArrayText[2];		/* 년월일 */

		switch(obj.id){
			case "times"	: obj_text.value = strYMD + "-" + obj.value + "-" + ArrayText[4] + "-" + ArrayText[5];	break;
			case "minutes"	: obj_text.value = strYMD + "-" + ArrayText[3] + "-" + obj.value + "-" + ArrayText[5];	break;
			case "second"	: obj_text.value = strYMD + "-" + ArrayText[3] + "-" + ArrayText[4] + "-" + obj.value;	break;
		}
		
	}
	/* 시간 분 초 를 셋팅한다. */
	function GetDateHIS(Gbn){
		var ret
		var obj_text 		= document.getElementById("submittext");
		var obj_times 		= document.getElementById("f_times");
		var obj_minutes 	= document.getElementById("f_minutes");
		var obj_second 		= document.getElementById("f_second");
		var ArrayText = obj_text.value.split("-");

		switch(Gbn){
			case "H" :	
				if(obj_times.value != "시" || ArrayText[3] != "00") ret = ArrayText[3];
				else ret = "00";
				break;
			case "I" :
				if(obj_minutes.value != "분" || ArrayText[4] != "00") ret = ArrayText[4];
				else ret = "00";
				break;
			case "S" :
				if(obj_second.value != "초" || ArrayText[5] != "00") ret = ArrayText[5];
				else ret = "00";
				break;
		}
		return ret;
	}
	 
	function fc_formatToken(date, token) {
		var command = token.substring(0, 1);
		if (command == 'y' || command == 'Y') {
			if (token.length == 2) { return fc_zeroPad(date.getFullYear() % 100); }
			if (token.length == 4) { return date.getFullYear(); } 
		}
		
		if (command == 'd' || command == 'D') {
			if (token.length == 2) { return fc_zeroPad(date.getDate()); }
		}
		
		if (command == 'm' || command == 'M') {
			if (token.length == 2) { return fc_zeroPad(date.getMonth() + 1); }
			if (token.length == 3) { return fc_months[date.getMonth()]; } 
		}
		
		if (command == 'h' || command == 'H') {
			if (token.length == 2) { return GetDateHIS('H'); }
		}
		
		if (command == 'i' || command == 'I') {
			if (token.length == 2) { return GetDateHIS('I'); }
		}
		
		if (command == 's' || command == 'S') {
			if (token.length == 2) { return GetDateHIS('S'); }
		}
		
		return token;
	}
     
     function fc_parseToken(date, token, value, start) {
        var command = token.substring(0, 1);
        var v;
        if (command == 'y' || command == 'Y') {
            if (token.length == 2) { 
                v = value.substring(start, start + 2);
                if (v < 70) { date.setFullYear(2000 + parseInt(v)); } else { date.setFullYear(1900 + parseInt(v)); } 
            }
            if (token.length == 4) { v = value.substring(start, start + 4); date.setFullYear(v);} 
        }
        if (command == 'd' || command == 'D') {
            if (token.length == 2) { v = value.substring(start, start + 2); date.setDate(v); }
        }
        if (command == 'm' || command == 'M') {
            if (token.length == 2) { v = value.substring(start, start + 2); date.setMonth(v - 1); }
            if (token.length == 3) { 
                v = value.substring(start, start + 3);
                var i;
                for (i = 0; i < fc_months.length; i++) {
                    if (fc_months[i].toUpperCase() == v.toUpperCase()) { date.setMonth(i); }
                }
            } 
        }
     }
     
	function fc_zeroPad(num) {
		if (num < 10) { return '0' + num; }
		return num;
	}

	function fc_getObj(id) {
		return document.getElementById(id);
		//if (fc_ie) { return document.all[id]; } 
		//else { return document.getElementById(id);    }
	}

	function fc_setFieldValue(field, value) {
		if (field.type.substring(0,6) == 'select') {
			var i;
			for (i = 0; i < field.options.length; i++) {
				if (fc_equals(field.options[i].value, value)) {
					field.selectedIndex = i;
				}
			}
		} else {
			field.value = value;
		}
	}

	function fc_getFieldValue(field) {
		if (field.type.substring(0,6) == 'select') {
				return field.options[field.selectedIndex].value;
		} else {
				return field.value;
		}
	}
	
	function fc_equals(val1, val2) {
		if (val1 == val2) return true;              
		if (1 * val1 == 1 * val2) return true;
		return false;
	}
     
	fnCalStyle();