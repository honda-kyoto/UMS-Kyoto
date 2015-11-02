function changePlasefolder(){
	var formdata = document.getElementById('web_form');
	if(formdata.selectbox.options[formdata.selectbox.selectedIndex].value == "create"){
		document.getElementById("form").style.visibility="visible";
		document.getElementById("server_info").style.visibility="visible";
		document.getElementById("create_option").style.visibility="visible";
		document.getElementById('textarea').placeholder = "アカウント名,職員番号,ﾌﾙﾈｰﾑ(漢字),姓(ローマ字),名(ローマ字),ﾌﾙﾈｰﾑ(ｶﾀｶﾅ),所属部署(漢字),所属部署(ローマ字),連絡先電話番号,職種(ローマ字1文字),役職(備考) のCSV形式で入力してください。";

	}else if(formdata.selectbox.options[formdata.selectbox.selectedIndex].value == "check"){
		document.getElementById("form").style.visibility="visible";
		document.getElementById("server_info").style.visibility="visible";
		document.getElementById("create_option").style.visibility="hidden";
		document.getElementById('textarea').placeholder = "アカウント名を入力してください。";

	}else if(formdata.selectbox.options[formdata.selectbox.selectedIndex].value == "liststop"){
		document.getElementById("form").style.visibility="visible";
		document.getElementById("server_info").style.visibility="visible";
		document.getElementById("create_option").style.visibility="hidden";
		document.getElementById('textarea').placeholder = "アカウント名を入力してください。";

	}else{
		document.getElementById("form").style.visibility="hidden";
		document.getElementById("server_info").style.visibility="hidden";
		document.getElementById("create_option").style.visibility="hidden";
		document.getElementById('textarea').placeholder = "アカウント名を入力してください。";
	}
}

function showMl_detail(id){
	var id_name = 'ml_list'+id;
	var elem = document.getElementById(id_name);
	elem.style.display="block";
}
function hiddenMl_detail(id){
	var id_name = 'ml_list'+id;
	document.getElementById(id_name).style.display="none";
}