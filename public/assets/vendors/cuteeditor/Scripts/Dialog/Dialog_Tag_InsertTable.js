var _$_3684=["rowSpan","removeNode","parentNode","removeChild","firstChild","insertBefore","colSpan","nodeName","TABLE","Map","rowIndex","item","rows","length","cells","cellIndex","insertCell","innerHTML","cell","&nbsp;","Can't Get The Position ?","RowCount","ColCount","Unknown Error , pos ",":"," already have cell","Unknown Error , Unable to find bestpos","insertRow","tbody","createElement","appendChild","uniqueID","richDropDown","list_Templates","subcolumns","addcolumns","subcolspan","addcolspan","btn_row_dialog","btn_cell_dialog","inp_cell_width","inp_cell_height","btn_cell_editcell","tabledesign","subrows","addrows","subrowspan","addrowspan","display","style","none","disabled","value","width","height","ValidNumber","","<table><tr><td height=\"24\"></td></tr></table>","<table><tr><td></td><td></td></tr></table>","<table><tr><td></td><td></td><td></td></tr></table>","<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td valign=\"top\" colspan=\"2\"></td></tr>","<tr><td valign=\"top\" rowspan=\"2\"></td><td valign=\"top\"></td></tr>","<tr><td valign=\"top\"></td></tr></table>","<tr><td valign=\"top\"></td><td valign=\"top\" rowspan=\"2\"></td></tr><tr><td valign=\"top\"></td></tr></table>","<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td valign=\"top\" colspan=\"3\"></td></tr>","<tr><td valign=\"top\"></td><td valign=\"top\"></td><td valign=\"top\"></td></tr>","<tr><td valign=\"top\" colspan=\"3\"></td></tr></table>","DIV","children","onclick","removeAttribute","bgColor","#d4d0c8","onmouseover","onmouseout","ondblclick","contains","ToggleBorder","ExecCommand","backgroundColor","highlight","EditInWindow","SetNextDialogWindow","ShowTagDialogWithNoCancellable","cssText","runtimeStyle","background-color:gray","onload","createPopup","body","document","csstext","font:normal 11px Tahoma;background-color:windowtext;","show","isOpen","hide"];function Table_GetCellFromMap(_0x30423,_0x3045C,_0x30410){var _0x265D4=_0x30423[_0x3045C];if(_0x265D4){return _0x265D4[_0x30410]}}function Table_CanSubRowSpan(_0x26ECF){return _0x26ECF[_$_3684[0]]> 1}function Element_RemoveNode(element,_0x2F785){if(element[_$_3684[1]]){element[_$_3684[1]](_0x2F785);return};var p=element[_$_3684[2]];if(!p){return};if(_0x2F785){p[_$_3684[3]](element);return};while(true){var _0x26633=element[_$_3684[4]];if(!_0x26633){break};p[_$_3684[5]](_0x26633,element)};p[_$_3684[3]](element)}function Table_CanSubColSpan(_0x26ECF){return _0x26ECF[_$_3684[6]]> 1}function Table_GetTable(_0x26C95){for(;_0x26C95!= null;_0x26C95= _0x26C95[_$_3684[2]]){if(_0x26C95[_$_3684[7]]== _$_3684[8]){return _0x26C95}};return null}function Table_SubRowSpan(_0x26ECF){var _0x27875=Table_GetTable(_0x26ECF);var _0x26E37=Table_CalculateTableInfo(_0x27875);var _0x30423=_0x26E37[_$_3684[9]];var _0x2639A=Table_GetCellPositionFromMap(_0x30423,_0x26ECF);var _0x3064A=_0x27875[_$_3684[12]][_$_3684[11]](_0x26ECF[_$_3684[2]][_$_3684[10]]+ _0x26ECF[_$_3684[0]]- 1);var _0x30624=0;for(var i=0;i< _0x3064A[_$_3684[14]][_$_3684[13]];i++){var _0x30611=_0x3064A[_$_3684[14]][_$_3684[11]](i);var _0x30637=Table_GetCellPositionFromMap(_0x30423,_0x30611);if(_0x30637[_$_3684[15]]< _0x2639A[_$_3684[15]]){_0x30624= i+ 1}};for(var i=0;i< _0x26ECF[_$_3684[6]];i++){var _0x26633=_0x3064A[_$_3684[16]](_0x30624);if(Browser_IsOpera()){_0x26633[_$_3684[17]]= _$_3684[18]}else {if(Browser_IsGecko()|| Browser_IsSafari()){_0x26633[_$_3684[17]]= _$_3684[19]}}};_0x26ECF[_$_3684[0]]--}function Table_GetCellPositionFromMap(_0x30423,_0x26ECF){for(var _0x281E2=0;_0x281E2< _0x30423[_$_3684[13]];_0x281E2++){var _0x265D4=_0x30423[_0x281E2];for(var _0x27272=0;_0x27272< _0x265D4[_$_3684[13]];_0x27272++){if(_0x265D4[_0x27272]== _0x26ECF){return {rowIndex:_0x281E2,cellIndex:_0x27272}}}};throw ( new Error(-1,_$_3684[20]))}function Table_GetCellMap(_0x27875){return Table_CalculateTableInfo(_0x27875)[_$_3684[9]]}function Table_GetRowCount(_0x26C95){return Table_CalculateTableInfo(_0x26C95)[_$_3684[21]]}function Table_GetColCount(_0x26C95){return Table_CalculateTableInfo(_0x26C95)[_$_3684[22]]}function Table_CalculateTableInfo(_0x26C95){var _0x27875=Table_GetTable(_0x26C95);var _0x30553=_0x27875[_$_3684[12]];for(var _0x26B9E=_0x30553[_$_3684[13]]- 1;_0x26B9E>= 0;_0x26B9E--){var _0x2AF4E=_0x30553[_$_3684[11]](_0x26B9E);if(_0x2AF4E[_$_3684[14]][_$_3684[13]]== 0){Element_RemoveNode(_0x2AF4E,true);continue}};var _0x30482=_0x30553[_$_3684[13]];var _0x3046F=0;var _0x30540= new Array(_0x30553[_$_3684[13]]);for(var _0x2B06B=0;_0x2B06B< _0x30482;_0x2B06B++){_0x30540[_0x2B06B]= []};function _0x30579(_0x26B9E,_0x26633,_0x26ECF){while(_0x26B9E>= _0x30482){_0x30540[_0x30482]= [];_0x30482++};var _0x304BB=_0x30540[_0x26B9E];if(_0x26633>= _0x3046F){_0x3046F= _0x26633+ 1};if(_0x304BB[_0x26633]!= null){throw ( new Error(-1,_$_3684[23]+ _0x26B9E+ _$_3684[24]+ _0x26633+ _$_3684[25]))};_0x304BB[_0x26633]= _0x26ECF}function _0x304F4(_0x26B9E,_0x26633){var _0x304BB=_0x30540[_0x26B9E];if(_0x304BB){return _0x304BB[_0x26633]}}for(var _0x2B06B=0;_0x2B06B< _0x30553[_$_3684[13]];_0x2B06B++){var _0x2AF4E=_0x30553[_$_3684[11]](_0x2B06B);var _0x304CE=_0x2AF4E[_$_3684[14]];for(var _0x2B058=0;_0x2B058< _0x304CE[_$_3684[13]];_0x2B058++){var _0x26ECF=_0x304CE[_$_3684[11]](_0x2B058);var _0x30566=_0x26ECF[_$_3684[0]];var _0x304E1=_0x26ECF[_$_3684[6]];var _0x304BB=_0x30540[_0x2B06B];var _0x304A8=-1;for(var _0x3058C=0;_0x3058C< _0x3046F+ _0x304E1+ 1;_0x3058C++){if(_0x30566== 1&& _0x304E1== 1){if(_0x304BB[_0x3058C]== null){_0x304A8= _0x3058C;break}}else {var _0x3052D=true;for(var _0x3051A=0;_0x3051A< _0x30566;_0x3051A++){for(var _0x30507=0;_0x30507< _0x304E1;_0x30507++){if(_0x304F4(_0x2B06B+ _0x3051A,_0x3058C+ _0x30507)!= null){_0x3052D= false;break}}};if(_0x3052D){_0x304A8= _0x3058C;break}}};if(_0x304A8==  -1){throw ( new Error(-1,_$_3684[26]))};if(_0x30566== 1&& _0x304E1== 1){_0x30579(_0x2B06B,_0x304A8,_0x26ECF)}else {for(var _0x3051A=0;_0x3051A< _0x30566;_0x3051A++){for(var _0x30507=0;_0x30507< _0x304E1;_0x30507++){_0x30579(_0x2B06B+ _0x3051A,_0x304A8+ _0x30507,_0x26ECF)}}}}};var _0x26F2E={};_0x26F2E[_$_3684[21]]= _0x30482;_0x26F2E[_$_3684[22]]= _0x3046F;_0x26F2E[_$_3684[9]]= _0x30540;for(var _0x26B9E=0;_0x26B9E< _0x30482;_0x26B9E++){var _0x304BB=_0x30540[_0x26B9E];for(var _0x26633=0;_0x26633< _0x3046F;_0x26633++){}};return _0x26F2E}function Table_SubColSpan(_0x26ECF){var _0x27875=Table_GetTable(_0x26ECF);_0x26ECF[_$_3684[2]][_$_3684[16]](_0x26ECF[_$_3684[15]]+ 1)[_$_3684[0]]= _0x26ECF[_$_3684[0]];_0x26ECF[_$_3684[6]]--}function Table_CanAddRowSpan(_0x26ECF){var _0x27875=Table_GetTable(_0x26ECF);var _0x26E37=Table_CalculateTableInfo(_0x27875);var _0x30423=_0x26E37[_$_3684[9]];var _0x2639A=Table_GetCellPositionFromMap(_0x30423,_0x26ECF);var _0x30495=0;for(var _0x26633=0;_0x26633< _0x26ECF[_$_3684[6]];_0x26633++){var _0x30436=Table_GetCellFromMap(_0x30423,_0x2639A[_$_3684[10]]+ _0x26ECF[_$_3684[0]],_0x2639A[_$_3684[15]]+ _0x26633);if(_0x30436== null){return false};if(_0x30495!= 0&& _0x30495!= _0x30436[_$_3684[0]]){return false};_0x30495= _0x30436[_$_3684[0]];var _0x305C5=Table_GetCellPositionFromMap(_0x30423,_0x30436);if(_0x305C5[_$_3684[15]]< _0x2639A[_$_3684[15]]){return false};if(_0x305C5[_$_3684[15]]+ _0x30436[_$_3684[6]]> _0x2639A[_$_3684[15]]+ _0x26ECF[_$_3684[6]]){return false}};return true}function Table_AddRow(_0x26ECF){var _0x27875=Table_GetTable(_0x26ECF);var _0x26E37=Table_CalculateTableInfo(_0x27875);var _0x30423=_0x26E37[_$_3684[9]];var _0x3046F=_0x26E37[_$_3684[22]];var _0x30482=_0x26E37[_$_3684[21]];var _0x2AF4E;if(!Browser_IsSafari()){_0x2AF4E= _0x27875[_$_3684[27]](-1)}else {var _0x26DEB=document[_$_3684[29]](_$_3684[28]);_0x27875[_$_3684[30]](_0x26DEB);_0x2AF4E= _0x26DEB[_$_3684[27]](-1)};for(var i=0;i< _0x3046F;i++){var _0x26633=_0x2AF4E[_$_3684[16]](-1);if(Browser_IsOpera()){_0x26633[_$_3684[17]]= _$_3684[18]}else {if(Browser_IsGecko()|| Browser_IsSafari()){_0x26633[_$_3684[17]]= _$_3684[19]}}}}function Table_AddCol(_0x26ECF){var _0x27875=Table_GetTable(_0x26ECF);for(var _0x26B9E=0;_0x26B9E< _0x27875[_$_3684[12]][_$_3684[13]];_0x26B9E++){var _0x2AF4E=_0x27875[_$_3684[12]][_$_3684[11]](_0x26B9E);var _0x26633=_0x2AF4E[_$_3684[16]](-1);if(Browser_IsOpera()){_0x26633[_$_3684[17]]= _$_3684[18]}else {if(Browser_IsGecko()|| Browser_IsSafari()){_0x26633[_$_3684[17]]= _$_3684[19]}}}}function Table_CleanUpTableInfo(_0x27875,_0x26E37){var _0x30553=_0x27875[_$_3684[12]];for(var _0x26B9E=_0x30553[_$_3684[13]]- 1;_0x26B9E>= 0;_0x26B9E--){var _0x2AF4E=_0x30553[_$_3684[11]](_0x26B9E);if(_0x2AF4E[_$_3684[14]][_$_3684[13]]== 0){Element_RemoveNode(_0x2AF4E,true);continue}};var _0x30423=_0x26E37[_$_3684[9]];var _0x3046F=_0x26E37[_$_3684[22]];var _0x30482=_0x26E37[_$_3684[21]];for(var _0x2B06B=1;_0x2B06B< _0x30482;_0x2B06B++){var _0x305D8=true;for(var _0x2B058=0;_0x2B058< _0x3046F;_0x2B058++){if(Table_GetCellFromMap(_0x30423,_0x2B06B- 1,_0x2B058)!= Table_GetCellFromMap(_0x30423,_0x2B06B,_0x2B058)){_0x305D8= false;break}};if(_0x305D8){for(var _0x2B058=0;_0x2B058< _0x3046F;_0x2B058++){var _0x26ECF=Table_GetCellFromMap(_0x30423,_0x2B06B,_0x2B058);if(_0x26ECF){if(_0x26ECF[_$_3684[0]]> 1){_0x26ECF[_$_3684[0]]= _0x26ECF[_$_3684[0]]- 1};_0x2B058+= _0x26ECF[_$_3684[6]]- 1}}}};for(var _0x2B058=1;_0x2B058< _0x3046F;_0x2B058++){var _0x305D8=true;for(var _0x2B06B=0;_0x2B06B< _0x30482;_0x2B06B++){if(Table_GetCellFromMap(_0x30423,_0x2B06B,_0x2B058- 1)!= Table_GetCellFromMap(_0x30423,_0x2B06B,_0x2B058)){_0x305D8= false;break}};if(_0x305D8){for(var _0x2B06B=0;_0x2B06B< _0x30482;_0x2B06B++){var _0x26ECF=Table_GetCellFromMap(_0x30423,_0x2B06B,_0x2B058);if(_0x26ECF){if(_0x26ECF[_$_3684[6]]> 1){_0x26ECF[_$_3684[6]]= _0x26ECF[_$_3684[6]]- 1};_0x2B06B+= _0x26ECF[_$_3684[0]]- 1}}}}}function Table_SubRow(_0x26ECF){var _0x27875=Table_GetTable(_0x26ECF);var _0x26E37=Table_CalculateTableInfo(_0x27875);var _0x30423=_0x26E37[_$_3684[9]];var _0x3046F=_0x26E37[_$_3684[22]];var _0x30482=_0x26E37[_$_3684[21]];if(_0x30482== 0){return};var _0x305EB={};var _0x2B06B=_0x30482- 1;for(var _0x2B058=0;_0x2B058< _0x3046F;_0x2B058++){var _0x26ECF=Table_GetCellFromMap(_0x30423,_0x2B06B,_0x2B058);if(_0x26ECF){if(_0x305EB[_0x26ECF[_$_3684[31]]]){continue};_0x305EB[_0x26ECF[_$_3684[31]]]= true;if(_0x26ECF[_$_3684[0]]== 1){Element_RemoveNode(_0x26ECF,true)}else {_0x26ECF[_$_3684[0]]= _0x26ECF[_$_3684[0]]- 1}}};_0x26E37[_$_3684[21]]--;Table_CleanUpTableInfo(_0x27875,_0x26E37)}function Table_CanAddColSpan(_0x26ECF){var _0x27875=Table_GetTable(_0x26ECF);var _0x26E37=Table_CalculateTableInfo(_0x27875);var _0x30423=_0x26E37[_$_3684[9]];var _0x2639A=Table_GetCellPositionFromMap(_0x30423,_0x26ECF);var _0x30449=0;for(var _0x26B9E=0;_0x26B9E< _0x26ECF[_$_3684[0]];_0x26B9E++){var _0x30436=Table_GetCellFromMap(_0x30423,_0x2639A[_$_3684[10]]+ _0x26B9E,_0x2639A[_$_3684[15]]+ _0x26ECF[_$_3684[6]]);if(_0x30436== null){return false};if(_0x30449!= 0&& _0x30449!= _0x30436[_$_3684[6]]){return false};_0x30449= _0x30436[_$_3684[6]];var _0x305C5=Table_GetCellPositionFromMap(_0x30423,_0x30436);if(_0x305C5[_$_3684[10]]< _0x2639A[_$_3684[10]]){return false};if(_0x305C5[_$_3684[10]]+ _0x30436[_$_3684[0]]> _0x2639A[_$_3684[10]]+ _0x26ECF[_$_3684[0]]){return false}};return true}function Table_AddRowSpan(_0x26ECF){var _0x27875=Table_GetTable(_0x26ECF);var _0x26E37=Table_CalculateTableInfo(_0x27875);var _0x30423=_0x26E37[_$_3684[9]];var _0x2639A=Table_GetCellPositionFromMap(_0x30423,_0x26ECF);var _0x30495=0;for(var _0x26633=0;_0x26633< _0x26ECF[_$_3684[6]];_0x26633++){var _0x30436=Table_GetCellFromMap(_0x30423,_0x2639A[_$_3684[10]]+ _0x26ECF[_$_3684[0]],_0x2639A[_$_3684[15]]+ _0x26633);if(_0x30495== 0){_0x30495= _0x30436[_$_3684[0]]};Element_RemoveNode(_0x30436,true)};_0x26ECF[_$_3684[0]]= _0x26ECF[_$_3684[0]]+ _0x30495;for(var _0x2B06B=0;_0x2B06B< _0x26ECF[_$_3684[0]];_0x2B06B++){for(var _0x2B058=0;_0x2B058< _0x26ECF[_$_3684[6]];_0x2B058++){_0x26E37[_$_3684[9]][_0x2639A[_$_3684[10]]+ _0x2B06B][_0x2639A[_$_3684[15]]+ _0x2B058]= _0x26ECF}};Table_CleanUpTableInfo(_0x27875,_0x26E37)}function Table_AddColSpan(_0x26ECF){var _0x27875=Table_GetTable(_0x26ECF);var _0x26E37=Table_CalculateTableInfo(_0x27875);var _0x30423=_0x26E37[_$_3684[9]];var _0x2639A=Table_GetCellPositionFromMap(_0x30423,_0x26ECF);var _0x30449=0;for(var _0x26B9E=0;_0x26B9E< _0x26ECF[_$_3684[0]];_0x26B9E++){var _0x30436=Table_GetCellFromMap(_0x30423,_0x2639A[_$_3684[10]]+ _0x26B9E,_0x2639A[_$_3684[15]]+ _0x26ECF[_$_3684[6]]);if(_0x30449== 0){_0x30449= _0x30436[_$_3684[6]]};Element_RemoveNode(_0x30436,true)};_0x26ECF[_$_3684[6]]+= _0x30449;for(var _0x2B06B=0;_0x2B06B< _0x26ECF[_$_3684[0]];_0x2B06B++){for(var _0x2B058=0;_0x2B058< _0x26ECF[_$_3684[6]];_0x2B058++){_0x26E37[_$_3684[9]][_0x2639A[_$_3684[10]]+ _0x2B06B][_0x2639A[_$_3684[15]]+ _0x2B058]= _0x26ECF}};Table_CleanUpTableInfo(_0x27875,_0x26E37)}function Table_SubCol(_0x26ECF){var _0x27875=Table_GetTable(_0x26ECF);var _0x26E37=Table_CalculateTableInfo(_0x27875);var _0x30423=_0x26E37[_$_3684[9]];var _0x3046F=_0x26E37[_$_3684[22]];var _0x30482=_0x26E37[_$_3684[21]];if(_0x30482== 0){return};var _0x305EB={};var _0x2B058=_0x3046F- 1;for(var _0x2B06B=0;_0x2B06B< _0x30482;_0x2B06B++){var _0x26ECF=Table_GetCellFromMap(_0x30423,_0x2B06B,_0x2B058);if(_0x305EB[_0x26ECF[_$_3684[31]]]){continue};_0x305EB[_0x26ECF[_$_3684[31]]]= true;if(_0x26ECF[_$_3684[6]]== 1){Element_RemoveNode(_0x26ECF,true)}else {_0x26ECF[_$_3684[6]]= _0x26ECF[_$_3684[6]]- 1}};_0x26E37[_$_3684[22]]--;Table_CleanUpTableInfo(_0x27875,_0x26E37)}var richDropDown=Window_GetElement(window,_$_3684[32],true);var list_Templates=Window_GetElement(window,_$_3684[33],true);var subcolumns=Window_GetElement(window,_$_3684[34],true);var addcolumns=Window_GetElement(window,_$_3684[35],true);var subcolspan=Window_GetElement(window,_$_3684[36],true);var addcolspan=Window_GetElement(window,_$_3684[37],true);var btn_row_dialog=Window_GetElement(window,_$_3684[38],true);var btn_cell_dialog=Window_GetElement(window,_$_3684[39],true);var inp_cell_width=Window_GetElement(window,_$_3684[40],true);var inp_cell_height=Window_GetElement(window,_$_3684[41],true);var btn_cell_editcell=Window_GetElement(window,_$_3684[42],true);var tabledesign=Window_GetElement(window,_$_3684[43],true);var subrows=Window_GetElement(window,_$_3684[44],true);var addrows=Window_GetElement(window,_$_3684[45],true);var subrowspan=Window_GetElement(window,_$_3684[46],true);var addrowspan=Window_GetElement(window,_$_3684[47],true);btn_cell_editcell[_$_3684[49]][_$_3684[48]]= _$_3684[50];UpdateState= function UpdateState_InsertTable(){btn_cell_editcell[_$_3684[51]]= btn_row_dialog[_$_3684[51]]= btn_cell_dialog[_$_3684[51]]= GetElementCell()== null};SyncToView= function SyncToView_InsertTable(){var _0x3039E=GetElementCell();if(_0x3039E){inp_cell_width[_$_3684[52]]= _0x3039E[_$_3684[53]];inp_cell_height[_$_3684[52]]= _0x3039E[_$_3684[54]]}};SyncTo= function SyncTo_InsertTable(element){var _0x3039E=GetElementCell();if(_0x3039E){try{_0x3039E[_$_3684[53]]= inp_cell_width[_$_3684[52]];_0x3039E[_$_3684[54]]= inp_cell_height[_$_3684[52]]}catch(er){alert(CE_GetStr(_$_3684[55]))}}};function selectTemplate(_0x27285){var _0x27888=_$_3684[56];switch(_0x27285){case 1:_0x27888= _$_3684[57];break;case 2:_0x27888= _$_3684[58];break;case 3:_0x27888= _$_3684[59];break;case 4:_0x27888= _$_3684[60];_0x27888= _0x27888+ _$_3684[61];_0x27888= _0x27888+ _$_3684[62];break;case 5:_0x27888= _$_3684[60];_0x27888= _0x27888+ _$_3684[63];break;case 6:_0x27888= _$_3684[64];_0x27888= _0x27888+ _$_3684[65];_0x27888= _0x27888+ _$_3684[66];break;default:break};try{var _0x26562=document[_$_3684[29]](_$_3684[67]);_0x26562[_$_3684[17]]= _0x27888;var _0x27875=_0x26562[_$_3684[68]](0);ApplyTable(_0x27875,element);ApplyTable(_0x27875,tabledesign);HandleTableChanged();hidePopup()}catch(x){}}subcolumns[_$_3684[69]]= function subcolumns_onclick(){Table_SubCol(GetElementCell());Table_SubCol(currentcell);HandleTableChanged()};addcolumns[_$_3684[69]]= function addcolumns_onclick(){Table_AddCol(GetElementCell());Table_AddCol(currentcell);HandleTableChanged()};subrows[_$_3684[69]]= function subrows_onclick(){Table_SubRow(GetElementCell());Table_SubRow(currentcell);HandleTableChanged()};addrows[_$_3684[69]]= function addrows_onclick(){Table_AddRow(currentcell);Table_AddRow(GetElementCell());HandleTableChanged()};subcolspan[_$_3684[69]]= function subcolspan_onclick(){Table_SubColSpan(GetElementCell());Table_SubColSpan(currentcell);HandleTableChanged()};addcolspan[_$_3684[69]]= function addcolspan_onclick(){Table_AddColSpan(GetElementCell());Table_AddColSpan(currentcell);HandleTableChanged()};subrowspan[_$_3684[69]]= function subrowspan_onclick(){Table_SubRowSpan(GetElementCell());Table_SubRowSpan(currentcell);HandleTableChanged()};addrowspan[_$_3684[69]]= function addrowspan_onclick(){Table_AddRowSpan(GetElementCell());Table_AddRowSpan(currentcell);HandleTableChanged()};function InitDesignTableCells(){for(var _0x26B9E=0;_0x26B9E< tabledesign[_$_3684[12]][_$_3684[13]];_0x26B9E++){var _0x2AF4E=tabledesign[_$_3684[12]][_0x26B9E];for(var _0x26633=0;_0x26633< _0x2AF4E[_$_3684[14]][_$_3684[13]];_0x26633++){var _0x26ECF=_0x2AF4E[_$_3684[14]][_0x26633];_0x26ECF[_$_3684[70]](_$_3684[53]);_0x26ECF[_$_3684[70]](_$_3684[54]);_0x26ECF[_$_3684[53]]= _$_3684[56];_0x26ECF[_$_3684[54]]= _$_3684[56];_0x26ECF[_$_3684[71]]= _$_3684[72];_0x26ECF[_$_3684[73]]= cell_mouseover;_0x26ECF[_$_3684[74]]= cell_mouseout;_0x26ECF[_$_3684[69]]= cell_click;_0x26ECF[_$_3684[75]]= cell_dblclick}}}function Element_Contains(element,_0x27B47){if(!Browser_IsOpera()){if(element[_$_3684[76]]){return element[_$_3684[76]](_0x27B47)}};for(;_0x27B47!= null;_0x27B47= _0x27B47[_$_3684[2]]){if(element== _0x27B47){return true}};return false}function HandleTableChanged(){if(!Element_Contains(tabledesign,currentcell)){SetCurrentCell(tabledesign[_$_3684[12]](0)[_$_3684[14]](0))};InitDesignTableCells();UpdateButtonStates();editor[_$_3684[78]](_$_3684[77]);editor[_$_3684[78]](_$_3684[77])}function cell_mouseover(){var _0x26ECF=this;_0x26ECF[_$_3684[49]][_$_3684[79]]= _$_3684[80]}function cell_mouseout(){var _0x26ECF=this;_0x26ECF[_$_3684[49]][_$_3684[79]]= _$_3684[72]}function cell_click(){var _0x26ECF=this;SetCurrentCell(_0x26ECF)}function cell_dblclick(){var _0x26ECF=this;SetCurrentCell(_0x26ECF)}btn_cell_editcell[_$_3684[69]]= function btn_cell_editcell_onclick(){var _0x26ECF=GetElementCell();var _0x262EF=editor[_$_3684[81]](_0x26ECF[_$_3684[17]],window);if(_0x262EF!= null&& _0x262EF!== false){_0x26ECF[_$_3684[17]]= _0x262EF}};btn_cell_dialog[_$_3684[69]]= function btn_cell_dialog_onclick(){editor[_$_3684[82]](window);editor[_$_3684[83]](GetElementCell())};btn_row_dialog[_$_3684[69]]= function btn_row_dialog_onclick(){editor[_$_3684[82]](window);editor[_$_3684[83]](GetElementCell()[_$_3684[2]])};btn_row_dialog[_$_3684[49]][_$_3684[48]]= btn_cell_dialog[_$_3684[49]][_$_3684[48]]= _$_3684[50];var currentcell=null;function GetElementCell(){if(currentcell== null){return null};return element[_$_3684[12]][currentcell[_$_3684[2]][_$_3684[10]]][_$_3684[14]][currentcell[_$_3684[15]]]}function SetCurrentCell(_0x26ECF){if(currentcell!= null){if(Browser_IsWinIE()){currentcell[_$_3684[85]][_$_3684[84]]= _$_3684[56]}else {currentcell[_$_3684[49]][_$_3684[84]]= _$_3684[56]}};currentcell= _0x26ECF;if(Browser_IsWinIE()){currentcell[_$_3684[85]][_$_3684[84]]= _$_3684[86]}else {currentcell[_$_3684[49]][_$_3684[84]]= _$_3684[86]};UpdateButtonStates();SyncToViewInternal()}function UpdateButtonStates(){subcolspan[_$_3684[51]]=  !Table_CanSubColSpan(currentcell);addcolspan[_$_3684[51]]=  !Table_CanAddColSpan(currentcell);subrowspan[_$_3684[51]]=  !Table_CanSubRowSpan(currentcell);addrowspan[_$_3684[51]]=  !Table_CanAddRowSpan(currentcell);subrows[_$_3684[51]]= Table_GetRowCount(currentcell)< 2;subcolumns[_$_3684[51]]= Table_GetColCount(currentcell)< 2}function ApplyTable(src,_0x26D8C){if(Browser_IsSafari()){_0x26D8C[_$_3684[49]][_$_3684[54]]= _$_3684[56]};for(var _0x26B9E=_0x26D8C[_$_3684[12]][_$_3684[13]]- 1;_0x26B9E>= 0;_0x26B9E--){_0x26D8C[_$_3684[12]][_0x26B9E][_$_3684[1]](true)};for(var _0x26B9E=0;_0x26B9E< src[_$_3684[12]][_$_3684[13]];_0x26B9E++){var _0x26DD8=src[_$_3684[12]][_0x26B9E];var _0x26DB2;if(!Browser_IsSafari()){_0x26DB2= _0x26D8C[_$_3684[27]](-1)}else {var _0x26DEB=document[_$_3684[29]](_$_3684[28]);_0x26D8C[_$_3684[30]](_0x26DEB);_0x26DB2= _0x26DEB[_$_3684[27]](-1)};_0x26DB2[_$_3684[49]][_$_3684[84]]= _0x26DD8[_$_3684[49]][_$_3684[84]];for(var _0x26633=0;_0x26633< _0x26DD8[_$_3684[14]][_$_3684[13]];_0x26633++){var _0x26DC5=_0x26DD8[_$_3684[14]][_0x26633];var _0x26D9F=_0x26DB2[_$_3684[16]](-1);_0x26D9F[_$_3684[0]]= _0x26DC5[_$_3684[0]];_0x26D9F[_$_3684[6]]= _0x26DC5[_$_3684[6]];_0x26D9F[_$_3684[49]][_$_3684[84]]= _0x26DC5[_$_3684[49]][_$_3684[84]];if(Browser_IsOpera()){_0x26D9F[_$_3684[17]]= _$_3684[18]}else {if(Browser_IsGecko()|| Browser_IsSafari()){_0x26D9F[_$_3684[17]]= _$_3684[19]}}}}}window[_$_3684[87]]= function window_onload(){ApplyTable(element,tabledesign);InitDesignTableCells();SetCurrentCell(tabledesign[_$_3684[12]][0][_$_3684[14]][0])};function updateList(){}var oPopup;if(Browser_IsWinIE()){oPopup= window[_$_3684[88]]()}else {richDropDown[_$_3684[49]][_$_3684[48]]= _$_3684[50]};function selectTemplates(){if(Browser_IsWinIE()){oPopup[_$_3684[90]][_$_3684[89]][_$_3684[17]]= list_Templates[_$_3684[17]];oPopup[_$_3684[90]][_$_3684[89]][_$_3684[49]][_$_3684[91]]= _$_3684[92];oPopup[_$_3684[93]](0,32,380,220,richDropDown)}}function hidePopup(){if(Browser_IsWinIE()){if(oPopup){if(oPopup[_$_3684[94]]){oPopup[_$_3684[95]]()}}}}