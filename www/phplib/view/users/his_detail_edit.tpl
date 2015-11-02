          <input type="hidden" name="list_no" id="list_no" value="<?php echo $mgr->getRequestData('list_no') ?>">
          <input type="hidden" name="edit_mode" id="edit_mode" value="<?php echo $mgr->getRequestData('edit_mode') ?>">
          <input type="hidden" name="history_no" id="history_no" value="<?php echo $mgr->getRequestData('history_no') ?>">
          <input type="hidden" name="history_mode" id="history_mode" value="<?php echo $mgr->getRequestData('history_mode') ?>">
          <h3>■必要事項を入力して「更新」ボタンをクリックしてください。履歴が必要な更新は各種ボタンより操作してください。</h3>
          <table border="0" cellspacing="3" cellpadding="3">
            <tr>
              <td><div class="inputComment">※基本情報の必須項目(*)が登録されていないと電子カルテに連携できません。未設定項目がある場合は最初に基本情報を登録してください。</div></td>
            </tr>
            <tr>
              <td><table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
                  <tr>
                    <th colspan="6">基本情報</th>
                  </tr>
                  <tr>
                    <th width="100">氏名</th>
                    <td colspan="5"><?php echo $mgr->getOutputData('kanjisei') ?>　<?php echo $mgr->getOutputData('kanjimei') ?>（<?php echo $mgr->getOutputData('kanasei') ?>　<?php echo $mgr->getOutputData('kanamei') ?>）</td>
                  </tr>
                  <tr>
                    <th>性別<span class="hissu">*</span></th>
                    <td><?php echo $mgr->getOutputData('sex_name') ?></td>
                    <th width="100">生年月日<span class="hissu">*</span></th>
                    <td><?php echo $mgr->getOutputData('birthday_text') ?></td>
                    <th width="90">PHS番号</th>
                    <td><?php echo $mgr->getOutputData('pbno') ?><input type="hidden" name="pbno" id="pbno" value="<?php echo $mgr->getRequestData('pbno') ?>"></td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td><input type="button" value="　　一覧に戻る　　" onclick="returnList();" /></td>
            </tr>
            <tr>
              <td><img src="image/space.gif" alt="" width="1" height="5" /></td>
            </tr>
            <tr>
              <td><hr /></td>
            </tr>
            <tr>
              <td><div id="hisTabs">
                  <ul class="hisclear">
                  <?php echo $mgr->getHisTabMenu() ?>
                  </ul>
                </div>
                <div id="hisBox">
<?php if ($mgr->has_reserve_data) { ?>
                  <table border="0" cellspacing="0" cellpadding="0" width="850">
                    <tr>
                      <td><h3>●予約データ</h3></td>
                    </tr>
                    <tr>
                      <td>
                        <table border="0" cellpadding="5" cellspacing="0" class="listTabF">
                          <tr>
                            <th nowrap="nowrap">有効開始日</th>
                            <th nowrap="nowrap">予約データ作成者</th>
                            <th nowrap="nowrap">参照</th>
                          </tr>
                          <tr>
                            <td nowrap="nowrap"><?php echo $mgr->getOutputData('reserve_send_date') ?></td>
                            <td nowrap="nowrap"><?php echo $mgr->getOutputData('reserve_user_name') ?></td>
                            <td align="center" nowrap="nowrap">[<a href="javascript:;" onclick="editModeChange('reserve');">参照</a>]</td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td><img src="image/space.gif" alt="" width="1" height="15" /></td>
                    </tr>
                  </table>
<?php } ?>
<?php if ($mgr->reserve_data_only) { ?>
                  <table border="0" cellspacing="0" cellpadding="0" width="850">
                    <tr>
                      <td><h3>●電子カルテ情報</h3></td>
                    </tr>
                    <tr>
                      <td>※このデータは予約のみです。</td>
                    </tr>
                    <tr>
                      <td><img src="image/space.gif" alt="" width="1" height="15" /></td>
                    </tr>
                  </table>
<?php } else { ?>
<?php   if ($mgr->getRequestData('edit_mode') != "") { ?>
                  <table border="0" cellspacing="0" cellpadding="0" width="850">
                    <tr>
                      <td><h3><font color="#f00">※<?php echo $mgr->getOutputData('edit_mode_name') ?>編集中</font></h3></td>
                    </tr>
                  </table>
<?php   } ?>
                  <table border="0" cellspacing="0" cellpadding="0" width="850">
                    <tr>
                      <td><h3>●電子カルテ情報</h3></td>
                      <td><div class="inputComment">(*)は必須項目です。（診療グループは診療科までが必須項目となります。）</div></td>
                    </tr>
                  </table>
                  <table border="0" cellspacing="0" cellpadding="0" width="850">
                      <tr>
                      <td><table border="0" cellpadding="5" cellspacing="0" class="inputTab" width="850">
<?php if ($mgr->getRequestData('edit_mode') != "history") { ?>
<?php   include("view/users/his_detail_regist_tab.tpl") ?>
<?php } else { ?>
<?php   include("view/users/his_detail_view_tab.tpl") ?>
<?php } ?>
<?php if ($mgr->getRequestData('list_no') != 'new' && $mgr->has_original_data) { ?>
                        <tr>
                          <th colspan="4">履歴作成</th>
                        </tr>
                        <tr>
                          <th>履歴区分</th>
                          <td colspan="3"><select name="his_history_kbn" id="his_history_kbn" >
                              <?php echo $mgr->getSelectList('his_history_kbn') ?>
                            </select></td>
                        </tr>
                        <tr>
                          <th>作成理由等</th>
                          <td colspan="3"><textarea name="history_note" id="history_note" style="width:400px; height:40px;">
<?php echo $mgr->getRequestData('history_note') ?></textarea></td>
                        </tr>
<?php } ?>
                      </table></td>
                    </tr>
                      <tr>
                        <td><img src="image/space.gif" alt="" width="1" height="5" /></td>
                      </tr>
                      <tr>
                        <td>
                          <input type="button" value="　　　更　　　新　　　" onclick="editUser();" />
                          <input type="button" value="　　　リセット　　　" onclick="resetUser();" />
<?php if ($mgr->getRequestData('edit_mode') != "") { ?>
                          <input type="button" value="　　　キャンセル　　　" onclick="editModeReset();" />
<?php   if ($mgr->getRequestData('edit_mode') == "reserve") { ?>
                          <input type="button" value="　　　予約取消　　　" onclick="deleteReserveData();" />
<?php   } ?>
<?php } else { ?>
<?php   if ($mgr->getRequestData('list_no') != 'new' && !$mgr->is_main_data) { ?>
                          <input type="button" value="　　　このデータをメインに変更　　　" onclick="changeMainData();" />
<?php   } ?>
<?php } ?>
                        </td>
                      </tr>
                      <tr>
                        <td><img src="image/space.gif" alt="" width="1" height="15" /></td>
                      </tr>
                  </table>
<?php   if ($mgr->getRequestData('list_no') != "new" && $mgr->getRequestData('edit_mode') == "") { ?>
                  <table border="0" cellspacing="0" cellpadding="0" width="850">
                    <tr>
                      <td><h3>●履歴一覧</h3></td>
                    </tr>
                    <tr>
                      <td>
<?php if (is_array($mgr->output['history_no'])) { ?>
                        <table border="0" cellpadding="5" cellspacing="0" class="listTab">
                        <tr>
                          <th nowrap="nowrap" scope="col">No.</th>
                          <th>履歴作成理由</th>
                          <th nowrap="nowrap">有効期間</th>
                          <th nowrap="nowrap">履歴作成者</th>
                          <th nowrap="nowrap">履歴種別</th>
                          <th nowrap="nowrap">参照</th>
                        </tr>
<?php   foreach ($mgr->output['history_no'] AS $history_no => $aryData) { ?>
                        <tr>
                          <td align="right" nowrap="nowrap" scope="col"><?php echo $history_no ?></td>
                          <td><?php echo nl2br($mgr->getOutputData('history_history_note', $history_no)) ?></td>
                          <td nowrap="nowrap"><?php echo $mgr->getOutputData('history_validstartdate', $history_no) ?>～<?php echo $mgr->getOutputData('history_validenddate', $history_no) ?></td>
                          <td nowrap="nowrap"><?php echo $mgr->getOutputData('history_user_name', $history_no) ?></td>
                          <td nowrap="nowrap"><?php echo $mgr->getOutputData('history_mode_name', $history_no) ?></td>
                          <td align="center" nowrap="nowrap">[<a href="javascript:;" onclick="historyEdit(<?php echo $history_no ?>);">参照</a>]</td>
                        </tr>
<?php   } ?>
                        </table>
<?php } else { ?>
                        ※現在履歴はありません。
<?php } ?>
                      </td>
                    </tr>
                    <tr>
                      <td><img src="image/space.gif" alt="" width="1" height="15" /></td>
                    </tr>
                  </table>
                  <table border="0" cellspacing="0" cellpadding="0" width="850">
                    <tr>
                      <td><a name="taishoku"></a><h3>●退職処理</h3></td>
                    </tr>
                    <tr>
                      <td><table border="0" cellpadding="5" cellspacing="0" class="inputTab">
                        <tr>
                          <th width="100">退職日</th>
                          <td><table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td><input name="retire_date" type="text" id="retire_date" onchange="cal_rd.getFormValue(); cal_rd.hide();" onclick="cal_rd.write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('retire_date') ?>" />[<a href="javascript:;" onclick="setToday('retire_date');">今日</a>]<br /><div id="caldiv_rd"></div></td>
                              </tr>
                            </table></td>
                          <td align="center" nowrap="nowrap"><input type="button" value="　　　退職処理　　　" onclick="retireUser();" /></td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td><img src="image/space.gif" alt="" width="1" height="15" /></td>
                    </tr>
                  </table>
<?php   } ?>
<?php } ?>
                </div></td>
            </tr>
          </table>