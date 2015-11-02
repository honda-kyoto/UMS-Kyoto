        <tr>
          <td>
            <h3>■動的配布の抽出条件</h3>
            <table border="0" cellpadding="2" cellspacing="0" class="searchTab">
              <tr>
                <th>職種</th>
                <td><select name="job_id" id="job_id" style="width:120px;">
                    <option value="">----</option>
                    <?php echo $mgr->getSelectList('job_id') ?>
                  </select></td>
                <th>役職</th>
                <td><select name="post_id" id="post_id" style="width:120px;">
                    <option value="">----</option>
                    <?php echo $mgr->getSelectList('post_id') ?>
                  </select></td>
                <th>常勤／非常勤</th>
                <td><select name="joukin_kbn" id="joukin_kbn" style="width:120px;">
                    <option value="">--</option>
                    <?php echo $mgr->getSelectList('joukin_kbn') ?>
                  </select></td>
              </tr>
              <tr>
                <th width="80">所属</th>
                <td colspan="5"><select name="belong_class_id" id="belong_class_id" onchange="belongClassChange(this.value);" style="width:120px;">
                    <option value="">---大分類---</option>
                    <?php echo $mgr->getSelectList('belong_class_id') ?>
                  </select><div class="floatLeft" id="belongClassJs"></div>
                  <select name="belong_div_id" id="belong_div_id" onchange="belongDivChange(this.value);" style="width:120px;">
                    <option value="">---部門---</option>
                    <?php echo $mgr->getSelectList('belong_div_id', "", $mgr->getRequestData('belong_class_id')) ?>
                  </select><div class="floatLeft" id="belongDivJs"></div>
                  <select name="belong_dep_id" id="belong_dep_id" onchange="belongDepChange(this.value);" style="width:120px;">
                    <option value="">---部---</option>
                    <?php echo $mgr->getSelectList('belong_dep_id', "", $mgr->getRequestData('belong_div_id')) ?>
                  </select><div class="floatLeft" id="belongDepJs"></div>
                  <select name="belong_sec_id" id="belong_sec_id" onchange="belongSecChange(this.value);" style="width:120px;">
                    <option value="">---課・科---</option>
                    <?php echo $mgr->getSelectList('belong_sec_id', "", $mgr->getRequestData('belong_dep_id')) ?>
                  </select><div class="floatLeft" id="belongSecJs"></div>
                  <select name="belong_chg_id" id="belong_chg_id" style="width:120px;">
                    <option value="">---係・室・他---</option>
                    <?php echo $mgr->getSelectList('belong_chg_id', "", $mgr->getRequestData('belong_sec_id')) ?>
                  </select></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
<?php if ($mgr->is_all_user) { ?>
            <input type="button" value="　　　条件を追加　　　" disabled />
<?php } else { ?>
            <input type="button" value="　　　条件を追加　　　" onclick="addAutoCond();" />
<?php } ?>
          </td>
        </tr>
        <tr>
          <td>
<?php if (is_array(@$mgr->aryAutoCond)) { ?>
            <div class="resultBlock">
              <h3>■登録済み抽出条件一覧</h3>
<?php   if (count($mgr->aryAutoCond) > 0) { ?>
              <input type="hidden" name="list_no" id="list_no" value="">
              <table border="0" cellpadding="2" cellspacing="0" class="listTab">
                <tr>
                  <th>職種</th>
                  <th>役職</th>
                  <th>常勤／非常勤</th>
                  <th>所属</th>
                  <th width="60">削除</th>
                </tr>
<?php     foreach ($mgr->aryAutoCond AS $no => $data) { ?>
                <tr>
<?php       if ($data['is_all_user']) { ?>
                  <td colspan="4">
                    全ての利用者
                    <input type="hidden" name="is_all_user" id="is_all_user" value="1">
                  </td>
<?php       } else { ?>
                  <td><?php echo $data['job_name'] ?></td>
                  <td><?php echo $data['post_name'] ?></td>
                  <td><?php echo $data['joukin_name'] ?></td>
                  <td><?php echo $data['belong_name'] ?></td>
<?php       } ?>
                  <td align="center">[<a href="javascript:;" onclick="deleteAutoCond(<?php echo $no ?>);">削除</a>]</td>
                </tr>
<?php     } ?>
              </table>
<?php   } else { ?>
              <table cellspacing="0">
                <tr>
                  <td align="left">現在登録されているデータはありません</td>
                </tr>
              </table>
<?php   } ?>
            </div>
<?php } ?>
          </td>
        </tr>
<?php if ($mgr->sender_kbn == SENDER_KBN_LIMIT) { ?>
        <tr>
          <td>
            <div class="resultBlock">
              <h3>■送信者の指定方法</h3>
              <div class="CheckBoxTab">
                <?php echo $mgr->getRadioButtonList('sender_set_type', '', "formSubmit('change');") ?>
              </div>
            </div>
          </td>
        </tr>
<?php } ?>
        <tr>
          <td>
<?php if (is_array(@$mgr->aryList)) { ?>
            <div class="resultBlock">
            <h3>■送信可能者追加</h3>
            <table border="0" cellpadding="2" cellspacing="0" class="listTab">
              <tr>
                <th width="230">メールアドレス<span class="hissu">*</span></th>
                <th width="230">氏名</th>
                <th width="80">追加</th>
              </tr>
              <tr>
                <td width="230"><input name="mail_addr" type="text" id="mail_addr" size="30" value="<?php echo $mgr->getRequestData('mail_addr') ?>"  /></td>
                <td width="230"><input name="member_name" type="text" id="member_name" size="30" value="<?php echo $mgr->getRequestData('member_name') ?>"  /></td>
                <td width="80" align="center">[<a href="javascript:;" onclick="addMember();">追加</a>]</td>
              </tr>
            </table>
            </div>
            <div class="resultBlock">
              <h3>■送信可能者</h3>
<?php   if (count($mgr->aryList) > 0) { ?>
              <table border="0" cellpadding="2" cellspacing="0" class="listTab">
                <tr>
                  <th width="230">メールアドレス<span class="hissu">*</span></th>
                  <th width="230">氏名</th>
                  <th width="80">削除</th>
                </tr>
<?php     foreach ($mgr->aryList AS $data) { ?>
                <tr>
                  <td><?php echo $data['mail_addr'] ?></td>
                  <td><?php echo $data['member_name'] ?></td>
                  <td align="center">[<a href="javascript:;" onclick="deleteMember('<?php echo $data['mail_addr'] ?>');">削除</a>]</td>
                </tr>
<?php     } ?>
              </table>
<?php   } else { ?>
              <table cellspacing="0">
                <tr>
                  <td align="left">現在登録されているデータはありません</td>
                </tr>
              </table>
<?php   } ?>
            </div>
<?php } ?>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
