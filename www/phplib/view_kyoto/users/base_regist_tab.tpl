            <table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
              <tr>
                <th colspan="6" scope="col">基本情報</th>
              </tr>
              <tr>
                <th width="100" scope="col">個人番号<span class="hissu">＊</span></th>
                <td scope="col" colspan="5"><div class="CheckBoxTab">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><input name="staff_id" type="text" id="staff_id" size="6" maxlength="<?php echo STAFF_ID_LEN ?>" value="<?php echo $mgr->getRequestData('staff_id') ?>" />&nbsp;</td>
                      <td><input type="checkbox" name="staff_id_flg" id="staff_id_flg" value="1" <?php echo $mgr->getCheckData('staff_id_flg', '1') ?> /></td>
                      <td><label for="staff_id_flg">職員番号として登録</label></td>
                      <td>&nbsp;<span class="inputRule">(半角数字<?php echo STAFF_ID_LEN ?>桁　※職員番号のある方は職員番号を記入してください)</span></td>
                    </tr>
                  </table></div>
                </td>
              </tr>
              <tr>
                <th width="100">氏名<span class="hissu">＊</span></th>
                <td>姓:<input name="kanjisei" type="text" id="kanjisei" size="6" value="<?php echo $mgr->getRequestData('kanjisei') ?>" />
                  名:<input name="kanjimei" type="text" id="kanjimei" size="6" value="<?php echo $mgr->getRequestData('kanjimei') ?>" /></td>
                <th width="100">氏名カナ<span class="hissu">＊</span></th>
                <td>姓:<input name="kanasei" type="text" id="kanasei" size="6" value="<?php echo $mgr->getRequestData('kanasei') ?>" />
                  名:<input name="kanamei" type="text" id="kanamei" size="6" value="<?php echo $mgr->getRequestData('kanamei') ?>" /></td>
                <th width="100">氏名英字</th>
                <td>姓:<input name="eijisei" type="text" id="eijisei" size="6" value="<?php echo $mgr->getRequestData('eijisei') ?>" />
                  名:<input name="eijimei" type="text" id="eijimei" size="6" value="<?php echo $mgr->getRequestData('eijimei') ?>" /></td>
              </tr>
              <tr>
                <th width="100">戸籍上の氏名</th>
                <td>姓:<input name="kanjisei_real" type="text" id="kanjisei_real" size="6" value="<?php echo $mgr->getRequestData('kanjisei_real') ?>" />
                  名:<input name="kanjimei_real" type="text" id="kanjimei_real" size="6" value="<?php echo $mgr->getRequestData('kanjimei_real') ?>" /></td>
                <th width="100">戸籍氏名カナ</th>
                <td>姓:<input name="kanasei_real" type="text" id="kanasei_real" size="6" value="<?php echo $mgr->getRequestData('kanasei_real') ?>" />
                  名:<input name="kanamei_real" type="text" id="kanamei_real" size="6" value="<?php echo $mgr->getRequestData('kanamei_real') ?>" /></td>
                <th width="100">旧姓</th>
                <td><input name="kyusei" type="text" id="kyusei" size="10" value="<?php echo $mgr->getRequestData('kyusei') ?>" /></td>
              </tr>
              <tr>
                <th width="100">性別<span class="hissu">＊</span></th>
                <td><select name="sex" id="sex">
                    <option value="">--</option>
                    <?php echo $mgr->getSelectList('sex') ?>
                  </select></td>
                <th width="100">生年月日<span class="hissu">＊</span></th>
                <td colspan="3"><select name="birth_year" id="birth_year">
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
                <th width="100">組織(メイン)<span class="hissu">＊</span></th>
                <td colspan="5">
                  <select name="belong_class_id" id="belong_class_id" onchange="belongClassChange(this.value);" style="width:0; visibility:hidden;">
                    <option value="">--大分類--</option>
                    <?php echo $mgr->getSelectList('belong_class_id') ?>
                  </select><div class="floatLeft" id="belongClassJs"></div>
                  <select name="belong_div_id" id="belong_div_id" onchange="belongDivChange(this.value);" style="width:0; visibility:hidden;"">
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
                          <select name="sub_belong_class_id[<?php echo $key ?>]" id="sub_<?php echo $key ?>_belong_class_id" onchange="belongClassChange(this.value, 'sub_<?php echo $key ?>_');" style="width:0; visibility:hidden;">
                            <option value="">--大分類--</option>
                            <?php echo $mgr->getSelectList('sub_belong_class_id', $key) ?>
                          </select><div class="floatLeft" id="sub_<?php echo $key ?>_belongClassJs"></div>
                          <select name="sub_belong_div_id[<?php echo $key ?>]" id="sub_<?php echo $key ?>_belong_div_id" onchange="belongDivChange(this.value, 'sub_<?php echo $key ?>_');" style="width:0; visibility:hidden;">
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
                          <label for="sub_<?php echo $key ?>_staff_id" id="sub_<?php echo $key ?>_staff_id_label" style="width:80px;">職員番号：</label><input name="sub_staff_id[<?php echo $key ?>]" type="text" id="sub_<?php echo $key ?>_staff_id" maxlength="<?php echo STAFF_ID_LEN ?>" value="<?php echo $mgr->getRequestData('sub_staff_id', $key) ?>" style="width:80px;" />
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
                <th width="100">常勤／非常勤<span class="hissu">＊</span></th>
                <td colspan="5"><select name="joukin_kbn" id="joukin_kbn">
                    <option value="">--</option>
                    <?php echo $mgr->getSelectList('joukin_kbn') ?>
                  </select></td>
              </tr>
              <tr>
                <th width="100">PHS番号</th>
                <td><input name="pbno" type="text" id="pbno" style="width:32px" maxlength="4" value="<?php echo $mgr->getRequestData('pbno') ?>" />&nbsp;<span class="inputRule">(数字4桁)</span>
                &nbsp;[<a href="javascript:;" onclick="existsPbno();">重複ﾁｪｯｸ</a>]</td>
                <th width="100">内線</th>
                <td colspan="3"><input name="naisen" type="text" id="naisen" size="22" maxlength="20" value="<?php echo $mgr->getRequestData('naisen') ?>" />&nbsp;<span class="inputRule">(20桁以内)</span></td>
              </tr>
              <tr>
                <th width="100">備考</th>
                <td colspan="5"><textarea name="note" cols="40" rows="4" id="note">
<?php echo $mgr->getRequestData('note') ?></textarea></td>
              </tr>
            </table>
