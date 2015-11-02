///////////////////////////////////2015/01/12 honda 追加/////////////////////////////////////////
///////////////////////////////////2015/09/29  (J6)(21)追加//////////////////////////////////////
///////////////////////////////////2015/10/26  カードタグ判別エラーチェック追加//////////////////////
window.onload = function(){
		/////////////////////////////////所属情報　1　/////////////////////////////////
		var chkObj_D1		=	document.getElementById("permission_25");		//第一臨床研究棟地階（分子イメージング集中研究センター）（D1）
		var chkObj_D2		=	document.getElementById("permission_26");		//第一臨床研究棟地階（D2）＊申請は別枠
		var chkObj_seki_s	=	document.getElementById("permission_6");		//積貞棟8階（特別室SS）（積S）
		var chkObj_seki_a	=	document.getElementById("permission_7");		//積貞棟6階（特別室SA）（積A)
		var chkObj_seki_g	=	document.getElementById("permission_4");		//積貞棟1階（抗がん剤取り扱い室）(積G)
		var chkObj_seki_k	=	document.getElementById("permission_5");		//積貞棟地階（給食部門）(積K)
		var chkObj_seki_h	=	document.getElementById("permission_36");		//積貞棟地階（給食部門）（積ｽﾀｯﾌ・積H）＊配膳スタッフ用
		var chkObj_seki_0	=	document.getElementById("permission_3");		//積貞棟地階～8階（積0）
		var chkObj_sen_c	=	document.getElementById("permission_27");		//先端
		var chkObj_sen_e	=	document.getElementById("permission_28");		//EVのみ　＊申請は別枠

		/////////////////////////////////所属情報　2　/////////////////////////////////
		var chkObj_T1		=	document.getElementById("permission_24");		//臨床研究総合センター（T1）
		var chkObj_H1		=	document.getElementById("permission_19");		//中央診療棟１階（放射線部インフォメーション)（H1）
		var chkObj_H2		=	document.getElementById("permission_20");		//中央診療棟１階（放射線部総合画像診断室)（H2）
		var chkObj_8_13		=	document.getElementById("permission_1");		//旧産婦人科病棟（8,13）
		var chkObj_10_11	=	document.getElementById("permission_2");		//外来診療棟　地階～４階、１階東（10,11）
		var chkObj_standard	=	document.getElementById("permission_37");		//標準
		var chkObj_21		=	document.getElementById("permission_39");		//外来診療棟４階（高度生殖医療センター）

		/////////////////////////////////所属情報　3　/////////////////////////////////
		var chkObj_R_m		=	document.getElementById("permission_29");		//R地下ロッカーM（Rm）					//////カードキーのみ//////
		var chkObj_R_f		=	document.getElementById("permission_30");		//R地下ロッカーF（Rf）					//////カードキーのみ//////
		var chkObj_R1_R3	=	document.getElementById("permission_16");		//和進会館2階（卒後臨床研究室）(R1-R3)	//////カードキーのみ//////
		var chkObj_Do_1		=	document.getElementById("permission_31");		//看護師寮(北部)(寮1)
		var chkObj_Do_2		=	document.getElementById("permission_32");		//看護師寮(白眉)(寮2)
		var chkObj_Do_3		=	document.getElementById("permission_33");		//看護師寮(瑞穂)(寮3)
		var chkObj_Do_4		=	document.getElementById("permission_34");		//看護師寮(看護師寮マスター)(寮4)
		var chkObj_S1		=	document.getElementById("permission_11");		//中央診療施設棟４階（ハイブリッド手術室）
		var chkObj_S2		=	document.getElementById("permission_12");		//中央診療施設棟３階（手術部）
		var chkObj_S3		=	document.getElementById("permission_13");		//中央診療施設棟４階（麻酔科）
		var chkObj_M1		=	document.getElementById("permission_9");		//外来診療棟地階（北側：MEセンター）
		var chkObj_M2		=	document.getElementById("permission_10");		//外来診療棟地階（南側：放射線部）

		/////////////////////////////////所属情報　4　処理/////////////////////////////////
		var chkObj_J1_J2	=	document.getElementById("permission_21");		//南病棟地階（医療情報部電算機室）
		var chkObj_J3		=	document.getElementById("permission_22");		//南病棟地階（KINGサーバー室）
		var chkObj_J4_J5	=	document.getElementById("permission_23");		//外来診療棟地階（病歴管理室）
		var chkObj_K1_K3	=	document.getElementById("permission_35");		//カードオプション（共同機器研究室）
		var chkObj_Y1		=	document.getElementById("permission_17");		//外来診療棟地階（薬剤部パスボックス）
		var chkObj_Y1_Y4	=	document.getElementById("permission_18");		//外来診療棟地階（薬剤部）
		var chkObj_N1		=	document.getElementById("permission_14");		//西病棟（中玄関のみ）					//////カードキーのみ//////
		var chkObj_N1_N4	=	document.getElementById("permission_15");		//西病棟								//////カードキーのみ//////
		var chkObj_J6		=	document.getElementById("permission_40");		//中央診療棟２階（診療情報業務掛）（J6）

		var num_1			=	document.getElementById("belong_info_1").value;
		var num_2			=	document.getElementById("belong_info_2").value;
		var num_3			=	document.getElementById("belong_info_3").value;
		var num_4			=	document.getElementById("belong_info_4").value;

		//////////////2015/10/26 カードタグ判別エラーチェック追加////////////
		var chkObj_card_type_1		=	document.getElementById("card_type_1");		//カードキー
		var chkObj_card_type_2		=	document.getElementById("card_type_2");		//タグキー

/////////////////////所属をチェックボックスへ反映STAR//////////////

		var num_all = [num_1,num_2,num_3,num_4];
	
		for(var i = 0; i < num_all.length; i++){
			if(num_all[i] === "99999999"){
				document.getElementById("permission_38").checked = "checked";
				break;
			}else{
				if(num_all[i].length === 10){
					num_all[i] = num_all[i].substring(2, 10);
				}
				//分割処理
				num_all[i].split;

				if(num_all[i] != ""){
					
					if(num_all[i][0] === "9"){
						//ALL判定
						document.getElementById("permission_38").checked = "checked";
						break;
					}
	
					if(num_all[i][1] === "2"){
						//北2階判定
						document.getElementById("permission_8").checked = "checked";
					}		

					switch (i){	
					case 0:
						switch (num_all[i][4]) {
							case "0":
								chkObj_D1.checked = "";
								chkObj_D2.checked = "";
								break;
							case "1":
								//第一臨床研究棟地階（分子イメージング集中研究センター）（D1）
								chkObj_D1.checked = "checked";
								break;
							case "2":
								//第一臨床研究棟地階（D2）＊申請は別枠
								chkObj_D2.checked = "checked";
								break;
							case "9":
								chkObj_D1.checked = "checked";
								chkObj_D2.checked = "checked";
								break;
						}

						switch (num_all[i][5]) {
							case "0":
								chkObj_seki_s.checked = "";
								chkObj_seki_a.checked = "";
								break;
							case "1":
								//積貞棟8階（特別室SS）（積S)
								chkObj_seki_s.checked = "checked";
								break;
							case "2":
								//積貞棟8階（特別室SA）（積A)
								chkObj_seki_a.checked = "checked";
								break;
							case "9":
								chkObj_seki_s.checked = "checked";
								chkObj_seki_a.checked = "checked";
							break;
						}
					
						switch (num_all[i][6]) {
							case "0":
								chkObj_seki_g.checked = "";
								chkObj_seki_k.checked = "";
								break;
							case "1":
								//積貞棟1階（抗がん剤取り扱い室）(積G)
								chkObj_seki_g.checked = "checked";
								break;
							case "2":
								//積貞棟地階（給食部門）(積K)
								chkObj_seki_k.checked = "checked";
								break;
							
								case "9":
								chkObj_seki_g.checked = "checked";
								chkObj_seki_k.checked = "checked";
							/*
								alert("(積K/積G)設定 チェックを見直してください。");
							*/
							break;
						}

						switch (num_all[i][7]) {
							case "0":
								chkObj_seki_0.checked = "";
								chkObj_sen_c.checked = "";
								chkObj_sen_e.checked = "";
								chkObj_seki_h.checked = "";
								break;
							case "1":
								//積貞棟地階～8階（積0
								chkObj_seki_0.checked = "checked";
								break;
							case "2":
								//先端
								chkObj_sen_c.checked = "checked";
								break;
							case "3":
								chkObj_sen_e.checked = "checked";
								break;
							case "4":
								chkObj_sen_c.checked = "checked";
								chkObj_sen_e.checked = "checked";
								break;
							case "5":
								chkObj_seki_h.checked = "checked";
								break;
							case "9":
								chkObj_seki_0.checked = "checked";
								chkObj_sen_c.checked = "checked";
								chkObj_sen_e.checked = "checked";
							break;
						}
					break;

					case 1:
						switch (num_all[i][4]) {
							case "0":
								chkObj_21.checked = "";
								break;
							case "1":
								//外来診療棟４階（高度生殖医療センター）
								chkObj_21.checked = "checked";
								break;
						}
						switch (num_all[i][5]) {
							case "0":
								chkObj_T1.checked = "";
								break;
							case "1":
								//臨床研究総合センター（T1）
								chkObj_T1.checked = "checked";
								break;
						}
						
						switch (num_all[i][6]) {
							case "0":
								chkObj_H1.checked = "";
								chkObj_H2.checked = "";
								break;
							case "1":
								//中央診療棟１階（放射線部インフォメーション)（H1)
								chkObj_H1.checked = "checked";
								break;
							case "2":
								//中央診療棟１階（放射線部総合画像診断室)（H2）
								chkObj_H2.checked = "checked";
								break;
							case "9":
								chkObj_H1.checked = "checked";
								chkObj_H2.checked = "checked";
								break;
						}

						switch (num_all[i][7]) {
							case "0":
								chkObj_standard.checked = "";
								chkObj_8_13.checked = "";
								chkObj_10_11.checked = "";
								break;
							case "1":
								//旧産婦人科病棟（8,13）
								chkObj_8_13.checked = "checked";
								break;
							case "2":
								//外来診療棟　地階～４階、１階東（10,11）
								chkObj_10_11.checked = "checked";
								break;
							case "3":
								//標準
								chkObj_standard.checked = "checked";
								break;
							case "9":
								chkObj_8_13.checked = "checked";
								chkObj_10_11.checked = "checked";
								chkObj_standard.checked = "checked";
								break;
						}
					break;


					case 2:
						switch (num_all[i][4]) {
							case "0":
								chkObj_R_m.checked = "";
								chkObj_R_f.checked = "";
								break;
							case "1":
								//R地下ロッカーM（Rm）
								chkObj_R_m.checked = "checked";
								break;
							case "2":
								//R地下ロッカーF（Rf）
								chkObj_R_f.checked = "checked";
								break;
							case "9":
								alert("Rロッカー（男女）設定 チェックを見直してください。");
							break;
						}

						switch (num_all[i][5]) {
							case "0":
								chkObj_R1_R3.checked = "";
								chkObj_Do_1.checked = "";
								chkObj_Do_2.checked = "";
								chkObj_Do_3.checked = "";	
								chkObj_Do_4.checked = "";	
								break;
							case "1":
								//和進会館2階（卒後臨床研究室）(R1-R3)
								chkObj_R1_R3.checked = "checked";
								break;
							case "2":
								//看護師寮(瑞穂)(寮3)
								chkObj_Do_3.checked = "checked";
								break;
							case "3":
								//看護師寮(北部)(寮1)
								chkObj_Do_1.checked = "checked";
								break;
							case "4":
								//看護師寮(白眉)(寮2)
								chkObj_Do_2.checked = "checked";
								break;
							case "5":
								//看護師寮(看護師寮マスター)(寮4)
								chkObj_Do_4.checked = "checked";
								break;
							case "9":
								alert("看護師寮/卒後臨床研究室の設定を見直してください。");
								break;
						}
					
						switch (num_all[i][6]) {
							case "0":
								chkObj_S1.checked = "";
								chkObj_S2.checked = "";
								chkObj_S3.checked = "";
								break;
							case "1":
								//中央診療施設棟4階（ハイブリッド手術室）
								chkObj_S1.checked = "checked";
								break;
							case "2":
								//中央診療施設棟3階（手術部）
								chkObj_S2.checked = "checked";
								break;
							case "3":
								//中央診療施設棟4階（麻酔科）
								chkObj_S3.checked = "checked";
								break;
							case "4":
								chkObj_S1.checked = "checked";
								chkObj_S2.checked = "checked";
								break;
							case "5":
								chkObj_S1.checked = "checked";
								chkObj_S3.checked = "checked";
								break;
							case "6":
								chkObj_S2.checked = "checked";
								chkObj_S3.checked = "checked";
								break;
							case "9":
								chkObj_S1.checked = "checked";
								chkObj_S2.checked = "checked";
								chkObj_S3.checked = "checked";
							break;
						}

						switch (num_all[i][7]) {
							case "0":
								chkObj_M1.checked = "";
								chkObj_M2.checked = "";
								break;
							case "1":
								//外来診療棟地階（北側：MEセンター）
								chkObj_M1.checked = "checked";
								break;
							case "2":
								//外来診療棟地階（南側：放射線部）
								chkObj_M2.checked = "checked";
								break;
							case "9":
								chkObj_M1.checked = "checked";
								chkObj_M2.checked = "checked";
								break;
						}
						break;

					case 3:
						switch (num_all[i][4]) {
							case "0":
								chkObj_J1_J2.checked = "";
								chkObj_J3.checked = "";
								chkObj_J4_J5.checked = "";
								break;
							case "1":
								//南病棟地階（医療情報部電算機室）
								chkObj_J1_J2.checked = "checked";
								break;
							case "2":
								//南病棟地階（KINGサーバー室）
								chkObj_J3.checked = "checked";
								break;
							case "3":
								//外来診療棟地階（病歴管理室）
								chkObj_J4_J5.checked = "checked";
								break;
							case "4":
								//南病棟地階（医療情報部電算機室）
								chkObj_J1_J2.checked = "checked";
								//南病棟地階（KINGサーバー室）
								chkObj_J3.checked = "checked";
								break;
							case "5":
								//南病棟地階（医療情報部電算機室）
								chkObj_J1_J2.checked = "checked";
								//外来診療棟地階（病歴管理室）
								chkObj_J4_J5.checked = "checked";
								break;
							case "6":
								//南病棟地階（KINGサーバー室）
								chkObj_J3.checked = "checked";
								//外来診療棟地階（病歴管理室）
								chkObj_J4_J5.checked = "checked";
								break;
							case "9":
								//南病棟地階（医療情報部電算機室）					
								chkObj_J1_J2.checked = "checked";
								//南病棟地階（KINGサーバー室）
								chkObj_J3.checked = "checked";
								//外来診療棟地階（病歴管理室）
								chkObj_J4_J5.checked = "checked";
								break;
						}

						switch (num_all[i][5]) {
							case "0":
								chkObj_K1_K3.checked = "";
								chkObj_J6.checked = "";
								break;
							case "1":
								//カードオプション（共同機器研究室）
								chkObj_K1_K3.checked = "checked";
								break;
							case "2":
								//中央診療棟２階（診療情報業務掛）
								chkObj_J6.checked = "checked";
								break;
							case "9":
								//カードオプション（共同機器研究室）/ 中央診療棟２階（診療情報業務掛）
								chkObj_K1_K3.checked = "checked";
								chkObj_J6.checked = "checked";
								break;
						}
					
						switch (num_all[i][6]) {
							case "0":
								chkObj_Y1.checked = "";
								chkObj_Y1_Y4.checked = "";
								break;
							case "1":
								//外来診療棟地階（薬剤部パスボックス）		
								chkObj_Y1.checked = "checked";
								break;
							case "2":
								//外来診療等（薬剤部）
								chkObj_Y1_Y4.checked = "checked";
								break;
							case "9":
								//外来診療等（パスボックス）	
								chkObj_Y1.checked = "checked";
								//外来診療等（薬剤部）
								chkObj_Y1_Y4.checked = "checked";
								break;
						}

						switch (num_all[i][7]) {
							case "0":
								//西病棟（中央玄関のみ）
								chkObj_N1.checked = "";
								//西病棟
								chkObj_N1_N4.checked = "";
								break;
							case "1":
								//西病棟（中央玄関のみ）
								chkObj_N1.checked = "checked";
								break;
							case "2":
								//西病棟
								chkObj_N1_N4.checked = "checked";
								break;
							case "9":
								//西病棟（中央玄関のみ）
								chkObj_N1.checked = "checked";
								//西病棟
								chkObj_N1_N4.checked = "checked";
								break;
						}
						break;
					}//switch閉じる
				}//所属判定閉じる
			}//else
		}//for閉じる

/////////////////////所属をチェックボックスへ反映END///////////////////////////////////////////



/////////////////////チェックボックスを所属情報へ反映START///////////////////////////////////////////
	document.getElementById("checkButton").onclick = function(){
		var affi_10000000	=	10000000;
	
		var chkObj_card_type	=	document.getElementById("card_type_1");//card_type取得

		//ALL権限
		if(document.getElementById("permission_38").checked == true){
			var affi_01 = 99999999;
			//document.getElementsByName("belong_info_1").value;
			//document.getElementById("belong_info_1").value = affi_01;
			//document.getElementsByName("belong_info_1").valueL = affi_01;
			document.getElementsByName("belong_info_1")[0].value = affi_01;
			return false;
		}	
		
		//所属　北2階
		if(document.getElementById("permission_8").checked == true){
			affi_North_2	=	2000000;
		}else{
			affi_North_2	=	0;
		}

////////////////////////////////////////カードキーのみ発行可設定////////////////////////////////////////

		var ffmm_name_Card	=	[chkObj_N1.checked,chkObj_N1_N4.checked,chkObj_R1_R3.checked,chkObj_R_m.checked,chkObj_R_f.checked];
		var ffmm_len_Card	=	ffmm_name_Card.length;
	
		var true_len_Card	=	0;
		for (i=0; i<=ffmm_len_Card; i++ ){
			if(ffmm_name_Card[i] == true){
				true_len_Card++ ;
			}
		}

		if(true_len_Card>=1 && chkObj_card_type.checked != true){
			alert("(N1)西病棟（中玄関）\n	(N1-N4)西病棟\n	(R1-R3)和進会館2階（卒後臨床研究室）\n(Rm)R地下ロッカーM (男)\n(Rf)R地下ロッカーF (女)\nはセキュリティーカードキーのみで申請可能です。\n\nセキュリティーカードへチェックを入れてください。");
			chkObj_R1_R3.checked	=	false;
			chkObj_N1.checked		=	false;
			chkObj_N1_N4.checked	=	false;
			chkObj_R_m.checked		=	false;
			chkObj_R_f.checked		=	false;
		}

//////////////2015/10/26 カードタグ判別エラーチェック追加////////////
		var chkObj_key_number		=	document.getElementById("key_number").value;

		if(chkObj_card_type_2.checked == true){
			num_st = new RegExp("T", "i");
			if (!chkObj_key_number.match(num_st)) {
		    	alert("正しいタグ番号を入力してください。");
				document.getElementById("key_number").value = "";
			}
		}else if(chkObj_card_type_1.checked == true){
			num_st = new RegExp("F", "i");
			if (!chkObj_key_number.match(num_st)) {
		    	alert("正しいカード番号を入力してください。");
		    	document.getElementById("key_number").value = "";
			}
		}
////////////////////////////////////////////////////////////////




/////////////////////////////////所属情報　1　処理/////////////////////////////////		
		document.getElementsByName("belong_info_1")[0].value = "";
		//1桁目　積貞棟地階～8階（積0）・積貞棟地階 給食部門/（積ｽﾀｯﾌ） チェック
		var ffmm_name_seki	=	[chkObj_seki_0.checked,chkObj_seki_h.checked];
		var ffmm_len_seki	=	ffmm_name_seki.length;
		var true_len_seki	=	0;
		for (i=0; i<=ffmm_len_seki; i++ ){
			if(ffmm_name_seki[i] == true){
				true_len_seki++ ;
			}
		}
		//1桁目　先端センター チェック		
		var ffmm_name_sen	=	[chkObj_sen_c.checked,chkObj_sen_e.checked];
		var ffmm_len_sen	=	ffmm_name_sen.length;
		var true_len_sen	=	0;
		for (i=0; i<=ffmm_len_sen; i++ ){
			if(ffmm_name_sen[i] == true){
				true_len_sen++ ;
			}
		}

		//4桁目　第一臨床研究棟地階(D1)　チェック
		if (chkObj_D1.checked && chkObj_D2.checked) {
			affi_10000000	=	( parseInt(affi_10000000) + 9000);
		}else if(chkObj_D1.checked){
			affi_10000000	=	( parseInt(affi_10000000) + 1000);
		}else if(chkObj_D2.checked){
			affi_10000000	=	( parseInt(affi_10000000) + 2000);
		}	

		//3桁目　積貞棟8階（特別室SS）・積貞棟6階（特別室SA）　チェック
		if (chkObj_seki_s.checked && chkObj_seki_a.checked) {
			affi_10000000 =( parseInt(affi_10000000) + 900);
		}else if(chkObj_seki_s.checked){
			affi_10000000 =( parseInt(affi_10000000) + 100);
		}else if(chkObj_seki_a.checked){
			affi_10000000 =( parseInt(affi_10000000) + 200);
		}

		//2桁目　積貞棟1階(積G) / 積貞棟地階(積K)　チェック	
/*
		if (chkObj_seki_g.checked && chkObj_seki_k.checked) {
			alert("(積G)積貞棟１階　抗がん剤取り扱い室と\n\n(積K)積貞棟地階　給食部門\n(積H)積貞棟地階　給食部門/積ｽﾀｯﾌ\nは同時に申請できません。\n\nもう一度選択してください。");
			chkObj_seki_g.checked	=	false;
			chkObj_seki_k.checked	=	false;
		}else if(chkObj_seki_g.checked && chkObj_seki_h.checked){
			alert("(積G)積貞棟１階　抗がん剤取り扱い室と\n\n(積K)積貞棟地階　給食部門\n(積H)積貞棟地階　給食部門/積ｽﾀｯﾌ\nは同時に申請できません。\n\nもう一度選択してください。");
			chkObj_seki_g.checked	=	false;
			chkObj_seki_k.checked	=	false;
			chkObj_seki_h.checked	=	false;
		}else if(chkObj_seki_k.checked){
			affi_10000000 =( parseInt(affi_10000000) + 20);
		}else if(chkObj_seki_g.checked){
			affi_10000000 =( parseInt(affi_10000000) + 10);
		}
*/

		/*2015/07/30改修*/
		if(chkObj_seki_g.checked && chkObj_seki_k.checked){
			affi_10000000 =( parseInt(affi_10000000) + 90);
		}else if(chkObj_seki_g.checked){
			affi_10000000 =( parseInt(affi_10000000) + 10);
		}else if(chkObj_seki_k.checked){
			affi_10000000 =( parseInt(affi_10000000) + 20);
		}


		if(true_len_seki>=2){
			affi_10000000 =( parseInt(affi_10000000) + 9);
		
		}else if (chkObj_seki_h.checked && true_len_sen>=1) {
			alert("先端センター\n(積H)積貞棟地階　給食部門/積ｽﾀｯﾌ\nは同時に申請できません。\n\nもう一度選択してください。");
			chkObj_sen_c.checked	=	false;
			chkObj_sen_e.checked	=	false;
			chkObj_seki_h.checked	=	false;
		}else if (true_len_sen>=1 && chkObj_seki_0.checked) {
			affi_10000000 =( parseInt(affi_10000000) + 9);

		}else if(chkObj_seki_h.checked){
			affi_10000000 =( parseInt(affi_10000000) + 5);			//(積H)積貞棟地階　給食部門/積ｽﾀｯﾌ チェック

		}else if (chkObj_sen_c.checked && chkObj_sen_e.checked) {	//1桁目　先端のみ・EVのみ　=　先端・EV チェック
			affi_10000000 =( parseInt(affi_10000000) + 4);

		}else if (chkObj_sen_e.checked) {							//1桁目　EVのみ　チェック
			affi_10000000 =( parseInt(affi_10000000) + 3);

		}else if (chkObj_sen_c.checked) {							//1桁目　先端のみ　チェック
			affi_10000000 =( parseInt(affi_10000000) + 2);

		}else if (chkObj_seki_0.checked) {							//1桁目　積貞棟地階～8階（積0）　チェック
			affi_10000000 =( parseInt(affi_10000000) + 1);

		}
	
		affi_01 = affi_10000000;

		if (affi_01 != 10000000){
			affi_01 = (affi_01 + affi_North_2);
		}else{
			 affi_01 = "";
		}

		document.getElementsByName("belong_info_1")[0].value = affi_01;


/////////////////////////////////所属情報　2　処理/////////////////////////////////
		var affi_20000000	=	20000000;

		//4桁目　外来診療棟４階(高度生殖医療センター)　チェック
		if (chkObj_21.checked) {
			affi_20000000	=	( parseInt(affi_20000000) + 1000);
		}
		//3桁目　臨床研究総合センター　チェック
		if (chkObj_T1.checked) {
			affi_20000000	=	( parseInt(affi_20000000) + 100);
		}
		//2桁目　中央診療棟１階(info) / 中央診療棟１階(診断室)　チェック	
		if (chkObj_H1.checked && chkObj_H2.checked) {
			affi_20000000 =( parseInt(affi_20000000) + 90);
		}else if(chkObj_H1.checked){
			affi_20000000 =( parseInt(affi_20000000) + 10);
		}else if(chkObj_H2.checked){
			affi_20000000 =( parseInt(affi_20000000) + 20);
		}

		//1桁目　旧産婦人科・外来診療棟・標準　チェック
		if (chkObj_8_13.checked && chkObj_10_11.checked && chkObj_standard.checked) {
			affi_20000000 =( parseInt(affi_20000000) + 9);
		//1桁目　旧産婦人科・外来診療棟　チェック
		}else if (chkObj_8_13.checked && chkObj_10_11.checked ) {
			affi_20000000 =( parseInt(affi_20000000) + 9);
		//1桁目　旧産婦人科・標準　チェック
		}else if (chkObj_8_13.checked && chkObj_10_11.checked){
			affi_20000000 =( parseInt(affi_20000000) + 9);
		//1桁目　旧産婦人科・標準　チェック
		}
		else if (chkObj_8_13.checked && chkObj_standard.checked) {
			affi_20000000 =( parseInt(affi_20000000) + 9);
		//1桁目　外来診療棟・標準　チェック
		}else if (chkObj_10_11.checked && chkObj_standard.checked) {
			affi_20000000 =( parseInt(affi_20000000) + 9);
		//1桁目　旧産婦人科　チェック
		}else if (chkObj_8_13.checked) {
			affi_20000000 =( parseInt(affi_20000000) + 1);
		//1桁目　外来診療棟　チェック
		}else if (chkObj_10_11.checked) {
			affi_20000000 =( parseInt(affi_20000000) + 2);
		//1桁目　標準　チェック
		}else if (chkObj_standard.checked) {
			affi_20000000 =( parseInt(affi_20000000) + 3);
		}
	
		affi_02 = affi_20000000;

		if (affi_02 != 20000000){
			affi_02 = (affi_02 + affi_North_2);
		}else{
			 affi_02 = "";
		}

		document.getElementsByName("belong_info_2")[0].value = affi_02;

/////////////////////////////////所属情報　3　処理/////////////////////////////////
		var affi_30000000	=	30000000;

		//4桁目　R地下ロッカーM / R地下ロッカーF チェック
		if (chkObj_R_m.checked && chkObj_R_f.checked) {
			alert("(Rm)地下ロッカーM 男\n(Rf)地下ロッカーF 女　は同時に申請できません。\n\nもう一度選択してください。");
			chkObj_R_m.checked	=	false;
			chkObj_R_f.checked	=	false;
		}else if(chkObj_R_m.checked){
			affi_30000000	=	( parseInt(affi_30000000) + 1000);　//******カードキーのみ******
		}else if(chkObj_R_f.checked){
			affi_30000000	=	( parseInt(affi_30000000) + 2000);　// ******カードキーのみ******
		}
		
		//3桁目　ALL権限禁止桁(9)
		var ffmm_name_R_Do	=	[chkObj_R1_R3.checked,chkObj_Do_1.checked,chkObj_Do_2.checked,chkObj_Do_3.checked,chkObj_Do_4.checked];
		var ffmm_len_R_Do	=	ffmm_name_R_Do.length;
		var true_len_R_Do	=	0;
		for (i=0; i<=ffmm_len_R_Do; i++ ){
			if(ffmm_name_R_Do[i] == true){
				true_len_R_Do++ ;
			}
		}
		//和進会館2階（卒後臨床研究室）看護師寮(北部)(白眉)(瑞穂)(看護師寮マスター)　チェック
		if(true_len_R_Do>=2){
			alert("和進会館２階（卒後臨床研修室)\n看護師寮(北部)(白眉)(瑞穂)(看護師寮マスター)\nは同時に申請できません。\n\nもう一度選択してください。");
			chkObj_R1_R3.checked	=	false;
			chkObj_Do_1.checked		=	false;
			chkObj_Do_2.checked		=	false;
			chkObj_Do_3.checked		=	false;
			chkObj_Do_4.checked		=	false;
		}

		if (chkObj_R1_R3.checked) {
			affi_30000000	=	( parseInt(affi_30000000) + 100);　//////カードキーのみ//////
		}else if(chkObj_Do_1.checked){
			affi_30000000	=	( parseInt(affi_30000000) + 300);//北部
		}else if(chkObj_Do_2.checked){
			affi_30000000	=	( parseInt(affi_30000000) + 400);//白眉
		}else if(chkObj_Do_3.checked){
			affi_30000000	=	( parseInt(affi_30000000) + 200);//瑞穂
		}else if(chkObj_Do_4.checked){
			affi_30000000	=	( parseInt(affi_30000000) + 500);
		}
		
		//2桁目　中央診療施設（ハイブリッド手術室）/（手術部）/（麻酔科）　チェック	
		if (chkObj_S1.checked && chkObj_S2.checked && chkObj_S3.checked) {
			affi_30000000 =( parseInt(affi_30000000) + 90);	//S1S2S3
		}else if(chkObj_S1.checked && chkObj_S2.checked){
			affi_30000000 =( parseInt(affi_30000000) + 40);	//S1S2
		}else if(chkObj_S1.checked && chkObj_S3.checked){
			affi_30000000 =( parseInt(affi_30000000) + 50);	//S1S3
		}else if(chkObj_S2.checked && chkObj_S3.checked){	
			affi_30000000 =( parseInt(affi_30000000) + 60);	//S2S3
		}else if(chkObj_S1.checked){	
			affi_30000000 =( parseInt(affi_30000000) + 10);	//S1
		}else if(chkObj_S2.checked){	
			affi_30000000 =( parseInt(affi_30000000) + 20);	//S2
		}else if(chkObj_S3.checked){	
			affi_30000000 =( parseInt(affi_30000000) + 30);	//S3
		}

		//1桁目　外来診療棟地階（北側：MEセンター）/（南側：放射線部）　チェック
		if (chkObj_M1.checked && chkObj_M2.checked) {
			affi_30000000 =( parseInt(affi_30000000) + 9);
		//1桁目　外来診療棟地階（北側：MEセンター)　チェック
		}else if (chkObj_M1.checked){
			affi_30000000 =( parseInt(affi_30000000) + 1);
		//1桁目　外来診療棟地階（南側：放射線部）　チェック
		}else if (chkObj_M2.checked) {
			affi_30000000 =( parseInt(affi_30000000) + 2);
		}
		affi_03 = affi_30000000;
		if (affi_03 != 30000000){
			affi_03 = (affi_03 + affi_North_2);
		}else{
			 affi_03 = "";
		}

		document.getElementsByName("belong_info_3")[0].value = affi_03;


/////////////////////////////////所属情報　4　処理/////////////////////////////////
		var affi_40000000	=	40000000;

		var ffmm_name_J	=	[chkObj_J1_J2.checked,chkObj_J3.checked,chkObj_J4_J5.checked];
		var ffmm_len_J	=	ffmm_name_J.length;
		var true_len_J	=	0;
		for (i=0; i<=ffmm_len_J; i++ ){
			if(ffmm_name_J[i] == true){
				true_len_J++ ;
			}
		}
		//4桁目　南病棟地階（医療情報部電算機室）/病歴管理室 チェック ＊申請書には（J4J5）有　コード無し。
		if(true_len_J>=3){
			affi_40000000	=	( parseInt(affi_40000000) + 9000);
		}
		else if(chkObj_J3.checked && chkObj_J4_J5.checked){
			affi_40000000	=	( parseInt(affi_40000000) + 6000);
		}
		else if(chkObj_J1_J2.checked && chkObj_J4_J5.checked){
			affi_40000000	=	( parseInt(affi_40000000) + 5000);
		}
		else if(chkObj_J1_J2.checked && chkObj_J3.checked){
			affi_40000000	=	( parseInt(affi_40000000) + 4000);
		}
		else if(chkObj_J4_J5.checked){
			affi_40000000	=	( parseInt(affi_40000000) + 3000);
		}
		else if(chkObj_J3.checked){
			affi_40000000	=	( parseInt(affi_40000000) + 2000);
		}		
		else if(chkObj_J1_J2.checked){
			affi_40000000	=	( parseInt(affi_40000000) + 1000);
		}
		
		//3桁目　カードオプション（共同機器研究室) チェック
		if (chkObj_K1_K3.checked && chkObj_J6.checked) {
			affi_40000000	=	( parseInt(affi_40000000) + 900);
		}else if (chkObj_J6.checked) {
			affi_40000000	=	( parseInt(affi_40000000) + 200);
		}else if(chkObj_K1_K3.checked) {
			affi_40000000	=	( parseInt(affi_40000000) + 100);
		}
		
		//2桁目　外来診療棟（薬剤部パスボックス）/（薬剤部）　チェック	
		if (chkObj_Y1.checked && chkObj_Y1_Y4.checked) {
			affi_40000000 =( parseInt(affi_40000000) + 20);	//Y1-Y4
		}else if(chkObj_Y1.checked){
			affi_40000000 =( parseInt(affi_40000000) + 10);	//Y1
		}else if(chkObj_Y1_Y4.checked){
			affi_40000000 =( parseInt(affi_40000000) + 20);	//Y1-Y4
		}

		//1桁目　西病棟　チェック	//////カードキーのみ//////
		if (chkObj_N1.checked && chkObj_N1_N4.checked) {
			affi_40000000 =( parseInt(affi_40000000) + 2);	//N1-N4
		}else if(chkObj_N1.checked){
			affi_40000000 =( parseInt(affi_40000000) + 1);	//N1
		}else if(chkObj_N1_N4.checked){
			affi_40000000 =( parseInt(affi_40000000) + 2);	//N1-N4
		}

		affi_04 = affi_40000000;
		if (affi_04 != 40000000){
			affi_04 = (affi_04 + affi_North_2);
		}else{
			 affi_04 = "";
		}

		document.getElementsByName("belong_info_4")[0].value = affi_04;

	}
/////////////////////チェックボックスを所属情報へ反映END///////////////////////////////////////////
}

////////////////////////////////////////////////////////////////////////