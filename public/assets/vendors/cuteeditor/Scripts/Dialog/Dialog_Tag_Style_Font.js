var _$_aa72=["getTime","SetStyle","toLowerCase","length","","GetStyle","GetText",":",";","split","replace","cssText","sel_font","div_font_detail","sel_fontfamily","cb_decoration_under","cb_decoration_over","cb_decoration_through","cb_style_bold","cb_style_italic","sel_fontTransform","sel_fontsize","inp_fontsize","sel_fontsize_unit","inp_color","inp_color_Preview","outer","div_demo","disabled","selectedIndex","style","value","font","fontFamily","color","backgroundColor","textDecoration","checked","overline","indexOf","underline","line-through","fontWeight","bold","fontStyle","italic","fontSize","options","textTransform","font-family","overline ","underline ","line-through ","substr","onclick"];function pause(_0x2FE0D){var _0x2F584= new Date();var _0x2FDFA=_0x2F584[_$_aa72[0]]()+ _0x2FE0D;while(true){_0x2F584=  new Date();if(_0x2F584[_$_aa72[0]]()> _0x2FDFA){return}}}function StyleClass(_0x26374){var _0x30306=[];var _0x2BBD9={};if(_0x26374){_0x30319()};this[_$_aa72[1]]= function SetStyle(name,_0x2640C,_0x3032C){name= name[_$_aa72[2]]();for(var i=0;i< _0x30306[_$_aa72[3]];i++){if(_0x30306[i]== name){break}};_0x30306[i]= name;_0x2BBD9[name]= _0x2640C?(_0x2640C+ (_0x3032C|| _$_aa72[4])):_$_aa72[4]};this[_$_aa72[5]]= function GetStyle(name){name= name[_$_aa72[2]]();return _0x2BBD9[name]|| _$_aa72[4]};this[_$_aa72[6]]= function _0x302F3(){var _0x26374=_$_aa72[4];for(var i=0;i< _0x30306[_$_aa72[3]];i++){var _0x26575=_0x30306[i];var p=_0x2BBD9[_0x26575];if(p){_0x26374+= _0x26575+ _$_aa72[7]+ p+ _$_aa72[8]}};return _0x26374};function _0x30319(){var arr=_0x26374[_$_aa72[9]](_$_aa72[8]);for(var i=0;i< arr[_$_aa72[3]];i++){var p=arr[i][_$_aa72[9]](_$_aa72[7]);var _0x26575=p[0][_$_aa72[10]](/^\s+/g,_$_aa72[4])[_$_aa72[10]](/\s+$/g,_$_aa72[4])[_$_aa72[2]]();_0x30306[_0x30306[_$_aa72[3]]]= _0x26575;_0x2BBD9[_0x26575]= p[1]}}}function GetStyle(_0x27C2B,name){return  new StyleClass(_0x27C2B[_$_aa72[11]])[_$_aa72[5]](name)}function SetStyle(_0x27C2B,name,_0x2640C,_0x30105){var _0x2FE59= new StyleClass(_0x27C2B[_$_aa72[11]]);_0x2FE59[_$_aa72[1]](name,_0x2640C,_0x30105);_0x27C2B[_$_aa72[11]]= _0x2FE59[_$_aa72[6]]()}function ParseFloatToString(_0x26445){var _0x27226=parseFloat(_0x26445);if(isNaN(_0x27226)){return _$_aa72[4]};return _0x27226+ _$_aa72[4]}var sel_font=Window_GetElement(window,_$_aa72[12],true);var div_font_detail=Window_GetElement(window,_$_aa72[13],true);var sel_fontfamily=Window_GetElement(window,_$_aa72[14],true);var cb_decoration_under=Window_GetElement(window,_$_aa72[15],true);var cb_decoration_over=Window_GetElement(window,_$_aa72[16],true);var cb_decoration_through=Window_GetElement(window,_$_aa72[17],true);var cb_style_bold=Window_GetElement(window,_$_aa72[18],true);var cb_style_italic=Window_GetElement(window,_$_aa72[19],true);var sel_fontTransform=Window_GetElement(window,_$_aa72[20],true);var sel_fontsize=Window_GetElement(window,_$_aa72[21],true);var inp_fontsize=Window_GetElement(window,_$_aa72[22],true);var sel_fontsize_unit=Window_GetElement(window,_$_aa72[23],true);var inp_color=Window_GetElement(window,_$_aa72[24],true);var inp_color_Preview=Window_GetElement(window,_$_aa72[25],true);var outer=Window_GetElement(window,_$_aa72[26],true);var div_demo=Window_GetElement(window,_$_aa72[27],true);UpdateState= function UpdateState_Font(){inp_fontsize[_$_aa72[28]]= sel_fontsize_unit[_$_aa72[28]]= (sel_fontsize[_$_aa72[29]]> 0);div_font_detail[_$_aa72[28]]= sel_font[_$_aa72[29]]> 0;div_demo[_$_aa72[30]][_$_aa72[11]]= element[_$_aa72[30]][_$_aa72[11]]};SyncToView= function SyncToView_Font(){sel_font[_$_aa72[31]]= element[_$_aa72[30]][_$_aa72[32]][_$_aa72[2]]()|| null;sel_fontfamily[_$_aa72[31]]= element[_$_aa72[30]][_$_aa72[33]];inp_color[_$_aa72[31]]= element[_$_aa72[30]][_$_aa72[34]];inp_color[_$_aa72[30]][_$_aa72[35]]= inp_color[_$_aa72[31]];var _0x2A724=element[_$_aa72[30]][_$_aa72[36]][_$_aa72[2]]();cb_decoration_over[_$_aa72[37]]= _0x2A724[_$_aa72[39]](_$_aa72[38])!=  -1;cb_decoration_under[_$_aa72[37]]= _0x2A724[_$_aa72[39]](_$_aa72[40])!=  -1;cb_decoration_through[_$_aa72[37]]= _0x2A724[_$_aa72[39]](_$_aa72[41])!=  -1;cb_style_bold[_$_aa72[37]]= element[_$_aa72[30]][_$_aa72[42]]== _$_aa72[43];cb_style_italic[_$_aa72[37]]= element[_$_aa72[30]][_$_aa72[44]]== _$_aa72[45];sel_fontsize[_$_aa72[31]]= element[_$_aa72[30]][_$_aa72[46]];sel_fontsize_unit[_$_aa72[29]]= 0;if(sel_fontsize[_$_aa72[29]]==  -1){if(ParseFloatToString(element[_$_aa72[30]][_$_aa72[46]])){sel_fontsize[_$_aa72[31]]= ParseFloatToString(element[_$_aa72[30]][_$_aa72[46]]);for(var i=0;i< sel_fontsize_unit[_$_aa72[47]][_$_aa72[3]];i++){var _0x271B4=sel_fontsize_unit[_$_aa72[47]](i)[_$_aa72[31]];if(_0x271B4&& element[_$_aa72[30]][_$_aa72[46]][_$_aa72[39]](_0x271B4)!=  -1){sel_fontsize_unit[_$_aa72[29]]= i;break}}}};sel_fontTransform[_$_aa72[31]]= element[_$_aa72[30]][_$_aa72[48]]};SyncTo= function SyncTo_Font(element){SetStyle(element[_$_aa72[30]],_$_aa72[32],sel_font[_$_aa72[31]]);if(sel_fontfamily[_$_aa72[31]]){element[_$_aa72[30]][_$_aa72[33]]= sel_fontfamily[_$_aa72[31]]}else {SetStyle(element[_$_aa72[30]],_$_aa72[49],_$_aa72[4])};try{element[_$_aa72[30]][_$_aa72[34]]= inp_color[_$_aa72[31]]|| _$_aa72[4]}catch(x){element[_$_aa72[30]][_$_aa72[34]]= _$_aa72[4]};var _0x30365=cb_decoration_over[_$_aa72[37]];var _0x30378=cb_decoration_under[_$_aa72[37]];var _0x3038B=cb_decoration_through[_$_aa72[37]];if(!_0x30365&&  !_0x30378 &&  !_0x3038B){element[_$_aa72[30]][_$_aa72[36]]= _$_aa72[4]}else {var _0x27C3E=_$_aa72[4];if(_0x30365){_0x27C3E+= _$_aa72[50]};if(_0x30378){_0x27C3E+= _$_aa72[51]};if(_0x3038B){_0x27C3E+= _$_aa72[52]};element[_$_aa72[30]][_$_aa72[36]]= _0x27C3E[_$_aa72[53]](0,_0x27C3E[_$_aa72[3]]- 1)};element[_$_aa72[30]][_$_aa72[42]]= cb_style_bold[_$_aa72[37]]?_$_aa72[43]:_$_aa72[4];element[_$_aa72[30]][_$_aa72[44]]= cb_style_italic[_$_aa72[37]]?_$_aa72[45]:_$_aa72[4];element[_$_aa72[30]][_$_aa72[48]]= sel_fontTransform[_$_aa72[31]]|| _$_aa72[4];if(sel_fontsize[_$_aa72[29]]> 0){element[_$_aa72[30]][_$_aa72[46]]= sel_fontsize[_$_aa72[31]]}else {if(ParseFloatToString(inp_fontsize[_$_aa72[31]])){element[_$_aa72[30]][_$_aa72[46]]= ParseFloatToString(inp_fontsize[_$_aa72[31]])+ sel_fontsize_unit[_$_aa72[31]]}else {element[_$_aa72[30]][_$_aa72[46]]= _$_aa72[4]}}};inp_color[_$_aa72[54]]= inp_color_Preview[_$_aa72[54]]= function inp_color_onclick(){SelectColor(inp_color,inp_color_Preview)}