        <input type="hidden" name="list_no" id="list_no" value="">
        <input type="hidden" name="edit_mode" id="edit_mode" value="<?php echo $mgr->getRequestData('edit_mode') ?>">
<?php if ($mgr->getRequestData('edit_mode') != 'reserve') { ?>
        <h3>■必要事項を入力して「更新」ボタンをクリックしてください。</h3>
<?php } else { ?>
        <input type="hidden" name="reserve_flg" id="reserve_flg" value="<?php echo $mgr->getRequestData('reserve_flg') ?>">
        <h3>■現在の予約内容は以下の通りです。</h3>
<?php } ?>
      <table border="0" cellspacing="3" cellpadding="3">
        <tr>
          <td><div class="inputComment">(*)は必須項目です。</div></td>
        </tr>
        <tr>
          <td><table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
            <tr>
              <th colspan="4">基本情報</th>
              </tr>
            <tr>
              <th width="100">氏名</th>
              <td><?php echo $mgr->getOutputData('kanjisei') ?>　<?php echo $mgr->getOutputData('kanjimei') ?>（<?php echo $mgr->getOutputData('kanasei') ?>　<?php echo $mgr->getOutputData('kanamei') ?>）</td>
              <th width="90">氏名英字</th>
              <td>姓:<input name="eijisei" type="text" id="eijisei" size="6" value="<?php echo $mgr->getRequestData('eijisei') ?>" />
                  名:<input name="eijimei" type="text" id="eijimei" size="6" value="<?php echo $mgr->getRequestData('eijimei') ?>" />
                </td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><img src="image/space.gif" alt="" width="1" height="5" /></td>
        </tr>
        <tr>
          <td>
<?php include("view/users/ncvc_regist_tab.tpl") ?>
          </td>
        </tr>
        <tr>
          <td align="left"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
            <tr>
              <th colspan="6">予約情報　※予約は１件しか登録できません。既に予約データがある場合上書きされます。</th>
            </tr>
<?php if ($mgr->getRequestData('edit_mode') != 'reserve') { ?>
            <tr>
              <th>予約登録</th>
              <td colspan="5"><div class="CheckBoxTab">
                <table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td><input type="checkbox" name="reserve_flg" id="reserve_flg" value="1"  onclick="changeReserveFlg();" <?php echo $mgr->getCheckData('reserve_flg', '1') ?> /></td>
                    <td><label for="reserve_flg">予約登録する</label></td>
                  </tr>
                </table>
              </div></td>
            </tr>
<?php } ?>
            <tr>
              <th width="105">反映日<span class="hissu"></span></th>
              <td colspan="5"><table border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td><input name="reflect_date" type="text" id="reflect_date" onchange="cal_rfd.getFormValue(); cal_rfd.hide();" onclick="cal_rfd.write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('reflect_date') ?>" <?php echo ($mgr->getRequestData('reserve_flg') == '1' ? "" : "disabled") ?> /><br /><div id="caldiv_rfd"></div></td>
                  </tr>
                </table></td>
            </tr>
<?php if ($mgr->getRequestData('edit_mode') != 'reserve') { ?>
            <tr>
              <th>予約状況</th>
              <td colspan="5">
<?php   if ($mgr->has_reserve_data) { ?>
                <a href="javascript:;" onclick="showReserveData();">反映日：<?php echo $mgr->getOutputData('reflect_date') ?>　登録者：<?php echo $mgr->getOutputData('reserve_user_name') ?></a>
<?php   } else { ?>
                予約データなし
<?php   } ?>
              </td>
            </tr>
<?php } ?>
          </table></td>
        </tr>
        <tr>
          <td align="left"><img src="image/space.gif" alt="" width="1" height="5" /></td>
        </tr>
        <tr>
          <td>
<?php if ($mgr->getRequestData('edit_mode') != 'reserve') { ?>
            <input type="button" value="　　　更　　　新　　　" onclick="editUser();" />
            <input type="button" value="　　　リセット　　　" onclick="resetUser();" />
            <input type="button" value="　　一覧に戻る　　" onclick="returnList();" />
            <input type="button" value="　　変更履歴一覧を表示　　" onclick="showHistoryList();" />
<?php } else { ?>
            <input type="button" value="　　　予約内容修正　　　" onclick="editUser();" />
            <input type="button" value="　　　予約取消　　　" onclick="deleteReserveData();" />
            <input type="button" value="　　キャンセル　　" onclick="editModeReset();" />
            <input type="button" value="　　　リセット　　　" onclick="resetUser();" />
<?php } ?>
          </td>
        </tr>
      </table>