<script src="js/prototype.js"></script>
<script type="text/javascript" src="js/jkl-calendar.js" charset="UTF-8"></script>
<script type="text/javascript">
<!--

var cal_sd = new JKL.Calendar("caldiv_sd","mainForm","start_date");
cal_sd.setStyle( "frame_color", "#3333CC" );
cal_sd.setStyle( "typestr", "yyyy/mm/dd" );
var cal_ed = new JKL.Calendar("caldiv_ed","mainForm","end_date");
cal_ed.setStyle( "frame_color", "#3333CC" );
cal_ed.setStyle( "typestr", "yyyy/mm/dd" );

function outputData()
{
    document.getElementById( "searchTab" ).className = " AjaxTableDisable";
    document.getElementById( "nowLoad" ).className = "AjaxTableEnable";
    setErrMsg("");

    var start_date =  document.getElementById( "start_date" ).value;
    var end_date =  document.getElementById( "end_date" ).value;

    new Ajax.Request('ajax/salaryDataExport.php',
					{
						asynchronous: true,
						evalScripts: true,
						onComplete: function(request) {
							if (request.responseText != '')
							{
								var aryResult = request.responseText.split("|");
								if (aryResult[0] == "1")
								{

									document.getElementById( "searchTab" ).className = "searchTab";
									document.getElementById( "nowLoad" ).className = "AjaxTableDisable";
									downloadData(aryResult[1]);
								}
								else
								{
									document.getElementById( "searchTab" ).className = "searchTab";
									document.getElementById( "nowLoad" ).className = "AjaxTableDisable";
									setErrMsg(aryResult[1]);
								}
							}
							else
							{
								alert("処理に失敗しました");
							}
						},
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'start_date='+encodeURIComponent(start_date) + '&end_date='+encodeURIComponent(end_date)
					});
}

function downloadData(file)
{
	document.getElementById('file_name').value = file;
	formSubmit('download');
}

function setErrMsg(msg)
{
	document.getElementById( "errMsgArea" ).innerHTML = msg;
}

//-->
</script>
