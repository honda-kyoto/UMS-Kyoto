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
<?php if ($mgr->getRequestData('list_no') != 'new') { ?>
                          <th scope="col">ログインID</th>
<?php   if ($mgr->getRequestData('edit_mode') == "") { ?>
                          <td scope="col" colspan="2"><?php echo $mgr->getRequestData('staffcode') ?><input name="staffcode" type="hidden" id="staffcode" value="<?php echo $mgr->getRequestData('staffcode') ?>" />
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="button" id="junhis_btn" value="　　ID通知書発行　　" onclick="printJunhisID('<?php echo $mgr->getRequestData('list_no') ?>');"></td>
                          <td align="right" scope="col"><a href="#taishoku">▽このデータの退職処理を行う</a></td>
<?php   } else { ?>
                          <td scope="col" colspan="3"><?php echo $mgr->getRequestData('staffcode') ?><input name="staffcode" type="hidden" id="staffcode" value="<?php echo $mgr->getRequestData('staffcode') ?>" />
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          </td>
<?php   } ?>
<?php } else { ?>
                          <th scope="col">ログインID<span class="hissu">*</span></th>
                          <td scope="col" colspan="3"><input name="staffcode" type="text" id="staffcode" value="<?php echo $mgr->getRequestData('staffcode') ?>" size="10" maxlength="8" <?php echo $mgr->disabled['staffcode'] ?> />
                            <span class="inputRule">(半角数字8桁)</span>
                          </td>
<?php } ?>
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
                          <td><input name="kanjiname" type="text" id="kanjiname" size="20" maxlength="10" value="<?php echo $mgr->getRequestData('kanjiname') ?>" <?php echo $mgr->disabled['kanjiname'] ?> /></td>
                          <th>カナ氏名<span class="hissu">*</span></th>
                          <td scope="col"><input name="kananame" type="text" id="kananame" maxlength="20" size="20" value="<?php echo $mgr->getRequestData('kananame') ?>" <?php echo $mgr->disabled['kananame'] ?> /></td>
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
                                <td><input name="validstartdate" type="text" id="validstartdate" onchange="cal_vsd.getFormValue(); cal_vsd.hide();" onclick="cal_vsd.write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('validstartdate') ?>" />[<a href="javascript:;" onclick="setToday('validstartdate');">今日</a>]<br /><div id="caldiv_vsd"></div></td>
                                <td> 　～　 </td>
                                <td><input name="validenddate" type="text" id="validenddate" onchange="cal_ved.getFormValue(); cal_ved.hide();" onclick="cal_ved.write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('validenddate') ?>" <?php echo ($mgr->getRequestData('is_retire_date') ? 'style="background-color : #cd5c5c;"' : '') ?> />[<a href="javascript:;" onclick="setToday('validenddate');">今日</a>]<br /><div id="caldiv_ved"></div></td>
                              </tr>
                            </table></td>
                        </tr>