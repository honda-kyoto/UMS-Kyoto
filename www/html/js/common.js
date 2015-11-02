// JavaScript Document

function formSubmit(mode, action, target)
{
	switch (arguments.length)
	{
		case 1:
			formSubmit1(mode);
			break;
		case 2:
			formSubmit2(mode, action);
			break;
		case 3:
			formSubmit3(mode, action, target);
			break;
		default:
			break;
	}
}

function formSubmit1(mode)
{
	document.mainForm.mode.value = mode;
	document.mainForm.target = '_self';
	document.mainForm.submit();
}

function formSubmit2(mode, action)
{
	document.mainForm.mode.value = mode;
	document.mainForm.action = action;
	document.mainForm.target = '_self';
	document.mainForm.submit();
}

function formSubmit3(mode, action, target)
{
	document.mainForm.mode.value = mode;
	document.mainForm.action = action;
	document.mainForm.target = target;
	document.mainForm.submit();
}

function number_format(value)
{
  return value.toString().replace( /([0-9]+?)(?=(?:[0-9]{3})+$)/g , '$1,');
}

function allCheck(all, chkname)
{
	var len = document.mainForm.elements.length; /* フォームの要素数 */
	var chg = all.checked;

	for (var i = 0 ; i < len ; i++)
	{
		name = document.mainForm.elements[i].name;
		name = name.substring(0,chkname.length);

		if (name == chkname)
		{
			document.mainForm.elements[i].checked = chg;
		}
	}
}

// チェックボックス名を配列形式（name[]）で定義した場合のメソッド
function clickAllCheckBox(allCheckBox, checkElementsName)
{
	var isChecked = allCheckBox.checked;

	if (document.mainForm.elements[checkElementsName].length) {
		for (var i=0; i<document.mainForm.elements[checkElementsName].length; i++) {
			document.mainForm.elements[checkElementsName][i].checked = isChecked;
		}
	} else {
		// チェックボックスが１つのとき（リストが１件のとき）
		document.mainForm.elements[checkElementsName].checked = isChecked;
	}
}

function getCheckCount(chkname)
{
	var len = document.mainForm.elements.length; /* フォームの要素数 */
	var cnt = 0;

	for (var i = 0 ; i < len ; i++)
	{
		name = document.mainForm.elements[i].name;
		name = name.substring(0,chkname.length);

		if (name == chkname && document.mainForm.elements[i].checked)
		{
			cnt++;
		}
	}

	return cnt;
}

function listMax()
{
	formSubmit('max');
}

function turnOver(page)
{
	document.getElementById('page').value = page;
	formSubmit('turn');
}
