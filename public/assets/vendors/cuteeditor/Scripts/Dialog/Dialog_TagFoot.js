var _$_b6d3=["btn_editinwin","btnok","btncc","controlparent","display","style","none","onclick","nocancel","length","nodeName","SELECT","INPUT","TEXTAREA","isnotinput","getAttribute","1","skipAutoFireChanged","onpropertychange","","OnPropertyChange()","onchange","if(!syncingtoview)FireUIChanged()","onkeypress","onkeyup"];var btn_editinwin=Window_GetElement(window,_$_b6d3[0],true);var btnok=Window_GetElement(window,_$_b6d3[1],true);var btncc=Window_GetElement(window,_$_b6d3[2],true);var controlparent=Window_GetElement(window,_$_b6d3[3],true);btn_editinwin[_$_b6d3[5]][_$_b6d3[4]]= _$_b6d3[6];btn_editinwin[_$_b6d3[7]]= function btn_editinwin_onclick(){};if(Window_GetDialogTop(window)[_$_b6d3[8]]){btncc[_$_b6d3[5]][_$_b6d3[4]]= _$_b6d3[6]};btnok[_$_b6d3[7]]= function btnok_onclick(){Window_SetDialogReturnValue(window,true);Window_CloseDialog(window)};btncc[_$_b6d3[7]]= function btncc_onclick(){Window_SetDialogReturnValue(window,false);Window_CloseDialog(window)};function HookChangeEvents(){var _0x27AE8=Element_GetAllElements(controlparent);for(var i=0;i< _0x27AE8[_$_b6d3[9]];i++){var _0x26C95=_0x27AE8[i];if(_0x26C95[_$_b6d3[10]]== _$_b6d3[11]|| _0x26C95[_$_b6d3[10]]== _$_b6d3[12]|| _0x26C95[_$_b6d3[10]]== _$_b6d3[13]){if(_0x26C95[_$_b6d3[15]](_$_b6d3[14])== _$_b6d3[16]|| _0x26C95[_$_b6d3[15]](_$_b6d3[17])== _$_b6d3[16]){continue};Event_Attach(_0x26C95,_$_b6d3[18], new Function(_$_b6d3[19],_$_b6d3[20]));Event_Attach(_0x26C95,_$_b6d3[21], new Function(_$_b6d3[19],_$_b6d3[22]));Event_Attach(_0x26C95,_$_b6d3[23], new Function(_$_b6d3[19],_$_b6d3[22]));Event_Attach(_0x26C95,_$_b6d3[24], new Function(_$_b6d3[19],_$_b6d3[22]))}}}HookChangeEvents();SyncToViewInternal();setInterval(UpdateState,300)