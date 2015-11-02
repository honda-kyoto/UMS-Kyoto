            <table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
              <tr>
                <th colspan="6" scope="col">基本情報</th>
              </tr>
              <tr>
                <th width="100" scope="col">職員番号</th>
                <td scope="col"><input name="staff_id" type="text" id="staff_id" size="6" maxlength="6" value="<?php echo $mgr->getRequestData('staff_id') ?>" />&nbsp;<span class="inputRule">(半角数字6桁以内)</span></td>
                <th width="100" scope="col">カードNo.</th>
                <td scope="col" colspan="3">※カード発行する場合は必ず電カル連携情報(メイン)をご入力ください。</td>
              </tr>
              <tr>
                <th width="100">氏名<span class="hissu">*</span></th>
                <td>姓:<input name="kanjisei" type="text" id="kanjisei" size="6" value="<?php echo $mgr->getRequestData('kanjisei') ?>" />
                  名:<input name="kanjimei" type="text" id="kanjimei" size="6" value="<?php echo $mgr->getRequestData('kanjimei') ?>" /></td>
                <th width="100">氏名カナ<span class="hissu">*</span></th>
                <td>姓:<input name="kanasei" type="text" id="kanasei" size="6" value="<?php echo $mgr->getRequestData('kanasei') ?>" />
                  名:<input name="kanamei" type="text" id="kanamei" size="6" value="<?php echo $mgr->getRequestData('kanamei') ?>" /></td>
                <th width="100">氏名英字</th>
                <td>姓:<input name="eijisei" type="text" id="eijisei" size="6" value="<?php echo $mgr->getRequestData('eijisei') ?>" />
                  名:<input name="eijimei" type="text" id="eijimei" size="6" value="<?php echo $mgr->getRequestData('eijimei') ?>" /></td>
              </tr>
              <tr>
                <th width="100">旧姓</th>
                <td><input name="kyusei" type="text" id="kyusei" size="10" value="<?php echo $mgr->getRequestData('kyusei') ?>" /></td>
                <th width="100">性別<span class="hissu">★</span></th>
                <td><select name="sex" id="sex">
                    <option value="">--</option>
                    <?php echo $mgr->getSelectList('sex') ?>
                  </select></td>
                <th width="100">生年月日<span class="hissu">★</span></th>
                <td><select name="birth_year" id="birth_year">
                    <option value="">--</option>
                    <?php echo $mgr->getSelectList('birth_year') ?>
                  </select>
                  年
                  <select name="birth_mon" id="birth_mon">
                    <option value="">--</option>
                    <?php echo $mgr->getSelectList('birth_mon') ?>
                  </select>
                  月
                  <select name="birth_day" id="birth_day">
                    <option value="">--</option>
                    <?php echo $mgr->getSelectList('birth_day') ?>
                  </select>
                  日</td>
              </tr>
              <tr>
                <th>統合ID<span class="hissu">▲</span></th>
                <td colspan="5"><input name="login_id" type="text" id="login_id" size="18" maxlength="20" value="<?php echo $mgr->getRequestData('login_id') ?>" />
                [<a href="javascript:;" onclick="makeLoginId();">自動生成</a>]
                [<a href="javascript:;" onclick="existsLoginId();">重複チェック</a>]
                &nbsp;<span class="inputRule">(ヘボン式ローマ字で「姓．名．規定の英字2文字[<a class="login_id_str" href="#" title="規定の英字2文字|<?php echo join("　", $GLOBALS['rand_tow_chars']) ?>">？</a>]」　例：yamada.taro.hp)</span></td>
              </tr>
              <tr>
                <th>パスワード<span class="hissu">▲</span></th>
<?php if ($mgr->getRequestData('user_id') == "" || $mgr->getRequestData('is_unknown_user')) { ?>
                <td colspan="5">
                  <input name="login_passwd" type="text" id="login_passwd" size="30" value="<?php echo $mgr->getRequestData('login_passwd') ?>" />
                  [<a href="javascript:;" onclick="makePassword('login_passwd');">自動生成</a>]
                  <span class="inputRule">(半角数字、半角英字大文字、半角英字小文字　全て混在　6～20文字)</span>
                </td>
<?php } else { ?>
                <td>
                  <input type="button" value="　　再発行する　　" onclick="reissuePassword('login_passwd');">
                </td>
                <th>ID通知書発行</th>
                <td colspan="3">
                  <input type="button" value="　　NCVCネット統合ID通知書発行　　" onclick="printNcvcID();">
                </td>
<?php } ?>
              </tr>
              <tr>
                <th>メール使用有無</th>
                <td colspan="5"><div class="CheckBoxTab">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><input type="checkbox" name="mail_disused_flg" id="mail_disused_flg" value="1" <?php echo $mgr->getCheckData('mail_disused_flg', '1') ?> onClick="changeAccText(this);" /></td>
                      <td><label for="mail_disused_flg">メールを使用しない</label></td>
                    </tr>
                  </table></div>
                </td>
              </tr>
              <tr>
                <th>メール<br />アカウント</th>
                <td colspan="5"><input name="mail_acc" type="text" id="mail_acc" size="20" maxlength="30" value="<?php echo $mgr->getRequestData('mail_acc') ?>" <?php echo ($mgr->getRequestData('mail_disused_flg') == "1" ? "disabled" : "") ?> />
                  <?php echo USER_MAIL_DOMAIN ?><br /><span class="inputRule">(数字、英字大文字・小文字、-（ハイフン）、_（アンダースコア）、.（ピリオド）　3～30文字)</span></td>
              </tr>
              <tr>
                <th width="100">組織(メイン)<span class="hissu">▲</span></th>
                <td colspan="5">
                  <select name="belong_class_id" id="belong_class_id" onchange="belongClassChange(this.value);" style="width:100px;">
                    <option value="">--大分類--</option>
                    <?php echo $mgr->getSelectList('belong_class_id') ?>
                  </select><div class="floatLeft" id="belongClassJs"></div>
                  <select name="belong_div_id" id="belong_div_id" onchange="belongDivChange(this.value);" style="width:100px;">
                    <option value="">--部門--</option>
                    <?php echo $mgr->getSelectList('belong_div_id', "", $mgr->getRequestData('belong_class_id')) ?>
                  </select><div class="floatLeft" id="belongDivJs"></div>
                  <select name="belong_dep_id" id="belong_dep_id" onchange="belongDepChange(this.value);" style="width:100px;">
                    <option value="">--部--</option>
                    <?php echo $mgr->getSelectList('belong_dep_id', "", $mgr->getRequestData('belong_div_id')) ?>
                  </select><div class="floatLeft" id="belongDepJs"></div>
                  <select name="belong_sec_id" id="belong_sec_id" onchange="belongSecChange(this.value);" style="width:100px;">
                    <option value="">--課・科--</option>
                    <?php echo $mgr->getSelectList('belong_sec_id', "", $mgr->getRequestData('belong_dep_id')) ?>
                  </select><div class="floatLeft" id="belongSecJs"></div>
                  <select name="belong_chg_id" id="belong_chg_id" style="width:100px;">
                    <option value="">--係・室・他--</option>
                    <?php echo $mgr->getSelectList('belong_chg_id', "", $mgr->getRequestData('belong_sec_id')) ?>
                  </select>
                  <select name="job_id" id="job_id" style="width:100px;">
                    <option value="">--職種--</option>
                    <?php echo $mgr->getSelectList('job_id') ?>
                  </select>
                  <select name="post_id" id="post_id" style="width:100px;">
                    <option value="">--役職--</option>
                    <?php echo $mgr->getSelectList('post_id') ?>
                  </select>
                  </td>
              </tr>
              <tr>
                <th width="100">組織(サブ)</th>
                <td colspan="5">
                  <table border="0" cellpadding="0" cellspacing="2">
<?php if (is_array($mgr->request['sub_belong_chg_id'])) { ?>
                    <tbody id="subBelongList">
<?php   foreach ($mgr->request['sub_belong_chg_id'] AS $key => $dummy_id) { ?>
                      <tr>
                        <td align="center">(<?php echo $key ?>)&nbsp;</td>
                        <td>
                          <select name="sub_belong_class_id[<?php echo $key ?>]" id="sub_<?php echo $key ?>_belong_class_id" onchange="belongClassChange(this.value, 'sub_<?php echo $key ?>_');" style="width:100px;">
                            <option value="">--大分類--</option>
                            <?php echo $mgr->getSelectList('sub_belong_class_id', $key) ?>
                          </select><div class="floatLeft" id="sub_<?php echo $key ?>_belongClassJs"></div>
                          <select name="sub_belong_div_id[<?php echo $key ?>]" id="sub_<?php echo $key ?>_belong_div_id" onchange="belongDivChange(this.value, 'sub_<?php echo $key ?>_');" style="width:100px;">
                            <option value="">--部門--</option>
                            <?php echo $mgr->getSelectList('sub_belong_div_id', $key, $mgr->getRequestData('sub_belong_class_id', $key)) ?>
                          </select><div class="floatLeft" id="sub_<?php echo $key ?>_belongDivJs"></div>
                          <select name="sub_belong_dep_id[<?php echo $key ?>]" id="sub_<?php echo $key ?>_belong_dep_id" onchange="belongDepChange(this.value, 'sub_<?php echo $key ?>_');" style="width:100px;">
                            <option value="">--部--</option>
                            <?php echo $mgr->getSelectList('sub_belong_dep_id', $key, $mgr->getRequestData('sub_belong_div_id', $key)) ?>
                          </select><div class="floatLeft" id="sub_<?php echo $key ?>_belongDepJs"></div>
                          <select name="sub_belong_sec_id[<?php echo $key ?>]" id="sub_<?php echo $key ?>_belong_sec_id" onchange="belongSecChange(this.value, 'sub_<?php echo $key ?>_');" style="width:100px;">
                            <option value="">--課・科--</option>
                            <?php echo $mgr->getSelectList('sub_belong_sec_id', $key, $mgr->getRequestData('sub_belong_dep_id', $key)) ?>
                          </select><div class="floatLeft" id="sub_<?php echo $key ?>_belongSecJs"></div>
                          <select name="sub_belong_chg_id[<?php echo $key ?>]" id="sub_<?php echo $key ?>_belong_chg_id" style="width:100px;">
                            <option value="">--係・室・他--</option>
                            <?php echo $mgr->getSelectList('sub_belong_chg_id', $key, $mgr->getRequestData('sub_belong_sec_id', $key)) ?>
                          </select>
                          <select name="sub_job_id[<?php echo $key ?>]" id="sub_<?php echo $key ?>_job_id" style="width:100px;">
                            <option value="">--職種--</option>
                            <?php echo $mgr->getSelectList('sub_job_id', $key, $mgr->getRequestData('sub_job_id', $key)) ?>
                          </select>
                          <select name="sub_post_id[<?php echo $key ?>]" id="sub_<?php echo $key ?>_post_id" style="width:100px;">
                            <option value="">--役職--</option>
                            <?php echo $mgr->getSelectList('sub_post_id', $key, $mgr->getRequestData('sub_post_id', $key)) ?>
                          </select>
                         [<a href="javascript:;" onclick="deleteBelongList(<?php echo $key ?>);">×</a>]
                        </td>
                      </tr>
<?php   } ?>
                    </tbody>
<?php } ?>
                    <tr>
                      <td colspan="2"><img src="image/space.gif" alt="" width="1" height="3" /></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>
                        [<a href="javascript:;" onclick="addBelongList('subBelongList');">組織を追加</a>]
                        <input type="hidden" name="maxBelongKey" id="maxBelongKey" value="<?php echo $key ?>">
                        <div class="floatLeft" id="addBelongListJs"></div>
                      </td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <th width="100">内線</th>
                <td><input name="naisen" type="text" id="naisen" size="10" value="<?php echo $mgr->getRequestData('naisen') ?>" />&nbsp;<span class="inputRule">(半角数字4桁)</span></td>
                <th width="100">PHS番号</th>
                <td><input name="pbno" type="text" id="pbno" size="8" maxlength="8" value="<?php echo $mgr->getRequestData('pbno') ?>" />&nbsp;<span class="inputRule">(半角数字4桁)</span></td>
                <th width="100">常勤／非常勤<span class="hissu">*</span></th>
                <td><select name="joukin_kbn" id="joukin_kbn">
                    <option value="">--</option>
                    <?php echo $mgr->getSelectList('joukin_kbn') ?>
                  </select></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td align="left"><img src="image/space.gif" width="1" height="5" /></td>
        </tr>
        <tr>
          <td><div class="inputComment">※診療グループは診療科までが必須項目となります。</div></td>
        </tr>
<?php if ($mgr->getRequestData('user_id') != "") { ?>
        <tr>
          <td><div class="inputCommentImp">※HISパスワードは再発行する場合のみ入力し、「再発行」ボタンを押してください。「更新」ボタンでは更新されません。</div></td>
        </tr>
<?php } ?>
        <tr>
          <td>
            <input type="hidden" name="his_init" id="his_init" value="<?php echo $mgr->getRequestData('his_init') ?>">
            <table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
              <tr>
                <th colspan="6" scope="col"><div class="CheckBoxTab">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                      <tr>
                        <td width="24"><input type="checkbox" name="his_flg" id="his_flg" value="1" <?php echo $mgr->getCheckData('his_flg', '1') ?> onclick="changeHisFlg(this);" /></td>
                        <td width="166"><label for="his_flg">電カル連携情報(メイン)</label></td>
                        <td><span class="inputRule">※電カル連携する場合はチェックしてください。</span></td>
                        <td align="right">有効開始日<span class="hissu">*</span>：</td>
                        <td width="100"><input name="send_date" type="text" id="send_date" onchange="cal_sdd.getFormValue(); cal_sdd.hide();" onclick="cal_sdd.write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('send_date') ?>" <?php echo ($mgr->getRequestData('his_flg') == "") ? "disabled" : "" ?> /><br /><div id="caldiv_sdd"></div></td>
                      </tr>
                    </table>
                  </div>
                </th>
              </tr>
              <tr>
                <th width="100" scope="col">ログインID<span class="hissu">*</span></th>
                <td scope="col"><input name="staffcode" type="text" id="staffcode" value="<?php echo $mgr->getRequestData('staffcode') ?>" size="10" maxlength="8" <?php echo ($mgr->getRequestData('his_flg') == "") ? "disabled" : "" ?> />
                  <span class="inputRule">(半角数字8桁)</span>
<?php if ($mgr->getRequestData('has_his_data') != "") { ?>
                  <input type="button" id="junhis_btn" value="　　ID通知書発行　　" onclick="printJunhisID('0');">
<?php } ?>
                </td>
                <th width="100">漢字氏名<span class="hissu">*</span></th>
                <td><input name="kanjiname" type="text" id="kanjiname" size="16" value="<?php echo $mgr->getRequestData('kanjiname') ?>" <?php echo ($mgr->getRequestData('his_flg') == "") ? "disabled" : "" ?> /></td>
                <th width="80">カナ氏名<span class="hissu">*</span></th>
                <td scope="col"><input name="kananame" type="text" id="kananame" size="16" value="<?php echo $mgr->getRequestData('kananame') ?>" <?php echo ($mgr->getRequestData('his_flg') == "") ? "disabled" : "" ?> /></td>
              </tr>
              <tr>
                <th>HISパスワード</th>
                <td colspan="5">
<?php if ($mgr->getRequestData('has_his_data') == "") { ?>
                  <input name="password" type="text" id="password" size="30" value="<?php echo $mgr->getRequestData('password') ?>" <?php echo ($mgr->getRequestData('his_flg') == "") ? "disabled" : "" ?> />
                  [<a id="password_create" href="javascript:;" onclick="makePassword('password');" <?php echo ($mgr->getRequestData('his_flg') == "") ? 'style="display:none;"' : "" ?>>自動生成</a><span id="password_text" <?php echo ($mgr->getRequestData('his_flg') == "") ? "" : 'style="display:none;"' ?>>自動生成</span>]
                  <span class="inputRule">(半角英数字　4～10文字)</span>
<?php } else { ?>
                  <input name="password" type="text" id="password" size="20" value="" <?php echo ($mgr->getRequestData('his_flg') == "") ? "disabled" : "" ?> />
                  [<a id="password_create" href="javascript:;" onclick="makePassword('password');" <?php echo ($mgr->getRequestData('his_flg') == "") ? 'style="display:none;"' : "" ?>>自動生成</a><span id="password_text" <?php echo ($mgr->getRequestData('his_flg') == "") ? "" : 'style="display:none;"' ?>>自動生成</span>]
                  <input type="button" id="password_btn" value="　　再発行する　　" onclick="reissuePassword('password', '0');">
                  <span class="inputRule">(半角英数字　4～10文字)</span>
                  <input type="hidden" name="has_his_data" id="has_his_data" value="1">
<?php } ?>
                </td>
              </tr>
              <tr>
                <th>部署<span class="hissu">*</span></th>
                <td>
                  <select name="wardstatus" id="wardstatus" onchange="wardstatusChange(this.value);" style="width:100px;" <?php echo ($mgr->getRequestData('his_flg') == "") ? "disabled" : "" ?>>
                    <option value="">---部署区分---</option>
                    <?php echo $mgr->getSelectList('wardstatus') ?>
                  </select><div class="floatLeft" id="wardstatusJs"></div>
                  <select name="wardcode" id="wardcode" style="width:120px;" <?php echo ($mgr->getRequestData('his_flg') == "") ? "disabled" : "" ?>>
                    <option value="">---部署---</option>
                    <?php echo $mgr->getSelectList('wardcode', "", $mgr->getRequestData('wardstatus')) ?>
                </select></td>
                <th>職種<span class="hissu">*</span></th>
                <td colspan="3">
                  <select name="professionstatus" id="professionstatus" onchange="professionstatusChange(this.value);" style="width:100px;" <?php echo ($mgr->getRequestData('his_flg') == "") ? "disabled" : "" ?>>
                    <option value="">---職種区分---</option>
                    <?php echo $mgr->getSelectList('professionstatus') ?>
                  </select><div class="floatLeft" id="professionstatusJs"></div>
                  <select name="professioncode" id="professioncode" style="width:120px;" <?php echo ($mgr->getRequestData('his_flg') == "") ? "disabled" : "" ?>>
                    <option value="">---職種---</option>
                    <?php echo $mgr->getSelectList('professioncode', "", $mgr->getRequestData('professionstatus')) ?>
                  </select></td>
              </tr>
              <tr>
                <th>役職<span class="hissu">*</span></th>
                <td>
                  <select name="gradecode" id="gradecode" <?php echo ($mgr->getRequestData('his_flg') == "") ? "disabled" : "" ?>>
                    <option value="">----</option>
                    <?php echo $mgr->getSelectList('gradecode') ?>
                  </select></td>
                <th>診療グループ</th>
                <td colspan="3">
                  <select name="deptstatus" id="deptstatus" onchange="deptstatusChange(this.value);" style="width:100px;" <?php echo ($mgr->getRequestData('his_flg') == "") ? "disabled" : "" ?>>
                    <option value="">---科区分---</option>
                    <?php echo $mgr->getSelectList('deptstatus') ?>
                  </select><div class="floatLeft" id="deptstatusJs"></div>
                  <select name="deptcode" id="deptcode" onchange="deptcodeChange(this.value);" style="width:100px;" <?php echo ($mgr->getRequestData('his_flg') == "") ? "disabled" : "" ?>>
                    <option value="">---診療科---</option>
                    <?php echo $mgr->getSelectList('deptcode', "", $mgr->getRequestData('deptstatus')) ?>
                  </select><div class="floatLeft" id="deptcodeJs"></div>
                  <select name="deptgroupcode" id="deptgroupcode" style="width:120px;" <?php echo ($mgr->getRequestData('his_flg') == "") ? "disabled" : "" ?>>
                    <option value="">---グループ---</option>
                    <?php echo $mgr->getSelectList('deptgroupcode', "", $mgr->getRequestData('deptcode')) ?>
                  </select></td>
              </tr>
              <tr>
                <th>予約項目コード</th>
                <td><input name="appcode" type="text" id="appcode" size="10" maxlength="5" value="<?php echo $mgr->getRequestData('appcode') ?>" <?php echo ($mgr->getRequestData('his_flg') == "") ? "disabled" : "" ?> />
                  [<a id="appcode_create" href="javascript:;" onclick="makeAppCode();" <?php echo ($mgr->getRequestData('his_flg') == "") ? 'style="display:none;"' : "" ?>>自動生成</a><span id="appcode_text" <?php echo ($mgr->getRequestData('his_flg') == "") ? "" : 'style="display:none;"' ?>>自動生成</span>] <span class="inputRule">(半角英数字5桁)</span></td>
                <th>有効期間<span class="hissu">*</span></th>
                <td colspan="3"><table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td><input name="validstartdate" type="text" id="validstartdate" onchange="cal_vsd.getFormValue(); cal_vsd.hide();" onclick="cal_vsd.write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('validstartdate') ?>" <?php echo ($mgr->getRequestData('his_flg') == "") ? "disabled" : "" ?> /><br /><div id="caldiv_vsd"></div></td>
                    <td> 　～　 </td>
                    <td><input name="validenddate" type="text" id="validenddate" onchange="cal_ved.getFormValue(); cal_ved.hide();" onclick="cal_ved.write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('validenddate') ?>" <?php echo ($mgr->getRequestData('his_flg') == "") ? "disabled" : "" ?> /><br /><div id="caldiv_ved"></div></td>
                  </tr>
                </table></td>
              </tr>
            </table>
           </td>
        </tr>
        <tbody id="hisDataList">
<?php $key = 0; ?>
<?php if (is_array($mgr->request['sub_his_flg'])) { ?>
<?php   foreach ($mgr->request['sub_his_flg'] AS $key => $dummy_id) { ?>
<script>
sub_cal_sdd[<?php echo $key ?>] = new JKL.Calendar("sub_<?php echo $key ?>_caldiv_sdd","mainForm","sub_<?php echo $key ?>_send_date");
sub_cal_sdd[<?php echo $key ?>].setStyle( "frame_color", "#3333CC" );
sub_cal_sdd[<?php echo $key ?>].setStyle( "typestr", "yyyy/mm/dd" );
sub_cal_vsd[<?php echo $key ?>] = new JKL.Calendar("sub_<?php echo $key ?>_caldiv_vsd","mainForm","sub_<?php echo $key ?>_validstartdate");
sub_cal_vsd[<?php echo $key ?>].setStyle( "frame_color", "#3333CC" );
sub_cal_vsd[<?php echo $key ?>].setStyle( "typestr", "yyyy/mm/dd" );
sub_cal_ved[<?php echo $key ?>] = new JKL.Calendar("sub_<?php echo $key ?>_caldiv_ved","mainForm","sub_<?php echo $key ?>_validenddate");
sub_cal_ved[<?php echo $key ?>].setStyle( "frame_color", "#3333CC" );
sub_cal_ved[<?php echo $key ?>].setStyle( "typestr", "yyyy/mm/dd" );

</script>
        <tr>
          <td align="left"><img src="image/space.gif" width="1" height="5" /></td>
        </tr>
        <tr>
          <td>
            <input type="hidden" name="sub_his_init[<?php echo $key ?>]" id="sub_<?php echo $key ?>_his_init" value="<?php echo $mgr->getRequestData('sub_his_init', $key) ?>">
            <table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
              <tr>
                <th colspan="6" scope="col"><div class="CheckBoxTab">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                      <tr>
                        <td width="24"><input type="checkbox" name="sub_his_flg[<?php echo $key ?>]" id="sub_<?php echo $key ?>_his_flg" value="1" <?php echo $mgr->getCheckData('sub_his_flg', '1', $key) ?> onclick="changeHisFlg(this, 'sub_<?php echo $key ?>_');" /></td>
                        <td width="166"><label for="sub_<?php echo $key ?>_his_flg">電カル連携情報（サブ<?php echo $key ?>）</label></td>
                        <td><span class="inputRule">　※電カル連携する場合はチェックしてください。</span></td>
                        <td align="right">有効開始日<span class="hissu">*</span>：</td>
                        <td width="100"><input name="sub_send_date[<?php echo $key ?>]" type="text" id="sub_<?php echo $key ?>_send_date" onchange="sub_cal_sdd[<?php echo $key ?>].getFormValue(); sub_cal_sdd[<?php echo $key ?>].hide();" onclick="sub_cal_sdd[<?php echo $key ?>].write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('sub_send_date', $key) ?>" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? "disabled" : "" ?> /><br /><div id="sub_<?php echo $key ?>_caldiv_sdd"></div></td>
                      </tr>
                    </table>
                  </div>
                </th>
              </tr>
              <tr>
                <th width="100" scope="col">ログインID<span class="hissu">*</span></th>
                <td scope="col"><input name="sub_staffcode[<?php echo $key ?>]" type="text" id="sub_<?php echo $key ?>_staffcode" value="<?php echo $mgr->getRequestData('sub_staffcode', $key) ?>" size="10" maxlength="8" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? "disabled" : "" ?> />
                  <span class="inputRule">(半角数字8桁)</span>
<?php if ($mgr->getRequestData('sub_has_his_data', $key) != "") { ?>
                  <input type="button" id="sub_<?php echo $key ?>_junhis_btn" value="　　ID通知書発行　　" onclick="printJunhisID('<?php echo $key ?>');">
<?php } ?>
                </td>
                <th width="100">漢字氏名<span class="hissu">*</span></th>
                <td><input name="sub_kanjiname[<?php echo $key ?>]" type="text" id="sub_<?php echo $key ?>_kanjiname" size="16" value="<?php echo $mgr->getRequestData('sub_kanjiname', $key) ?>" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? "disabled" : "" ?> /></td>
                <th width="80">氏名カナ<span class="hissu">*</span></th>
                <td scope="col"><input name="sub_kananame[<?php echo $key ?>]" type="text" id="sub_<?php echo $key ?>_kananame" size="16" value="<?php echo $mgr->getRequestData('sub_kananame', $key) ?>" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? "disabled" : "" ?> /></td>
              </tr>
              <tr>
                <th>HISパスワード</th>
                <td colspan="5">
<?php if ($mgr->getRequestData('sub_has_his_data', $key) == "") { ?>
                  <input name="sub_password[<?php echo $key ?>]" type="text" id="sub_<?php echo $key ?>_password" size="30" value="<?php echo $mgr->getRequestData('sub_password', $key) ?>" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? "disabled" : "" ?> />
                  [<a id="sub_<?php echo $key ?>_password_create" href="javascript:;" onclick="makePassword('sub_<?php echo $key ?>_password');" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? 'style="display:none;"' : "" ?>>自動生成</a><span id="sub_<?php echo $key ?>_password_text" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? "" : 'style="display:none;"' ?>>自動生成</span>]
                  <span class="inputRule">(半角英数字　4～10文字)</span>
<?php } else { ?>
                  <input name="sub_password[<?php echo $key ?>]" type="text" id="sub_<?php echo $key ?>_password" size="20" value="" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? "disabled" : "" ?> />
                  [<a id="sub_<?php echo $key ?>_password_create" href="javascript:;" onclick="makePassword('sub_<?php echo $key ?>_password');" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? 'style="display:none;"' : "" ?>>自動生成</a><span id="sub_<?php echo $key ?>_password_text" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? "" : 'style="display:none;"' ?>>自動生成</span>]
                  <input type="button" id="sub_<?php echo $key ?>_password_btn" value="　　再発行する　　" onclick="reissuePassword('password', '<?php echo $key ?>');">
                  <span class="inputRule">(半角英数字　4～10文字)</span>
                  <input type="hidden" name="sub_has_his_data[<?php echo $key ?>]" id="sub_<?php echo $key ?>_has_his_data" value="1">
<?php } ?>
                </td>
              </tr>
              <tr>
                <th>部署</th>
                <td>
                  <select name="sub_wardstatus[<?php echo $key ?>]" id="sub_<?php echo $key ?>_wardstatus" onchange="wardstatusChange(this.value, 'sub_<?php echo $key ?>_');" style="width:100px;" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? "disabled" : "" ?>>
                    <option value="">---部署区分---</option>
                    <?php echo $mgr->getSelectList('sub_wardstatus', $key) ?>
                  </select><div class="floatLeft" id="sub_<?php echo $key ?>_wardstatusJs"></div>
                  <select name="sub_wardcode[<?php echo $key ?>]" id="sub_<?php echo $key ?>_wardcode" style="width:120px;" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? "disabled" : "" ?>>
                    <option value="">---部署---</option>
                    <?php echo $mgr->getSelectList('sub_wardcode', $key, $mgr->getRequestData('sub_wardstatus', $key)) ?>
                </select></td>
                <th>職種</th>
                <td colspan="3">
                  <select name="sub_professionstatus[<?php echo $key ?>]" id="sub_<?php echo $key ?>_professionstatus" onchange="professionstatusChange(this.value, 'sub_<?php echo $key ?>_');" style="width:100px;" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? "disabled" : "" ?>>
                    <option value="">---職種区分---</option>
                    <?php echo $mgr->getSelectList('sub_professionstatus', $key) ?>
                  </select><div class="floatLeft" id="sub_<?php echo $key ?>_professionstatusJs"></div>
                  <select name="sub_professioncode[<?php echo $key ?>]" id="sub_<?php echo $key ?>_professioncode" style="width:120px;" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? "disabled" : "" ?>>
                    <option value="">---職種---</option>
                    <?php echo $mgr->getSelectList('sub_professioncode', $key, $mgr->getRequestData('sub_professionstatus', $key)) ?>
                  </select></td>
              </tr>
              <tr>
                <th>役職</th>
                <td>
                  <select name="sub_gradecode[<?php echo $key ?>]" id="sub_<?php echo $key ?>_gradecode" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? "disabled" : "" ?>>
                    <option value="">----</option>
                    <?php echo $mgr->getSelectList('sub_gradecode', $key) ?>
                  </select></td>
                <th>診療グループ</th>
                <td colspan="3">
                  <select name="sub_deptstatus[<?php echo $key ?>]" id="sub_<?php echo $key ?>_deptstatus" onchange="deptstatusChange(this.value, 'sub_<?php echo $key ?>_');" style="width:100px;" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? "disabled" : "" ?>>
                    <option value="">---科区分---</option>
                    <?php echo $mgr->getSelectList('sub_deptstatus', $key) ?>
                  </select><div class="floatLeft" id="sub_<?php echo $key ?>_deptstatusJs"></div>
                  <select name="sub_deptcode[<?php echo $key ?>]" id="sub_<?php echo $key ?>_deptcode" onchange="deptcodeChange(this.value, 'sub_<?php echo $key ?>_');" style="width:100px;" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? "disabled" : "" ?>>
                    <option value="">---診療科---</option>
                    <?php echo $mgr->getSelectList('sub_deptcode', $key, $mgr->getRequestData('sub_deptstatus', $key)) ?>
                  </select><div class="floatLeft" id="sub_<?php echo $key ?>_deptcodeJs"></div>
                  <select name="sub_deptgroupcode[<?php echo $key ?>]" id="sub_<?php echo $key ?>_deptgroupcode" style="width:120px;" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? "disabled" : "" ?>>
                    <option value="">---グループ---</option>
                    <?php echo $mgr->getSelectList('sub_deptgroupcode', $key, $mgr->getRequestData('sub_deptcode', $key)) ?>
                  </select></td>
              </tr>
              <tr>
                <th>予約項目コード</th>
                <td><input name="sub_appcode[<?php echo $key ?>]" type="text" id="sub_<?php echo $key ?>_appcode" size="10" maxlength="5" value="<?php echo $mgr->getRequestData('sub_appcode', $key) ?>" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? "disabled" : "" ?> />
                  [<a id="sub_<?php echo $key ?>_appcode_create" href="javascript:;" onclick="makeAppCode('sub_<?php echo $key ?>_');" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? 'style="display:none;"' : "" ?>>自動生成</a><span id="sub_<?php echo $key ?>_appcode_text" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? "" : 'style="display:none;"' ?>>自動生成</span>] <span class="inputRule">(半角英数字5桁)</span></td>
                <th>有効期間</th>
                <td colspan="3"><table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td><input name="sub_validstartdate[<?php echo $key ?>]" type="text" id="sub_<?php echo $key ?>_validstartdate" onchange="sub_cal_vsd[<?php echo $key ?>].getFormValue(); sub_cal_vsd[<?php echo $key ?>].hide();" onclick="sub_cal_vsd[<?php echo $key ?>].write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('sub_validstartdate', $key) ?>" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? "disabled" : "" ?> /><br /><div id="sub_<?php echo $key ?>_caldiv_vsd"></div></td>
                    <td> 　～　 </td>
                    <td><input name="sub_validenddate[<?php echo $key ?>]" type="text" id="sub_<?php echo $key ?>_validenddate" onchange="sub_cal_ved[<?php echo $key ?>].getFormValue(); sub_cal_ved[<?php echo $key ?>].hide();" onclick="sub_cal_ved[<?php echo $key ?>].write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('sub_validenddate', $key) ?>" <?php echo ($mgr->getRequestData('sub_his_flg', $key) == "") ? "disabled" : "" ?> /><br /><div id="sub_<?php echo $key ?>_caldiv_ved"></div></td>
                  </tr>
                </table></td>
              </tr>
            </table>
          </td>
        </tr>
<?php   } ?>
<?php } ?>
        </tbody>
        <tr>
          <td>[<a href="javascript:;" onclick="addHisDataList('hisDataList');">電カル連携情報を追加</a>]
            <input type="hidden" name="maxHisDataKey" id="maxHisDataKey" value="<?php echo $key ?>">
            <div class="floatLeft" id="addHisDataListJs"></div>
          </td>
        </tr>
        <tr>
          <td><img src="image/space.gif" alt="" width="1" height="5" /></td>
        </tr>
        <tr>
          <td><table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
              <tr>
                <th colspan="4">システム情報</th>
              </tr>
              <tr>
                <th width="100">利用期間</th>
                <td colspan="3"><div class="CheckBoxTab">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><input name="start_date" type="text" id="start_date" onchange="cal_sd.getFormValue(); cal_sd.hide();" onclick="cal_sd.write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('start_date') ?>" /><br /><div id="caldiv_sd"></div></td>
                      <td> 　～　 </td>
                      <td><input name="end_date" type="text" id="end_date" onchange="cal_ed.getFormValue(); cal_ed.hide();" onclick="cal_ed.write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('end_date') ?>" /><br /><div id="caldiv_ed"></div></td>
                      <td>&nbsp;&nbsp;&nbsp;</td>
                      <td><input type="checkbox" name="retire_flg" id="retire_flg" value="1" <?php echo $mgr->getCheckData('retire_flg', '1') ?> /></td>
                      <td><label for="retire_flg">退職（利用期間終了日が退職日となります）</label></td>
                    </tr>
                  </table></div>
                </td>
              </tr>
              <tr>
                <th width="100">利用者種別</th>
                <td colspan="3"><div class="CheckBoxTab">
                    <?php echo $mgr->getRadioButtonList('user_type_id') ?>
                </div></td>
              </tr>
              <tr>
                <th width="100">データ出力権限</th>
                <td colspan="3"><div class="CheckBoxTab">
                    <?php echo $mgr->getCheckBoxList('user_role_id') ?>
                  </div></td>
              </tr>
              <tr>
                <th width="100">連携不要項目</th>
                <td colspan="3"><div class="CheckBoxTab">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><input type="checkbox" name="garoon_disused_flg" id="garoon_disused_flg" value="1" <?php echo $mgr->getCheckData('garoon_disused_flg', '1') ?> /></td>
                      <td><label for="garoon_disused_flg">ガルーン</label></td>
                      <td>&nbsp;&nbsp;&nbsp;</td>
                      <td><input type="checkbox" name="mlist_disused_flg" id="mlist_disused_flg" value="1" <?php echo $mgr->getCheckData('mlist_disused_flg', '1') ?> /></td>
                      <td><label for="mlist_disused_flg">動的配布リスト</label></td>
                      <td>&nbsp;<span class="inputRule">※連携が不要な場合のみチェックして下さい。</span></td>
                    </tr>
                  </table></div>
                </td>
              </tr>
              <tr>
                <th width="100">備考</th>
                <td colspan="3"><textarea name="note" cols="40" rows="4" id="note">
<?php echo $mgr->getRequestData('note') ?></textarea></td>
              </tr>
            </table>
