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
<?php if ($mgr->getRequestData('list_no') != "new" && $mgr->getRequestData('edit_mode') == "") { ?>
                  <table border="0" cellspacing="0" cellpadding="0" width="850">
                    <tr>
                      <td><h3>●履歴作成メニュー</h3></td>
                    </tr>
                    <tr>
                      <td>
                        <input type="button" value="　　組織及び氏名の変更　　" onclick="editModeChange('all');" />
                        <input type="button" value="　　組織情報の変更　　" onclick="editModeChange('belong');" />
                        <input type="button" value="　　氏名の変更　　" onclick="editModeChange('name');" />
                        <input type="button" value="　　退職処理　　" onclick="editModeChange('retire');" /></td>
                    </tr>
                    <tr>
                      <td><img src="image/space.gif" alt="" width="1" height="15" /></td>
                    </tr>
                  </table>
<?php } ?>
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
<?php if ($mgr->getRequestData('edit_mode') != 'history') { ?>
                        <tr>
                          <th width="100" scope="col">反映日<span class="hissu">*</span></th>
                          <td colspan="3" scope="col">
<?php if ($mgr->getRequestData('edit_mode') == 'reserve') { ?>
                            <input name="send_date" type="text" id="send_date" onchange="cal_sdd.getFormValue(); cal_sdd.hide();" onclick="cal_sdd.write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('send_date') ?>" <?php echo $mgr->disabled['send_date'] ?> /><br /><div id="caldiv_sdd"></div>
<?php } else { ?>
                            <div class="CheckBoxTab">
                            <table border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td><input type="radio" name="immediate_flg" id="immediate_flg_1" value="1"  onClick="changeImmediateFlg();" <?php echo $mgr->getCheckData('immediate_flg', '1') ?> /></td>
                                <td><label for="immediate_flg_1">即時反映</label></td>
                                <td>&nbsp;&nbsp;&nbsp;</td>
                                <td><input type="radio" name="immediate_flg" id="immediate_flg_2" value="2"  onClick="changeImmediateFlg();" <?php echo $mgr->getCheckData('immediate_flg', '2') ?> /></td>
                                <td><label for="immediate_flg_2">反映日指定</label></td>
                                <td>：</td>
                                <td><input name="send_date" type="text" id="send_date" onchange="cal_sdd.getFormValue(); cal_sdd.hide();" onclick="cal_sdd.write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('send_date') ?>" <?php echo $mgr->disabled['send_date'] ?> /><br /><div id="caldiv_sdd"></div></td>
                              </tr>
                            </table></div>
<?php } ?>
                         </td>
                        </tr>
<?php } ?>
                        <tr>
                          <th scope="col">ログインID<span class="hissu">*</span></th>
                          <td scope="col" colspan="3"><input name="staffcode" type="text" id="staffcode" value="<?php echo $mgr->getRequestData('staffcode') ?>" size="10" maxlength="8" <?php echo $mgr->disabled['staffcode'] ?> />
                            <span class="inputRule">(半角数字8桁)</span>
<?php if ($mgr->getRequestData('list_no') != 'new' && $mgr->getRequestData('edit_mode') == "") { ?>
                          <input type="button" id="junhis_btn" value="　　ID通知書発行　　" onclick="printJunhisID('<?php echo $mgr->getRequestData('list_no') ?>');">
<?php } ?>
                          </td>
                        </tr>
                        <tr>
                          <th>パスワード</th>
                          <td colspan="5">
<?php if ($mgr->getRequestData('edit_mode') != "") { ?>
                            <div class="inputRule">※変更できません。</div>
<?php } else { ?>
                            <div class="CheckBoxTab">
<?php   if ($mgr->getRequestData('list_no') != 'new') { ?>
                            <table border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td><div class="inputRule">※再発行する場合のみ入力し「再発行」ボタンを押してください。「更新」ボタンでは更新されません。</div></td>
                              </tr>
                              <tr>
                                <td><img src="image/space.gif" alt="" width="1" height="3" /></td>
                              </tr>
                            </table>
<?php   } ?>
                            <table border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td><input name="password" type="text" id="password" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('password') ?>" />
                                  [<a id="password_create" href="javascript:;" onclick="makePassword('password');" >自動生成</a>]
                                  <span class="inputRule">(半角英数字　4～10文字)</td>
<?php   if (!$mgr->is_main_data) { ?>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td><input type="checkbox" name="copy_main_passwd" id="copy_main_passwd" value="1" <?php echo $mgr->getCheckData('copy_main_passwd', '1') ?> onclick="changeCopyMainPwd();" /></td>
                                <td><label for="copy_main_passwd">メインのパスワードを使用</label></td>
<?php   } ?>
<?php   if ($mgr->getRequestData('list_no') != 'new') { ?>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="password_btn" value="　　再発行する　　" onclick="reissuePassword('<?php echo $mgr->getRequestData('list_no') ?>');"></td>
<?php   } ?>
                              </tr>
                            </table>
<?php } ?>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <th width="100">漢字氏名<span class="hissu">*</span></th>
                          <td><input name="kanjiname" type="text" id="kanjiname" size="16" value="<?php echo $mgr->getRequestData('kanjiname') ?>" <?php echo $mgr->disabled['kanjiname'] ?> /></td>
                          <th>カナ氏名<span class="hissu">*</span></th>
                          <td scope="col"><input name="kananame" type="text" id="kananame" size="16" value="<?php echo $mgr->getRequestData('kananame') ?>" <?php echo $mgr->disabled['kananame'] ?> /></td>
                        </tr>
                        <tr>
                          <th>部署<span class="hissu">*</span></th>
                          <td>
                            <select name="wardstatus" id="wardstatus" onchange="wardstatusChange(this.value);" style="width:100px;" <?php echo $mgr->disabled['wardstatus'] ?>>
                              <option value="">---部署区分---</option>
                              <?php echo $mgr->getSelectList('wardstatus') ?>
                            </select><div class="floatLeft" id="wardstatusJs"></div>
                            <select name="wardcode" id="wardcode" style="width:120px;" <?php echo $mgr->disabled['wardcode'] ?>>
                              <option value="">---部署---</option>
                              <?php echo $mgr->getSelectList('wardcode', "", $mgr->getRequestData('wardstatus')) ?>
                            </select></td>
                          <th>職種<span class="hissu">*</span></th>
                          <td>
                            <select name="professionstatus" id="professionstatus" onchange="professionstatusChange(this.value);" style="width:100px;" <?php echo $mgr->disabled['professionstatus'] ?>>
                              <option value="">---職種区分---</option>
                              <?php echo $mgr->getSelectList('professionstatus') ?>
                            </select><div class="floatLeft" id="professionstatusJs"></div>
                            <select name="professioncode" id="professioncode" style="width:120px;" <?php echo $mgr->disabled['professioncode'] ?>>
                              <option value="">---職種---</option>
                              <?php echo $mgr->getSelectList('professioncode', "", $mgr->getRequestData('professionstatus')) ?>
                            </select></td>
                        </tr>
                        <tr>
                        <th>役職<span class="hissu">*</span></th>
                          <td>
                            <select name="gradecode" id="gradecode" <?php echo $mgr->disabled['gradecode'] ?>>
                              <option value="">----</option>
                              <?php echo $mgr->getSelectList('gradecode') ?>
                            </select></td>
                          <th>診療グループ</th>
                          <td>
                            <select name="deptstatus" id="deptstatus" onchange="deptstatusChange(this.value);" style="width:100px;" <?php echo $mgr->disabled['deptstatus'] ?>>
                              <option value="">---科区分---</option>
                              <?php echo $mgr->getSelectList('deptstatus') ?>
                            </select><div class="floatLeft" id="deptstatusJs"></div>
                            <select name="deptcode" id="deptcode" onchange="deptcodeChange(this.value);" style="width:100px;" <?php echo $mgr->disabled['deptcode'] ?>>
                              <option value="">---診療科---</option>
                              <?php echo $mgr->getSelectList('deptcode', "", $mgr->getRequestData('deptstatus')) ?>
                            </select><div class="floatLeft" id="deptcodeJs"></div>
                            <select name="deptgroupcode" id="deptgroupcode" style="width:120px;" <?php echo $mgr->disabled['deptgroupcode'] ?>>
                              <option value="">---グループ---</option>
                              <?php echo $mgr->getSelectList('deptgroupcode', "", $mgr->getRequestData('deptcode')) ?>
                            </select></td>
                        </tr>
                        <tr>
                          <th>予約項目コード</th>
                          <td><input name="appcode" type="text" id="appcode" size="10" maxlength="5" value="<?php echo $mgr->getRequestData('appcode') ?>" <?php echo $mgr->disabled['appcode'] ?> />
                            [<a id="appcode_create" href="javascript:;" onclick="makeAppCode();" <?php echo $mgr->disabled['appcode'] ?>>自動生成</a>] <span class="inputRule">(半角英数字5桁)</span></td>
                          <th>有効期間<span class="hissu">*</span></th>
                          <td><table border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td><input name="validstartdate" type="text" id="validstartdate" onchange="cal_vsd.getFormValue(); cal_vsd.hide();" onclick="cal_vsd.write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('validstartdate') ?>" /><br /><div id="caldiv_vsd"></div></td>
                                <td> 　～　 </td>
                                <td><input name="validenddate" type="text" id="validenddate" onchange="cal_ved.getFormValue(); cal_ved.hide();" onclick="cal_ved.write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('validenddate') ?>" /><br /><div id="caldiv_ved"></div></td>
                              </tr>
                            </table></td>
                        </tr>
<?php if ($mgr->getRequestData('edit_mode') != "" && $mgr->getRequestData('edit_mode') != "reserve") { ?>
                        <tr>
                          <th>履歴作成理由</th>
                          <td colspan="3"><textarea name="history_note" id="history_no" style="width:400px; height:40px;">
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
                          <th nowrap="nowrap">履歴発生日</th>
                          <th nowrap="nowrap">履歴作成者</th>
                          <th nowrap="nowrap">履歴種別</th>
                          <th nowrap="nowrap">参照</th>
                        </tr>
<?php   foreach ($mgr->output['history_no'] AS $history_no => $aryData) { ?>
                        <tr>
                          <td align="right" nowrap="nowrap" scope="col"><?php echo $history_no ?></td>
                          <td><?php echo nl2br($mgr->getOutputData('history_note', $history_no)) ?></td>
                          <td nowrap="nowrap"><?php echo $mgr->getOutputData('history_date', $history_no) ?></td>
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
<?php   } ?>
<?php } ?>
                </div></td>
            </tr>
          </table>