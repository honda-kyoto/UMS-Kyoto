jQuery.noConflict();

var g_InitialList = "";
var g_strParamList = "";

// jQueryの初期化処理？
jQuery( document ).ready(
	function()
	{
		var strTmpList = jQuery('.listTab_TB').tableDnDSerialize();

		// 最初のテーブル項目の並び順を配列で保持する
		g_InitialList = SplitParams( strTmpList );
	}
);

jQuery(function( $ )
{
	$(".listTab").tableDnD(
		{
			onDragClass: "nowDragColor",
			onDrop: function( table, row ) // Dropした時の動作
			{
				// 元々の並びと同じかチェック
				var arTmp = SplitParams( $('.listTab_TB').tableDnDSerialize() );

				var flg = false;
				for( i=0; i<arTmp.length; i++ )
				{
					// グローバルに保持している配列と現在の並び順のチェック
					if( g_InitialList[i] != arTmp[i] )
					{
						// 並び順が違う場合
						flg = true;
						break;
					}
				}

				// 並び順のフラグチェック
				if( !flg )
				{
					// D&Dしたが、並び順が変更されていない場合
					return;
				}
				else
				{
					// D&Dした結果、並び順が変更された場合

					// tbody部のidをパラメーター化
					// XX[]=Y&XX[]=Y・・・
					g_strParamList = $('.listTab_TB').tableDnDSerialize();
					// モードを追加
					g_strParamList += '&mode=dispnum';

					// NowLoadingの表示
					SwitchTable( false );
					// 表示順更新
					new Ajax.Request('<?php echo $_SERVER['SCRIPT_NAME'] ?>',
									 {asynchronous: true,
										evalScripts: true,
										onSuccess: function (request)
										{
											var ret = request.responseText;
											LineRefresh( ret );
										},
										parameters: g_strParamList
									 });
				}
			}
		}
	);
});

// D&D後のテーブル内の行項目のCSSクラスの書き換え
function LineRefresh( responseText )
{
	if( responseText == 1 )
	{
		// 並び替え成功の場合
		// パラメーターを分割
		arSplit = g_strParamList.split( "&" );
		// 分割後の配列の最後尾はmodeなので削除
		arSplit.pop();

		for( i=0; i<arSplit.length; i++ )
		{
			// 分割した値をさらに分割し、ID部のみ取得
			arTmp = arSplit[i].split( "=" );
			if( ( i % 2 ) > 0 )
			{
				document.getElementById( arTmp[1] ).className = "onDragClass";
			}
			else
			{
				document.getElementById( arTmp[1] ).className = "onDragClass";
			}
		}
		// 行項目の参照クラスの更新が終わったのでnowLoadを不可視化し、テーブル項目を可視化する
		SwitchTable( true );

		// 並び順を更新
		var strTmpList = jQuery('.listTab_TB').tableDnDSerialize();
		g_InitialList = SplitParams( strTmpList );
	}
	else
	{
		// 並び替えエラーの場合
		document.getElementById( "errMsgArea" ).innerHTML = "並び替えに失敗しました。";
		// nowLoadを非表示にする
		document.getElementById( "nowLoad" ).className = "AjaxTableDisable";
	}
	return;
}

// テーブル項目の可視化/不可視化切り替え、及びnowLoadの不可視化/可視化切り替え
function SwitchTable ( bSwitch ) {
	if( bSwitch )
	{
		document.getElementById( "listTab" ).className = "listTab";
		document.getElementById( "nowLoad" ).className = "AjaxTableDisable";
		document.getElementById( "editAll1" ).style.display = "";
		document.getElementById( "editAll2" ).style.display = "";
	}
	else
	{
		document.getElementById( "listTab" ).className = " AjaxTableDisable";
		document.getElementById( "nowLoad" ).className = "AjaxTableEnable";
		document.getElementById( "editAll1" ).style.display = "none";
		document.getElementById( "editAll2" ).style.display = "none";
	}
}

// 渡された文字列を&と=によって分割し、配列で返す
function SplitParams( strList )
{
	arSplit = strList.split( "&" );
	arId = new Array( arSplit.length );
	for( i=0; i<arSplit.length; i++ )
	{
		arTmp = arSplit[i].split( "=" );
		arId[i] = arTmp[1];
	}
	return arId;
}
