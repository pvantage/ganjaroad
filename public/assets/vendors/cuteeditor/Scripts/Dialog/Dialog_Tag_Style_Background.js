var _$_cf8f=["getTime","SetStyle","toLowerCase","length","","GetStyle","GetText",":",";","split","replace","cssText","inp_color","inp_color_Preview","tb_image","btnbrowse","sel_bgrepeat","sel_bgattach","sel_hor","tb_hor","sel_hor_unit","sel_ver","tb_ver","sel_ver_unit","outer","div_demo","onclick","value","SetNextDialogWindow","ShowSelectImageDialog","disabled","selectedIndex","style","backgroundImage","backgroundColor","backgroundRepeat","backgroundAttachment","backgroundPositionX","options","indexOf","backgroundPositionY","url(",")","background-image","backgroundPosition","substr","none"];function pause(_0x2FE0D){var _0x2F584= new Date();var _0x2FDFA=_0x2F584[_$_cf8f[0]]()+ _0x2FE0D;while(true){_0x2F584=  new Date();if(_0x2F584[_$_cf8f[0]]()> _0x2FDFA){return}}}function StyleClass(_0x26374){var _0x30306=[];var _0x2BBD9={};if(_0x26374){_0x30319()};this[_$_cf8f[1]]= function SetStyle(name,_0x2640C,_0x3032C){name= name[_$_cf8f[2]]();for(var i=0;i< _0x30306[_$_cf8f[3]];i++){if(_0x30306[i]== name){break}};_0x30306[i]= name;_0x2BBD9[name]= _0x2640C?(_0x2640C+ (_0x3032C|| _$_cf8f[4])):_$_cf8f[4]};this[_$_cf8f[5]]= function GetStyle(name){name= name[_$_cf8f[2]]();return _0x2BBD9[name]|| _$_cf8f[4]};this[_$_cf8f[6]]= function _0x302F3(){var _0x26374=_$_cf8f[4];for(var i=0;i< _0x30306[_$_cf8f[3]];i++){var _0x26575=_0x30306[i];var p=_0x2BBD9[_0x26575];if(p){_0x26374+= _0x26575+ _$_cf8f[7]+ p+ _$_cf8f[8]}};return _0x26374};function _0x30319(){var arr=_0x26374[_$_cf8f[9]](_$_cf8f[8]);for(var i=0;i< arr[_$_cf8f[3]];i++){var p=arr[i][_$_cf8f[9]](_$_cf8f[7]);var _0x26575=p[0][_$_cf8f[10]](/^\s+/g,_$_cf8f[4])[_$_cf8f[10]](/\s+$/g,_$_cf8f[4])[_$_cf8f[2]]();_0x30306[_0x30306[_$_cf8f[3]]]= _0x26575;_0x2BBD9[_0x26575]= p[1]}}}function GetStyle(_0x27C2B,name){return  new StyleClass(_0x27C2B[_$_cf8f[11]])[_$_cf8f[5]](name)}function SetStyle(_0x27C2B,name,_0x2640C,_0x30105){var _0x2FE59= new StyleClass(_0x27C2B[_$_cf8f[11]]);_0x2FE59[_$_cf8f[1]](name,_0x2640C,_0x30105);_0x27C2B[_$_cf8f[11]]= _0x2FE59[_$_cf8f[6]]()}function ParseFloatToString(_0x26445){var _0x27226=parseFloat(_0x26445);if(isNaN(_0x27226)){return _$_cf8f[4]};return _0x27226+ _$_cf8f[4]}var inp_color=Window_GetElement(window,_$_cf8f[12],true);var inp_color_Preview=Window_GetElement(window,_$_cf8f[13],true);var tb_image=Window_GetElement(window,_$_cf8f[14],true);var btnbrowse=Window_GetElement(window,_$_cf8f[15],true);var sel_bgrepeat=Window_GetElement(window,_$_cf8f[16],true);var sel_bgattach=Window_GetElement(window,_$_cf8f[17],true);var sel_hor=Window_GetElement(window,_$_cf8f[18],true);var tb_hor=Window_GetElement(window,_$_cf8f[19],true);var sel_hor_unit=Window_GetElement(window,_$_cf8f[20],true);var sel_ver=Window_GetElement(window,_$_cf8f[21],true);var tb_ver=Window_GetElement(window,_$_cf8f[22],true);var sel_ver_unit=Window_GetElement(window,_$_cf8f[23],true);var outer=Window_GetElement(window,_$_cf8f[24],true);var div_demo=Window_GetElement(window,_$_cf8f[25],true);btnbrowse[_$_cf8f[26]]= function btnbrowse_onclick(){function _0x26BC4(_0x26F2E){if(_0x26F2E){tb_image[_$_cf8f[27]]= _0x26F2E}}editor[_$_cf8f[28]](window);if(Browser_IsSafari()){editor[_$_cf8f[29]](_0x26BC4,tb_image[_$_cf8f[27]],tb_image)}else {editor[_$_cf8f[29]](_0x26BC4,tb_image[_$_cf8f[27]])}};UpdateState= function UpdateState_Background(){tb_hor[_$_cf8f[30]]= sel_hor_unit[_$_cf8f[30]]= (sel_hor[_$_cf8f[31]]> 0);tb_ver[_$_cf8f[30]]= sel_ver_unit[_$_cf8f[30]]= (sel_ver[_$_cf8f[31]]> 0);div_demo[_$_cf8f[32]][_$_cf8f[11]]= element[_$_cf8f[32]][_$_cf8f[11]]};SyncToView= function SyncToView_Background(){tb_image[_$_cf8f[27]]= element[_$_cf8f[32]][_$_cf8f[33]];FixTbImage();inp_color[_$_cf8f[27]]= element[_$_cf8f[32]][_$_cf8f[34]];inp_color[_$_cf8f[32]][_$_cf8f[34]]= element[_$_cf8f[32]][_$_cf8f[34]];inp_color_Preview[_$_cf8f[32]][_$_cf8f[34]]= element[_$_cf8f[32]][_$_cf8f[34]];sel_bgrepeat[_$_cf8f[27]]= element[_$_cf8f[32]][_$_cf8f[35]];sel_bgattach[_$_cf8f[27]]= element[_$_cf8f[32]][_$_cf8f[36]];sel_hor[_$_cf8f[27]]= element[_$_cf8f[32]][_$_cf8f[37]];sel_hor_unit[_$_cf8f[31]]= 0;if(sel_hor[_$_cf8f[31]]==  -1){if(ParseFloatToString(element[_$_cf8f[32]][_$_cf8f[37]])){tb_hor[_$_cf8f[27]]= ParseFloatToString(element[_$_cf8f[32]][_$_cf8f[37]]);for(var i=0;i< sel_hor_unit[_$_cf8f[38]][_$_cf8f[3]];i++){var _0x271B4=sel_hor_unit[_$_cf8f[38]][i][_$_cf8f[27]];if(_0x271B4&& element[_$_cf8f[32]][_$_cf8f[37]][_$_cf8f[39]](_0x271B4)!=  -1){sel_hor_unit[_$_cf8f[31]]= i;break}}}};sel_ver[_$_cf8f[27]]= element[_$_cf8f[32]][_$_cf8f[40]];sel_ver_unit[_$_cf8f[31]]= 0;if(sel_ver[_$_cf8f[31]]==  -1){if(ParseFloatToString(element[_$_cf8f[32]][_$_cf8f[40]])){tb_ver[_$_cf8f[27]]= ParseFloatToString(element[_$_cf8f[32]][_$_cf8f[40]]);for(var i=0;i< sel_ver_unit[_$_cf8f[38]][_$_cf8f[3]];i++){var _0x271B4=sel_ver_unit[_$_cf8f[38]][i][_$_cf8f[27]];if(_0x271B4&& element[_$_cf8f[32]][_$_cf8f[40]][_$_cf8f[39]](_0x271B4)!=  -1){sel_ver_unit[_$_cf8f[31]]= i;break}}}}};SyncTo= function SyncTo_Background(element){if(tb_image[_$_cf8f[27]]){element[_$_cf8f[32]][_$_cf8f[33]]= _$_cf8f[41]+ tb_image[_$_cf8f[27]]+ _$_cf8f[42]}else {SetStyle(element[_$_cf8f[32]],_$_cf8f[43],_$_cf8f[4])};try{element[_$_cf8f[32]][_$_cf8f[34]]= inp_color[_$_cf8f[27]]|| _$_cf8f[4]}catch(x){element[_$_cf8f[32]][_$_cf8f[34]]= _$_cf8f[4]};element[_$_cf8f[32]][_$_cf8f[35]]= sel_bgrepeat[_$_cf8f[27]]|| _$_cf8f[4];element[_$_cf8f[32]][_$_cf8f[36]]= sel_bgattach[_$_cf8f[27]]|| _$_cf8f[4];element[_$_cf8f[32]][_$_cf8f[44]]= _$_cf8f[4];if(sel_hor[_$_cf8f[31]]> 0){element[_$_cf8f[32]][_$_cf8f[37]]= sel_hor[_$_cf8f[27]]}else {if(ParseFloatToString(tb_hor[_$_cf8f[27]])){element[_$_cf8f[32]][_$_cf8f[37]]= ParseFloatToString(tb_hor[_$_cf8f[27]])+ sel_hor_unit[_$_cf8f[27]]}else {element[_$_cf8f[32]][_$_cf8f[37]]= _$_cf8f[4]}};if(sel_ver[_$_cf8f[31]]> 0){element[_$_cf8f[32]][_$_cf8f[40]]= sel_ver[_$_cf8f[27]]}else {if(ParseFloatToString(tb_ver[_$_cf8f[27]])){element[_$_cf8f[32]][_$_cf8f[40]]= ParseFloatToString(tb_ver[_$_cf8f[27]])+ sel_ver_unit[_$_cf8f[27]]}else {element[_$_cf8f[32]][_$_cf8f[40]]= _$_cf8f[4]}}};function FixTbImage(){var _0x271B4=tb_image[_$_cf8f[27]][_$_cf8f[10]](/^(\s+)/g,_$_cf8f[4])[_$_cf8f[10]](/(\s+)$/g,_$_cf8f[4]);if(_0x271B4[_$_cf8f[45]](0,4)[_$_cf8f[2]]()== _$_cf8f[41]){_0x271B4= _0x271B4[_$_cf8f[45]](4,_0x271B4[_$_cf8f[3]]- 4)};if(_0x271B4[_$_cf8f[45]](_0x271B4[_$_cf8f[3]]- 1,1)== _$_cf8f[42]){_0x271B4= _0x271B4[_$_cf8f[45]](0,_0x271B4[_$_cf8f[3]]- 1)};if(_0x271B4== _$_cf8f[46]){_0x271B4= _$_cf8f[4]};tb_image[_$_cf8f[27]]= _0x271B4}inp_color[_$_cf8f[26]]= inp_color_Preview[_$_cf8f[26]]= function inp_color_onclick(){SelectColor(inp_color,inp_color_Preview)}