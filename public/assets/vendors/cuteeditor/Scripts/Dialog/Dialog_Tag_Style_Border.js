var _$_ee53=["getTime","SetStyle","toLowerCase","length","","GetStyle","GetText",":",";","split","replace","cssText","div_selector_event","div_selector","sel_part","tb_margin","sel_margin_unit","tb_padding","sel_padding_unit","tb_border","sel_border_unit","sel_border","sel_style","inp_color","inp_color_Preview","outer","div_demo","offsetWidth","offsetHeight","Top","Left","Bottom","Right","onmousemove","runtimeStyle","border","4px solid red","style","onmouseout","onclick","value","onchange","disabled","selectedIndex","4px solid blue","-","Color"," ","#7f7c75","backgroundColor","Style","Width","options","indexOf","margin","padding"];function pause(_0x2FE0D){var _0x2F584= new Date();var _0x2FDFA=_0x2F584[_$_ee53[0]]()+ _0x2FE0D;while(true){_0x2F584=  new Date();if(_0x2F584[_$_ee53[0]]()> _0x2FDFA){return}}}function StyleClass(_0x26374){var _0x30306=[];var _0x2BBD9={};if(_0x26374){_0x30319()};this[_$_ee53[1]]= function SetStyle(name,_0x2640C,_0x3032C){name= name[_$_ee53[2]]();for(var i=0;i< _0x30306[_$_ee53[3]];i++){if(_0x30306[i]== name){break}};_0x30306[i]= name;_0x2BBD9[name]= _0x2640C?(_0x2640C+ (_0x3032C|| _$_ee53[4])):_$_ee53[4]};this[_$_ee53[5]]= function GetStyle(name){name= name[_$_ee53[2]]();return _0x2BBD9[name]|| _$_ee53[4]};this[_$_ee53[6]]= function _0x302F3(){var _0x26374=_$_ee53[4];for(var i=0;i< _0x30306[_$_ee53[3]];i++){var _0x26575=_0x30306[i];var p=_0x2BBD9[_0x26575];if(p){_0x26374+= _0x26575+ _$_ee53[7]+ p+ _$_ee53[8]}};return _0x26374};function _0x30319(){var arr=_0x26374[_$_ee53[9]](_$_ee53[8]);for(var i=0;i< arr[_$_ee53[3]];i++){var p=arr[i][_$_ee53[9]](_$_ee53[7]);var _0x26575=p[0][_$_ee53[10]](/^\s+/g,_$_ee53[4])[_$_ee53[10]](/\s+$/g,_$_ee53[4])[_$_ee53[2]]();_0x30306[_0x30306[_$_ee53[3]]]= _0x26575;_0x2BBD9[_0x26575]= p[1]}}}function GetStyle(_0x27C2B,name){return  new StyleClass(_0x27C2B[_$_ee53[11]])[_$_ee53[5]](name)}function SetStyle(_0x27C2B,name,_0x2640C,_0x30105){var _0x2FE59= new StyleClass(_0x27C2B[_$_ee53[11]]);_0x2FE59[_$_ee53[1]](name,_0x2640C,_0x30105);_0x27C2B[_$_ee53[11]]= _0x2FE59[_$_ee53[6]]()}function ParseFloatToString(_0x26445){var _0x27226=parseFloat(_0x26445);if(isNaN(_0x27226)){return _$_ee53[4]};return _0x27226+ _$_ee53[4]}var div_selector_event=Window_GetElement(window,_$_ee53[12],true);var div_selector=Window_GetElement(window,_$_ee53[13],true);var sel_part=Window_GetElement(window,_$_ee53[14],true);var tb_margin=Window_GetElement(window,_$_ee53[15],true);var sel_margin_unit=Window_GetElement(window,_$_ee53[16],true);var tb_padding=Window_GetElement(window,_$_ee53[17],true);var sel_padding_unit=Window_GetElement(window,_$_ee53[18],true);var tb_border=Window_GetElement(window,_$_ee53[19],true);var sel_border_unit=Window_GetElement(window,_$_ee53[20],true);var sel_border=Window_GetElement(window,_$_ee53[21],true);var sel_style=Window_GetElement(window,_$_ee53[22],true);var inp_color=Window_GetElement(window,_$_ee53[23],true);var inp_color_Preview=Window_GetElement(window,_$_ee53[24],true);var outer=Window_GetElement(window,_$_ee53[25],true);var div_demo=Window_GetElement(window,_$_ee53[26],true);function GetPartFromEvent(){var src=Event_GetSrcElement();var _0x27272=Event_GetOffsetX();var _0x281E2=Event_GetOffsetY();if(src== div_selector){_0x27272+= 10;_0x281E2+= 10};var _0x27B80=15- _0x27272;var _0x26B9E=_0x27272- (div_selector_event[_$_ee53[27]]- 15);var _0x261D2=15- _0x281E2;var _0x27B5A=_0x281E2- (div_selector_event[_$_ee53[28]]- 15);if(_0x27B80> 0){if(_0x261D2> 0){return _0x27B80> _0x261D2?_$_ee53[29]:_$_ee53[30]};if(_0x27B5A> 0){return _0x27B80> _0x27B5A?_$_ee53[31]:_$_ee53[30]};return _$_ee53[30]};if(_0x26B9E> 0){if(_0x261D2> 0){return _0x26B9E> _0x261D2?_$_ee53[29]:_$_ee53[32]};if(_0x27B5A> 0){return _0x26B9E> _0x27B5A?_$_ee53[31]:_$_ee53[32]};return _$_ee53[32]};if(_0x261D2> 0){return _$_ee53[29]};if(_0x27B5A> 0){return _$_ee53[31]};return _$_ee53[4]}div_selector_event[_$_ee53[33]]= function div_selector_event_onmousemove(){var _0x2BF30=GetPartFromEvent();if(Browser_IsWinIE()){div_selector[_$_ee53[34]][_$_ee53[11]]= _$_ee53[4];div_selector[_$_ee53[34]][_$_ee53[35]+ _0x2BF30]= _$_ee53[36]}else {div_selector[_$_ee53[37]][_$_ee53[11]]= _$_ee53[4];div_selector[_$_ee53[37]][_$_ee53[35]+ _0x2BF30]= _$_ee53[36]}};div_selector_event[_$_ee53[38]]= function div_selector_event_onmouseout(){if(Browser_IsWinIE()){div_selector[_$_ee53[34]][_$_ee53[11]]= _$_ee53[4]}else {div_selector[_$_ee53[37]][_$_ee53[11]]= _$_ee53[4]}};div_selector_event[_$_ee53[39]]= function div_selector_event_onclick(){sel_part[_$_ee53[40]]= GetPartFromEvent();SyncToViewInternal();UpdateState()};sel_part[_$_ee53[41]]= function sel_part_onchange(){SyncToViewInternal();UpdateState()};UpdateState= function UpdateState_Border(){tb_border[_$_ee53[42]]= sel_border_unit[_$_ee53[42]]= (sel_border[_$_ee53[43]]> 0);div_demo[_$_ee53[37]][_$_ee53[11]]= element[_$_ee53[37]][_$_ee53[11]];div_selector[_$_ee53[37]][_$_ee53[11]]= _$_ee53[4];div_selector[_$_ee53[37]][_$_ee53[35]+ (sel_part[_$_ee53[40]]|| _$_ee53[4])]= _$_ee53[44]};SyncToView= function SyncToView_Border(){var _0x2BF30=sel_part[_$_ee53[40]];var _0x3033F=_0x2BF30?_$_ee53[45]+ _0x2BF30:_0x2BF30;if(Browser_IsWinIE()){inp_color[_$_ee53[40]]= element[_$_ee53[37]][_$_ee53[35]+ _0x2BF30+ _$_ee53[46]]}else {var arr=revertColor(element[_$_ee53[37]][_$_ee53[35]+ _0x2BF30+ _$_ee53[46]])[_$_ee53[9]](_$_ee53[47]);if(arr[0]!= _$_ee53[48]){inp_color[_$_ee53[40]]= arr[0]}};inp_color[_$_ee53[37]][_$_ee53[49]]= inp_color[_$_ee53[40]];sel_style[_$_ee53[40]]= element[_$_ee53[37]][_$_ee53[35]+ _0x2BF30+ _$_ee53[50]];sel_border[_$_ee53[40]]= element[_$_ee53[37]][_$_ee53[35]+ _0x2BF30+ _$_ee53[51]];var _0x303EA=element[_$_ee53[37]][_$_ee53[35]+ _0x2BF30+ _$_ee53[51]];tb_border[_$_ee53[40]]= ParseFloatToString(_0x303EA);if(tb_border[_$_ee53[40]]){for(var i=0;i< sel_border_unit[_$_ee53[52]][_$_ee53[3]];i++){var _0x271B4=sel_border_unit[_$_ee53[52]][i][_$_ee53[40]];if(_0x271B4&& _0x303EA[_$_ee53[53]](_0x271B4)!=  -1){sel_border_unit[_$_ee53[43]]= i;break}}};var _0x303C4=element[_$_ee53[37]][_$_ee53[54]+ _0x2BF30];tb_margin[_$_ee53[40]]= ParseFloatToString(_0x303C4);if(tb_margin[_$_ee53[40]]){for(var i=0;i< sel_margin_unit[_$_ee53[52]][_$_ee53[3]];i++){var _0x271B4=sel_margin_unit[_$_ee53[52]][i][_$_ee53[40]];if(_0x271B4&& _0x303C4[_$_ee53[53]](_0x271B4)!=  -1){sel_margin_unit[_$_ee53[43]]= i;break}}};var _0x303D7=element[_$_ee53[37]][_$_ee53[55]+ _0x2BF30];tb_padding[_$_ee53[40]]= ParseFloatToString(_0x303D7);if(tb_padding[_$_ee53[40]]){for(var i=0;i< sel_padding_unit[_$_ee53[52]][_$_ee53[3]];i++){var _0x271B4=sel_padding_unit[_$_ee53[52]][i][_$_ee53[40]];if(_0x271B4&& _0x303D7[_$_ee53[53]](_0x271B4)!=  -1){sel_padding_unit[_$_ee53[43]]= i;break}}}};SyncTo= function SyncTo_Border(element){var _0x2BF30=sel_part[_$_ee53[40]];var _0x3033F=_0x2BF30?_$_ee53[45]+ _0x2BF30:_0x2BF30;var _0x2BAA9=_$_ee53[4];if(sel_border[_$_ee53[43]]> 0){_0x2BAA9= sel_border[_$_ee53[40]]}else {if(ParseFloatToString(tb_border[_$_ee53[40]])){_0x2BAA9= ParseFloatToString(tb_border[_$_ee53[40]])+ sel_border_unit[_$_ee53[40]]}else {_0x2BAA9= _$_ee53[4]}};var _0x27239=inp_color[_$_ee53[40]]|| _$_ee53[4];var _0x27C2B=sel_style[_$_ee53[40]]|| _$_ee53[4];if(_0x2BAA9|| _0x27C2B){SetStyle(element[_$_ee53[37]],_$_ee53[35]+ _0x3033F,_0x2BAA9+ _$_ee53[47]+ _0x27C2B+ _$_ee53[47]+ _0x27239)}else {SetStyle(element[_$_ee53[37]],_$_ee53[35]+ _0x3033F,_$_ee53[4])};SetStyle(element[_$_ee53[37]],_$_ee53[54]+ _0x3033F,ParseFloatToString(tb_margin[_$_ee53[40]]),sel_margin_unit[_$_ee53[40]]);SetStyle(element[_$_ee53[37]],_$_ee53[55]+ _0x3033F,ParseFloatToString(tb_padding[_$_ee53[40]]),sel_padding_unit[_$_ee53[40]])};inp_color[_$_ee53[39]]= inp_color_Preview[_$_ee53[39]]= function inp_color_onclick(){SelectColor(inp_color,inp_color_Preview)}